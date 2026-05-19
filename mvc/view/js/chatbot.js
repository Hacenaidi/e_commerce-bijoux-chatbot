// Chatbot API Configuration
const CHATBOT_API_URL = 'http://localhost:8000'; // URL de l'API Python
const HEALTH_CHECK_INTERVAL = 5000; // Vérifier la santé de l'API toutes les 5s
const CHATBOT_LANGUAGE_KEY = 'chatbot-language';

// Store messages
let chatMessages = [];

// DOM Elements
const chatMessagesDiv = document.getElementById('chatMessages');
const questionInput = document.getElementById('questionInput');
const chatForm = document.getElementById('chatForm');
const loadingIndicator = document.getElementById('loadingIndicator');

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chatbot initialized');
    initLanguageSelector();
    checkAPIHealth();
    // Auto-scroll to bottom
    scrollToBottom();
});

function initLanguageSelector() {
    if (!chatForm || document.getElementById('chatLanguageSelect')) return;

    const wrapper = document.createElement('div');
    wrapper.className = 'chat-language-wrapper';
    wrapper.style.cssText = 'display:flex;align-items:center;justify-content:flex-end;gap:12px;margin:0 0 14px;padding:10px 14px;border-radius:16px;background:linear-gradient(135deg,#E63946 0%,#A4161A 100%);box-shadow:0 10px 24px rgba(230,57,70,.18);';

    const label = document.createElement('label');
    label.setAttribute('for', 'chatLanguageSelect');
    label.textContent = 'Language';
    label.style.cssText = 'font-size:11px;color:rgba(255,255,255,.92);letter-spacing:.12em;text-transform:uppercase;font-weight:700;';

    const select = document.createElement('select');
    select.id = 'chatLanguageSelect';
    select.style.cssText = 'min-width:120px;border:1px solid rgba(255,255,255,.26);border-radius:999px;padding:8px 34px 8px 14px;background:rgba(255,255,255,.12);color:#fff;box-shadow:0 6px 16px rgba(0,0,0,.18);appearance:none;-webkit-appearance:none;-moz-appearance:none;';
    select.innerHTML = '<option value="fr">Français</option><option value="en">English</option>';

    const saved = localStorage.getItem(CHATBOT_LANGUAGE_KEY);
    if (saved === 'fr' || saved === 'en') {
        select.value = saved;
    }
    select.addEventListener('change', () => {
        localStorage.setItem(CHATBOT_LANGUAGE_KEY, select.value);
    });

    select.style.backgroundImage = 'linear-gradient(45deg, transparent 50%, rgba(255,255,255,.95) 50%), linear-gradient(135deg, rgba(255,255,255,.95) 50%, transparent 50%), linear-gradient(180deg,rgba(255,255,255,.12),rgba(255,255,255,.06))';
    select.style.backgroundPosition = 'calc(100% - 16px) calc(50% - 2px), calc(100% - 11px) calc(50% - 2px), 0 0';
    select.style.backgroundSize = '5px 5px, 5px 5px, 100% 100%';
    select.style.backgroundRepeat = 'no-repeat';

    const optionStyle = document.createElement('style');
    optionStyle.textContent = `
        #chatLanguageSelect option {
            background: #fff5f5;
            color: #4a1d1d;
        }
        #chatLanguageSelect option:checked,
        #chatLanguageSelect option:hover {
            background: #E63946;
            color: #fff;
        }
    `;
    document.head.appendChild(optionStyle);

    wrapper.appendChild(label);
    wrapper.appendChild(select);

    chatForm.parentNode.insertBefore(wrapper, chatForm);
}

/**
 * Check if API is healthy
 */
function checkAPIHealth() {
    fetch(`${CHATBOT_API_URL}/health`)
        .then(response => response.json())
        .then(data => {
            console.log('✓ API is healthy:', data);
        })
        .catch(error => {
            console.error('✗ API is not available:', error);
            showErrorMessage('⚠️ L\'API chatbot n\'est pas disponible. Assurez-vous que le serveur Python est lancé.');
        });
}

