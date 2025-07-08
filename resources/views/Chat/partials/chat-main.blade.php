{{-- Chat Main Area Component --}}
<div class="chat-main">
    <div class="chat-main-header">
        <div class="current-contact">
            <div class="current-contact-avatar">?</div>
            <div class="current-contact-info">
                <div class="current-contact-name">Pilih kontak untuk memulai chat</div>
                <div class="current-contact-status">
                    <span class="status-indicator"></span>
                    <span class="status-text"></span>
                </div>
            </div>
        </div>
        <div class="chat-actions">
            <button class="btn-icon" id="paymentButton" title="Send Payment">
                <i class="fas fa-money-bill-wave"></i>
            </button>

        </div>
    </div>
    
    <div class="messages-container" id="messagesContainer">
        <div class="welcome-message">
            <i class="fas fa-comments"></i>
            <h3>Selamat datang di Chat App</h3>
            <p>Pilih kontak dari sidebar untuk memulai percakapan</p>
        </div>
    </div>
    
    <div class="image-preview" id="imagePreview" style="display: none;"></div>
    
    <div class="chat-input-container">
        <div class="chat-input">
            <button class="btn-icon" id="attachButton" title="Attach File">
                <i class="fas fa-paperclip"></i>
                <input type="file" id="imageUpload" accept="image/*" style="display: none;">
            </button>
            <input type="text" id="messageInput" placeholder="Ketik pesan..." autocomplete="off">
            <button class="send-button" id="sendButton">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
// Attach button functionality
document.addEventListener('DOMContentLoaded', function() {
    const attachButton = document.getElementById('attachButton');
    const imageUpload = document.getElementById('imageUpload');
    
    if (attachButton && imageUpload) {
        attachButton.addEventListener('click', function() {
            imageUpload.click();
        });
    }
});
</script>