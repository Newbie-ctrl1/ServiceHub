@extends('layouts.index')

@section('title', 'Home')

@section('content')
<!-- Main content section -->
<div class="home-content" id="content">
    <!-- Hero Banner Section -->
    <div class="hero-banner-section mb-5">
        <div class="container-fluid">
            <div class="row">
                <!-- Main Banner -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="main-banner">
                        <div class="banner-content">
                            
                           
                        </div>
                        <div class="banner-overlay"></div>
                        <div class="banner-image" style="background-image: url('https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1074&q=80');"></div>
                    </div>
                </div>
                
                <!-- Side Banners -->
                <div class="col-lg-4">
                    <div class="row">
                        <!-- Top Side Banner -->
                        <div class="col-12 mb-4">
                            <div class="side-banner">
                                <div class="banner-content">
                                
                                </div>
                                <div class="banner-overlay"></div>
                                <div class="banner-image" style="background-image: url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTR8fGJ1c2luZXNzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60');"></div>
                            </div>
                        </div>
                        
                        <!-- Bottom Side Banner -->
                        <div class="col-12">
                            <div class="side-banner">
                                <div class="banner-content">
                                    <!-- Banner content elements removed -->
                                </div>
                                <div class="banner-overlay"></div>
                                <div class="banner-image" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTh8fGJ1c2luZXNzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60');"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Services Section -->
    <div class="featured-services-section mb-5">
        <div class="container-fluid">
            <div class="section-header d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">Service Center</h2>

            </div>
            <div class="row">
                @php
                    $hasActiveBanner = false;
                    if($allBanners && $allBanners->count() > 0) {
                        foreach($allBanners as $banner) {
                            if($banner->is_active) {
                                $hasActiveBanner = true;
                                break;
                            }
                        }
                    }
                @endphp
                @if($allBanners && $allBanners->count() > 0)
                    @foreach($allBanners as $banner)
                    @if($banner->is_active)
                    <!-- Store Banner -->
                    <div class="col-12 mb-4">
                        <div class="service-card service-card-horizontal">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="service-image">
                                        @if($banner->image)
                                            <img src="data:image/jpeg;base64,{{ base64_encode($banner->image) }}" alt="{{ $banner->title }}" class="img-fluid">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTB8fGJ1c2luZXNzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60" alt="{{ $banner->title ?? 'Store Banner' }}" class="img-fluid">
                                        @endif
                                        <span class="service-badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $banner->is_active ? 'Toko Aktif' : 'Toko Tidak Aktif' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="service-content">
                                        <h3 class="service-title">{{ $banner->title }}</h3>
                                        @if($banner->location)
                                            <div class="service-location mb-2">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                <span class="text-muted">{{ $banner->location }}</span>
                                            </div>
                                        @endif
                                        @if($banner->text)
                                            <p class="service-description">{{ $banner->text }}</p>
                                        @endif
                                        <div class="service-footer d-flex justify-content-between align-items-center">
                                            <div class="banner-status">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    Dibuat: {{ $banner->created_at->format('d M Y') }}
                                                </small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                @if($banner->user_id)
                                                    <a href="{{ route('chat.user', $banner->user_id) }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-comments me-1"></i>Chat
                                                    </a>
                                                @else
                                                    <a href="/Chat" class="btn btn-sm btn-success">
                                                        <i class="fas fa-comments me-1"></i>Chat
                                                    </a>
                                                @endif
                                                @if($banner->user_id)
                                                    <a href="{{ route('store.user.admin', $banner->user_id) }}" class="btn btn-sm btn-primary">View Details</a>
                                                @else
                                                    <span class="btn btn-sm btn-secondary disabled">No Store Available</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                @elseif(!$hasActiveBanner)
                <!-- No Active Banner Available -->
                <div class="col-12 mb-4">
                    <div class="service-card service-card-horizontal">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="service-image">
                                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTB8fGJ1c2luZXNzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60" alt="Default Store" class="img-fluid">
                                    <span class="service-badge">Toko Tersedia</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="service-content">
                                    <h3 class="service-title">Belum Ada Toko Aktif</h3>
                                    <div class="service-location mb-2">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        <span class="text-muted">Saat ini tidak ada toko yang aktif untuk ditampilkan</span>
                                    </div>
                                    <p class="service-description">Untuk membuat toko aktif, silakan login sebagai admin dan aktifkan toko di halaman admin.</p>
                                    <div class="service-footer d-flex justify-content-end align-items-center">
                                        <span class="btn btn-sm btn-secondary disabled">No Store Available</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- No Banner Available -->
                <div class="col-12 mb-4">
                    <div class="service-card service-card-horizontal">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="service-image">
                                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTB8fGJ1c2luZXNzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60" alt="Default Store" class="img-fluid">
                                    <span class="service-badge">Toko Tersedia</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="service-content">
                                    <h3 class="service-title">Belum Ada Banner Toko</h3>
                                    <div class="service-location mb-2">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        <span class="text-muted">Silakan buat banner toko untuk menampilkan informasi toko</span>
                                    </div>
                                    <p class="service-description">Untuk membuat banner toko, silakan login sebagai admin dan buat banner di halaman admin.</p>
                                    <div class="service-footer d-flex justify-content-end align-items-center">
                                        <span class="btn btn-sm btn-secondary disabled">No Store Available</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .home-content {
        padding: 0;
    }
    
    .main-banner, .side-banner {
        height: auto;
        min-height: 200px;
    }
}

