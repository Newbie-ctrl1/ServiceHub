@extends('layouts.index')

@section('title', 'Konsultasi AI')

@section('content')
<style>
/* Gaya Dasar */
.ai-chat-container {
    display: flex;
    height: calc(100vh - 100px);
    background-color: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    max-width: 1400px;
}

/* Sidebar */
.chat-sidebar {
    width: 260px;
    background-color: #f7f7f8;
    border-right: 1px solid #e5e5e5;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 16px;
    border-bottom: 1px solid #e5e5e5;
}

.sidebar-header h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: #202123;
}

.new-chat-btn {
    width: 100%;
    padding: 10px;
    background-color: #4f46e5;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background-color 0.3s;
}

.new-chat-btn:hover {
    background-color: #4338ca;
}

.chat-history {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.history-title {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 10px;
    padding: 0 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.chat-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.chat-list li {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 5px;
    cursor: pointer;
    font-size: 14px;
    color: #374151;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-list li:hover {
    background-color: #e5e7eb;
}

.chat-list li.active {
    background-color: #e5e7eb;
    font-weight: 500;
}

.sidebar-footer {
    padding: 16px;
    border-top: 1px solid #e5e5e5;
    display: flex;
    justify-content: space-between;
}

.sidebar-footer a {
    color: #6b7280;
    font-size: 13px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
}

.sidebar-footer a:hover {
    color: #4f46e5;
}

/* Area Chat Utama */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: #ffffff;
}

.chat-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e5e5e5;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header h2 {
    margin: 0;
    font-size: 18px;
    color: #202123;
}



/* Area Pesan */
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.message {
    display: flex;
    gap: 16px;
    padding: 10px 0;
    max-width: 90%;
}

.user-message {
    align-self: flex-end;
}

.system-message, .ai-message {
    align-self: flex-start;
}

.message-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.user-message .message-avatar {
    background-color: #4f46e5;
    color: white;
}

.ai-message .message-avatar {
    background-color: #10a37f;
    color: white;
}

.message-content {
    background-color: #f7f7f8;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 15px;
    line-height: 1.5;
    color: #374151;
}

.user-message .message-content {
    background-color: #ede9fe;
}

.message-content p {
    margin: 0 0 10px 0;
}

.message-content p:last-child {
    margin-bottom: 0;
}

.message-content pre {
    background-color: #282c34;
    color: #abb2bf;
    padding: 12px;
    border-radius: 6px;
    overflow-x: auto;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    margin: 10px 0;
}

.message-content code {
    font-family: 'Courier New', monospace;
    background-color: #f1f1f1;
    padding: 2px 4px;
    border-radius: 4px;
    font-size: 14px;
}

/* Area Input */
.chat-input-container {
    padding: 16px 20px;
    border-top: 1px solid #e5e5e5;
}

.input-wrapper {
    display: flex;
    align-items: flex-end;
    background-color: #f9fafb;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 8px 16px;
}

#chat-input {
    flex: 1;
    border: none;
    background: transparent;
    resize: none;
    padding: 8px 0;
    font-size: 15px;
    line-height: 1.5;
    max-height: 200px;
    outline: none;
    font-family: inherit;
}

#send-button {
    background-color: #4f46e5;
    color: white;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    margin-left: 8px;
    transition: background-color 0.3s;
}

#send-button:hover {
    background-color: #4338ca;
}

#send-button:disabled {
    background-color: #d1d5db;
    cursor: not-allowed;
}

.input-footer {
    display: flex;
    justify-content: center;
    margin-top: 8px;
}

.disclaimer {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
    text-align: center;
}

/* Animasi Loading */
.typing-indicator {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background-color: #d1d5db;
    border-radius: 50%;
    display: inline-block;
    animation: typing 1.4s infinite ease-in-out both;
}

.typing-indicator span:nth-child(1) {
    animation-delay: 0s;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.2);
        opacity: 1;
    }
}

