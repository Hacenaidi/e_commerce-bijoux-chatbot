// Chatbot Widget (Floating)
// Peut être intégré dans toutes les pages

const CHATBOT_API_URL = 'http://localhost:8000';
const CHATBOT_LANGUAGE_KEY = 'chatbot-language';
// Typing indicator state
let _chatbotTypingElem = null;
let _chatbotTypingStart = 0;
let _chatbotTypingTimer = null;
const CHATBOT_TYPING_MIN_MS = 1000; // minimum visible typing time (ms)

// Create widget HTML
function initChatbotWidget() {
    // Check if widget already exists
    if (document.querySelector('.chatbot-widget')) return;

    // Create widget container
    const widgetHTML = `
        <!-- Chatbot Widget -->
        <div class="chatbot-widget">
            <button class="chatbot-toggle-btn" onclick="toggleChatbotModal()">
                ${String.fromCodePoint(0x1F4AC)}
                <span class="chatbot-badge" id="chatbotBadge" style="display: none;">1</span>
            </button>
        </div>

        <!-- Chatbot Modal -->
        <div class="chatbot-modal" id="chatbotModal">
            <div class="chatbot-modal-content">
                <div class="chatbot-modal-header">
                    <h3>${String.fromCodePoint(0x1F4AC)} Assistant Bijoux</h3>
                    <button class="chatbot-close-btn" onclick="toggleChatbotModal()">X</button>
                </div>

                <div class="chatbot-language-bar">
                    <label for="chatbotLanguageSelect">Language</label>
                    <select id="chatbotLanguageSelect" class="chatbot-language-select" aria-label="Chat language">
                        <option value="fr">Français</option>
                        <option value="en">English</option>
                    </select>
                </div>
                
                <div class="chatbot-modal-body" id="chatbotModalBody">
                    <div class="message bot-message" style="margin-top: 10px;">
                                <div class="message-content">
                                    Bonjour! ${String.fromCodePoint(0x1F44B)} Comment puis-je vous aider?
                                </div>
                        <small>Assistant</small>
                    </div>
                </div>

                <div class="chatbot-modal-footer">
                    <form onsubmit="sendWidgetMessage(event)" id="chatbotWidgetForm">
                        <div class="chatbot-input-row">
                            <input 
                                type="text" 
                                id="chatbotWidgetInput" 
                                class="chatbot-modal-input"
                                placeholder="Tapez votre question..."
                                autocomplete="off"
                            >
                            <button type="submit" class="chatbot-send-btn" aria-label="Envoyer">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    // Add widget to page
    document.body.insertAdjacentHTML('beforeend', widgetHTML);
    bindWidgetLanguageEvents();
    syncWidgetLanguageUI();
}

/**
 * Toggle modal
 */
function toggleChatbotModal() {
    const modal = document.getElementById('chatbotModal');
    modal.classList.toggle('show');
    
    if (modal.classList.contains('show')) {
        document.getElementById('chatbotWidgetInput').focus();
        hideChatbotBadge();
        syncWidgetLanguageUI();
    }
}

/**
 * Send message from widget
 */
function sendWidgetMessage(event) {
    event.preventDefault();

    const input = document.getElementById('chatbotWidgetInput');
    const question = input.value.trim();
    const language = getSelectedWidgetLanguage();

    if (!question) return;

    // Add user message
    addWidgetMessage('user', question);

    // Clear input
    input.value = '';
    input.focus();

    // Send to API
    sendWidgetToAPI(question, language);
}

/**
 * Send to API
 */
function sendWidgetToAPI(question, language) {
    // start typing indicator
    _chatbotTypingStart = Date.now();
    showChatbotTyping();
    fetch(`${CHATBOT_API_URL}/ask`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ question: question, language: language || 'fr' })
    })
    .then(response => response.json())
            .then(data => {
        if (data.success === false) {
            // ensure typing hidden then show error
            hideChatbotTypingImmediate();
            addWidgetMessage('bot', '❌ Erreur: ' + data.error);
        } else {
            // Remove sources from answer (robust: handles "Sources" with/without colon, multi-line)
            let answer = data.answer || '';
            answer = answer.replace(/\n\s*Sources?:[\s\S]*$/i, '');
            // ensure typing visible at least CHATBOT_TYPING_MIN_MS
            const elapsed = Date.now() - _chatbotTypingStart;
            const remaining = Math.max(0, CHATBOT_TYPING_MIN_MS - elapsed);
            _chatbotTypingTimer = setTimeout(() => {
                hideChatbotTyping();
                addWidgetMessage('bot', answer.trim());
            }, remaining);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        addWidgetMessage('bot', '⚠️ L\'API n\'est pas disponible. Assurez-vous que le serveur Python est lancé.');
    });
}

function getSelectedWidgetLanguage() {
    const select = document.getElementById('chatbotLanguageSelect');
    return select ? select.value : 'fr';
}

function bindWidgetLanguageEvents() {
    const select = document.getElementById('chatbotLanguageSelect');
    if (!select) return;
    const saved = localStorage.getItem(CHATBOT_LANGUAGE_KEY);
    if (saved === 'fr' || saved === 'en') {
        select.value = saved;
    }
    select.addEventListener('change', () => {
        localStorage.setItem(CHATBOT_LANGUAGE_KEY, select.value);
        syncWidgetLanguageUI();
    });
}

function syncWidgetLanguageUI() {
    const select = document.getElementById('chatbotLanguageSelect');
    const input = document.getElementById('chatbotWidgetInput');
    const sendButton = document.querySelector('.chatbot-send-btn');
    const header = document.querySelector('.chatbot-modal-header h3');
    const body = document.getElementById('chatbotModalBody');
    if (!select) return;

    const language = select.value === 'en' ? 'en' : 'fr';
    localStorage.setItem(CHATBOT_LANGUAGE_KEY, language);

    if (input) {
        input.placeholder = language === 'en' ? 'Type your question...' : 'Tapez votre question...';
    }
    if (sendButton) {
        sendButton.textContent = language === 'en' ? 'Send' : 'Envoyer';
    }
    if (header) {
        header.textContent = language === 'en'
            ? `${String.fromCodePoint(0x1F4AC)} Jewelry Assistant`
            : `${String.fromCodePoint(0x1F4AC)} Assistant Bijoux`;
    }
    if (body) {
        const greeting = body.querySelector('.bot-message .message-content');
        if (greeting && body.children.length > 0) {
            greeting.textContent = language === 'en'
                ? `Hello! ${String.fromCodePoint(0x1F44B)} How can I help you?`
                : `Bonjour! ${String.fromCodePoint(0x1F44B)} Comment puis-je vous aider?`;
        }
    }
}

/**
 * Add message to widget
 */
function addWidgetMessage(type, message) {
    const body = document.getElementById('chatbotModalBody');
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type === 'user' ? 'user-message' : 'bot-message'}`;
    
    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    
    if (type === 'bot') {
        // Formatter la réponse pour les bots
        const formatted = formatChatbotResponse(message);
        contentDiv.innerHTML = formatted.replace(/\n/g, '<br>');
    } else {
        // Messages utilisateur en texte simple
        contentDiv.textContent = message;
    }
    
    const timeDiv = document.createElement('small');
    const now = new Date();
    const time = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
    timeDiv.textContent = time;
    
    messageDiv.appendChild(contentDiv);
    messageDiv.appendChild(timeDiv);
    
    body.appendChild(messageDiv);
    
    // Scroll to bottom
    setTimeout(() => {
        body.scrollTop = body.scrollHeight;
    }, 0);
}

