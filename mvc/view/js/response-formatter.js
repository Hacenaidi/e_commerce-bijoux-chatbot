// Response Formatter
// Formate les réponses du chatbot pour un meilleur affichage

/**
 * Parse et formate la réponse du chatbot
 */
function formatChatbotResponse(answer) {
    // Enlever les sources si présentes (gère "Sources :", espace avant deux-points, majuscules/minuscules)
    answer = (answer || '').replace(/\n\s*Sources?\s*[:：]\s*[\s\S]*$/i, '');

    // Détecter et formater les listes
    answer = formatLists(answer);
    
    // Détecter et formater les sections
    answer = formatSections(answer);

    return answer.trim();
}

/**
 * Formate les listes (numérotées ou à points)
 */
function formatLists(text) {
    // Listes numérotées existantes
    text = text.replace(/^\d+\.\s+/gm, '<li>');
    text = text.replace(/<li>(.+?)(?=<li>|$)/gs, '<li>$1</li>');
    text = text.replace(/<li>(.+?)<\/li>/gs, '<li>$1</li>');
    
    // Listes à points
    text = text.replace(/^[-•]\s+/gm, '<li>');
    
    // Convertir en listes HTML
    text = text.replace(/(<li>.+?<\/li>)/s, '<ul>$1</ul>');
    text = text.replace(/<\/li><li>/g, '</li><li>');

    // Bijoux / produits disponibles
    text = text.replace(/Bijoux disponibles:/gi, '<strong>Bijoux disponibles:</strong><ul>');
    text = text.replace(/Styles disponibles:/gi, '<strong>Styles disponibles:</strong><ul>');
    text = text.replace(/Nos produits:/gi, '<strong>Nos produits:</strong><ul>');
    text = text.replace(/Collections:/gi, '<strong>Collections:</strong><ul>');

    // Fermer les listes
    text = text.replace(/(<ul>.*?<\/li>)\n\n/s, '$1</ul>\n\n');

    return text;
}

/**
 * Formate les sections (prix, livraison, etc.)
 */
function formatSections(text) {
    // Sections principales
    const sections = [
        'Prix|Tarif|Coût',
        'Livraison|Shipping|Expédition',
        'Style|Styles',
        'Bijoux|Produits|Collections',
        'Remise|Réduction|Promo',
        'Paiement|Payment|Moyens de paiement'
    ];

    sections.forEach(section => {
        const regex = new RegExp(`^(${section})\\s*[:–-]\\s*(.+?)(?=\\n\\n|$)`, 'gmi');
        text = text.replace(regex, '<div class="response-section"><strong>$1:</strong> $2</div>');
    });

    return text;
}

/**
 * Crée un élément HTML formaté pour la réponse
 */
function createFormattedMessageElement(message) {
    const div = document.createElement('div');
    div.className = 'message-formatted-content';
    
    // Traiter le contenu
    let html = formatChatbotResponse(message);
    
    // Convertir les sauts de ligne en <br>
    html = html.replace(/\n/g, '<br>');
    
    // Ajouter les styles
    div.innerHTML = html;
    
    return div;
}

/**
 * Formate un texte brut en HTML avec liste
 */
function formatAsListHTML(items) {
    if (Array.isArray(items)) {
        let html = '<ul class="response-list">';
        items.forEach((item, index) => {
            html += `<li>${escapeHtml(item.trim())}</li>`;
        });
        html += '</ul>';
        return html;
    }
    return escapeHtml(items);
}

/**
 * Détecte si la réponse contient une liste
 */
function containsList(text) {
    return /^\d+\.|^[-•]|bijoux|produit|collection|style/mi.test(text);
}

/**
 * Escape HTML
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
