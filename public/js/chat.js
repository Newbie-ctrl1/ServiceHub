// Chat Application JavaScript
class ChatApp {
    constructor() {
        this.pusher = null;
        this.channel = null;
        this.currentReceiverId = null;
        this.selectedImage = null;
        this.pollingInterval = null;
        this.lastMessageId = 0;
        this.isPolling = false;
        this.processedMessageIds = new Set();
        this.isPusherConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        
        this.messagesContainer = document.getElementById('messagesContainer');
        this.messageInput = document.getElementById('messageInput');
        this.sendButton = document.getElementById('sendButton');
        this.imageUploadInput = document.getElementById('imageUpload');
        this.imagePreview = document.getElementById('imagePreview');
        
        this.init();
    }
    
    init() {
        this.initEventListeners();
        this.initPusher();
        this.updateUnreadCount();
        this.setInitialContact();
        
        // Start hybrid polling - always run alongside Pusher for reliability
        this.startHybridPolling();
        
        // Monitor connection status
        this.monitorConnection();
    }
    
    initEventListeners() {
        // Send message on button click
        this.sendButton.addEventListener('click', () => this.sendMessage());
        
        // Send message on Enter key
        this.messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.sendMessage();
            }
        });
        
        // Image upload handling
        this.imageUploadInput.addEventListener('change', (e) => this.handleImageUpload(e));
        
        // Contact selection
        this.initContactListeners();
        
        // Initialize mobile menu
        this.initMobileMenu();
        
        // Payment button
        this.initPaymentButton();
        
        // Cancel image preview
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('cancel-image')) {
                this.clearImagePreview();
            }
        });
    }

    // Initialize mobile menu functionality
    initMobileMenu() {
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const sidebar = document.querySelector('.chat-sidebar');
        const overlay = document.querySelector('.mobile-overlay');
        
        if (!mobileMenuBtn || !sidebar) return;
        
        // Create overlay if it doesn't exist
        if (!overlay) {
            const newOverlay = document.createElement('div');
            newOverlay.className = 'mobile-overlay';
            document.body.appendChild(newOverlay);
        }
        
        const mobileOverlay = document.querySelector('.mobile-overlay');
        
        // Toggle sidebar on menu button click
        mobileMenuBtn.addEventListener('click', (e) => {
            e.preventDefault();
            sidebar.classList.toggle('show');
            mobileOverlay.classList.toggle('show');
        });
        
        // Close sidebar when overlay is clicked
        mobileOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            mobileOverlay.classList.remove('show');
        });
        
        // Close sidebar when contact is selected on mobile
        const contacts = document.querySelectorAll('.contact');
        contacts.forEach(contact => {
            contact.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    mobileOverlay.classList.remove('show');
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                mobileOverlay.classList.remove('show');
            }
        });
    }
    
    // Initialize payment button functionality
    initPaymentButton() {
        const paymentButton = document.getElementById('paymentButton');
        const paymentPanel = document.getElementById('paymentPanel');
        
        if (paymentButton && paymentPanel) {
            paymentButton.addEventListener('click', () => {
                if (this.currentReceiverId) {
                    this.showPaymentPanel();
                } else {
                    this.showNotification('Pilih kontak terlebih dahulu untuk mengirim pembayaran');
                }
            });
        }
    }
    
    // Show payment panel
    showPaymentPanel() {
        console.log('Showing payment panel');
        const paymentPanel = document.getElementById('paymentPanel');
        if (paymentPanel) {
            paymentPanel.style.display = 'block';
            paymentPanel.classList.add('show');
            
            // Update recipient information
            const recipientElement = document.getElementById('paymentRecipient');
            const recipientNameElement = document.getElementById('recipientName');
            const currentContactName = document.querySelector('.current-contact-name');
            
            console.log('Recipient elements:', { recipientElement, recipientNameElement, currentContactName });
            
            if (recipientElement && recipientNameElement && currentContactName) {
                recipientNameElement.textContent = currentContactName.textContent;
                recipientElement.style.display = 'block';
            }
            
            // Focus on amount input
            const amountInput = document.getElementById('paymentAmount');
            if (amountInput) {
                setTimeout(() => amountInput.focus(), 100);
            }
        } else {
            console.error('Payment panel not found!');
        }
    }
    
    // Hide payment panel
    hidePaymentPanel() {
        console.log('Hiding payment panel');
        const paymentPanel = document.getElementById('paymentPanel');
        if (paymentPanel) {
            paymentPanel.style.display = 'none';
            paymentPanel.classList.remove('show');
        }
    }
    
    initContactListeners() {
        const contacts = document.querySelectorAll('.contact');
        contacts.forEach(contact => {
            contact.addEventListener('click', () => {
                contacts.forEach(c => c.classList.remove('active'));
                contact.classList.add('active');
                
                // Update current contact info
                const name = contact.querySelector('.contact-name').textContent;
                const initials = name.split(' ').map(n => n[0]).join('');
                
                document.querySelector('.current-contact-name').textContent = name;
                document.querySelector('.current-contact-avatar').textContent = initials;
                
                // Set current receiver and load messages
                const newReceiverId = contact.getAttribute('data-user-id');
                if (newReceiverId && newReceiverId !== this.currentReceiverId) {
                    this.currentReceiverId = newReceiverId;
                    this.loadMessages(this.currentReceiverId).then(() => {
                        // Reset hybrid polling for new conversation
                        if (this.isPolling) {
                            this.stopPolling();
                            this.startHybridPolling();
                        }
                    });
                }
            });
        });
    }
    
    // Initialize Pusher with enhanced connection handling
    initPusher() {
        try {
            this.pusher = new Pusher(window.chatConfig.pusherKey, {
                cluster: window.chatConfig.pusherCluster,
                encrypted: true,
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                },
                enabledTransports: ['ws', 'wss'],
                disabledTransports: []
            });
            
            // Connection state monitoring
            this.pusher.connection.bind('connected', () => {
                console.log('Pusher connected successfully');
                this.isPusherConnected = true;
                this.reconnectAttempts = 0;
            });
            
            this.pusher.connection.bind('disconnected', () => {
                console.log('Pusher disconnected');
                this.isPusherConnected = false;
            });
            
            this.pusher.connection.bind('error', (error) => {
                console.error('Pusher connection error:', error);
                this.isPusherConnected = false;
                this.handleReconnection();
            });
            
            if (window.chatConfig.userId) {
                this.channel = this.pusher.subscribe(`chat.${window.chatConfig.userId}`);
                
                this.channel.bind('pusher:subscription_succeeded', () => {
                    console.log('Successfully subscribed to chat channel');
                });
                
                this.channel.bind('pusher:subscription_error', (error) => {
                    console.error('Pusher subscription error:', error);
                    this.isPusherConnected = false;
                });
                
                this.channel.bind('message.sent', (data) => {
                    console.log('Received message via Pusher:', data);
                    this.handleIncomingMessage(data);
                });
            }
        } catch (error) {
            console.error('Failed to initialize Pusher:', error);
            this.isPusherConnected = false;
        }
    }
    
    // Handle incoming messages from Pusher or polling
    handleIncomingMessage(data) {
        if (!data.message) return;
        
        const messageId = data.message.id;
        
        // Prevent duplicate messages
        if (this.processedMessageIds.has(messageId)) {
            return;
        }
        
        this.processedMessageIds.add(messageId);
        
        // Update last message ID
        if (messageId > this.lastMessageId) {
            this.lastMessageId = messageId;
        }
        
        // Only show message if it's from current conversation
        if (data.message.user_id == this.currentReceiverId || data.message.receiver_id == this.currentReceiverId) {
            const isUser = data.message.user_id == window.chatConfig.userId;
            if (!isUser) {
                this.addMessage(data.message.message, false, data.message.image_url, data.message.created_at, data.user);
                this.markAsRead(data.message.id);
            }
        }
        
        this.updateUnreadCount();
    }
    
    // Handle reconnection attempts
    handleReconnection() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            console.log(`Attempting to reconnect Pusher (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
            
            setTimeout(() => {
                if (this.pusher) {
                    this.pusher.disconnect();
                }
                this.initPusher();
            }, 2000 * this.reconnectAttempts);
        } else {
            console.log('Max reconnection attempts reached, relying on polling');
        }
    }
    
    // Monitor connection status
    monitorConnection() {
        setInterval(() => {
            if (this.pusher && this.pusher.connection) {
                const state = this.pusher.connection.state;
                if (state !== 'connected' && this.isPusherConnected) {
                    console.log('Connection lost, updating status');
                    this.isPusherConnected = false;
                }
            }
        }, 10000); // Check every 10 seconds
    }
    
    // Start hybrid polling - runs alongside Pusher for reliability
    startHybridPolling() {
        if (this.isPolling) return;
        
        this.isPolling = true;
        console.log('Starting hybrid polling for message synchronization');
        
        this.pollingInterval = setInterval(async () => {
            if (this.currentReceiverId) {
                try {
                    const response = await fetch(`/chat/messages/${this.currentReceiverId}`);
                    const data = await response.json();
                    
                    if (response.ok && data.success && data.messages) {
                        const newMessages = data.messages.filter(msg => 
                            msg.id > this.lastMessageId && !this.processedMessageIds.has(msg.id)
                        );
                        
                        if (newMessages.length > 0) {
                            newMessages.forEach(message => {
                                this.handleIncomingMessage({ message, user: message.user });
                            });
                        }
                    }
                } catch (error) {
                    console.error('Hybrid polling error:', error);
                }
            }
        }, this.isPusherConnected ? 10000 : 3000); // Slower polling when Pusher is connected
    }
    
    // Stop polling
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
            this.isPolling = false;
            console.log('Stopped hybrid polling');
        }
    }
    
    // Function to load messages
    async loadMessages(userId) {
        if (!userId) {
            console.error('No user ID provided for loading messages');
            return;
        }
        
        this.messagesContainer.innerHTML = '<div class="loading-messages"><i class="fas fa-spinner fa-spin"></i> Memuat pesan...</div>';
        
        try {
            const response = await fetch(`/chat/messages/${userId}`);
            const data = await response.json();
            
            this.messagesContainer.innerHTML = '';
            
            if (response.ok && data.success) {
                if (data.messages && data.messages.length > 0) {
                    this.lastMessageId = Math.max(...data.messages.map(msg => msg.id));
                    
                    // Clear processed messages for new conversation
                    this.processedMessageIds.clear();
                    
                    data.messages.forEach(message => {
                        const isUser = message.user_id == window.chatConfig.userId;
                        this.addMessage(message.message, isUser, message.image_url, message.created_at, message.user);
                        this.processedMessageIds.add(message.id);
                    });
                } else {
                    this.messagesContainer.innerHTML = '<div class="empty-chat"><i class="fas fa-comments"></i><p>Belum ada pesan. Mulai percakapan!</p></div>';
                    this.lastMessageId = 0;
                    this.processedMessageIds.clear();
                }
            } else {
                console.error('Error response:', data);
                this.messagesContainer.innerHTML = '<div class="error-loading"><i class="fas fa-exclamation-triangle"></i><p>Gagal memuat pesan. Silakan coba lagi.</p></div>';
            }
        } catch (error) {
            console.error('Error loading messages:', error);
            this.messagesContainer.innerHTML = '<div class="error-loading"><i class="fas fa-exclamation-triangle"></i><p>Terjadi kesalahan saat memuat pesan.</p></div>';
        }
    }
    
    // Function to send a message
    async sendMessage() {
        const text = this.messageInput.value.trim();
        
        if (!this.currentReceiverId) {
            this.showNotification('Pilih kontak terlebih dahulu');
            return;
        }
        
        if (text || this.selectedImage) {
            const formData = new FormData();
            formData.append('receiver_id', this.currentReceiverId);
            formData.append('message', text);
            
            if (this.selectedImage) {
                const response = await fetch(this.selectedImage);
                const blob = await response.blob();
                formData.append('image', blob, 'image.jpg');
            }
            
            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    // Add sent message to UI
                    this.addMessage(text, true, this.selectedImage);
                    
                    // Track sent message to prevent duplication
                    if (data.message && data.message.id) {
                        this.processedMessageIds.add(data.message.id);
                        if (data.message.id > this.lastMessageId) {
                            this.lastMessageId = data.message.id;
                        }
                    }
                    
                    this.messageInput.value = '';
                    this.clearImagePreview();
                    this.updateUnreadCount();
                } else {
                    this.showNotification('Gagal mengirim pesan');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                this.showNotification('Terjadi kesalahan saat mengirim pesan');
            }
        }
    }
    
    // Function to add a message to the chat
    addMessage(text, isUser, imageUrl = null, timestamp = null, user = null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'sent' : 'received'}`;
        
        let messageContent = '';
        
        if (text) {
            messageContent += `<div class="message-text">${this.escapeHtml(text)}</div>`;
        }
        
        if (imageUrl) {
            messageContent += `<img src="${imageUrl}" alt="Image" class="message-image" onclick="this.style.transform = this.style.transform ? '' : 'scale(1.5)'; this.style.zIndex = this.style.zIndex ? '' : '1000'; this.style.position = this.style.position ? '' : 'relative';">`;
        }
        
        const time = timestamp ? new Date(timestamp).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        messageContent += `<div class="message-time">${time}</div>`;
        
        messageDiv.innerHTML = messageContent;
        this.messagesContainer.appendChild(messageDiv);
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    }
    
    // Function to handle image upload
    handleImageUpload(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                this.showNotification('Ukuran file terlalu besar. Maksimal 5MB.');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = (e) => {
                this.selectedImage = e.target.result;
                this.showImagePreview(e.target.result);
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Function to show image preview
    showImagePreview(imageSrc) {
        this.imagePreview.innerHTML = `
            <img src="${imageSrc}" alt="Preview">
            <div class="cancel-image"><i class="fas fa-times"></i></div>
        `;
        this.imagePreview.style.display = 'block';
    }
    
    // Function to clear image preview
    clearImagePreview() {
        this.selectedImage = null;
        this.imagePreview.style.display = 'none';
        this.imagePreview.innerHTML = '';
        this.imageUploadInput.value = '';
    }
    
    // Function to mark messages as read
    async markAsRead(messageId) {
        try {
            await fetch('/chat/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message_id: messageId })
            });
        } catch (error) {
            console.error('Error marking message as read:', error);
        }
    }
    
    // Function to update unread count
    async updateUnreadCount() {
        try {
            const response = await fetch('/chat/unread-count');
            const data = await response.json();
            
            if (response.ok && data.success) {
                const badges = document.querySelectorAll('.unread-badge');
                badges.forEach(badge => {
                    const userId = badge.closest('.contact').getAttribute('data-user-id');
                    const count = data.unread_counts[userId] || 0;
                    
                    if (count > 0) {
                        badge.textContent = count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                });
            }
        } catch (error) {
            console.error('Error updating unread count:', error);
        }
    }
    
    // Function to show notification
    showNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = `
            <i class="fas fa-info-circle"></i>
            <span>${message}</span>
        `;
        
        // Add to body
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => document.body.removeChild(notification), 300);
        }, 3000);
    }
    
    // Function to escape HTML
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Function to set initial contact
    setInitialContact() {
        // Check if there's an active contact (from URL parameter)
        const activeContact = document.querySelector('.contact.active[data-user-id]');
        const targetContact = activeContact || document.querySelector('.contact[data-user-id]');
        
        if (targetContact) {
            // Simulate click to properly activate the contact
            targetContact.click();
            console.log('Setting initial contact:', targetContact.getAttribute('data-user-id'));
        } else {
            console.log('No contacts available');
            this.messagesContainer.innerHTML = '<div class="empty-chat"><i class="fas fa-users"></i><p>Tidak ada kontak tersedia untuk chat.</p></div>';
        }
    }
}

// Initialize chat app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.chatApp = new ChatApp();
});