/* Typing indicator helpers */
function createTypingElement() {
    const div = document.createElement('div');
    div.className = 'message bot-message typing-message';
    div.innerHTML = `
        <div class="message-content">
            <div class="typing-dots" aria-hidden="true">
                <span></span><span></span><span></span>
            </div>
        </div>
    `;
    return div;
}

function showChatbotTyping() {
    const body = document.getElementById('chatbotModalBody');
    if (!body) return;
    if (_chatbotTypingElem) return; // already shown
    _chatbotTypingElem = createTypingElement();
    body.appendChild(_chatbotTypingElem);
    // scroll to show typing
    setTimeout(() => { body.scrollTop = body.scrollHeight; }, 0);
}

function hideChatbotTyping() {
    if (_chatbotTypingTimer) {
        clearTimeout(_chatbotTypingTimer);
        _chatbotTypingTimer = null;
    }
    if (!_chatbotTypingElem) return;
    _chatbotTypingElem.remove();
    _chatbotTypingElem = null;
}

function hideChatbotTypingImmediate() {
    if (_chatbotTypingTimer) {
        clearTimeout(_chatbotTypingTimer);
        _chatbotTypingTimer = null;
    }
    if (_chatbotTypingElem) {
        _chatbotTypingElem.remove();
        _chatbotTypingElem = null;
    }
}

/**
 * Hide badge
 */
function hideChatbotBadge() {
    const badge = document.getElementById('chatbotBadge');
    if (badge) {
        badge.style.display = 'none';
    }
}

/**
 * Show badge (for new messages)
 */
function showChatbotBadge() {
    const badge = document.getElementById('chatbotBadge');
    if (badge) {
        badge.style.display = 'flex';
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initChatbotWidget);