/**
 * Send message to chatbot
 */
function sendMessage(event) {
    event.preventDefault();

    const question = questionInput.value.trim();

    if (!question) {
        alert('Veuillez poser une question!');
        return;
    }

    // Add user message to chat
    addUserMessage(question);

    // Clear input
    questionInput.value = '';
    questionInput.focus();

    // Show loading indicator
    showLoading(true);

    // Send to API
    sendToAPI(question);
}

/**
 * Send question to API
 */
function sendToAPI(question) {
    const languageSelect = document.getElementById('chatLanguageSelect');
    const language = languageSelect ? languageSelect.value : (localStorage.getItem(CHATBOT_LANGUAGE_KEY) || 'fr');
    fetch(`${CHATBOT_API_URL}/ask`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ question: question, language: language })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        showLoading(false);
        
        if (data.success === false) {
            showErrorMessage('Erreur: ' + data.error);
            return;
        }

        // Add bot response
        addBotMessage(data.answer, data.sources);
    })
    .catch(error => {
        showLoading(false);
        console.error('Error:', error);
        showErrorMessage('Erreur de connexion. Vérifiez que l\'API Python est lancée sur le port 8000.');
    });
}

/**
 * Add user message to chat
 */
function addUserMessage(message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message user-message';
    
    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    contentDiv.textContent = message;
    
    const timeDiv = document.createElement('small');
    timeDiv.textContent = getCurrentTime();
    
    messageDiv.appendChild(contentDiv);
    messageDiv.appendChild(timeDiv);
    
    chatMessagesDiv.appendChild(messageDiv);
    scrollToBottom();
}

/**
 * Add bot message to chat
 */
function addBotMessage(answer, sources) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message bot-message';
    
    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    
    // Remove sources from answer (handles "Sources", "Source", optional spaces around colon, multilingual colon)
    let cleanAnswer = (answer || '').replace(/\n\s*Sources?\s*[:：]\s*[\s\S]*$/i, '');
    contentDiv.innerHTML = cleanAnswer;
    
    // DO NOT show sources (hidden as requested)
    // Sources are completely removed from display
    
    const timeDiv = document.createElement('small');
    timeDiv.textContent = getCurrentTime();
    
    messageDiv.appendChild(contentDiv);
    messageDiv.appendChild(timeDiv);
    
    chatMessagesDiv.appendChild(messageDiv);
    scrollToBottom();
}

/**
 * Show error message
 */
function showErrorMessage(error) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message bot-message error-message';
    
    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    contentDiv.textContent = error;
    
    const timeDiv = document.createElement('small');
    timeDiv.textContent = getCurrentTime();
    
    messageDiv.appendChild(contentDiv);
    messageDiv.appendChild(timeDiv);
    
    chatMessagesDiv.appendChild(messageDiv);
    scrollToBottom();
}

/**
 * Show/hide loading indicator
 */
function showLoading(show) {
    if (show) {
        loadingIndicator.style.display = 'flex';
        scrollToBottom();
    } else {
        loadingIndicator.style.display = 'none';
    }
}

/**
 * Quick question handler
 */
function quickQuestion(question) {
    questionInput.value = question;
    sendMessage(new Event('submit'));
}

/**
 * Get current time
 */
function getCurrentTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    return `${hours}:${minutes}`;
}

/**
 * Scroll chat to bottom
 */
function scrollToBottom() {
    setTimeout(() => {
        chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
    }, 0);
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

/**
 * Handle Enter key in input
 */
if (questionInput) {
    questionInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage(new Event('submit'));
        }
    });
}

if (chatForm) {
    chatForm.addEventListener('submit', sendMessage);
}

// Periodic health check
setInterval(checkAPIHealth, HEALTH_CHECK_INTERVAL);
