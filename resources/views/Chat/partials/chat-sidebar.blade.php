{{-- Chat Sidebar Component --}}
<div class="chat-sidebar">
    <div class="sidebar-header">
        <h3>Kontak</h3>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari kontak..." id="contactSearch">
        </div>
    </div>
    
    <div class="contacts-list" id="contactsList">
        @forelse($users as $user)
            <div class="contact {{ isset($selectedUser) && $selectedUser->id == $user->id ? 'active' : '' }}" data-user-id="{{ $user->id }}">
                <div class="contact-avatar">
                    @if($user->profile_photo)
                        <img src="data:image/jpeg;base64,{{ base64_encode($user->profile_photo) }}" alt="{{ $user->name }}" class="avatar-img">
                    @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="{{ $user->name }}" class="avatar-img">
                    @endif
                </div>
                <div class="contact-info">
                    <div class="contact-name">{{ $user->name }}</div>
                    <div class="contact-status">
                        <span class="status-indicator online"></span>
                        Online
                    </div>
                </div>
                <div class="contact-meta">
                    <div class="unread-badge" style="display: none;">0</div>
                    <div class="last-message-time"></div>
                </div>
            </div>
        @empty
            <div class="no-contacts">
                <i class="fas fa-users"></i>
                <p>Tidak ada kontak tersedia</p>
            </div>
        @endforelse
    </div>
</div>

<script>
// Contact search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('contactSearch');
    const contactsList = document.getElementById('contactsList');
    
    if (searchInput && contactsList) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const contacts = contactsList.querySelectorAll('.contact');
            
            contacts.forEach(contact => {
                const contactName = contact.querySelector('.contact-name').textContent.toLowerCase();
                if (contactName.includes(searchTerm)) {
                    contact.style.display = 'flex';
                } else {
                    contact.style.display = 'none';
                }
            });
        });
    }
    
    // Auto-select contact if selectedUser is provided
    @if(isset($selectedUser))
        // Wait for ChatApp to be initialized
        setTimeout(() => {
            const selectedContact = document.querySelector('.contact[data-user-id="{{ $selectedUser->id }}"]');
            if (selectedContact) {
                // Trigger contact selection
                selectedContact.click();
                console.log('Auto-selected contact: {{ $selectedUser->name }}');
            }
        }, 500);
    @endif
});
</script>