<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#4a6cf7">
    <meta name="description" content="ServiceHub - Your one-stop service platform">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="ServiceHub">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}" crossorigin="use-credentials">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"></noscript>
    
    <link href="{{ asset('css/style1.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layout-fix.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script defer src="{{ asset('js/main.js') }}"></script>
    
    <title>ServiceHub - Your Service Platform</title>
    
    <!-- Orientation change handling -->
    <script>
      window.addEventListener("orientationchange", function() {
        // Force reload on orientation change to ensure proper layout
        window.location.reload();
      });
      
      // Handle image loading with fade-in effect
      document.addEventListener('DOMContentLoaded', function() {
          const images = document.querySelectorAll('.img-fluid');
          
          images.forEach(function(img) {
              // Add loading class
              img.classList.add('loading');
              
              // When image is loaded, remove loading class and add loaded class
              img.onload = function() {
                  img.classList.remove('loading');
                  img.classList.add('loaded');
              };
              
              // If image is already loaded (from cache)
              if (img.complete) {
                  img.classList.remove('loading');
                  img.classList.add('loaded');
              }
              
              // If image fails to load
              img.onerror = function() {
                  img.classList.remove('loading');
              };
          });
      });
    </script>
</head>
<body class="light-theme">
    <!-- Header Section -->
    @include('layouts.header')
    
    <!-- Breadcrumb Section removed as not used -->
    
    <div class="nft-store">
      <div class="container-fluid">
        <div class="nft-store-inner d-flex">
          <!-- Main Content -->
          <div class="main-content-wrapper">
            @yield('content')
            <!-- Content will be rendered here -->
          </div>
        </div>
      </div>
    </div>
    
    <style>
      .main-content-wrapper {
        margin-left: 250px;
        width: 100%;
        transition: margin-left 0.3s ease;
        padding: 0 30px 0 40px;
      }
      
      @media (min-width: 1400px) {
        .main-content-wrapper {
          padding: 0 40px 0 50px;
        }
      }
      
      @media (max-width: 768px) {
        .main-content-wrapper {
          margin-left: 0 !important;
          padding: 0 15px;
        }
      }
      
      /* Pastikan konten tidak tertutup sidebar pada tampilan desktop */
      @media (min-width: 769px) and (max-width: 1199px) {
        .container-fluid {
          max-width: calc(100% - 30px);
          margin-right: auto;
          margin-left: auto;
        }
      }
      
      @media (min-width: 1200px) and (max-width: 1399px) {
        .container-fluid {
          max-width: 1320px;
          margin-right: auto;
          margin-left: auto;
        }
      }
      
      @media (min-width: 1400px) {
        .container-fluid {
          max-width: 1500px;
          margin-right: auto;
          margin-left: auto;
        }
      }
      
      /* Pastikan konten memiliki ruang yang cukup pada tampilan tablet */
      @media (min-width: 769px) and (max-width: 991px) {
        .main-content-wrapper {
          padding: 0 20px 0 25px;
        }
      }
    </style>
    
    <!-- Sidebar Section -->
    @include('layouts.sidebar')
    
    <!-- Footer Section -->
    @include('layouts.footer')
</body>
</html>