<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat ServiceHub</title>
    
    {{-- External CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    {{-- Custom CSS --}}
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
    
    {{-- External JavaScript --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    {{-- Chat Configuration --}}
    <script>
        // Chat Configuration
        window.chatConfig = {
            // Pusher configuration
            pusherKey: '{{ env("PUSHER_APP_KEY") }}',
            pusherCluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            
            // User configuration
            userId: {{ auth()->id() ?? 'null' }},
            
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
    </script>
</head>
<body>
    <div class="chat-app">
        {{-- Chat Header --}}
        @include('Chat.partials.chat-header')
        
        <div class="chat-container">
            {{-- Chat Sidebar --}}
            @include('Chat.partials.chat-sidebar')
            
            {{-- Chat Main Area --}}
            @include('Chat.partials.chat-main')
        </div>
        
        {{-- Payment Panel --}}
        @include('Chat.partials.payment-panel')
    </div>
    
    {{-- Custom JavaScript --}}
    <script src="{{ asset('js/chat.js') }}"></script>
    
    {{-- Additional Scripts --}}
    @stack('scripts')
</body>
</html>