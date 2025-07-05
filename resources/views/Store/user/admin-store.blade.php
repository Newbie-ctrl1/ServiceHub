@extends('store.user.index')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.store-content {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 30px 20px 60px 20px;
    overflow-x: hidden;
}

.store-banner {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.banner-content {
    backdrop-filter: blur(10px);
    border-radius: 20px;
}

.service-card {
    background: white;
    border: none;
    border-radius: 16px;
    overflow: visible;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    position: relative;
    margin-bottom: 20px;
}

.service-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    z-index: 10;
}

.service-img {
    height: 200px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.service-card:hover .service-img {
    transform: scale(1.1);
}

.service-badge {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    border-radius: 20px;
    padding: 5px 12px;
    font-size: 0.8rem;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.service-fav-btn {
    background: rgba(255,255,255,0.9);
    border: none;
    width: 40px;
    height: 40px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.service-fav-btn:hover {
    background: #ff6b6b;
    color: white;
    transform: scale(1.1);
}

.service-info {
    padding: 20px;
}

.service-title {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 12px;
    font-size: 1.2rem;
}

.service-rating {
    margin-bottom: 15px;
}

.service-desc {
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 15px;
}

.service-meta {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 15px;
}

.service-price {
    color: #28a745;
    font-size: 1.1rem;
    font-weight: 700;
}

.service-time {
    color: #6c757d;
    font-size: 0.9rem;
}

.service-card-location {
    color: #6c757d;
    font-size: 0.9rem;
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.service-actions .btn {
    border-radius: 10px;
    font-weight: 600;
    padding: 12px 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.service-view-btn {
    border: 2px solid #007bff;
    color: #007bff;
}

.service-view-btn:hover {
    background: #007bff;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,123,255,0.3);
}

.service-book-btn {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    color: white;
}

.service-book-btn:hover {
    background: linear-gradient(45deg, #20c997, #28a745);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(40,167,69,0.3);
}

.services-grid {
    gap: 25px;
    margin-bottom: 40px;
    padding-bottom: 20px;
}

/* Clearfix for floating elements */
.services-grid::after {
    content: "";
    display: table;
    clear: both;
}

.empty-state {
    background: white;
    border-radius: 20px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin: 40px auto;
    max-width: 600px;
}

.empty-state i {
    background: linear-gradient(45deg, #6c757d, #adb5bd);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #495057;
    font-weight: 700;
    margin-bottom: 15px;
}

.empty-state p {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.empty-state .btn {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    border-radius: 12px;
    padding: 15px 30px;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
}

.empty-state .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,123,255,0.4);
}

/* Modal Animations */
.modal.fade .modal-dialog {
    transform: scale(0.8) translateY(-50px);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.modal.show .modal-dialog {
    transform: scale(1) translateY(0);
}

/* Ensure modal doesn't get cut off */
.modal {
    padding-right: 0 !important;
}

.modal-dialog {
    margin: 20px auto;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
}

/* Service Card Image Container */
.service-img-container {
    overflow: hidden;
    border-radius: 16px 16px 0 0;
    position: relative;
    height: 200px;
}

/* Rating Stars Animation */
.service-rating i {
    transition: transform 0.2s ease;
}

.service-rating:hover i {
    transform: scale(1.1);
}

/* Button Ripple Effect */
.btn {
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    transition: width 0.6s, height 0.6s;
    transform: translate(-50%, -50%);
    z-index: 0;
}

.btn:active::before {
    width: 300px;
    height: 300px;
}

.btn * {
    position: relative;
    z-index: 1;
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}

body {
    overflow-x: hidden;
}

/* Ensure proper viewport */
.container-fluid {
    padding-bottom: 40px;
}

/* Loading Animation for Images */
.service-img {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

.service-img[src] {
    animation: none;
    background: none;
}

@media (max-width: 992px) {
    .store-content {
        padding: 25px 20px 50px 20px;
    }
}

/* Modal Detail Product Styles */
.modal-lg {
    max-width: 900px;
}

.service-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.service-main-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.service-main-image:hover {
    transform: scale(1.05);
}

.service-thumbnails {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    overflow-x: auto;
    padding: 4px 0;
}

.service-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    flex-shrink: 0;
}

.service-thumb:hover {
    transform: scale(1.1);
    border-color: #007bff;
}

.service-thumb.active {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.service-highlights {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 15px 0;
}

.highlight-item {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    color: #1976d2;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 500;
    border: 1px solid rgba(25,118,210,0.2);
}

.service-meta {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin: 20px 0;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.meta-item:last-child {
    border-bottom: none;
}

.meta-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9em;
}

.meta-value {
    color: #007bff;
    font-weight: 500;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
    font-weight: 500;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link.active {
    background: none;
    border-bottom-color: #007bff;
    color: #007bff;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #007bff;
    color: #007bff;
}

.tab-content {
    padding: 20px 0;
}

.process-step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #007bff;
}

.step-number {
    background: #007bff;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9em;
    margin-right: 15px;
    flex-shrink: 0;
}

.step-content h6 {
    margin: 0 0 8px 0;
    color: #495057;
    font-weight: 600;
}

.step-content p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9em;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .store-content {
        padding: 20px 15px 40px 15px;
    }
    
    .service-info {
        padding: 16px;
    }
    
    .services-grid {
        gap: 20px;
        padding-bottom: 30px;
    }
    
    .service-card {
        margin-bottom: 25px;
    }
    
    .empty-state {
        padding: 40px 20px;
        margin: 20px;
    }
    
    .modal-dialog {
        margin: 10px;
        max-height: calc(100vh - 20px);
    }
    
    .modal-body {
        padding: 20px !important;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
    
    .service-actions {
        flex-direction: column;
    }
    
    .service-actions .btn {
        margin-bottom: 10px;
    }
}
</style>

<div class="store-content">
    <!-- Admin Store Banner Section -->
    <div class="store-banner mb-4">
        @if($activeBanner)
            <div class="text-white text-center banner-content position-relative" 
                 style="padding: 40px 30px; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('data:image/jpeg;base64,{{ base64_encode($activeBanner->image) }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    @if($admin->profile_photo)
                        <img src="data:image/jpeg;base64,{{ base64_encode($admin->profile_photo) }}" 
                             alt="{{ $admin->name }}" 
                             class="rounded-circle me-3" 
                             style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white;">
                    @else
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; background-color: rgba(255,255,255,0.2); border: 3px solid white;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    @endif
                    <div class="text-start">
                        <h2 class="banner-title mb-1">{{ $activeBanner->title ?? 'Toko ' . $admin->name }}</h2>
                        <p class="mb-0"><i class="fas fa-envelope me-2"></i>{{ $admin->email }}</p>
                    </div>
                </div>
                <p class="banner-text mt-3">{{ $activeBanner->text ?? 'Layanan berkualitas dari teknisi profesional dan berpengalaman' }}</p>
                @if($activeBanner->location)
                    <p class="banner-location mt-2"><i class="fas fa-map-marker-alt me-2"></i> {{ $activeBanner->location }}</p>
                @endif
                @if($services->count() > 0)
                    <p class="banner-services mt-2"><i class="fas fa-tools me-2"></i> {{ $services->count() }} Layanan Tersedia</p>
                @else
                    <p class="banner-services mt-2"><i class="fas fa-info-circle me-2"></i> Belum ada layanan tersedia</p>
                @endif
            </div>
        @else
            <div class="text-white text-center banner-content position-relative" 
                 style="padding: 40px 30px; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ secure_asset('images/default-banner.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    @if($admin->profile_photo)
                        <img src="data:image/jpeg;base64,{{ base64_encode($admin->profile_photo) }}" 
                             alt="{{ $admin->name }}" 
                             class="rounded-circle me-3" 
                             style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white;">
                    @else
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; background-color: rgba(255,255,255,0.2); border: 3px solid white;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    @endif
                    <div class="text-start">
                        <h2 class="banner-title mb-1">Toko {{ $admin->name }}</h2>
                        <p class="mb-0"><i class="fas fa-envelope me-2"></i>{{ $admin->email }}</p>
                    </div>
                </div>
                <p class="banner-text mt-3">Layanan berkualitas dari teknisi profesional dan berpengalaman</p>
                @if($services->count() > 0)
                    <p class="banner-location mt-3"><i class="fas fa-tools me-2"></i> {{ $services->count() }} Layanan Tersedia</p>
                @else
                    <p class="banner-location mt-3"><i class="fas fa-info-circle me-2"></i> Belum ada layanan tersedia</p>
                @endif
            </div>
        @endif
    </div>

    <!-- Services Grid -->
    <div class="services-section" style="margin-bottom: 80px;">
        @if($services->count() > 0)
            <div class="services-grid row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4" style="min-height: 300px;">
                @foreach($services as $serviceItem)
                    <div class="col">
                        <div class="service-card h-100" data-service-id="{{ $serviceItem->id }}">
                            <div class="service-img-container position-relative">
                                @if($serviceItem->main_image)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($serviceItem->main_image) }}" alt="{{ $serviceItem->title }}" class="service-img img-fluid w-100 rounded-top">
                                @else
                                    <img src="{{ asset('images/default-banner.png') }}" alt="Service Image" class="service-img img-fluid w-100 rounded-top">
                                @endif
                                
                                @if($serviceItem->badge)
                                    <span class="service-badge badge bg-primary position-absolute top-0 end-0 m-2">{{ $serviceItem->badge }}</span>
                                @endif
                                
                                <button class="service-fav-btn position-absolute top-0 start-0 m-2 btn btn-light btn-sm rounded-circle">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="service-info">
                                <h5 class="service-title">{{ $serviceItem->title }}</h5>
                                <div class="service-rating mb-2">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                    <span class="ms-1 text-muted">(4.5)</span>
                                </div>
                                <p class="service-desc text-muted mb-2">{{ $serviceItem->short_description }}</p>
                                <div class="service-meta d-flex justify-content-between align-items-center mb-2">
                                    <span class="service-time"><i class="far fa-clock me-1"></i> {{ $serviceItem->min_time }}-{{ $serviceItem->max_time }} jam</span>
                                    <span class="service-price fw-bold">Rp {{ number_format($serviceItem->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="service-card-location mb-3">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $serviceItem->location ?? 'Lokasi tidak tersedia' }}
                                </div>
                                <div class="service-actions d-flex gap-2">
                                    <button class="service-view-btn btn btn-outline-primary flex-grow-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#productDetailModal"
                                            data-id="{{ $serviceItem->id }}"
                                            data-title="{{ $serviceItem->title }}"
                                            data-description="{{ $serviceItem->description }}"
                                            data-price="{{ $serviceItem->price }}"
                                            data-min-time="{{ $serviceItem->min_time }}"
                                            data-max-time="{{ $serviceItem->max_time }}"
                                            data-location="{{ $serviceItem->location ?? 'Lokasi tidak tersedia' }}"
                                            data-image="{{ $serviceItem->main_image ? 'data:image/jpeg;base64,' . base64_encode($serviceItem->main_image) : asset('images/default-banner.png') }}"
                                            data-full-description="{{ $serviceItem->full_description ?? 'Deskripsi lengkap tidak tersedia.' }}"
                                            onclick="showServiceDetail(this)">
                                        <i class="fas fa-eye me-2"></i>Lihat Detail
                                    </button>
                                    <button class="service-book-btn btn btn-primary flex-grow-1" 
                                            onclick="confirmBooking('{{ $serviceItem->user_id }}', '{{ $serviceItem->title }}')">
                                        <i class="fas fa-shopping-cart me-2"></i>Pesan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="mb-4">
                    <i class="fas fa-store fa-4x"></i>
                </div>
                <h4>Belum Ada Layanan</h4>
                <p>{{ $admin->name }} belum menambahkan layanan apapun.</p>
                <a href="{{ route('home') }}" class="btn mt-3">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
        @endif
    </div>  
</div>

<!-- Product Detail Modal -->
<div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productDetailModalLabel">Detail Layanan Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <div class="service-detail-img-container">
                            <img src="{{ asset('images/default-banner.png') }}" alt="Service Detail" class="img-fluid rounded mb-3" id="serviceMainImage">
                            <div class="service-thumbnails d-flex gap-2 overflow-auto pb-2">
                                <img src="{{ asset('images/default-banner.png') }}" alt="Thumbnail 1" class="img-thumbnail service-thumb active" onclick="changeMainImage(this.src)">
                                <img src="{{ asset('images/default-banner.png') }}" alt="Thumbnail 2" class="img-thumbnail service-thumb" onclick="changeMainImage(this.src)">
                                <img src="{{ asset('images/default-banner.png') }}" alt="Thumbnail 3" class="img-thumbnail service-thumb" onclick="changeMainImage(this.src)">
                                <img src="{{ asset('images/default-banner.png') }}" alt="Thumbnail 4" class="img-thumbnail service-thumb" onclick="changeMainImage(this.src)">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="service-detail-info">
                            <h4 class="service-detail-title mb-2" id="modal-service-title">Nama Layanan</h4>
                            <div class="service-detail-badge mb-3">
                                <span class="badge bg-primary" id="modal-service-badge">Service</span>
                            </div>
                            <div class="service-highlights p-3 mb-3 rounded" style="background-color: #f8f9fa;">
                                <h6 class="mb-2">Highlight Layanan:</h6>
                                <ul class="service-highlights-list ps-3 mb-0">
                                    <li>Teknisi berpengalaman dan tersertifikasi</li>
                                    <li>Garansi service 30 hari</li>
                                    <li>Konsultasi gratis sebelum perbaikan</li>
                                    <li>Suku cadang original</li>
                                    <li>Layanan antar-jemput tersedia</li>
                                </ul>
                            </div>
                            <p class="service-detail-desc mb-3" id="modal-service-description">Deskripsi layanan tidak tersedia</p>
                            <div class="service-meta-info d-flex justify-content-between mb-3">
                                <div class="service-time">
                                    <i class="far fa-clock me-1"></i> Estimasi waktu: 
                                    <strong id="modal-service-time">Belum ditentukan</strong>
                                </div>
                                <div class="service-price">
                                    <span class="fw-bold fs-5" style="color: #007bff;" id="modal-service-price">Rp 0</span>
                                </div>
                            </div>
                            <div class="service-location-detail mb-3">
                                <i class="fas fa-map-marker-alt me-1"></i> Lokasi: <strong id="modal-service-location">Lokasi tidak tersedia</strong>
                            </div>
                            <div class="service-category-detail mb-3">
                                <i class="fas fa-tag me-1"></i> Kategori: <strong id="modal-service-category">Kategori tidak tersedia</strong>
                            </div>
                            <button class="service-book-btn btn btn-primary w-100 py-2">Pesan Sekarang</button>
                        </div>
                    </div>
                </div>

                <!-- Service Detail Tabs -->
                <div class="service-detail-tabs mt-4">
                    <ul class="nav nav-tabs" id="serviceDetailTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Deskripsi Lengkap</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="process-tab" data-bs-toggle="tab" data-bs-target="#process" type="button" role="tab" aria-controls="process" aria-selected="false">Proses Service</button>
                        </li>
                    </ul>
                    <div class="tab-content p-3 border border-top-0 rounded-bottom" id="serviceDetailTabsContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <h6>Deskripsi Layanan</h6>
                            <p id="modal-full-description">Deskripsi lengkap layanan tidak tersedia</p>
                            
                            <h6 class="mt-3">Informasi Layanan:</h6>
                            <ul>
                                <li><strong>Kategori:</strong> <span id="modal-info-category">Tidak dikategorikan</span></li>
                                <li><strong>Lokasi:</strong> <span id="modal-info-location">Lokasi tidak tersedia</span></li>
                                <li><strong>Estimasi Waktu:</strong> <span id="modal-info-time">Belum ditentukan</span></li>
                                <li><strong>Harga:</strong> <span id="modal-info-price">Rp 0</span></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="process" role="tabpanel" aria-labelledby="process-tab">
                            <h6>Tahapan Service</h6>
                            <div class="service-process">
                                <div class="process-step d-flex mb-3">
                                    <div class="process-icon me-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; box-shadow: 0 3px 8px rgba(26, 115, 232, 0.25); border: 2px solid rgba(255, 255, 255, 0.8);">
                                            <i class="fas fa-clipboard-check" style="font-size: 18px; padding: 2px;"></i>
                                        </div>
                                    </div>
                                    <div class="process-content">
                                        <h6>1. Konsultasi dan Diagnosis</h6>
                                        <p>Teknisi kami akan melakukan diagnosis awal untuk mengidentifikasi masalah pada perangkat Anda. Kami akan memberikan penjelasan mengenai masalah yang ditemukan dan solusi yang direkomendasikan.</p>
                                    </div>
                                </div>
                                
                                <div class="process-step d-flex mb-3">
                                    <div class="process-icon me-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; box-shadow: 0 3px 8px rgba(26, 115, 232, 0.25); border: 2px solid rgba(255, 255, 255, 0.8);">
                                            <i class="fas fa-tools" style="font-size: 18px; padding: 2px;"></i>
                                        </div>
                                    </div>
                                    <div class="process-content">
                                        <h6>2. Persetujuan Service</h6>
                                        <p>Setelah diagnosis, kami akan memberikan estimasi biaya dan waktu perbaikan. Service akan dilakukan setelah Anda menyetujui estimasi tersebut.</p>
                                    </div>
                                </div>
                                
                                <div class="process-step d-flex mb-3">
                                    <div class="process-icon me-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; box-shadow: 0 3px 8px rgba(26, 115, 232, 0.25); border: 2px solid rgba(255, 255, 255, 0.8);">
                                            <i class="fas fa-laptop-medical" style="font-size: 18px; padding: 2px;"></i>
                                        </div>
                                    </div>
                                    <div class="process-content">
                                        <h6>3. Proses Perbaikan</h6>
                                        <p>Teknisi kami akan melakukan perbaikan sesuai dengan diagnosis yang telah dilakukan. Kami menggunakan peralatan modern dan suku cadang berkualitas untuk memastikan hasil yang optimal.</p>
                                    </div>
                                </div>
                                
                                <div class="process-step d-flex mb-3">
                                    <div class="process-icon me-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; box-shadow: 0 3px 8px rgba(26, 115, 232, 0.25); border: 2px solid rgba(255, 255, 255, 0.8);">
                                            <i class="fas fa-vial" style="font-size: 18px; padding: 2px;"></i>
                                        </div>
                                    </div>
                                    <div class="process-content">
                                        <h6>4. Pengujian</h6>
                                        <p>Setelah perbaikan selesai, kami akan melakukan serangkaian pengujian untuk memastikan perangkat berfungsi dengan baik dan masalah telah teratasi.</p>
                                    </div>
                                </div>
                                
                                <div class="process-step d-flex">
                                    <div class="process-icon me-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; box-shadow: 0 3px 8px rgba(26, 115, 232, 0.25); border: 2px solid rgba(255, 255, 255, 0.8);">
                                            <i class="fas fa-check-circle" style="font-size: 18px; padding: 2px;"></i>
                                        </div>
                                    </div>
                                    <div class="process-content">
                                        <h6>5. Serah Terima dan Garansi</h6>
                                        <p>Perangkat yang telah diperbaiki akan diserahkan kepada Anda beserta penjelasan mengenai perbaikan yang telah dilakukan. Kami memberikan garansi service selama 30 hari.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showServiceDetail(button) {
    const title = button.getAttribute('data-title');
    const description = button.getAttribute('data-description');
    const price = button.getAttribute('data-price');
    const minTime = button.getAttribute('data-min-time');
    const maxTime = button.getAttribute('data-max-time');
    const location = button.getAttribute('data-location');
    const image = button.getAttribute('data-image');
    const fullDescription = button.getAttribute('data-full-description');
    
    // Update modal elements with new IDs
    document.getElementById('modal-service-title').textContent = title;
    document.getElementById('modal-service-description').textContent = description;
    document.getElementById('modal-service-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
    document.getElementById('modal-service-time').textContent = minTime + '-' + maxTime + ' jam';
    document.getElementById('modal-service-location').textContent = location;
    document.getElementById('modal-service-category').textContent = 'Service Elektronik'; // Default category
    document.getElementById('serviceMainImage').src = image;
    document.getElementById('modal-full-description').textContent = fullDescription;
    
    // Update info section
    document.getElementById('modal-info-category').textContent = 'Service Elektronik';
    document.getElementById('modal-info-location').textContent = location;
    document.getElementById('modal-info-time').textContent = minTime + '-' + maxTime + ' jam';
    document.getElementById('modal-info-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
    
    // Update all thumbnail images to show the main image
    const thumbnails = document.querySelectorAll('.service-thumb');
    thumbnails.forEach(thumb => {
        thumb.src = image;
        thumb.classList.remove('active');
    });
    if (thumbnails.length > 0) {
        thumbnails[0].classList.add('active');
    }
}

function changeMainImage(src) {
    document.getElementById('serviceMainImage').src = src;
    
    // Update active thumbnail
    const thumbnails = document.querySelectorAll('.service-thumb');
    thumbnails.forEach(thumb => {
        thumb.classList.remove('active');
        if (thumb.src === src) {
            thumb.classList.add('active');
        }
    });
}

function confirmBooking(userId, serviceTitle) {
    if (confirm(`Apakah Anda yakin ingin memesan layanan "${serviceTitle}"?`)) {
        // Cari service_id berdasarkan serviceTitle dan userId
        const serviceCards = document.querySelectorAll('.service-card');
        let serviceId = null;
        let serviceCard = null;
        
        serviceCards.forEach(card => {
             const cardTitle = card.querySelector('.service-title')?.textContent?.trim();
             const cardUserId = card.querySelector('[onclick*="confirmBooking"]')?.getAttribute('onclick')?.match(/confirmBooking\('([^']+)'/)?.[1];
             
             if (cardTitle === serviceTitle && cardUserId == userId) {
                 // Ambil service_id dari data attribute
                 serviceId = card.dataset.serviceId;
                 serviceCard = card;
             }
         });
        
        if (!serviceId) {
            alert('Service ID tidak ditemukan. Silakan coba lagi.');
            return;
        }
        
        // Buat pesanan baru
        fetch('/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                service_id: serviceId,
                notes: `Pesanan untuk layanan: ${serviceTitle}`
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tampilkan notifikasi sukses
                showBookingNotification(`Pesanan berhasil dibuat dengan nomor: ${data.order_number}. Mengarahkan ke chat...`);
                
                // Kirim detail produk ke chat secara otomatis
                sendProductDetailsToChat(userId, serviceCard, data.order_number);
                
                // Redirect ke chat dengan userId setelah delay
                setTimeout(() => {
                    if (userId) {
                        window.location.href = `/Chat/${userId}?order=${data.order_number}`;
                    } else {
                        window.location.href = '/Chat';
                    }
                }, 3000);
            } else {
                alert('Gagal membuat pesanan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error creating order:', error);
            alert('Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.');
        });
    } else {
        // Log pembatalan
        console.log('Booking cancelled for service:', serviceTitle);
        showCancelNotification();
    }
}

// Function untuk mengirim detail produk ke chat
function sendProductDetailsToChat(userId, serviceCard, orderNumber) {
    if (!serviceCard || !userId) return;
    
    // Ekstrak detail produk dari kartu
    const title = serviceCard.querySelector('.service-title')?.textContent?.trim() || '';
    const description = serviceCard.querySelector('.service-desc')?.textContent?.trim() || '';
    const price = serviceCard.querySelector('.service-price')?.textContent?.trim() || '';
    const time = serviceCard.querySelector('.service-time')?.textContent?.trim() || '';
    const location = serviceCard.querySelector('.service-card-location')?.textContent?.trim() || '';
    
    // Format pesan detail produk
    let productMessage = `🛒 DETAIL PESANAN #${orderNumber}\n\n`;
    productMessage += `🔧 Layanan: ${title}\n`;
    productMessage += `📝 Deskripsi: ${description}\n`;
    productMessage += `💰 Harga: ${price}\n`;
    productMessage += `⏱️ Estimasi: ${time}\n`;
    if (location) {
        productMessage += `📍 Lokasi: ${location}\n`;
    }
    productMessage += `\n✅ Pesanan Anda telah dikonfirmasi!\n`;
    productMessage += `💬 Silakan diskusikan detail lebih lanjut dengan teknisi.`;
    
    // Kirim pesan ke chat
    fetch('/chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            receiver_id: userId,
            message: productMessage
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Product details sent to chat successfully');
        } else {
            console.error('Failed to send product details to chat');
        }
    })
    .catch(error => {
        console.error('Error sending product details to chat:', error);
    });
}

function showBookingNotification(message) {
    // Buat elemen notifikasi
    const notification = document.createElement('div');
    notification.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            z-index: 10000;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            font-weight: 500;
            max-width: 350px;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            border-left: 4px solid #fff;
        ">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle" style="font-size: 18px;"></i>
                <span>${message}</span>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animasi masuk
    setTimeout(() => {
        notification.firstElementChild.style.transform = 'translateX(0)';
    }, 100);
    
    // Hapus notifikasi setelah 3 detik
    setTimeout(() => {
        notification.firstElementChild.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function showCancelNotification() {
    // Buat elemen notifikasi pembatalan
    const notification = document.createElement('div');
    notification.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
            z-index: 10000;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            font-weight: 500;
            max-width: 350px;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            border-left: 4px solid #fff;
        ">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-times-circle" style="font-size: 18px;"></i>
                <span>Pemesanan dibatalkan</span>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animasi masuk
    setTimeout(() => {
        notification.firstElementChild.style.transform = 'translateX(0)';
    }, 100);
    
    // Hapus notifikasi setelah 2 detik
    setTimeout(() => {
        notification.firstElementChild.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 2000);
}
</script>
@endsection