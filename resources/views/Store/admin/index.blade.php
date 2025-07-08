<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="ServiceHub - Layanan jasa service terbaik dan terpercaya">
    <meta name="keywords" content="service, jasa, perbaikan, servicehub, layanan">
    <meta name="theme-color" content="#1a73e8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Jasa Service ServiceHub</title>
    <link rel="shortcut icon" href="{{ secure_asset('images/logo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ secure_asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/bootstrap.min.css') }}">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    <link rel="stylesheet" href="{{ secure_asset('css/style1.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/layout-fix.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/store-icons.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/service-status-chart.css') }}">
    
    @yield('head')
    
    <style>
        .admin-navigation {
            margin-left: 20px;
        }
        
        @media (max-width: 992px) {
            .store-header {
                flex-direction: column;
                align-items: center;
            }
            
            .admin-navigation {
                margin-left: 0;
                margin-top: 15px;
                width: 100%;
            }
            
            .admin-navigation .d-flex {
                justify-content: center;
            }
        }
        
        @media (max-width: 576px) {
            .admin-navigation .d-flex {
                flex-direction: column;
                width: 100%;
            }
            
            .admin-navigation .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }

        .typewriter {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 2px solid #00ff00;
            font-size: 1rem;
            color: #00ff00;
            text-shadow: 0 0 5px #00ff00, 0 0 10px #00ff00;
            animation: 
                typing 5s steps(40, end) infinite,
                blink-caret 0.7s step-end infinite;
        }

        @keyframes typing {
            0% { width: 0; }
            50% { width: 100%; }
            100% { width: 0; } /* supaya animasi bisa mengulang */
        }

        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #00ff00; }
        }
    
    </style>

</head>
<h6 class="typewriter">>>> Labubu tahhh ikiiiii ??? Swargo panggon mu cakk!!! <<<</h6>

<body style="height: 100%; margin: 0; display: flex; flex-direction: column; overflow: hidden;">
<div class="page-wrapper" style="height: 100%; display: flex; flex-direction: column;">
    <div class="store-container" style="flex: 1; overflow: hidden; display: flex; flex-direction: column;">
        <div class="store-header">
            <div class="header-left">
                <div class="servicehub-logo" onclick="window.location.href="{{ route("home") }}'">
                    <img src="{{ secure_asset('images/logo.png') }}" alt="ServiceHub Logo">
                </div>
                <video width="300" height="80" autoplay loop muted style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <source src="{{ secure_asset('videos/animasi.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="store-search">
                <form id="searchForm">
                    <div class="search-input-group">
                        <input type="text" id="searchInput" placeholder="Cari layanan service..." class="form-control">
                        <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="admin-navigation">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-primary"><i class="fas fa-tools me-2"></i>Layanan</a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary"><i class="fas fa-clipboard-list me-2"></i>Pesanan</a>
                    <a href="{{ route('store.user.admin', Auth::id()) }}" class="btn btn-outline-success"><i class="fas fa-store me-2"></i>Lihat Toko Saya</a>
                </div>
            </div>
        </div>
        
        <div style="flex: 1; overflow: auto;">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-3" style="background-color: var(--background-light); border-top: 1.5px solid var(--border-color); box-shadow: 0 -2px 10px rgba(0,0,0,0.03);">
    <div class="container px-4">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0" style="color: #555; font-size: 14px; letter-spacing: 0.2px; font-weight: 400;">Cintaku Hanya Kamuuuu..... Cinta Merah Merah Jambu.......</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="{{ route('home') }}" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s;">
                    <i class="fa fa-home me-2" style="font-size: 16px;"></i> Kembali ke ServiceHub
                </a>
            </div>
        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Product action buttons
        const actionBtns = document.querySelectorAll('.product-action-btn');
        actionBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const action = this.getAttribute('title');
                const productTitle = this.closest('.product-card').querySelector('.product-title').textContent;
                
                if (action === 'Wishlist') {
                    alert(`Produk "${productTitle}" ditambahkan ke wishlist!`);
                }
                // Here you would implement the actual action logic
            });
        });
        
        // Product cards click
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('click', function() {
                const productTitle = this.querySelector('.product-title').textContent;
                // Navigate to product detail page
            });
        });
    });
</script>

<!-- JavaScript Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ secure_asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ secure_asset('js/main.js') }}"></script>

@yield('scripts')

<!-- Script untuk interaksi CRUD -->
<script>
    $(document).ready(function() {

        
        // Tombol favorit di header
        $('.store-actions .store-action-btn[title="Favorit"]').on('click', function() {
            alert('Favorit Anda');
            // Di sini Anda bisa menampilkan halaman favorit
        });
        
        // Tombol riwayat di header
        $('.store-actions .store-action-btn[title="Riwayat Service"]').on('click', function() {
            alert('Riwayat Service Anda');
            // Di sini Anda bisa menampilkan halaman riwayat
        });
        
        // Tombol akun di header
        $('.store-actions .store-action-btn[title="Akun Saya"]').on('click', function() {
            alert('Akun Anda');
            // Di sini Anda bisa menampilkan halaman akun
        });
    });
</script>
</div> <!-- Closing tag for page-wrapper -->
</body>
</html>