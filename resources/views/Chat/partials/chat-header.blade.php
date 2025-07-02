{{-- Chat Header Component --}}
<div class="chat-header">
    <div class="header-left">
        <div class="logo">
            <i class="fas fa-comments"></i>
            <span>ServiceHub</span>
        </div>
    </div>
    <div class="header-right">
        <div class="user-info">
            <div class="user-avatar">
                @if(auth()->user()->profile_photo)
                    <img src="data:image/jpeg;base64,{{ base64_encode(auth()->user()->profile_photo) }}" alt="{{ auth()->user()->name }}" class="header-avatar-img">
                @else
                    <img src="{{ asset('images/default-profile.png') }}" alt="{{ auth()->user()->name }}" class="header-avatar-img">
                @endif
            </div>
            <span class="user-name">{{ auth()->user()->name }}</span>
        </div>
        <div class="header-actions">

            <a href="{{ route('home') }}" class="btn-icon" title="Home">
                <i class="fas fa-home"></i>
            </a>
        </div>
    </div>
</div>