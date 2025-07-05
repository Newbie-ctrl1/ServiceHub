@php
  use Illuminate\Support\Facades\Storage;
@endphp
<header>
  <link rel="stylesheet" href="{{ secure_asset('css/chat-icons.css') }}">
  <nav class="navbar navbar-expand-lg navbar-light">
      <div class="header-inner">
        <a class="navbar-brand flex-shrink-0" href="/home"><img src="{{ secure_asset('images/logo.png') }}" alt="logo-image" class="img-fluid">
          ServiceHub</a>
        <div class="header-content d-flex align-items-center justify-content-between">
          <form class="d-flex justify-content-start align-items-center order-mobile-2">
            <div class="search-icon">
              <i class="fa fa-search" aria-hidden="true"></i>
              <input class="form-control" type="search" placeholder="Search" aria-label="Search">
            </div>
            <label class="switch flex-shrink-0 mb-0">
              <!-- <input id="checkbox" type="checkbox">
              <span class="slider round"></span> -->
            </label>
          </form>
          <div class="d-flex align-items-center order-mobile-1">
            @if(Auth::check())
            <a href="{{ route('profile') }}" class="profile">
                @if(Auth::user()->profile_photo)
                    <img src="data:image/jpeg;base64,{{ base64_encode(Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}">
                @else
                    <img src="{{ secure_asset('images/default-profile.png') }}" alt="{{ Auth::user()->name }}">
                @endif
            </a>
            <a href="{{ route('Chat') }}" class="chat" title="Messages"><i class="fa fa-comments" aria-hidden="true"></i></a>
            <a href="{{ route('Notification') }}" class="notification" title="Notifications"><i class="fa fa-bell" aria-hidden="true"></i></a>
            @else
            <a href="{{ route('login') }}" class="profile">
                <img src="{{ secure_asset('images/default-profile.png') }}" alt="Guest">
            </a>
            @endif
            
            @if(Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="store" title="Admin Toko"><i class="fa fa-store" aria-hidden="true"></i></a>
            @elseif(Auth::check())
                <a href="{{ route('admin.register') }}" class="store" title="Daftar Admin Toko"><i class="fa fa-store" aria-hidden="true"></i></a>
            @else
                <a href="{{ route('login') }}" class="store" title="Login untuk Akses Toko"><i class="fa fa-store" aria-hidden="true"></i></a>
            @endif
            
            @if(Auth::check())
            <form action="/logout" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-logout"><i class="fa fa-sign-out" aria-hidden="true"></i> <span class="logout-text">Logout</span></button>
            </form>
            @endif
          </div>
        </div>
        <div class="hamburger-menu" id="menu-toggle" aria-label="Toggle Menu">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
  </nav>
</header>

<div class="menu-overlay" id="menu-overlay"></div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const menuOverlay = document.getElementById('menu-overlay');
    const sidebar = document.getElementById('sidebar');
    const body = document.body;
    
    if (menuToggle && menuOverlay && sidebar) {
      menuToggle.addEventListener('click', function() {
        menuToggle.classList.toggle('ham-style');
        menuOverlay.classList.toggle('active');
        sidebar.classList.toggle('left');
        body.classList.toggle('menu-open');
      });
      
      menuOverlay.addEventListener('click', function() {
        menuToggle.classList.remove('ham-style');
        menuOverlay.classList.remove('active');
        sidebar.classList.remove('left');
        body.classList.remove('menu-open');
      });
    }
    
    // Tutup menu saat ukuran layar berubah ke desktop
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768) {
        menuToggle.classList.remove('ham-style');
        menuOverlay.classList.remove('active');
        sidebar.classList.remove('left');
        body.classList.remove('menu-open');
      }
    });
  });
</script>