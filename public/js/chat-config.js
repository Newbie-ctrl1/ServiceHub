// Chat Configuration
window.chatConfig = {
    // Pusher configuration - These will be set dynamically from the layout
    pusherKey: window.PUSHER_APP_KEY || '',
    pusherCluster: window.PUSHER_APP_CLUSTER || 'ap1',
    
    // User configuration - This will be set dynamically from the layout
    userId: window.CURRENT_USER_ID || null,
    
    // API endpoints
    endpoints: {
        sendMessage: '/chat/send',
        getMessages: '/chat/messages',
        markAsRead: '/chat/mark-as-read',
        unreadCount: '/chat/unread-count'
    },
    
    // Chat settings
    settings: {
        maxFileSize: 5 * 1024 * 1024, // 5MB
        pollingInterval: 3000, // 3 seconds
        notificationDuration: 3000, // 3 seconds
        pusherFallbackDelay: 5000 // 5 seconds
    },
    
    // Messages
    messages: {
        selectContact: 'Pilih kontak terlebih dahulu',
        fileTooLarge: 'Ukuran file terlalu besar. Maksimal 5MB.',
        sendFailed: 'Gagal mengirim pesan',
        sendError: 'Terjadi kesalahan saat mengirim pesan',
        loadingMessages: 'Memuat pesan...',
        noMessages: 'Belum ada pesan. Mulai percakapan!',
        loadError: 'Gagal memuat pesan. Silakan coba lagi.',
        loadException: 'Terjadi kesalahan saat memuat pesan.',
        noContacts: 'Tidak ada kontak tersedia untuk chat.'
    }
};
