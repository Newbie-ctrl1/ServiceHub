<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiceHub - Jasa Perbaikan dan Pemeliharaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/landingpage-icons.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap');
        
        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        
        .hero-buttons {
            margin-top: 2rem;
            animation: fadeInUp 1s ease-out 1.1s both;
        }
        
        .preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            transition: transform 0.3s ease;
        }
        
        .navbar-toggler:hover {
            transform: scale(1.1);
        }
        
        .loader {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }
        
        .loader-text {
            color: white;
            margin-top: 20px;
            font-size: 1rem;
            letter-spacing: 2px;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        :root {
            --primary-color: #0066FF;
            --text-color: #333;
            --bg-color: #fff;
            --secondary-color: #f5f5f7;
            --accent-color: #FF6431;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Manrope', sans-serif;
            color: var(--text-color);
            background-color: var(--bg-color);
            overflow-x: hidden;
        }
        
        .navbar {
            padding: 1.5rem 2rem;
            background-color: transparent;
            transition: background-color 0.3s ease;
            z-index: 1000;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .navbar.scrolled {
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            color: white !important;
        }
        
        .navbar-brand img {
            margin-right: 8px;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover img {
            transform: scale(1.1);
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 0.5rem;
            color: rgba(255, 255, 255, 0.9) !important;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white !important;
            text-decoration: none;
            border-bottom: 2px solid white;
            padding-bottom: 3px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-primary:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            z-index: -1;
        }
        
        .btn-primary:hover:before {
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 102, 255, 0.3);
        }
        
        .btn-outline-light {
            color: white;
            border-color: white;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-outline-light:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background-color: white;
            transition: all 0.3s ease;
            z-index: -1;
        }
        
        .btn-outline-light:hover:before {
            width: 100%;
        }
        
        .btn-outline-light:hover {
            color: var(--primary-color) !important;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 255, 255, 0.2);
        }
        
        .hero-section {
            min-height: 100vh;
            position: relative;
            padding: 0;
            margin: 0;
            overflow: hidden;
        }
        
        .hero-content {
            padding: 2rem 0;
            color: white;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
            z-index: 3;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: white;
            animation: fadeInUp 1s ease-out 0.5s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            font-weight: 400;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.8);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease-out 0.8s both;
        }
        
        .spline-viewer {
            width: 100%;
            height: 80vh;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            transition: all 0.3s ease;
            background-color: #000;
        }
        
        .spline-viewer.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            border-radius: 0;
            box-shadow: none;
        }
        
        .fullscreen-hero {
            width: 100%;
            height: 100vh;
            border-radius: 0;
            box-shadow: none;
            position: relative;
        }
        
        .fullscreen-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: background-color 0.3s ease;
        }
        
        .fullscreen-btn:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
        
        .spline-instructions {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            display: flex;
            gap: 15px;
            z-index: 10;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .instruction-icon {
            margin-right: 5px;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 100%);
        }
        
        .scroll-down {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 1.5rem;
            z-index: 10;
            animation: bounce 2s infinite;
            cursor: pointer;
            text-align: center;
        }
        
        .scroll-text {
            font-size: 0.8rem;
            display: block;
            margin-top: 5px;
            opacity: 0.8;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0) translateX(-50%);
            }
            40% {
                transform: translateY(-20px) translateX(-50%);
            }
            60% {
                transform: translateY(-10px) translateX(-50%);
            }
        }
        

        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .section-title.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .section-description {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 3rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease 0.2s, transform 0.6s ease 0.2s;
        }
        
        .section-description.visible {
            opacity: 1;
            transform: translateY(0);
        }
        

        
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3a7bd5 100%);
            padding: 5rem 0;
            color: white;
            text-align: center;
            border-radius: 0;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            animation: pulse 15s linear infinite;
            z-index: 1;
        }
        
        .cta-section .container {
            position: relative;
            z-index: 2;
        }
        
        @keyframes pulse {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0.5;
            }
            50% {
                transform: translate(-5%, -5%) scale(1.05);
                opacity: 0.7;
            }
            100% {
                transform: translate(0, 0) scale(1);
                opacity: 0.5;
            }
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .cta-title.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .cta-subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease 0.2s, transform 0.6s ease 0.2s;
        }
        
        .cta-description.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .cta-button {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease 0.4s, transform 0.6s ease 0.4s;
        }
        
        .cta-button.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 3rem 0;
        }
        
        .footer-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        /* Team Section Styling */
        .team-section {
            padding: 5rem 0;
            background-color: #fff;
        }
        
        .team-card {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem 1rem;
            margin-bottom: 1.5rem;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .team-img-container {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1rem;
            border: 3px solid var(--primary-color);
        }
        
        .team-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .team-card:hover .team-img {
            transform: scale(1.1);
        }
        
        .team-name {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
            color: var(--text-color);
        }
        
        .team-position {
            font-size: 0.85rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.7rem;
        }
        
        /* Team description removed for compact layout */
        
        .team-social {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        
        .social-link {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        
        .social-link:hover {
            background-color: var(--text-color);
            transform: translateY(-3px);
            color: white;
            text-decoration: none;
        }
        
        .footer-links a {
            color: #666;
            text-decoration: none;
        }
        
        .footer-links a:hover {
            color: var(--primary-color);
        }
        
        .social-icons {
            font-size: 1.5rem;
        }
        
        .social-icons a {
            color: #666;
            margin-right: 1rem;
        }
        
        .social-icons a:hover {
            color: var(--primary-color);
        }
        
        .copyright {
            margin-top: 2rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .spline-viewer {
                height: 400px;
                margin-top: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero-section {
                text-align: center;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .spline-instructions {
                flex-direction: column;
                gap: 5px;
                padding: 10px 15px;
                bottom: 15px;
            }
            
            .spline-instructions .instruction-item {
                margin: 2px 0;
                font-size: 12px;
            }
            
            .fullscreen-btn {
                width: 35px;
                height: 35px;
                top: 10px;
                right: 10px;
            }
            
            .navbar {
                background-color: rgba(0, 0, 0, 0.8);
                padding: 0.8rem 1rem;
            }
            
            .hero-content {
                padding-top: 4rem;
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .scroll-down {
                bottom: 20px;
                font-size: 1.2rem;
            }
            
            .scroll-text {
                font-size: 0.7rem;
            }
            
            .navbar-collapse {
                background-color: rgba(0, 0, 0, 0.9);
                padding: 1rem;
                border-radius: 8px;
                margin-top: 0.5rem;
            }
            
            .nav-item {
                margin-bottom: 0.5rem;
            }
            
            .btn {
                display: block;
                width: 100%;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="loader"></div>
        <div class="loader-text">SERVICEHUB</div>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ secure_asset('images/logo.png') }}" alt="ServiceHub Logo" height="30" class="d-inline-block align-top mr-2">
                ServiceHub
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="#pricing"><i class="fas fa-tag mr-1"></i> Harga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer"><i class="fas fa-info-circle mr-1"></i> Tentang</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="btn btn-outline-light ml-2" href="{{ route('login') }}"><i class="fas fa-sign-in-alt mr-1"></i> Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ml-2" href="{{ route('register') }}"><i class="fas fa-user-plus mr-1"></i> Daftar Sekarang</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="spline-viewer fullscreen-hero" id="splineViewer">
            <iframe src="https://my.spline.design/usethisasaroombuilderclickinteraction-ExNgEWZMrZ33XWwu0ZTWOCiV/" frameborder="0" width="100%" height="100%" allowfullscreen="true" allow="autoplay; fullscreen; vr; xr; camera; microphone; midi; geolocation; gyroscope; accelerometer" loading="lazy" id="splineFrame" style="position: relative; z-index: 1;"></iframe>
            <button class="fullscreen-btn" id="fullscreenBtn" title="Tampilkan layar penuh">
                <i class="fas fa-expand"></i>
            </button>

            <div class="hero-overlay">
                <div class="container">
                    <div class="hero-content text-center">
                        <h1 class="hero-title">Jasa Perbaikan dan Pemeliharaan</h1>
                        <p class="hero-subtitle">Daftar dan coba rasakan seluruh layanan kami semoga anda menikmati semuanya dan jangan lupa untuk makan karena makan adalah untuk kebahagiaan</p>
                        
                    </div>
                </div>
            </div>
            <div class="scroll-down" id="scrollDown">
                <i class="fas fa-chevron-down"></i>
                <span class="scroll-text">Scroll Down</span>
            </div>
        </div>
    </section>

    <!-- Our Team Section -->
    <section class="team-section" id="team">
        <div class="container">
            <h2 class="section-title">Our Team</h2>
            <div class="row justify-content-center">
                <!-- Team Member 1 -->
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="team-card">
                        <div class="team-img-container">
                            <img src="{{ ('images/arek_arek/rendi.jpg') }}" alt="Team Member" class="team-img">
                        </div>
                        <h3 class="team-name">Rendi Bagus</h3>
                        <p class="team-position">Teknik informatika</p>                   
                    </div>
                </div>
                
                <!-- Team Member 2 -->
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="team-card">
                        <div class="team-img-container">
                            <img src="{{ secure_asset('images/arek_arek/hamdan.jpg')}}" alt="Team Member" class="team-img">
                        </div>
                        <h3 class="team-name">Hamdan H</h3>
                        <p class="team-position">Teknik Informatika</p>                       
                    </div>
                </div>
                
                <!-- Team Member 3 -->
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="team-card">
                        <div class="team-img-container">
                            <img src="{{ ('images/arek_arek/yusron.jpg') }}" alt="Team Member" class="team-img">
                        </div>
                        <h3 class="team-name">Yusron M</h3>
                        <p class="team-position">Teknik Informatika</p>
                    </div>
                </div>
                
                <!-- Team Member 4 -->
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="team-card">
                        <div class="team-img-container">
                            <img src="{{ secure_asset('images/Avatar_KOnsultasi.png') }}" alt="Team Member" class="team-img">
                        </div>
                        <h3 class="team-name">Arif Hidayat</h3>
                        <p class="team-position">Teknik Informatika</p>
                    </div>
                </div>
                
                <!-- Team Member 5 -->
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="team-card">
                        <div class="team-img-container">
                            <img src="{{ secure_asset('images/arek_arek/zahroh.jpg') }}" alt="Team Member" class="team-img">
                        </div>
                        <h3 class="team-name">Laila Zahro</h3>
                        <p class="team-position">Teknik Informatika</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- CTA Section -->
    <section class="cta-section" id="pricing">
        <div class="container">
            <h2 class="cta-title">Mulai Gunakan ServiceHub Sekarang</h2>
            <p class="cta-description">Dapatkan layanan perbaikan dan pemeliharaan terbaik untuk kebutuhan Anda. Daftar sekarang dan nikmati berbagai keuntungan menarik!</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg cta-button">Mulai Sekarang — Gratis</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="footer-title">ServiceHub</h5>
                    <p>Platform jasa perbaikan dan pemeliharaan terpercaya untuk kebutuhan Anda.</p>
                    <div class="social-icons">
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" title="Discord"><i class="fab fa-discord"></i></a>
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h5 class="footer-title">Layanan</h5>
                    <ul class="footer-links">
                        <li><a href="#">Elektronik</a></li>
                        <li><a href="#">Komputer</a></li>
                        <li><a href="#">Gadget</a></li>
                        <li><a href="#">Otomotif</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5 class="footer-title">Perusahaan</h5>
                    <ul class="footer-links">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5 class="footer-title">Bantuan</h5>
                    <ul class="footer-links">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Panduan</a></li>
                        <li><a href="#">Dukungan</a></li>
                        <li><a href="#">Komunitas</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5 class="footer-title">Legal</h5>
                    <ul class="footer-links">
                        <li><a href="#">Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Keamanan</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center copyright">
                <p>Selamat Datang di ServiceHub & Selamat menservice</p>
            </div>
        </div>
    </footer>

<script>
        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(function() {
                preloader.classList.add('hidden');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            }, 1500); // Tampilkan preloader selama 1.5 detik
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const splineViewer = document.getElementById('splineViewer');
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            const splineFrame = document.getElementById('splineFrame');
            const splineInstructions = document.querySelector('.spline-instructions');
            const navbar = document.querySelector('.navbar');
            
            let isFullscreen = false;
            let instructionsTimeout;
            
            // Sembunyikan instruksi setelah beberapa detik
            function hideInstructionsAfterDelay() {
                clearTimeout(instructionsTimeout);
                splineInstructions.style.opacity = '0.8';
                instructionsTimeout = setTimeout(() => {
                    splineInstructions.style.opacity = '0';
                }, 5000); // Sembunyikan setelah 5 detik
            }
            
            // Tampilkan instruksi saat mouse bergerak di atas viewer
            splineViewer.addEventListener('mousemove', () => {
                splineInstructions.style.opacity = '0.8';
                hideInstructionsAfterDelay();
            });
            
            // Tampilkan instruksi saat sentuhan pada perangkat mobile
            splineViewer.addEventListener('touchstart', () => {
                splineInstructions.style.opacity = '0.8';
                hideInstructionsAfterDelay();
            });
            
            // Inisialisasi dengan menampilkan instruksi
            hideInstructionsAfterDelay();
            
            // Efek scroll untuk navbar dan parallax
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                
                // Efek parallax pada hero section
                const scrollPosition = window.scrollY;
                const heroContent = document.querySelector('.hero-content');
                const scrollDown = document.querySelector('.scroll-down');
                
                if (heroContent && scrollPosition < window.innerHeight) {
                    heroContent.style.transform = `translateY(${scrollPosition * 0.3}px)`;
                    heroContent.style.opacity = 1 - (scrollPosition / 700);
                }
                
                if (scrollDown && scrollPosition < window.innerHeight) {
                    scrollDown.style.opacity = 1 - (scrollPosition / 300);
                }
                
                // Scroll animations
                const animatedElements = document.querySelectorAll('.section-title, .section-description, .cta-title, .cta-description, .cta-button');
                
                animatedElements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementVisible = 150;
                    
                    if (elementTop < window.innerHeight - elementVisible) {
                        element.classList.add('visible');
                    }
                });
            });
            
            fullscreenBtn.addEventListener('click', function() {
                if (!isFullscreen) {
                    // Masuk mode fullscreen
                    splineViewer.classList.add('fullscreen');
                    fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
                    fullscreenBtn.title = 'Keluar dari layar penuh';
                    
                    // Coba gunakan Fullscreen API jika didukung browser
                    if (splineViewer.requestFullscreen) {
                        splineViewer.requestFullscreen();
                    } else if (splineViewer.webkitRequestFullscreen) { /* Safari */
                        splineViewer.webkitRequestFullscreen();
                    } else if (splineViewer.msRequestFullscreen) { /* IE11 */
                        splineViewer.msRequestFullscreen();
                    }
                    
                    // Tambahkan event listener untuk keluar dari fullscreen
                    document.addEventListener('fullscreenchange', exitHandler);
                    document.addEventListener('webkitfullscreenchange', exitHandler);
                    document.addEventListener('mozfullscreenchange', exitHandler);
                    document.addEventListener('MSFullscreenChange', exitHandler);
                    
                    // Tampilkan instruksi saat masuk mode fullscreen
                    splineInstructions.style.opacity = '0.8';
                    hideInstructionsAfterDelay();
                } else {
                    // Keluar dari mode fullscreen
                    exitFullscreen();
                }
                
                isFullscreen = !isFullscreen;
            });
            
            function exitHandler() {
                if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
                    exitFullscreen();
                    isFullscreen = false;
                }
            }
            
            function exitFullscreen() {
                splineViewer.classList.remove('fullscreen');
                fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
                fullscreenBtn.title = 'Tampilkan layar penuh';
                
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) { /* Safari */
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { /* IE11 */
                    document.msExitFullscreen();
                }
            }
            
            // Tambahkan event listener untuk tombol ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isFullscreen) {
                    exitFullscreen();
                    isFullscreen = false;
                }
            });
            
            // Tambahkan kelas active pada link navbar saat di-scroll
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');
            
            window.addEventListener('scroll', function() {
                let current = '';
                
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    
                    if (window.scrollY >= (sectionTop - 200)) {
                        current = section.getAttribute('id');
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('active');
                    }
                });
            });
            
            // Smooth scroll untuk navigasi
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 70,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Scroll down button
            const scrollDownBtn = document.getElementById('scrollDown');
            if (scrollDownBtn) {
                scrollDownBtn.addEventListener('click', function() {
                    const featuresSection = document.getElementById('features');
                    if (featuresSection) {
                        window.scrollTo({
                            top: featuresSection.offsetTop - 70,
                            behavior: 'smooth'
                        });
                    }
                });
            }
        });
    </script>

</body>
</html>