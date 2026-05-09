// Chatbot API Configuration
const CHATBOT_API_URL = 'http://localhost:8000'; // URL de l'API Python
const HEALTH_CHECK_INTERVAL = 5000; // Vérifier la santé de l'API toutes les 5s

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
    checkAPIHealth();
    // Auto-scroll to bottom
    scrollToBottom();
});

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
    fetch(`${CHATBOT_API_URL}/ask`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ question: question })
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
questionInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage(new Event('submit'));
    }
});

// Periodic health check
setInterval(checkAPIHealth, HEALTH_CHECK_INTERVAL);