/* Hero Banner Section Styles */
.hero-banner-section {
    margin-top: 25px;
}

.main-banner, .side-banner {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    height: 350px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.side-banner {
    height: 165px;
}

@media (min-width: 1400px) {
    .main-banner {
        height: 400px;
    }
    
    .side-banner {
        height: 190px;
    }
}

.banner-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    z-index: 1;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3));
    z-index: 2;
}

.banner-content {
    position: relative;
    z-index: 3;
    padding: 30px;
    color: #fff;
    max-width: 550px;
}

.side-banner .banner-content {
    padding: 20px;
    max-width: 100%;
}

.banner-badge {
    display: inline-block;
    background-color: #4a6cf7;
    color: #fff;
    padding: 5px 15px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.banner-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 15px;
    line-height: 1.2;
}

.side-banner .banner-title {
    font-size: 20px;
    margin-bottom: 10px;
}

@media (min-width: 1400px) {
    .banner-title {
        font-size: 38px;
    }
    
    .side-banner .banner-title {
        font-size: 24px;
    }
}

.banner-description {
    font-size: 15px;
    margin-bottom: 20px;
    opacity: 0.9;
    line-height: 1.5;
}

.side-banner .banner-description {
    font-size: 13px;
    margin-bottom: 12px;
}

.banner-actions {
    display: flex;
    gap: 15px;
}

/* Featured Services Section Styles */
.section-title {
    font-size: 22px;
    font-weight: 700;
    color: #333;
    position: relative;
    padding-left: 15px;
    border-left: 4px solid #4a6cf7;
}

@media (min-width: 1400px) {
    .section-title {
        font-size: 26px;
        padding-left: 18px;
        border-left-width: 5px;
    }
}

.service-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Horizontal Service Card Styles */
.service-card-horizontal {
    display: flex;
    flex-direction: column;
}

@media (min-width: 768px) {
    .service-card-horizontal .row {
        height: 100%;
    }
    
    .service-card-horizontal .service-content {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 20px 25px;
    }
}

.service-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

@media (min-width: 1400px) {
    .service-image {
        height: 220px;
    }
}

/* Adjust image height for horizontal cards */
.service-card-horizontal .service-image {
    height: 100%;
    min-height: 200px;
}

@media (min-width: 768px) {
    .service-card-horizontal .col-md-4,
    .service-card-horizontal .col-md-8 {
        height: 100%;
    }
}

.service-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.service-card:hover .service-image img {
    transform: scale(1.05);
}

.service-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}