/* Responsif */
@media (max-width: 768px) {
    .ai-chat-container {
        flex-direction: column;
        height: calc(100vh - 80px);
    }
    
    .chat-sidebar {
        width: 100%;
        height: 60px;
        flex-direction: row;
        align-items: center;
    }
    
    .sidebar-header {
        width: 100%;
        border-bottom: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .chat-history, .sidebar-footer {
        display: none;
    }
    
    .new-chat-btn {
        width: auto;
    }
}
</style>

<div class="ai-chat-container">
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <h3>Konsultasi AI</h3>
            <button class="new-chat-btn"><i class="fas fa-plus"></i> Chat Baru</button>
        </div>
        <div class="chat-history">
            <div class="history-title">Riwayat Chat</div>
            <ul class="chat-list">
                <!-- Riwayat chat akan ditampilkan di sini melalui JavaScript -->
            </ul>
        </div>
        <div class="sidebar-footer">
            <a href="#" class="settings-link"><i class="fas fa-cog"></i> Pengaturan</a>
            <a href="#" class="help-link"><i class="fas fa-question-circle"></i> Bantuan</a>
        </div>
    </div>
    
    <div class="chat-main">
        <div class="chat-header">
            <h2>Asisten Teknis</h2>

        </div>
        
        <div class="chat-messages" id="chat-messages">
            <div class="message system-message">
                <div class="message-content">
                    <p>Halo! Saya adalah Habib Service Center. Saya dapat membantu Anda dengan pertanyaan berbagai layanan service. Apa yang bisa saya bantu hari ini?</p>
                </div>
            </div>
            <!-- Pesan-pesan chat akan ditampilkan di sini -->
        </div>
        
        <div class="chat-input-container">
            <form id="chat-form">
                <div class="input-wrapper">
                    <textarea id="chat-input" placeholder="Ketik pesan Anda di sini..." rows="1"></textarea>
                    <button type="submit" id="send-button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="input-footer">
                    <p class="disclaimer">Asisten AI ini menggunakan Groq API. Jawaban mungkin tidak selalu akurat.</p>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const sendButton = document.getElementById('send-button');
    
    // Add CSRF token meta tag if not present
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const metaTag = document.createElement('meta');
        metaTag.setAttribute('name', 'csrf-token');
        metaTag.setAttribute('content', '{{ csrf_token() }}');
        document.head.appendChild(metaTag);
    }
    
    // Get CSRF token function
    function getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }
    
    // Initialize CSRF token
    let csrfToken = getCSRFToken();
    
    // Function to add message to chat (defined early so it can be used)
    function addMessage(role, content) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', role);
        
        // Format the message content (handle code blocks, links, etc.)
        const formattedContent = formatMessage(content);
        
        messageDiv.innerHTML = `
            <div class="message-content">${formattedContent}</div>
        `;
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        return messageDiv;
    }
    
    // Function to format message content
    function formatMessage(content) {
        if (!content) return '';
        
        // Replace code blocks
        let formatted = content.replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>');
        
        // Replace inline code
        formatted = formatted.replace(/`([^`]+)`/g, '<code>$1</code>');
        
        // Replace URLs with links
        formatted = formatted.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
        
        // Replace line breaks with <br>
        formatted = formatted.replace(/\n/g, '<br>');
        
        return formatted;
    }
    
    // Fetch CSRF cookie from Laravel
    fetch('/sanctum/csrf-cookie', {
        method: 'GET',
        credentials: 'same-origin'
    }).then(response => {
        console.log('CSRF cookie fetch response:', response.status);
        // Update CSRF token after fetching cookie
        setTimeout(() => {
            csrfToken = getCSRFToken();
            console.log('Updated CSRF token after cookie fetch:', csrfToken);
            
            // Get XSRF token from cookies after fetch
            let updatedXsrfToken = getCookie('XSRF-TOKEN');
            if (updatedXsrfToken) {
                try {
                    xsrfToken = decodeURIComponent(updatedXsrfToken);
                    console.log('Updated decoded XSRF token:', xsrfToken);
                    // Update system message will be added later
                } catch (e) {
                    console.error('Error decoding updated XSRF token:', e);
                }
            }
        }, 500); // Wait for cookies to be set
    }).catch(error => {
        console.error('Error fetching CSRF cookie:', error);
    });
    
    // Get XSRF token from cookies
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
    
    // Get XSRF token and decode it (Laravel encodes it)
    let xsrfToken = getCookie('XSRF-TOKEN');
    if (xsrfToken) {
        try {
            xsrfToken = decodeURIComponent(xsrfToken);
            console.log('Decoded XSRF token:', xsrfToken);
        } catch (e) {
            console.error('Error decoding XSRF token:', e);
        }
    }
    
    // CSRF token check
    
    if (!csrfToken && !xsrfToken) {
        addMessage('system', 'PERINGATAN: CSRF Token tidak tersedia. Ini dapat menyebabkan masalah dengan permintaan API.');
    }
    
    // Store chat history
    let chatHistory = [];
    
    // Auto-resize textarea
    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        
        // Enable/disable send button based on input
        sendButton.disabled = !this.value.trim();
    });
    
    // Handle form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        
        if (!message) return;
        
        // Add user message to chat
        addMessage('user', message);
        
        // Add to chat history
        chatHistory.push({
            role: 'user',
            content: message
        });
        
        // Clear input
        chatInput.value = '';
        chatInput.style.height = 'auto';
        sendButton.disabled = true;
        
        // Show typing indicator
        showTypingIndicator();
        
        // Use fixed model
        const selectedModel = "llama3-70b-8192";
        
        // Log request details for debugging
        console.log('Sending request to Groq API with fixed model:', selectedModel);
        console.log('CSRF Token:', '{{ csrf_token() }}');
        
        // Get API URL
        const apiUrl = '{{ route("api.groq.chat") }}';
        console.log('API URL:', apiUrl);
        
        // API call to Groq
        
        // Test API endpoint with a GET request first
        const testApiUrl = '{{ route("api.groq.test") }}';
        console.log('Testing API connection with:', testApiUrl);
        fetch(testApiUrl, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '{{ csrf_token() }}',
                'X-XSRF-TOKEN': xsrfToken || csrfToken || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('GET test response status:', response.status);
            return response.text().then(text => {
                console.log('GET test response text:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Test GET response:', data);
                } catch (e) {
                    console.log('Test GET response status:', response.status, 'text:', text);
                }
            });
        })
        .catch(error => {
            console.error('GET test error:', error);
        });
        
        // Log request payload for debugging
        console.log('Request payload:', {
            message: message,
            model: selectedModel,
            chat_history: chatHistory
        });
        
        // Make API call to Groq
        console.log('Making POST request to:', apiUrl);
        console.log('Using CSRF token:', csrfToken || '{{ csrf_token() }}');
        
        // Prepare request payload with fixed model
        const payload = {
            message: message,
            model: "llama3-70b-8192", // Model tetap: Llama3 70B
            chat_history: chatHistory
        };
        console.log('Request payload:', payload);
        
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '{{ csrf_token() }}',
                'X-XSRF-TOKEN': xsrfToken || csrfToken || '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload),
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            // Clone the response so we can read the text and still parse as JSON
            const responseClone = response.clone();
            
            // Log the raw response text for debugging
            return responseClone.text().then(text => {
                console.log('Raw response:', text);
                
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status + ', body: ' + text);
                }
                
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    throw new Error('Gagal memproses respons JSON: ' + e.message + ', raw: ' + text.substring(0, 100));
                }
            }).catch(err => {
                console.error('Error reading response text:', err);
                throw err;
            });
        })
        .then(data => {
            // Remove typing indicator
            removeTypingIndicator();
            
            // Add AI response to chat
            addMessage('ai', data.message);
            
            // Add to chat history
            chatHistory.push({
                role: 'assistant',
                content: data.message
            });
            
            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Remove typing indicator
            removeTypingIndicator();
            
            // Show detailed error message
            let errorMessage = 'Maaf, terjadi kesalahan saat menghubungi asisten AI: ' + error.message;
            
            // Add more debugging info if available
            if (error.response) {
                errorMessage += '\nStatus: ' + error.response.status;
                try {
                    const responseData = error.response.json();
                    errorMessage += '\nDetail: ' + JSON.stringify(responseData);
                } catch (e) {
                    errorMessage += '\nTidak dapat membaca detail respons.';
                }
            }
            
            // Add SSL certificate error detection
            if (error.message.includes('certificate') || error.message.includes('SSL')) {
                errorMessage += '\n\nKemungkinan masalah dengan sertifikat SSL. Administrator telah diberitahu dan masalah ini akan segera diperbaiki.';
                console.error('SSL Certificate error detected:', error.message);
            }
            
            addMessage('system', errorMessage);
            
            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    });
    
    // Function to add message to chat
    function addMessage(type, content) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        
        let avatarContent = '';
        if (type === 'user') {
            avatarContent = '<i class="fas fa-user"></i>';
        } else if (type === 'ai') {
            avatarContent = '<i class="fas fa-robot"></i>';
        } else if (type === 'system') {
            avatarContent = '<i class="fas fa-info-circle"></i>';
        }
        
        messageDiv.innerHTML = `
            <div class="message-avatar">${avatarContent}</div>
            <div class="message-content">
                <p>${formatMessage(content)}</p>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
    }
    
    // Function to show typing indicator
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message ai-message typing-indicator-container';
        typingDiv.innerHTML = `
            <div class="message-avatar"><i class="fas fa-robot"></i></div>
            <div class="message-content typing-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;
        typingDiv.id = 'typing-indicator';
        chatMessages.appendChild(typingDiv);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Function to remove typing indicator
    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
    
    // Function to format message (handle code blocks, links, etc.)
    function formatMessage(text) {
        // Replace URLs with clickable links
        text = text.replace(/https?:\/\/[^\s]+/g, function(url) {
            return `<a href="${url}" target="_blank" rel="noopener noreferrer">${url}</a>`;
        });
        
        // Handle code blocks (text between ``` ```)
        text = text.replace(/```([\s\S]*?)```/g, function(match, code) {
            return `<pre><code>${code.trim()}</code></pre>`;
        });
        
        // Handle inline code (text between ` `)
        text = text.replace(/`([^`]+)`/g, function(match, code) {
            return `<code>${code}</code>`;
        });
        
        // Handle line breaks
        text = text.replace(/\n/g, '<br>');
        
        return text;
    }
    
    // New Chat button functionality
    document.querySelector('.new-chat-btn').addEventListener('click', function() {
        // Clear chat messages except the first system message
        while (chatMessages.children.length > 1) {
            chatMessages.removeChild(chatMessages.lastChild);
        }
        
        // Clear chat history
        chatHistory = [];
        
        // Clear input
        chatInput.value = '';
        chatInput.style.height = 'auto';
        sendButton.disabled = true;
    });
    
    // Initialize
    sendButton.disabled = true;
    
    // Set fixed model to llama3-70b-8192
    const fixedModel = 'llama3-70b-8192';
    
    // Using fixed model
});
</script>
@endsection