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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ secure_asset('images/logo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ secure_asset('images/logo.png') }}">
    <link rel="manifest" href="{{ secure_asset('manifest.json') }}" crossorigin="use-credentials">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"></noscript>
    
    <link href="{{ secure_asset('css/style1.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/layout-fix.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/responsive.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script defer src="{{ secure_asset('js/main.js') }}"></script>
    
    <title>ServiceHub - Your Service Platform</title>
</head>
<body class="light-theme">
    <!-- Header Section -->
    @include('layouts.header')
    
    <div class="main-layout">
        <!-- Sidebar Section -->
        @include('layouts.sidebar')
        
        <!-- Content Section -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    
    <!-- Footer Section -->
    @include('layouts.footer')
</body>
</html>