.service-badge.bg-success {
    background: linear-gradient(45deg, #28a745 0%, #20c997 100%);
}

.service-badge.bg-secondary {
    background: linear-gradient(45deg, #6c757d 0%, #495057 100%);
}

.banner-status {
    display: flex;
    align-items: center;
}

.banner-status small {
    font-size: 0.8rem;
}

/* Adjust badge position for horizontal cards */
.service-card-horizontal .service-badge {
    top: 10px;
    right: 10px;
}

.service-content {
    padding: 25px;
}

.service-card-horizontal .service-content {
    padding: 15px 20px;
}

.service-title {
    color: #2d3748;
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 10px;
    line-height: 1.3;
}

.service-card-horizontal .service-title {
    font-size: 18px;
    margin-bottom: 10px;
}

@media (min-width: 1400px) {
    .service-content {
        padding: 20px;
    }
    
    .service-card-horizontal .service-content {
        padding: 25px 30px;
    }
    
    .service-title {
        font-size: 18px;
        margin-bottom: 10px;
    }
    
    .service-card-horizontal .service-title {
        font-size: 22px;
    }
}

.service-location {
    margin-bottom: 15px;
}

.service-location i {
    font-size: 0.9rem;
}

.service-rating {
    margin-bottom: 15px;
}

.service-rating .fas {
    color: #ffd700;
    margin-right: 2px;
}

.service-rating .far {
    color: #e2e8f0;
    margin-right: 2px;
}

.rating-count {
    color: #718096;
    font-size: 0.9rem;
    margin-left: 8px;
}

.service-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 20px;
    font-size: 0.95rem;
}

.service-card-horizontal .service-description {
    margin-bottom: 20px;
}

@media (min-width: 1400px) {
    .service-description {
        font-size: 15px;
        margin-bottom: 18px;
    }
    
    .service-card-horizontal .service-description {
        font-size: 16px;
        margin-bottom: 25px;
        line-height: 1.6;
    }
}

.service-footer {
    border-top: 1px solid #e2e8f0;
    padding-top: 15px;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* Chat Button Styling */
.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    padding: 8px 16px;
    margin: 0;
}

.btn-sm.btn-success {
    padding: 6px 12px;
    font-size: 0.875rem;
    line-height: 1.5;
}

.service-footer .d-flex.gap-2 {
    gap: 0.75rem !important;
}

.service-footer .btn {
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-success::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-success:hover::before {
    left: 100%;
}

.btn-success:hover {
    background: linear-gradient(135deg, #20c997, #17a2b8);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    color: white;
}

.btn-success:focus {
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success i {
    transition: transform 0.3s ease;
}

.btn-success:hover i {
    transform: scale(1.1);
}

.service-card-horizontal .service-footer {
    margin-top: auto;
    padding-top: 15px;
}

/* Price styles removed */

@media (min-width: 1400px) {
    .service-footer {
        padding-top: 18px;
    }
    
    .service-card-horizontal .service-footer {
        padding-top: 20px;
    }
    
    /* Price styles removed */
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    
    .main-banner, .side-banner {
        height: 250px;
    }
}

@media (max-width: 768px) {
    .banner-title {
        font-size: 22px;
    }
    
    .side-banner .banner-title {
        font-size: 16px;
    }
    
    .banner-description {
        font-size: 14px;
    }
    
    .side-banner .banner-description {
        font-size: 12px;
    }
    
    .banner-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .banner-actions .btn {
        width: 100%;
        padding: 8px 16px;
        font-size: 14px;
    }
    
    .service-image {
        height: 140px;
    }
    
    .service-card-horizontal .service-image {
        min-height: 160px;
    }
    
    .service-content {
        padding: 12px;
    }
    
    .service-card-horizontal .service-content {
        padding: 15px;
    }
    
    .service-title {
        font-size: 15px;
    }
    
    .service-card-horizontal .service-title {
        font-size: 16px;
        margin-bottom: 8px;
    }
    
    .service-card-horizontal .service-description {
        margin-bottom: 15px;
    }
    
    .service-card-horizontal .service-footer {
        padding-top: 12px;
    }
    
    .service-description {
        font-size: 13px;
        margin-bottom: 12px;
    }
    
    .section-title {
        font-size: 20px;
    }
}
</style>
@endsection