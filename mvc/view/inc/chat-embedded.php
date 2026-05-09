<?php
// Shared chat embed partial
?>

<!-- Embedded Chat UI (shared include) -->
<div class="chatbot-container embedded-chat">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="chatbot-wrapper">
                    <div class="chatbot-header">
                        <h2>💬 Assistant Bijoux</h2>
                        <p>Posez vos questions sur nos collections, prix et livraison</p>
                    </div>

                    <!-- Chat Messages -->
                    <div class="chat-messages" id="chatMessages">
                        <div class="message bot-message">
                            <div class="message-content">
                                Bonjour! 👋 Je suis votre assistant virtuel. Comment puis-je vous aider aujourd'hui?
                            </div>
                            <small>Assistant</small>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="chat-input-area">
                        <form id="chatForm" onsubmit="sendMessage(event)">
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    id="questionInput" 
                                    class="form-control chat-input" 
                                    placeholder="Tapez votre question..."
                                    autocomplete="off"
                                >
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-send"></i> Envoyer
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>

                    <!-- Loading Indicator -->
                    <div class="loading-indicator" id="loadingIndicator" style="display: none;">
                        <div class="spinner"></div>
                        <span>L'assistant réfléchit...</span>
                    </div>

                    <!-- Quick Questions -->
                    <div class="quick-questions">
                        <p>Questions rapides:</p>
                        <button class="btn btn-sm btn-default" onclick="quickQuestion('Quels bijoux avez-vous disponibles?')">Bijoux disponibles</button>
                        <button class="btn btn-sm btn-default" onclick="quickQuestion('Quel est le prix?')">Prix</button>
                        <button class="btn btn-sm btn-default" onclick="quickQuestion('Comment fonctionne la livraison?')">Livraison</button>
                        <button class="btn btn-sm btn-default" onclick="quickQuestion('Pouvez-vous recommander des styles?')">Styles</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
