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
        
        // Initialize connection status with unknown state
        this.updateConnectionStatus('unknown');
        
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
    
    // Initialize Pusher with enhanced connection handling and validation
    initPusher() {
        try {
            // Ensure we're using the global variables if available
            if (window.PUSHER_APP_KEY && window.PUSHER_APP_CLUSTER) {
                console.log('Using global Pusher variables for initialization');
                window.chatConfig.pusherKey = window.PUSHER_APP_KEY;
                window.chatConfig.pusherCluster = window.PUSHER_APP_CLUSTER;
            }
            
            // Check for previously successful cluster
            const savedCluster = localStorage.getItem('pusher_successful_cluster');
            if (savedCluster) {
                console.log(`Using previously successful cluster: ${savedCluster}`);
                window.chatConfig.pusherCluster = savedCluster;
            }
            
            // Validate Pusher configuration
            if (!this.validatePusherConfig()) {
                console.error('Pusher initialization failed: Invalid configuration');
                this.isPusherConnected = false;
                this.updateConnectionStatus('failed');
                this.showNotification('Koneksi chat real-time gagal. Menggunakan mode polling.');
                return;
            }
            
            console.log('Initializing Pusher with:', {
                key: window.chatConfig.pusherKey,
                cluster: window.chatConfig.pusherCluster
            });
            
            // Update UI to show connecting status
            this.updateConnectionStatus('connecting');
            
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
                this.updateConnectionStatus('connected');
                
                // Verify WebSocket connection is actually established
                setTimeout(() => {
                    this.verifyWebSocketConnection();
                }, 1000);
            });
            
            this.pusher.connection.bind('disconnected', () => {
                console.log('Pusher disconnected');
                this.isPusherConnected = false;
                this.updateConnectionStatus('disconnected');
            });
            
            this.pusher.connection.bind('error', (error) => {
                console.error('Pusher connection error:', error);
                this.isPusherConnected = false;
                this.updateConnectionStatus('failed');
                this.handleReconnection();
            });
            
            if (window.chatConfig.userId) {
                this.subscribeToChannel();
            }
        } catch (error) {
            console.error('Failed to initialize Pusher:', error);
            
            // Provide more detailed error information
            if (error.message && error.message.includes('cluster')) {
                console.error('Pusher cluster configuration error. Check your .env file for PUSHER_APP_CLUSTER value.');
            } else if (error.message && error.message.includes('key')) {
                console.error('Pusher key configuration error. Check your .env file for PUSHER_APP_KEY value.');
            }
            
            // Log connection URL for debugging
            if (window.chatConfig.pusherCluster) {
                console.log(`Expected WebSocket URL format: wss://ws-${window.chatConfig.pusherCluster}.pusher.com/...`);
            }
            
            this.isPusherConnected = false;
            
            // Show notification to user
            this.showNotification('Koneksi chat real-time gagal. Mencoba alternatif...');
            
            // Try with alternative configuration
            this.tryAlternativeConnection();
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
    
    // Subscribe to Pusher channel for receiving messages
    subscribeToChannel() {
        if (!this.pusher || !window.chatConfig.userId) {
            console.error('Cannot subscribe to channel: Pusher not initialized or user ID not available');
            return false;
        }
        
        const channelName = `chat.${window.chatConfig.userId}`;
        console.log(`Subscribing to channel: ${channelName}`);
        
        try {
            // Unsubscribe from existing channel if any
            if (this.channel) {
                try {
                    this.pusher.unsubscribe(channelName);
                } catch (e) {
                    console.warn('Error unsubscribing from existing channel:', e);
                }
            }
            
            // Subscribe to channel
            this.channel = this.pusher.subscribe(channelName);
            
            // Set up event handlers
            this.channel.bind('pusher:subscription_succeeded', () => {
                console.log('Successfully subscribed to chat channel');
            });
            
            this.channel.bind('pusher:subscription_error', (error) => {
                console.error('Pusher subscription error:', error);
                this.isPusherConnected = false;
                this.updateConnectionStatus('failed');
            });
            
            this.channel.bind('message.sent', (data) => {
                console.log('Received message via Pusher:', data);
                this.handleIncomingMessage(data);
            });
            
            return true;
        } catch (error) {
            console.error('Failed to subscribe to channel:', error);
            return false;
        }
    }
    
    // Verify that WebSocket connection is actually established
    verifyWebSocketConnection() {
        if (!this.pusher || !this.pusher.connection) {
            console.error('Pusher instance or connection not available');
            return false;
        }
        
        const state = this.pusher.connection.state;
        console.log(`Verifying WebSocket connection. Current state: ${state}`);
        
        // Check if socket is available
        if (!this.pusher.connection.socket) {
            console.error('WebSocket connection not established despite Pusher reporting connected state');
            
            // Log detailed information
            this.logWebSocketInfo();
            
            // Try to reconnect with correct configuration
            this.reconnectWithCorrectCluster();
            return false;
        }
        
        // Verify socket URL matches expected format
        const socketUrl = this.pusher.connection.socket.url;
        const expectedUrl = `wss://ws-${window.chatConfig.pusherCluster}.pusher.com`;
        
        if (!socketUrl || !socketUrl.includes(expectedUrl)) {
            console.error('WebSocket URL mismatch or invalid');
            console.error(`Expected URL to include: ${expectedUrl}`);
            console.error(`Actual URL: ${socketUrl || 'tidak tersedia'}`);
            
            // Try to reconnect with correct configuration
            this.reconnectWithCorrectCluster();
            return false;
        }
        
        console.log('WebSocket connection verified successfully');
        return true;
    }
    
    // Monitor connection status with enhanced diagnostics
    monitorConnection() {
        // Initial connection check
        this.validatePusherConfig();
        
        setInterval(() => {
            if (this.pusher && this.pusher.connection) {
                const state = this.pusher.connection.state;
                if (state !== 'connected' && this.isPusherConnected) {
                    console.log('Connection lost, updating status');
                    this.isPusherConnected = false;
                }
                
                // Log connection state for debugging
                console.log(`Pusher connection state: ${state}`);
                if (state === 'connecting') {
                    console.log(`Connecting to: wss://ws-${window.chatConfig.pusherCluster}.pusher.com`);
                }
                
                // Verify WebSocket connection if Pusher reports connected
                if (state === 'connected') {
                    this.verifyWebSocketConnection();
                } else {
                    // Log WebSocket URL information for debugging
                    this.logWebSocketInfo();
                }
            }
        }, 10000); // Check every 10 seconds
    }
    
    // Log WebSocket URL information for debugging
    logWebSocketInfo() {
        const config = window.chatConfig;
        
        // Expected WebSocket URL
        const expectedUrl = `wss://ws-${config.pusherCluster}.pusher.com`;
        console.log(`Expected WebSocket URL: ${expectedUrl}`);
        
        // Log global variables for debugging
        console.log('Global Pusher variables:', {
            PUSHER_APP_KEY: window.PUSHER_APP_KEY,
            PUSHER_APP_CLUSTER: window.PUSHER_APP_CLUSTER
        });
        
        // Log chatConfig for debugging
        console.log('chatConfig values:', {
            pusherKey: config.pusherKey,
            pusherCluster: config.pusherCluster
        });
        
        // Actual WebSocket URL if available
        if (this.pusher && this.pusher.connection && this.pusher.connection.socket) {
            const actualUrl = this.pusher.connection.socket.url || 'tidak tersedia';
            console.log(`Actual WebSocket URL: ${actualUrl}`);
            
            // Check if URLs match
            if (actualUrl.includes(expectedUrl)) {
                console.log('WebSocket URL format is correct');
            } else {
                console.warn('WebSocket URL format mismatch!');
                console.warn(`Expected to include: ${expectedUrl}`);
                console.warn(`But got: ${actualUrl}`);
                
                // Try to reconnect with correct cluster
                this.reconnectWithCorrectCluster();
            }
        } else {
            console.log('Actual WebSocket URL: tidak tersedia (koneksi belum dibuat)');
            
            // Check if Pusher is in connected state but socket is not available
            if (this.pusher && this.pusher.connection && this.pusher.connection.state === 'connected') {
                console.warn('Pusher reports connected state but WebSocket is not available');
                this.reconnectWithCorrectCluster();
            }
        }
    }
    
    // Reconnect with correct cluster
    reconnectWithCorrectCluster() {
        console.log('Attempting to reconnect with correct cluster configuration');
        
        // Ensure we're using the global variables directly
        if (window.PUSHER_APP_KEY && window.PUSHER_APP_CLUSTER) {
            console.log('Using global Pusher variables for reconnection');
            window.chatConfig.pusherKey = window.PUSHER_APP_KEY;
            window.chatConfig.pusherCluster = window.PUSHER_APP_CLUSTER;
            
            // Disconnect existing connection
            if (this.pusher) {
                this.pusher.disconnect();
            }
            
            // Reinitialize Pusher
            setTimeout(() => {
                this.initPusher();
            }, 1000);
        }
    }
    
    // Validate Pusher configuration
    validatePusherConfig() {
        const config = window.chatConfig;
        let isValid = true;
        let errorMessages = [];
        
        if (!config) {
            console.error('Chat configuration is missing');
            errorMessages.push('Konfigurasi chat tidak tersedia');
            return false;
        }
        
        // Check for required Pusher configuration
        if (!config.pusherKey) {
            console.error('Pusher key is missing in configuration');
            errorMessages.push('Pusher key tidak tersedia');
            
            // Try to use global variable if available
            if (window.PUSHER_APP_KEY) {
                console.log('Using global PUSHER_APP_KEY as fallback');
                config.pusherKey = window.PUSHER_APP_KEY;
                isValid = true;
            } else {
                isValid = false;
            }
        }
        
        if (!config.pusherCluster) {
            console.error('Pusher cluster is missing in configuration');
            errorMessages.push('Pusher cluster tidak tersedia');
            
            // Try to use global variable if available
            if (window.PUSHER_APP_CLUSTER) {
                console.log('Using global PUSHER_APP_CLUSTER as fallback');
                config.pusherCluster = window.PUSHER_APP_CLUSTER;
                isValid = true;
            } else {
                isValid = false;
            }
        }
        
        // Validate cluster format
        if (!/^[a-z]{2}[0-9]?$/.test(config.pusherCluster)) {
            console.error(`Invalid Pusher cluster format: ${config.pusherCluster}`);
            console.log('Cluster should be in format like: ap1, eu, us2, etc.');
            errorMessages.push(`Format cluster tidak valid: ${config.pusherCluster}`);
            
            // Try to use global variable if available and valid
            if (window.PUSHER_APP_CLUSTER && /^[a-z]{2}[0-9]?$/.test(window.PUSHER_APP_CLUSTER)) {
                console.log('Using global PUSHER_APP_CLUSTER as fallback for invalid format');
                config.pusherCluster = window.PUSHER_APP_CLUSTER;
                isValid = true;
            } else {
                // Default to ap1 as last resort
                console.log('Defaulting to ap1 cluster as last resort');
                config.pusherCluster = 'ap1';
                isValid = true;
            }
        }
        
        // Display error messages if any
        if (errorMessages.length > 0 && !isValid) {
            // Update connection status
            this.updateConnectionStatus('failed');
            
            // Show notification with first error
            this.showNotification(errorMessages[0], 5000);
            
            // Log all errors
            console.error('Pusher configuration errors:', errorMessages);
        } else {
            console.log('Pusher configuration is valid or has been fixed:', {
                pusherKey: config.pusherKey,
                pusherCluster: config.pusherCluster
            });
        }
        
        return isValid;
    }
    
    // Try alternative connection if primary fails
    tryAlternativeConnection() {
        console.log('Attempting alternative Pusher connection...');
        
        // Update UI to show connecting status
        this.updateConnectionStatus('connecting');
        
        // Ensure we're using the global variables if available
        if (window.PUSHER_APP_KEY && window.PUSHER_APP_CLUSTER) {
            console.log('Using global Pusher variables for alternative connection');
            window.chatConfig.pusherKey = window.PUSHER_APP_KEY;
            
            // If the global cluster is different from current one, try it first
            if (window.PUSHER_APP_CLUSTER !== window.chatConfig.pusherCluster) {
                console.log(`Trying global cluster first: ${window.PUSHER_APP_CLUSTER}`);
                this.showNotification(`Mencoba koneksi dengan cluster global: ${window.PUSHER_APP_CLUSTER}`, 2000);
                
                try {
                    // Update configuration with global cluster
                    window.chatConfig.pusherCluster = window.PUSHER_APP_CLUSTER;
                    
                    // Create new Pusher instance with global cluster
                    const globalPusher = new Pusher(window.chatConfig.pusherKey, {
                        cluster: window.chatConfig.pusherCluster,
                        encrypted: true,
                        enabledTransports: ['ws', 'wss'],
                        disabledTransports: []
                    });
                    
                    // Set up connection event handlers
                    globalPusher.connection.bind('connected', () => {
                        console.log(`Successfully connected with global cluster: ${window.PUSHER_APP_CLUSTER}`);
                        this.showNotification(`Koneksi berhasil dengan cluster global: ${window.PUSHER_APP_CLUSTER}`);
                        
                        // Replace the main pusher instance
                        if (this.pusher) {
                            this.pusher.disconnect();
                        }
                        
                        this.pusher = globalPusher;
                        this.isPusherConnected = true;
                        
                        // Update UI to show connected status
                        this.updateConnectionStatus('connected');
                        
                        // Subscribe to the channel with the new connection
                        this.subscribeToChannel();
                        
                        // Save successful cluster to localStorage for future use
                        localStorage.setItem('pusher_successful_cluster', window.PUSHER_APP_CLUSTER);
                        
                        return; // Exit if successful
                    });
                    
                    // Handle connection errors
                    globalPusher.connection.bind('error', (error) => {
                        console.error(`Failed to connect with global cluster ${window.PUSHER_APP_CLUSTER}:`, error);
                        // Continue with common clusters
                    });
                    
                    // Set timeout to prevent hanging
                    setTimeout(() => {
                        if (globalPusher.connection.state !== 'connected') {
                            console.log(`Global cluster ${window.PUSHER_APP_CLUSTER} connection timed out`);
                            globalPusher.disconnect();
                            // Continue with common clusters
                        }
                    }, 5000);
                    
                } catch (error) {
                    console.error(`Failed to connect with global cluster ${window.PUSHER_APP_CLUSTER}:`, error);
                    // Continue with common clusters
                }
            }
        }
        
        // Common Pusher clusters to try
        const commonClusters = ['ap1', 'ap2', 'us1', 'us2', 'eu', 'mt1'];
        
        // Store original cluster for reference
        const originalCluster = window.chatConfig.pusherCluster;
        
        // Try each cluster except the one that already failed
        for (const cluster of commonClusters) {
            if (cluster === originalCluster) continue;
            
            console.log(`Trying alternative cluster: ${cluster}`);
            this.showNotification(`Mencoba koneksi dengan cluster: ${cluster}`, 2000);
            
            try {
                // Update configuration temporarily
                window.chatConfig.pusherCluster = cluster;
                
                // Create new Pusher instance with alternative cluster
                const altPusher = new Pusher(window.chatConfig.pusherKey, {
                    cluster: cluster,
                    encrypted: true,
                    enabledTransports: ['ws', 'wss'],
                    disabledTransports: []
                });
                
                // Set up connection event handlers
                altPusher.connection.bind('connected', () => {
                    console.log(`Successfully connected with alternative cluster: ${cluster}`);
                    this.showNotification(`Koneksi berhasil dengan cluster: ${cluster}`);
                    
                    // Replace the main pusher instance
                    if (this.pusher) {
                        this.pusher.disconnect();
                    }
                    
                    this.pusher = altPusher;
                    this.isPusherConnected = true;
                    
                    // Update UI to show connected status
                    this.updateConnectionStatus('connected');
                    
                    // Subscribe to the channel with the new connection
                    this.subscribeToChannel();
                    
                    // Save successful cluster to localStorage for future use
                    localStorage.setItem('pusher_successful_cluster', cluster);
                    
                    return; // Exit the loop if successful
                });
                
                // Handle connection errors
                altPusher.connection.bind('error', (error) => {
                    console.error(`Failed to connect with alternative cluster ${cluster}:`, error);
                    // Update UI to show failed status for this attempt
                    this.updateConnectionStatus('failed');
                });
                
                // Set timeout to prevent hanging
                setTimeout(() => {
                    if (altPusher.connection.state !== 'connected') {
                        console.log(`Alternative cluster ${cluster} connection timed out`);
                        altPusher.disconnect();
                    }
                }, 5000);
                
            } catch (error) {
                console.error(`Failed to connect with alternative cluster ${cluster}:`, error);
                this.updateConnectionStatus('failed');
            }
        }
        
        // Restore original configuration after attempts
        window.chatConfig.pusherCluster = originalCluster;
        
        // If all alternatives fail, fall back to polling
        setTimeout(() => {
            if (!this.isPusherConnected) {
                console.log('All alternative connections failed, using polling mode');
                this.showNotification('Koneksi real-time gagal. Menggunakan mode polling.');
                this.updateConnectionStatus('failed');
            }
        }, 15000); // Wait for all connection attempts
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
    showNotification(message, duration = 3000) {
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
        }, duration);
    }
    
    // Display connection status in UI
    updateConnectionStatus(state) {
        // Create or update status indicator
        let statusIndicator = document.getElementById('pusher-status-indicator');
        
        if (!statusIndicator) {
            statusIndicator = document.createElement('div');
            statusIndicator.id = 'pusher-status-indicator';
            statusIndicator.style.position = 'fixed';
            statusIndicator.style.bottom = '10px';
            statusIndicator.style.right = '10px';
            statusIndicator.style.padding = '5px 10px';
            statusIndicator.style.borderRadius = '3px';
            statusIndicator.style.fontSize = '12px';
            statusIndicator.style.zIndex = '9999';
            statusIndicator.style.cursor = 'pointer';
            statusIndicator.title = 'Klik untuk detail koneksi';
            
            // Add click event to show detailed info
            statusIndicator.addEventListener('click', () => {
                this.showConnectionDetails();
            });
            
            document.body.appendChild(statusIndicator);
        }
        
        // Get cluster info for display
        const config = window.chatConfig;
        const clusterInfo = config && config.pusherCluster ? ` (${config.pusherCluster})` : '';
        
        // Update status indicator based on connection state
        switch(state) {
            case 'connected':
                statusIndicator.textContent = `🟢 Terhubung${clusterInfo}`;
                statusIndicator.style.backgroundColor = '#dff0d8';
                statusIndicator.style.color = '#3c763d';
                break;
            case 'connecting':
                statusIndicator.textContent = `🟡 Menghubungkan...${clusterInfo}`;
                statusIndicator.style.backgroundColor = '#fcf8e3';
                statusIndicator.style.color = '#8a6d3b';
                break;
            case 'disconnected':
            case 'failed':
                statusIndicator.textContent = `🔴 Terputus${clusterInfo}`;
                statusIndicator.style.backgroundColor = '#f2dede';
                statusIndicator.style.color = '#a94442';
                break;
            default:
                statusIndicator.textContent = `⚪ Tidak diketahui${clusterInfo}`;
                statusIndicator.style.backgroundColor = '#f5f5f5';
                statusIndicator.style.color = '#333';
        }
        
        // Add debug mode toggle
        statusIndicator.setAttribute('data-debug', 'false');
        statusIndicator.addEventListener('dblclick', (e) => {
            e.stopPropagation();
            const isDebug = statusIndicator.getAttribute('data-debug') === 'true';
            statusIndicator.setAttribute('data-debug', (!isDebug).toString());
            
            if (!isDebug) {
                // Show extended debug info
                const globalKey = window.PUSHER_APP_KEY || 'tidak tersedia';
                const globalCluster = window.PUSHER_APP_CLUSTER || 'tidak tersedia';
                const configKey = config && config.pusherKey ? '✓' : '✗';
                
                statusIndicator.innerHTML = `
                    <div style="font-size:10px;line-height:1.2">
                        <div>🔑 ${configKey} | 🌐 ${config.pusherCluster || '?'}</div>
                        <div>G: ${globalKey.substring(0,5)}...${globalCluster}</div>
                    </div>
                `;
            } else {
                // Restore normal status
                this.updateConnectionStatus(state);
            }
        });
    }
    
    // Show detailed connection information
    showConnectionDetails() {
        const config = window.chatConfig;
        const state = this.pusher ? this.pusher.connection.state : 'not initialized';
        
        // Get global variables
        const globalKey = window.PUSHER_APP_KEY || 'tidak tersedia';
        const globalCluster = window.PUSHER_APP_CLUSTER || 'tidak tersedia';
        
        // Get actual WebSocket URL if available
        let wsUrl = 'tidak tersedia';
        if (this.pusher && this.pusher.connection && this.pusher.connection.socket) {
            wsUrl = this.pusher.connection.socket.url || `wss://ws-${config.pusherCluster}.pusher.com`;
        } else {
            wsUrl = `wss://ws-${config.pusherCluster}.pusher.com`;
        }
        
        // Check if URL is valid (contains cluster)
        const isUrlValid = wsUrl.includes(`ws-${config.pusherCluster}`);
        
        // Get saved cluster from localStorage if any
        const savedCluster = localStorage.getItem('pusher_successful_cluster') || 'tidak ada';
        
        // Get connection history
        const reconnectAttempts = this.reconnectAttempts || 0;
        
        // Check for configuration issues
        const hasGlobalVars = globalKey !== 'tidak tersedia' && globalCluster !== 'tidak tersedia';
        const hasConfigVars = config && config.pusherKey && config.pusherCluster;
        const configMismatch = hasGlobalVars && hasConfigVars && 
                              (globalKey !== config.pusherKey || globalCluster !== config.pusherCluster);
        
        const details = document.createElement('div');
        details.style.position = 'fixed';
        details.style.bottom = '40px';
        details.style.right = '10px';
        details.style.width = '380px';
        details.style.padding = '15px';
        details.style.backgroundColor = '#fff';
        details.style.boxShadow = '0 0 10px rgba(0,0,0,0.2)';
        details.style.borderRadius = '5px';
        details.style.zIndex = '10000';
        details.style.fontSize = '12px';
        details.style.maxHeight = '80vh';
        details.style.overflowY = 'auto';
        
        details.innerHTML = `
            <h3 style="margin-top:0;font-size:14px;font-weight:bold">Detail Koneksi Pusher</h3>
            <p><strong>Status:</strong> <span style="color:${state === 'connected' ? '#3c763d' : (state === 'connecting' ? '#8a6d3b' : '#a94442')}">${state}</span></p>
            
            <div style="margin-top: 10px; padding: 8px; background-color: #f0f8ff; border-radius: 4px; border-left: 3px solid #1e90ff;">
                <p style="margin: 0 0 5px 0; font-weight: bold;">Konfigurasi Global:</p>
                <p style="margin: 0;"><strong>PUSHER_APP_KEY:</strong> ${globalKey !== 'tidak tersedia' ? globalKey.substring(0, 5) + '...' : 'tidak tersedia'}</p>
                <p style="margin: 0;"><strong>PUSHER_APP_CLUSTER:</strong> ${globalCluster}</p>
            </div>
            
            <div style="margin-top: 10px; padding: 8px; background-color: #f0fff0; border-radius: 4px; border-left: 3px solid #32cd32;">
                <p style="margin: 0 0 5px 0; font-weight: bold;">Konfigurasi chatConfig:</p>
                <p style="margin: 0;"><strong>pusherKey:</strong> ${config.pusherKey ? config.pusherKey.substring(0, 5) + '...' : 'tidak tersedia'}</p>
                <p style="margin: 0;"><strong>pusherCluster:</strong> ${config.pusherCluster || 'tidak tersedia'}</p>
            </div>
            
            <div style="margin-top: 10px;">
                <p><strong>URL Ekspektasi:</strong> wss://ws-${config.pusherCluster}.pusher.com</p>
                <p><strong>URL Aktual:</strong> <span style="word-break: break-all; color:${isUrlValid ? '#3c763d' : '#a94442'}">${wsUrl}</span></p>
                <p><strong>URL Valid:</strong> <span style="color:${isUrlValid ? '#3c763d' : '#a94442'}">${isUrlValid ? '✓ Ya' : '✗ Tidak'}</span></p>
                <p><strong>Mode Polling:</strong> ${this.pollingEnabled ? 'Aktif' : 'Tidak aktif'}</p>
                <p><strong>Cluster Tersimpan:</strong> ${savedCluster}</p>
                <p><strong>Percobaan Koneksi Ulang:</strong> ${reconnectAttempts}</p>
                <p><strong>Koneksi Terakhir:</strong> ${new Date().toLocaleString()}</p>
            </div>
            
            <div style="margin-top: 10px; padding: 8px; background-color: ${configMismatch ? '#fff0f0' : '#f8f9fa'}; border-radius: 4px; ${configMismatch ? 'border-left: 3px solid #ff6347;' : ''}">
                <p style="margin: 0 0 5px 0; font-weight: bold;">Diagnostik:</p>
                <ul style="margin: 0; padding-left: 20px;">
                    ${configMismatch ? '<li style="color: #a94442;"><strong>Ketidakcocokan konfigurasi!</strong> Variabel global dan chatConfig memiliki nilai berbeda.</li>' : ''}
                    ${!hasGlobalVars ? '<li style="color: #a94442;"><strong>Variabel global tidak tersedia!</strong> window.PUSHER_APP_KEY dan/atau window.PUSHER_APP_CLUSTER tidak terdefinisi.</li>' : ''}
                    ${!hasConfigVars ? '<li style="color: #a94442;"><strong>Konfigurasi chatConfig tidak lengkap!</strong> window.chatConfig.pusherKey dan/atau window.chatConfig.pusherCluster tidak terdefinisi.</li>' : ''}
                    <li>Jika URL aktual menunjukkan <code>wss://ws-.pusher.com</code> (tanpa cluster), kemungkinan variabel lingkungan <code>PUSHER_APP_CLUSTER</code> tidak terdefinisi dengan benar.</li>
                    <li>Pastikan nilai <code>PUSHER_APP_CLUSTER</code> di file <code>.env</code> sudah benar (contoh: ap1, eu, us2).</li>
                    <li>Jika menggunakan Railway, pastikan variabel lingkungan sudah dikonfigurasi dengan benar.</li>
                </ul>
            </div>
            
            <div style="margin-top: 15px;">
                <button id="close-connection-details" style="padding:5px 10px;background:#f5f5f5;border:1px solid #ddd;border-radius:3px;cursor:pointer">Tutup</button>
                <button id="retry-connection" style="padding:5px 10px;background:#5cb85c;color:white;border:1px solid #4cae4c;border-radius:3px;cursor:pointer;margin-left:5px">Coba Lagi</button>
                <button id="copy-debug-info" style="padding:5px 10px;background:#5bc0de;color:white;border:1px solid #46b8da;border-radius:3px;cursor:pointer;margin-left:5px">Salin Info</button>
                <button id="force-reconnect" style="padding:5px 10px;background:#f0ad4e;color:white;border:1px solid #eea236;border-radius:3px;cursor:pointer;margin-left:5px">Paksa Koneksi Ulang</button>
            </div>
        `;
        
        document.body.appendChild(details);
        
        // Add event listeners
        document.getElementById('close-connection-details').addEventListener('click', () => {
            document.body.removeChild(details);
        });
        
        document.getElementById('retry-connection').addEventListener('click', () => {
            document.body.removeChild(details);
            this.reconnectPusher();
        });
        
        document.getElementById('copy-debug-info').addEventListener('click', () => {
            // Create debug info text
            const debugInfo = `
                Pusher Debug Info:
                - Status: ${state}
                - Global PUSHER_APP_KEY: ${globalKey !== 'tidak tersedia' ? globalKey.substring(0, 5) + '...' : 'tidak tersedia'}
                - Global PUSHER_APP_CLUSTER: ${globalCluster}
                - chatConfig.pusherKey: ${config.pusherKey ? config.pusherKey.substring(0, 5) + '...' : 'tidak tersedia'}
                - chatConfig.pusherCluster: ${config.pusherCluster || 'tidak tersedia'}
                - URL Ekspektasi: wss://ws-${config.pusherCluster}.pusher.com
                - URL Aktual: ${wsUrl}
                - URL Valid: ${isUrlValid ? 'Ya' : 'Tidak'}
                - Mode Polling: ${this.pollingEnabled ? 'Aktif' : 'Tidak aktif'}
                - Cluster Tersimpan: ${savedCluster}
                - Percobaan Koneksi Ulang: ${reconnectAttempts}
                - Waktu: ${new Date().toLocaleString()}
                
                Catatan: 
                - Jika URL aktual menunjukkan wss://ws-.pusher.com (tanpa cluster), kemungkinan variabel lingkungan PUSHER_APP_CLUSTER tidak terdefinisi dengan benar.
                - Pastikan window.PUSHER_APP_KEY dan window.PUSHER_APP_CLUSTER diinisialisasi sebelum window.chatConfig.
            `.trim();
            
            // Copy to clipboard
            navigator.clipboard.writeText(debugInfo).then(() => {
                this.showNotification('Informasi debug disalin ke clipboard');
            }).catch(err => {
                console.error('Gagal menyalin ke clipboard:', err);
                this.showNotification('Gagal menyalin informasi debug');
            });
        });
        
        // Add force reconnect handler
        document.getElementById('force-reconnect').addEventListener('click', () => {
            document.body.removeChild(details);
            this.reconnectWithCorrectCluster();
        });
    }
    
    // Reconnect Pusher
    reconnectPusher() {
        this.showNotification('Mencoba menghubungkan kembali...');
        
        // Disconnect existing connection if any
        if (this.pusher) {
            this.pusher.disconnect();
        }
        
        // Clear any saved cluster to try fresh
        localStorage.removeItem('pusher_successful_cluster');
        
        // Reinitialize with original configuration
        this.initPusher();
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