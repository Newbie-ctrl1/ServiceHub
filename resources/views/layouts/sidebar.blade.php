<div class="menu-links fixed-top" id="sidebar">
  <div class="sidebar-header d-flex d-md-none align-items-center justify-content-between p-3 border-bottom">
    <div class="sidebar-logo d-flex align-items-center">
      <img src="{{ asset('images/logo.png') }}" alt="logo-image" class="img-fluid" style="width: 32px; height: auto;">
      <span class="ml-2 font-weight-bold">ServiceHub</span>
    </div>
    <button class="close-sidebar btn" aria-label="Close Sidebar">
      <i class="fa fa-times"></i>
    </button>
  </div>
  <div class="sidebar-profile d-flex align-items-center p-3 mb-3">
    <div class="sidebar-welcome">
      <p class="welcome-text mb-0">Welcome,</p>
      <h6 class="user-name mb-0">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</h6>
    </div>
  </div>
  <div class="sidebar-menu">
    <div class="menu-category">
      <h6 class="menu-title">Main</h6>
      <ul>
        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
          <a href="{{ route('home')}}" class="d-flex align-items-center nav-link"><i class="fa fa-home" aria-hidden="true"></i>
            <span>Home</span></a>
        </li>
      </ul>
    </div>
    
    <div class="menu-category">
      <h6 class="menu-title">Kategori</h6>
      <ul>
        <li class="nav-item {{ request()->routeIs('Electronic') ? 'active' : '' }}">
          <a href="{{ route('Electronic')}}" class="d-flex align-items-center nav-link"><i class="fa-solid fa-bolt" aria-hidden="true"></i>
            <span>Electronik</span></a>
        </li>
        <li class="nav-item {{ request()->routeIs('Fashion') ? 'active' : '' }}">
          <a href="{{ route('Fashion')}}" class="d-flex align-items-center nav-link"><i class="fa-solid fa-shirt" aria-hidden="true"></i>
            <span>Fashion</span></a>
        </li>
        <li class="nav-item {{ request()->routeIs('Gadget') ? 'active' : '' }}">
          <a href="{{ route('Gadget')}}" class="d-flex align-items-center nav-link"><i class="fa-solid fa-laptop" aria-hidden="true"></i>
            <span>Gadget</span></a>
        </li>
        <li class="nav-item {{ request()->routeIs('Otomotif') ? 'active' : '' }}">
          <a href="{{ route('Otomotif')}}" class="d-flex align-items-center nav-link"><i class="fa-solid fa-gear" aria-hidden="true"></i>
            <span>Otomotif</span></a>
        </li>
        <li class="nav-item {{ request()->routeIs('Other') ? 'active' : '' }}">
          <a href="{{ route('Other')}}" class="d-flex align-items-center nav-link"><i class="fa-solid fa-warehouse" aria-hidden="true"></i>
            <span>Lainya</span></a>
        </li>
      </ul>
    </div>
    
    <div class="menu-category">
      <h6 class="menu-title">Account</h6>
      <ul>
        <li class="nav-item {{ request()->routeIs('Chat') ? 'active' : '' }}">
          <a href="{{ route('Chat')}}" class="d-flex align-items-center nav-link"><i class="fa fa-comments" aria-hidden="true"></i>
            <span>Messages</span></a>
        </li>
        <li class="nav-item">
          <a href="/payment" class="d-flex align-items-center nav-link"><i class="fa fa-credit-card" aria-hidden="true"></i>
            <span>Pembayaran</span></a>
        </li>
        <li class="nav-item">
          <a href="/consult" class="d-flex align-items-center nav-link"><i class="fa fa-handshake" aria-hidden="true"></i>
            <span>Konsultasi Gratis</span></a>
        </li>
      </ul>
    </div>
  </div>
  
  <div class="sidebar-footer">
    <div class="sidebar-footer-content">
      <a href="#" class="logout-btn d-flex align-items-center">
        <i class="fa fa-sign-out" aria-hidden="true"></i>
        <span>Logout</span>
      </a>
    </div>
  </div>
</div>

<script>
  // Pastikan jQuery sudah dimuat sebelum menjalankan kode ini
  document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan event listener untuk tombol close sidebar
    const closeBtn = document.querySelector('.close-sidebar');
    if (closeBtn) {
      closeBtn.addEventListener('click', function() {
        const menuToggle = document.getElementById('menu-toggle');
        const menuOverlay = document.getElementById('menu-overlay');
        const sidebar = document.getElementById('sidebar');
        const body = document.body;
        
        if (menuToggle && menuOverlay && sidebar) {
          menuToggle.classList.remove('ham-style');
          menuOverlay.classList.remove('active');
          sidebar.classList.remove('left');
          body.classList.remove('menu-open');
        }
      });
    }
    
    if (typeof jQuery !== 'undefined') {
      initSidebar();
    } else {
      // Jika jQuery belum dimuat, tunggu sebentar dan coba lagi
      setTimeout(function() {
        if (typeof jQuery !== 'undefined') {
          initSidebar();
        } else {
          console.error('jQuery tidak tersedia');
        }
      }, 500);
    }
  });
  
  function initSidebar() {
    // Function to close mobile menu
    function closeMobileMenu() {
      $(".menu-links").removeClass("left");
      $(".hamburger-icon").removeClass("ham-style");
      $(".menu-overlay").removeClass("active");
      $("body").removeClass("menu-open");
    }
    
    // Function to open mobile menu
    function openMobileMenu() {
      $(".menu-links").addClass("left");
      $(".hamburger-icon").addClass("ham-style");
      $(".menu-overlay").addClass("active");
      $("body").addClass("menu-open");
    }
    
    $(".menu-links li a").click(function (e) {
      $(".menu-links li.active").removeClass("active");
      var $parent = $(this).parent();
      $parent.addClass("active");
      
      // Close menu on mobile when clicking a menu item
      if($(window).width() <= 768) {
        closeMobileMenu();
      }
    });
    
    // Tambahkan event listener untuk hamburger icon
    $(".hamburger-icon").on("click", function () {
      console.log('Hamburger clicked');
      if($(".menu-links").hasClass("left")) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });
    
    // Close menu when clicking on overlay
    $(".menu-overlay").click(function() {
      closeMobileMenu();
    });
    
    // Handle resize events
    $(window).resize(function() {
      if($(window).width() > 768) {
        closeMobileMenu();
      }
    });
    
    // Close menu when pressing escape key
    $(document).keyup(function(e) {
      if (e.key === "Escape") { 
        closeMobileMenu();
      }
    });
  }
</script>