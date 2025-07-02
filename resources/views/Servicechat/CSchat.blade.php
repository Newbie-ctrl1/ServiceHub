@extends('layouts.app')

@section('content')
<div class="cs-chat-container">
    <div class="cs-chat-header">
        <div class="cs-profile">
            <div class="cs-avatar">
                <img src="{{ asset('images/cs-avatar.png') }}" alt="CS Avatar" onerror="this.src='{{ asset(\'images/logo.png\') }}'" style="width: 40px; height: 40px; object-fit: cover;">
                <span class="cs-status online"></span>
            </div>
            <div class="cs-info">
                <h5>Customer Service</h5>
                <p class="cs-status-text">Online</p>
            </div>
        </div>
        <div class="cs-actions">
            <button class="btn-minimize" id="minimizeChat"><i class="fas fa-minus"></i></button>
            <button class="btn-close" id="closeChat"><i class="fas fa-times"></i></button>
        </div>
    </div>
    
    <div class="cs-chat-body" id="chatMessages">
        <div class="chat-day-divider">
            <span>Hari ini</span>
        </div>
        
        <div class="message cs-message">
            <div class="message-content">
                <p>Halo! Selamat datang di ServiceHub. Ada yang bisa saya bantu?</p>
                <span class="message-time">10:00</span>
            </div>
        </div>
        
        <!-- Messages will be dynamically added here -->
    </div>
    
    <div class="cs-chat-footer">
        <div class="cs-input-container">
            <div class="cs-input-actions">
                <button class="btn-attachment"><i class="fas fa-paperclip"></i></button>
            </div>
            <input type="text" id="messageInput" class="cs-message-input" placeholder="Ketik pesan Anda di sini...">
            <button class="btn-send" id="sendMessage"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<style>
.cs-chat-container {
    position: fixed;
    bottom: 100px;
    right: 30px;
    width: 350px;
    height: 450px;
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    z-index: 1001;
    transition: all 0.3s ease;
    transform: translateY(0);
    opacity: 1;
}

.cs-chat-container.minimized {
    height: 60px;
    overflow: hidden;
}

.cs-chat-container.hidden {
    transform: translateY(20px);
    opacity: 0;
    pointer-events: none;
}

.cs-chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: var(--primary-color, #1a73e8);
    color: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.cs-profile {
    display: flex;
    align-items: center;
}

.cs-avatar {
    position: relative;
    margin-right: 12px;
}

.cs-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.5);
}

.cs-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.cs-status.online {
    background-color: #4CAF50;
}

.cs-info h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.cs-status-text {
    margin: 0;
    font-size: 12px;
    opacity: 0.8;
}

.cs-actions button {
    background: transparent;
    border: none;
    color: white;
    font-size: 16px;
    margin-left: 8px;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.cs-actions button:hover {
    opacity: 1;
}

.cs-chat-body {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    background-color: #f5f7fa;
}

.chat-day-divider {
    text-align: center;
    margin: 10px 0;
    position: relative;
}

.chat-day-divider:before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    width: 100%;
    height: 1px;
    background: #e0e0e0;
    z-index: 1;
}

.chat-day-divider span {
    background: #f5f7fa;
    padding: 0 10px;
    font-size: 12px;
    color: #757575;
    position: relative;
    z-index: 2;
}

.message {
    display: flex;
    margin-bottom: 16px;
}

.message-content {
    max-width: 80%;
    padding: 10px 14px;
    border-radius: 18px;
    position: relative;
}

.message-content p {
    margin: 0;
    font-size: 14px;
    line-height: 1.4;
}

.message-time {
    font-size: 11px;
    color: #757575;
    display: block;
    margin-top: 4px;
    text-align: right;
}

.cs-message {
    justify-content: flex-start;
}

.cs-message .message-content {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-top-left-radius: 4px;
}

.user-message {
    justify-content: flex-end;
}

.user-message .message-content {
    background-color: var(--primary-color, #1a73e8);
    color: white;
    border-top-right-radius: 4px;
}

.user-message .message-time {
    color: rgba(255, 255, 255, 0.8);
}

.cs-chat-footer {
    padding: 12px 16px;
    background-color: white;
    border-top: 1px solid #e0e0e0;
}

.cs-input-container {
    display: flex;
    align-items: center;
}

.cs-input-actions {
    margin-right: 8px;
}

.cs-input-actions button {
    background: transparent;
    border: none;
    color: #757575;
    font-size: 18px;
    cursor: pointer;
    transition: color 0.2s;
}

.cs-input-actions button:hover {
    color: var(--primary-color, #1a73e8);
}

.cs-message-input {
    flex: 1;
    border: none;
    background-color: #f5f7fa;
    border-radius: 20px;
    padding: 10px 16px;
    font-size: 14px;
    outline: none;
}

.btn-send {
    background: transparent;
    border: none;
    color: var(--primary-color, #1a73e8);
    font-size: 18px;
    margin-left: 8px;
    cursor: pointer;
    transition: transform 0.2s;
}

.btn-send:hover {
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .cs-chat-container {
        width: 100%;
        height: 100%;
        bottom: 0;
        right: 0;
        border-radius: 0;
    }
    
    .cs-chat-header {
        border-radius: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.querySelector('.cs-chat-container');
    const minimizeBtn = document.getElementById('minimizeChat');
    const closeBtn = document.getElementById('closeChat');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendMessage');
    const chatMessages = document.getElementById('chatMessages');
    
    // Initially hide the chat container since it will be shown when the CS button is clicked
    chatContainer.classList.add('hidden');
    
    // Function to add a new message
    function addMessage(text, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = isUser ? 'message user-message' : 'message cs-message';
        
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const timeString = `${hours}:${minutes}`;
        
        messageDiv.innerHTML = `
            <div class="message-content">
                <p>${text}</p>
                <span class="message-time">${timeString}</span>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // If it's a user message, simulate a CS response after a delay
        if (isUser) {
            setTimeout(() => {
                // Simple auto-responses
                let response;
                const lowerText = text.toLowerCase();
                
                if (lowerText.includes('halo') || lowerText.includes('hai') || lowerText.includes('hi')) {
                    response = 'Halo! Ada yang bisa saya bantu?';
                } else if (lowerText.includes('terima kasih') || lowerText.includes('makasih')) {
                    response = 'Sama-sama! Ada yang bisa saya bantu lagi?';
                } else if (lowerText.includes('layanan') || lowerText.includes('service')) {
                    response = 'Kami menyediakan berbagai layanan seperti perbaikan elektronik, jasa kebersihan, dan banyak lagi. Anda bisa melihat detailnya di halaman layanan kami.';
                } else if (lowerText.includes('harga') || lowerText.includes('biaya')) {
                    response = 'Harga layanan kami bervariasi tergantung jenis layanan. Anda bisa melihat detail harga di halaman masing-masing layanan.';
                } else if (lowerText.includes('pembayaran') || lowerText.includes('bayar')) {
                    response = 'Kami menerima pembayaran melalui transfer bank, e-wallet, dan kartu kredit/debit.';
                } else {
                    response = 'Terima kasih atas pesan Anda. Customer service kami akan segera menghubungi Anda.';
                }
                
                addMessage(response);
            }, 1000);
        }
    }
    
    // Send message when send button is clicked
    sendBtn.addEventListener('click', function() {
        const message = messageInput.value.trim();
        if (message) {
            addMessage(message, true);
            messageInput.value = '';
        }
    });
    
    // Send message when Enter key is pressed
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const message = messageInput.value.trim();
            if (message) {
                addMessage(message, true);
                messageInput.value = '';
            }
        }
    });
    
    // Minimize chat
    minimizeBtn.addEventListener('click', function() {
        chatContainer.classList.toggle('minimized');
    });
    
    // Close chat
    closeBtn.addEventListener('click', function() {
        chatContainer.classList.add('hidden');
    });
    
    // Listen for CS button click from footer.blade.php
    window.addEventListener('cs-button-clicked', function() {
        chatContainer.classList.remove('hidden');
        chatContainer.classList.remove('minimized');
    });
    
    // For testing - simulate CS button click after 1 second
    // setTimeout(() => {
    //     window.dispatchEvent(new CustomEvent('cs-button-clicked'));
    // }, 1000);
    
    // Add a sample message after a short delay to make it feel more interactive
    setTimeout(() => {
        if (chatMessages.querySelectorAll('.message').length <= 1) {
            addMessage('Jika Anda memiliki pertanyaan tentang layanan kami, jangan ragu untuk bertanya!');
        }
    }, 2000);
});
</script>
@endsection