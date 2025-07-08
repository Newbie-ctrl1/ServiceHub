@extends('Store.admin.index')

@section('head')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
@endsection

@section('content')
<style>
    /* Location and Category Styles */
    .service-location, .service-category {
        font-size: 0.85rem;
        color: #666;
        display: flex;
        align-items: center;
    }
    
    .service-location i, .service-category i {
        color: var(--primary-color);
        font-size: 0.9rem;
        margin-right: 5px;
    }
    
    .service-location-detail, .service-category-detail {
        font-size: 0.9rem;
        color: #555;
        background-color: rgba(26, 115, 232, 0.05);
        padding: 8px 12px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .service-location-detail i, .service-category-detail i {
        color: var(--primary-color);
        font-size: 1rem;
        margin-right: 5px;
    }
    
    .banner-location {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 20px;
        margin-top: 10px;
    }
    
    .banner-location i {
        margin-right: 5px;
    }
    
    /* Banner Admin Controls */
    .banner-admin-controls {
        background-color: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .banner-form .form-control {
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #333;
    }
    
    .banner-form .form-control::placeholder {
        color: #666;
    }
    
    .banner-image-preview {
        position: relative;
        margin-bottom: 15px;
        min-height: 40px;
    }
    
    .cancel-banner-image {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 25px;
        height: 25px;
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
    }
    
    .cancel-banner-image:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }
    
    /* Banner Control Icons */
    .banner-control-icons {
        display: flex;
        gap: 8px;
    }
    
    .banner-control-icons .btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .banner-control-icons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }
    
    .banner-control-icons .btn-light:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .banner-control-icons .btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
    }
    
    .banner-control-icons .btn-danger:hover {
        background-color: #bb2d3b;
        border-color: #b02a37;
    }
    
    /* Banner Preview Overlay */
    .banner-preview-overlay {
        transition: all 0.3s ease;
    }
    
    .banner-preview-overlay .fa-eye {
        opacity: 0.8;
    }
    
    /* Banner Admin Form Animation */
    .banner-admin-controls {
        transform-origin: top;
        overflow: hidden;
    }
    
    /* Service Cards */
    .service-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.4s ease;
        cursor: pointer;
        background-color: var(--background-card);
        border: 1.5px solid var(--border-color);
        position: relative;
    }
    
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        border-color: rgba(26, 115, 232, 0.3);
    }
    
    .service-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--primary-gradient, linear-gradient(90deg, var(--primary-color), var(--primary-light)));
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.4s ease;
    }
    
    .service-card:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }
    
    .service-img-container {
        height: 180px;
        overflow: hidden;
    }
    
    .service-img {
        object-fit: cover;
        height: 100%;
        transition: transform 0.5s;
    }
    
    .service-card:hover .service-img {
        transform: scale(1.05);
    }
    
    .service-badge {
        font-size: 12px;
        font-weight: 500;
        padding: 5px 10px;
    }
    
    .service-fav-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.9);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        border: none;
        transition: all 0.3s;
    }
    
    .service-fav-btn:hover {
        background-color: white;
        color: var(--accent-color);
        transform: scale(1.1);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }
    
    .service-fav-btn .fas {
        color: var(--accent-color);
        font-size: 16px;
    }
    
    .service-fav-btn .far {
        font-size: 16px;
    }
    
    .service-title {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--text-primary);
    }
    
    .service-desc {
        font-size: 14px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .service-time, .service-price {
        font-size: 14px;
    }
    
    .service-price {
        color: var(--primary-color);
    }
    
    /* Banner */
    .store-banner {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border: 1.5px solid rgba(255, 255, 255, 0.2);
        position: relative;
    }
    
    .store-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.1) 100%);
        z-index: 1;
    }
    
    .store-banner .banner-content {
        position: relative;
        z-index: 2;
        background-size: cover;
        background-position: center;
    }
    
    .banner-delete-form .btn-danger {
        opacity: 0.8;
        transition: all 0.3s;
    }
    
    .banner-delete-form .btn-danger:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    .banner-title {
        font-size: 36px;
        font-weight: 800;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.5px;
    }
    
    .banner-text {
        font-size: 18px;
        opacity: 0.95;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }
    
    .banner-btn {
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    
    .banner-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }
    
    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }
    
    .modal-header {
        border-bottom: 1.5px solid var(--border-color);
        padding: 20px 24px;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-footer {
        border-top: 1.5px solid var(--border-color);
        padding: 16px 24px;
    }
    
    .service-thumb {
        width: 80px;
        height: 60px;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.3s;
        border-radius: 8px;
        margin: 0 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
    }
    
    .service-thumb.active {
        border: 2px solid var(--primary-color);
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(26, 115, 232, 0.25);
    }
    
    .service-thumb:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }
    
    .service-detail-tabs .nav-link {
        color: var(--text-secondary);
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        font-weight: 500;
        transition: all 0.3s;
        position: relative;
        border: 1px solid transparent;
        margin-right: 4px;
    }
    
    .service-detail-tabs .nav-link:hover {
        color: var(--primary-color);
        background-color: rgba(26, 115, 232, 0.05);
    }
    
    .service-detail-tabs .nav-link.active {
        color: var(--primary-color);
        font-weight: 600;
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-bottom-color: #fff;
        box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .service-detail-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary-gradient, linear-gradient(90deg, var(--primary-color), var(--primary-light)));
        border-radius: 3px 3px 0 0;
    }
    
    .service-detail-tabs .tab-content {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-radius: 0 0 8px 8px;
    }
    
    /* Pagination */
    .pagination {
        margin-top: 30px;
        gap: 5px;
    }
    
    .pagination .page-link {
        color: var(--text-secondary);
        border: 1.5px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 500;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
    }
    
    .pagination .page-item.active .page-link {
        background: var(--primary-gradient, linear-gradient(90deg, var(--primary-color), var(--primary-light)));
        border-color: var(--primary-color);
        color: white;
        box-shadow: 0 4px 8px rgba(26, 115, 232, 0.25);
    }
    
    .pagination .page-link:hover {
        background-color: var(--background-light);
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 10px 14px;
    }
</style>

<div class="store-content">
    <!-- Banner Section -->
    <div class="store-banner mb-4">
        <div class="p-5 text-white text-center banner-content position-relative" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ secure_asset('images/default-banner.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <!-- Banner Control Icons -->
            <div class="banner-control-icons position-absolute" style="top: 15px; right: 15px; z-index: 10;">
                <button type="button" class="btn btn-light btn-sm me-2" id="edit-banner-btn" title="Edit Banner">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm me-2" id="upload-banner-btn" title="Upload Banner">
                    <i class="fas fa-upload"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="delete-banner-btn" title="Hapus Banner">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <!-- Banner Admin Controls (Hidden by default) -->
            <div class="banner-admin-controls mb-4" id="banner-admin-form" style="display: none;">
                <form class="banner-form" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="text-start mb-0">Upload Banner Toko</h5>
                                <button type="button" class="btn btn-outline-light btn-sm" id="close-banner-form">
                                    <i class="fas fa-times"></i> Tutup
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="banner-image-preview" id="banner-image-preview">
                                <img id="banner-preview-img" src="#" alt="Preview Banner" style="display: none; max-width: 100%; max-height: 200px; border-radius: 8px;">
                                <div class="cancel-banner-image" id="cancel-banner-image" style="display: none;">&times;</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <input type="file" class="form-control" id="banner-image-upload" accept="image/*">
                                <label class="input-group-text" for="banner-image-upload">Pilih Gambar</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="banner-title" placeholder="Judul Banner">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="banner-text" placeholder="Deskripsi Banner">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="banner-location" placeholder="Lokasi">
                        </div>
                        <div class="col-12 text-start">
                            <button type="button" class="btn btn-primary" id="save-banner-btn">Simpan Banner</button>
                            <button type="button" class="btn btn-secondary ms-2" id="cancel-banner-btn">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Banner Content -->
            <div id="banner-display" class="position-relative">
                <div class="banner-preview-overlay" id="banner-preview-overlay" style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); border-radius: 8px; z-index: 5;">
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center text-white">
                            <i class="fas fa-eye fa-2x mb-2"></i>
                            <p class="mb-0">Preview Banner</p>
                        </div>
                    </div>
                </div>
                <img id="display-banner-img" src="{{ secure_asset('images/default-banner.png') }}" alt="Banner" class="img-fluid mb-3" style="max-height: 200px; border-radius: 8px; display: none;">
                <h2 class="banner-title" id="display-banner-title">Layanan Service Terbaik</h2>
                <p class="banner-text mt-3" id="display-banner-text">Temukan berbagai layanan service berkualitas dengan teknisi profesional dan berpengalaman</p>
                <p class="banner-location mt-3"><i class="fas fa-map-marker-alt me-1"></i> {{ $service->location ?? 'Lokasi tidak tersedia' }}</p>
            </div>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="services-section mb-5">
        <!-- Alert untuk notifikasi sukses -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <!-- Tombol Tambah Layanan -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Daftar Layanan</h4>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Layanan
            </a>
        </div>

        <div class="services-grid row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @forelse($services as $service)
            <!-- Service Card -->
            <div class="col">
                <div class="service-card h-100">
                    <div class="service-img-container position-relative">
                        @if($service->main_image)
                            <img src="data:image/jpeg;base64,{{ base64_encode($service->main_image) }}" alt="{{ $service->title }}" class="service-img img-fluid w-100 rounded-top">
                        @else
                            <img src="{{ secure_asset('images/default-banner.png') }}" alt="Service Image" class="service-img img-fluid w-100 rounded-top">
                        @endif
                        
                        @if($service->badge)
                            <span class="service-badge badge bg-primary position-absolute top-0 end-0 m-2">{{ $service->badge }}</span>
                        @endif
                        
                        <button class="service-fav-btn position-absolute top-0 start-0 m-2 btn btn-light btn-sm rounded-circle">
                            <i class="far fa-heart"></i>
                        </button>
                        
                        <!-- Admin Action Buttons -->
                        <div class="admin-actions position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                            <a href="{{ route('admin.services.show', $service->id) }}" class="btn btn-sm btn-info" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="service-info p-3">
                        <h5 class="service-title">{{ $service->title }}</h5>
                        <div class="service-rating mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                            <span class="ms-1 text-muted">(4.5)</span>
                        </div>
                        <p class="service-desc text-muted mb-2">{{ $service->short_description }}</p>
                        <div class="service-meta d-flex justify-content-between align-items-center mb-2">
                            <span class="service-time"><i class="far fa-clock me-1"></i> {{ $service->min_time }}-{{ $service->max_time }} jam</span>
                            <span class="service-price fw-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="service-location mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $service->location ?? 'Lokasi tidak tersedia' }}
                        </div>
                        <div class="service-category mb-2">
                            <i class="fas fa-tag me-1"></i> {{ $service->category ?? 'Kategori tidak tersedia' }}
                        </div>
                        <div class="service-actions d-flex gap-2">
                            <button class="service-view-btn btn btn-outline-primary flex-grow-1" data-id="{{ $service->id }}">Lihat Detail</button>
                            <button class="service-book-btn btn btn-primary flex-grow-1">Pesan</button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Belum ada layanan yang tersedia. Silakan tambahkan layanan baru.
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="store-pagination d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Service Detail Modal -->
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
                            <h4 class="service-detail-title mb-2" id="modal-service-title">{{ $service->title ?? 'Nama Layanan' }}</h4>
                            <div class="service-detail-badge mb-3">
                                <span class="badge bg-primary">{{ $service->badge ?? 'Service' }}</span>
                            </div>
                            <div class="service-highlights p-3 mb-3 rounded" style="background-color: var(--background-light);">
                                <h6 class="mb-2">Highlight Layanan:</h6>
                                <ul class="service-highlights-list ps-3 mb-0">
                                    <li>Teknisi berpengalaman dan tersertifikasi</li>
                                    <li>Garansi service 30 hari</li>
                                    <li>Konsultasi gratis sebelum perbaikan</li>
                                    <li>Suku cadang original</li>
                                    <li>Layanan antar-jemput tersedia</li>
                                </ul>
                            </div>
                            <p class="service-detail-desc mb-3" id="modal-service-description">{{ $service->description ?? 'Deskripsi layanan tidak tersedia' }}</p>
                            <div class="service-meta-info d-flex justify-content-between mb-3">
                                <div class="service-time">
                                    <i class="far fa-clock me-1"></i> Estimasi waktu: 
                                    <strong id="modal-service-time">
                                        @if($service->min_time && $service->max_time)
                                            {{ $service->min_time }} - {{ $service->max_time }} jam
                                        @elseif($service->min_time)
                                            Minimal {{ $service->min_time }} jam
                                        @elseif($service->max_time)
                                            Maksimal {{ $service->max_time }} jam
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </strong>
                                </div>
                                <div class="service-price">
                                    <span class="fw-bold fs-5" style="color: var(--primary-color);" id="modal-service-price">Rp {{ number_format($service->price ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="service-location-detail mb-3">
                                <i class="fas fa-map-marker-alt me-1"></i> Lokasi: <strong>{{ $service->location ?? 'Lokasi tidak tersedia' }}</strong>
                            </div>
                            <div class="service-category-detail mb-3">
                                <i class="fas fa-tag me-1"></i> Kategori: <strong>{{ $service->category ?? 'Kategori tidak tersedia' }}</strong>
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
                            <p id="modal-full-description">{{ $service->full_description ?? $service->description ?? 'Deskripsi lengkap layanan tidak tersedia' }}</p>
                            
                            @if($service->features)
                            <h6 class="mt-3">Fitur Layanan:</h6>
                            <div id="modal-service-features">
                                {!! nl2br(e($service->features)) !!}
                            </div>
                            @endif
                            
                            <h6 class="mt-3">Informasi Layanan:</h6>
                            <ul>
                                <li><strong>Kategori:</strong> {{ $service->category ?? 'Tidak dikategorikan' }}</li>
                                <li><strong>Lokasi:</strong> {{ $service->location ?? 'Lokasi tidak tersedia' }}</li>
                                <li><strong>Estimasi Waktu:</strong> {{ $service->estimated_time ?? 'Belum ditentukan' }}</li>
                                <li><strong>Harga:</strong> Rp {{ number_format($service->price ?? 0, 0, ',', '.') }}</li>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Category handlers removed
        
        // Favorite Button Click Handler
        $('.service-fav-btn').on('click', function(e) {
            e.stopPropagation();
            $(this).find('i').toggleClass('far fas');
            // Here you would add/remove from favorites in the backend
        });
        
        // View Detail Button Click Handler
        $('.service-view-btn').on('click', function(e) {
            e.stopPropagation();
            // Get service ID from data attribute
            const serviceId = $(this).data('id');
            // Here you would fetch service details from backend and populate the modal
            // For now, we'll just show the modal
            new bootstrap.Modal(document.getElementById('productDetailModal')).show();
        });
        
        // Service Card Click Handler
        $('.service-card').on('click', function() {
            // Here you would populate the modal with service details
            // For now, we'll just show the modal
            new bootstrap.Modal(document.getElementById('productDetailModal')).show();
        });
        
        // Booking Button Click Handler
        $('.service-book-btn').on('click', function(e) {
            e.stopPropagation();
            // Here you would redirect to booking page or show booking modal
            window.location.href = '/Chat';
        });
        
        // Delete Service Button Click Handler
        $('.delete-service').on('click', function(e) {
            e.stopPropagation();
            const serviceId = $(this).data('id');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Layanan yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form to submit DELETE request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("store.delete", ":id") }}'.replace(':id', serviceId);
                    form.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add method spoofing for DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
        
        
        // Initialize banner image upload functionality
        initBannerImageUpload();
        
        // Banner control icons event handlers
        $('#edit-banner-btn, #upload-banner-btn').on('click', function() {
            $('#banner-admin-form').slideDown(300);
            $('#banner-preview-overlay').fadeIn(200);
        });
        
        $('#close-banner-form, #cancel-banner-btn').on('click', function() {
            $('#banner-admin-form').slideUp(300);
            $('#banner-preview-overlay').fadeOut(200);
        });
        
        // Delete banner button handler
        $('#delete-banner-btn').on('click', function() {
            Swal.fire({
                title: 'Hapus Banner?',
                text: "Banner yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteBanner();
                }
            });
        });
    });
    
    // Function to change main image when thumbnail is clicked
    function changeMainImage(src) {
        document.getElementById('serviceMainImage').src = src;
        document.querySelectorAll('.service-thumb').forEach(thumb => {
            thumb.classList.remove('active');
            if (thumb.src === src) {
                thumb.classList.add('active');
            }
        });
    }
    
    // Initialize banner image upload functionality
    function initBannerImageUpload() {
        // Add event listener for banner image upload
        const bannerImageUpload = document.getElementById('banner-image-upload');
        bannerImageUpload.addEventListener('change', handleBannerImageUpload);
        
        // Add event listener for cancel button
        const cancelBannerImage = document.getElementById('cancel-banner-image');
        cancelBannerImage.addEventListener('click', clearBannerImagePreview);
        
        // Add event listener for save button
        const saveBannerBtn = document.getElementById('save-banner-btn');
        saveBannerBtn.addEventListener('click', saveBanner);
        
        // Add event listeners for real-time preview of title and text
        const bannerTitle = document.getElementById('banner-title');
        const bannerText = document.getElementById('banner-text');
        
        bannerTitle.addEventListener('input', function() {
            document.getElementById('display-banner-title').textContent = this.value || 'Layanan Service Terbaik';
        });
        
        bannerText.addEventListener('input', function() {
            document.getElementById('display-banner-text').textContent = this.value || 'Temukan berbagai layanan service berkualitas dengan teknisi profesional dan berpengalaman';
        });
        
        // Load active banner if exists
        loadActiveBanner();
    }
    
    // Function to load active banner from server
    function loadActiveBanner() {
        fetch('{{ secure_url(route("api.banner.active")) }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.banner) {
                // Update form fields
                document.getElementById('banner-title').value = data.banner.title;
                document.getElementById('banner-text').value = data.banner.text;
                document.getElementById('banner-location').value = data.banner.location || '';
                
                // Update display
                document.getElementById('display-banner-title').textContent = data.banner.title;
                document.getElementById('display-banner-text').textContent = data.banner.text;
                
                // Update location display
                const locationElement = document.querySelector('.banner-location');
                if (locationElement) {
                    locationElement.innerHTML = data.banner.location ? 
                        `<i class="fas fa-map-marker-alt me-1"></i> ${data.banner.location}` : 
                        `<i class="fas fa-map-marker-alt me-1"></i> Lokasi tidak tersedia`;
                }
                
                // Update banner image
                if (data.banner.image) {
                    const displayBannerImg = document.getElementById('display-banner-img');
                    displayBannerImg.src = 'data:image/jpeg;base64,' + data.banner.image;
                    displayBannerImg.style.display = 'block';
                    
                    // Also update preview
                    const previewImg = document.getElementById('banner-preview-img');
                    previewImg.src = 'data:image/jpeg;base64,' + data.banner.image;
                    previewImg.style.display = 'block';
                    document.getElementById('cancel-banner-image').style.display = 'block';
                    
                    // Update banner background with uploaded image
                    document.querySelector('.banner-content').style.backgroundImage = `linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url(data:image/jpeg;base64,${data.banner.image})`;
                    document.querySelector('.banner-content').style.backgroundSize = 'cover';
                    document.querySelector('.banner-content').style.backgroundPosition = 'center';
                } else {
                    // No banner image, use default
                    document.querySelector('.banner-content').style.background = "linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('images/default-banner.png') }}')";
                    document.querySelector('.banner-content').style.backgroundSize = 'cover';
                    document.querySelector('.banner-content').style.backgroundPosition = 'center';
                    document.querySelector('.banner-content').style.backgroundRepeat = 'no-repeat';
                }
            } else {
                // No active banner found, use default banner image
                document.querySelector('.banner-content').style.background = "linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('images/default-banner.png') }}')";
                document.querySelector('.banner-content').style.backgroundSize = 'cover';
                document.querySelector('.banner-content').style.backgroundPosition = 'center';
                document.querySelector('.banner-content').style.backgroundRepeat = 'no-repeat';
            }
        })
        .catch(error => {
            console.error('Error loading active banner:', error);
            // On error, use default banner image
            document.querySelector('.banner-content').style.background = "linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('images/default-banner.png') }}')";
            document.querySelector('.banner-content').style.backgroundSize = 'cover';
            document.querySelector('.banner-content').style.backgroundPosition = 'center';
            document.querySelector('.banner-content').style.backgroundRepeat = 'no-repeat';
        });
    }
    
    // Function to handle banner image upload
    function handleBannerImageUpload(event) {
        const file = event.target.files[0];
        if (file && file.type.match('image.*')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Store the image URL
                const bannerImage = e.target.result;
                
                // Show image preview
                const previewImg = document.getElementById('banner-preview-img');
                previewImg.src = bannerImage;
                previewImg.style.display = 'block';
                
                // Display the cancel button
                document.getElementById('cancel-banner-image').style.display = 'block';
                
                // Update banner background (for preview)
                document.querySelector('.banner-content').style.backgroundImage = `linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url(${bannerImage})`;
                document.querySelector('.banner-content').style.backgroundSize = 'cover';
                document.querySelector('.banner-content').style.backgroundPosition = 'center';
            };
            
            reader.readAsDataURL(file);
        }
    }
    
    // Function to clear banner image preview
    function clearBannerImagePreview() {
        document.getElementById('banner-preview-img').style.display = 'none';
        document.getElementById('cancel-banner-image').style.display = 'none';
        document.getElementById('banner-image-upload').value = '';
        
        // Reset banner background to default image
        document.querySelector('.banner-content').style.backgroundImage = '';
        document.querySelector('.banner-content').style.background = "linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('images/default-banner.png') }}')";
        document.querySelector('.banner-content').style.backgroundSize = 'cover';
        document.querySelector('.banner-content').style.backgroundPosition = 'center';
        document.querySelector('.banner-content').style.backgroundRepeat = 'no-repeat';
    }
    
    // Function to save banner (in a real app, this would send data to the server)
    function saveBanner() {
        // Pastikan SweetAlert2 tersedia
        if (typeof Swal === 'undefined') {
            // Jika SweetAlert2 belum dimuat, muat secara dinamis
            const sweetAlertScript = document.createElement('script');
            sweetAlertScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js';
            sweetAlertScript.onload = function() {
                // Setelah script dimuat, jalankan fungsi saveBanner lagi
                saveBanner();
            };
            document.head.appendChild(sweetAlertScript);
            return; // Keluar dari fungsi dan tunggu script dimuat
        }
        // Get values
        const bannerImageInput = document.getElementById('banner-image-upload');
        const bannerTitle = document.getElementById('banner-title').value;
        const bannerText = document.getElementById('banner-text').value;
        
        // Validate inputs
        if (!bannerImageInput.files[0] && !document.getElementById('banner-preview-img').src.includes('data:image')) {
            alert('Silakan pilih gambar banner terlebih dahulu!');
            return;
        }
        
        if (!bannerTitle) {
            alert('Silakan isi judul banner!');
            return;
        }
        
        if (!bannerText) {
            alert('Silakan isi deskripsi banner!');
            return;
        }
        
        // Create FormData object
        const formData = new FormData();
        
        // If there's a new file selected, add it to FormData
        if (bannerImageInput.files[0]) {
            formData.append('image', bannerImageInput.files[0]);
        } else {
            // If using existing image from preview, convert base64 to blob and append
            const base64Image = document.getElementById('banner-preview-img').src;
            if (base64Image && base64Image.includes('data:image')) {
                try {
                    const blob = dataURItoBlob(base64Image);
                    if (blob) {
                        formData.append('image', blob, 'banner.jpg');
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses gambar. Silakan pilih gambar lain.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                } catch (error) {
                    console.error('Error processing image:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memproses gambar: ' + error.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            }
        }
        
        formData.append('title', bannerTitle);
        formData.append('text', bannerText);
        
        // Tambahkan lokasi ke formData
        const bannerLocation = document.getElementById('banner-location').value;
        formData.append('location', bannerLocation);
        
        formData.append('_token', '{{ csrf_token() }}');
        
        // Show loading indicator
        const saveBtn = document.getElementById('save-banner-btn');
        const originalBtnText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        saveBtn.disabled = true;
        
        // Send data to server using fetch API
        fetch('{{ secure_url(route("admin.banner.store")) }}', {
            method: 'POST',
            body: formData,
            // Tidak perlu menambahkan header X-CSRF-TOKEN karena token sudah ada di formData
            // dan Laravel akan otomatis memvalidasi token dari formData
        })
        .then(response => {
            // Periksa apakah respons adalah JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // Jika respons bukan JSON, coba dapatkan teks respons untuk informasi lebih lanjut
                return response.text().then(text => {
                    throw new Error('Respons bukan JSON: ' + response.status + '\nDetail: ' + text);
                });
            }
        })
        .then(data => {
            if (data.success) {
                // Show success message
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                
                // Update display banner with new values
                document.getElementById('display-banner-title').textContent = bannerTitle;
                document.getElementById('display-banner-text').textContent = bannerText;
                
                // Update location display
                const bannerLocation = document.getElementById('banner-location').value;
                const locationElement = document.querySelector('.banner-location');
                if (locationElement) {
                    locationElement.innerHTML = bannerLocation ? 
                        `<i class="fas fa-map-marker-alt me-1"></i> ${bannerLocation}` : 
                        `<i class="fas fa-map-marker-alt me-1"></i> Lokasi tidak tersedia`;
                }
                
                // Update banner image display
                const displayBannerImg = document.getElementById('display-banner-img');
                if (data.banner && data.banner.image) {
                    displayBannerImg.src = 'data:image/jpeg;base64,' + data.banner.image;
                    displayBannerImg.style.display = 'block';
                } else if (bannerImageInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        displayBannerImg.src = e.target.result;
                        displayBannerImg.style.display = 'block';
                    }
                    reader.readAsDataURL(bannerImageInput.files[0]);
                }
            } else {
                // Show error message
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Terjadi kesalahan saat menyimpan banner',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Pastikan Swal tersedia sebelum menggunakannya
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyimpan banner: ' + error.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } else {
                // Fallback jika Swal tidak tersedia
                alert('Terjadi kesalahan saat menyimpan banner: ' + error.message);
            }
        })
        .finally(() => {
            // Reset button state
            saveBtn.innerHTML = originalBtnText;
            saveBtn.disabled = false;
        });
    }

    // Function to convert Data URI to Blob
    function dataURItoBlob(dataURI) {
        // Pastikan dataURI valid
        if (!dataURI || !dataURI.includes('data:image')) {
            console.error('Invalid Data URI');
            return null; // Return null instead of empty blob
        }
        
        try {
            // Validasi format data URI
            const parts = dataURI.split(',');
            if (parts.length !== 2) {
                console.error('Invalid Data URI format');
                return null;
            }
            
            // Ekstrak MIME type
            const mimeMatch = parts[0].match(/data:([^;]+);base64/);
            if (!mimeMatch) {
                console.error('Invalid MIME format in Data URI');
                return null;
            }
            const mimeString = mimeMatch[1];
            
            // Decode base64
            try {
                const byteString = atob(parts[1]);
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                
                return new Blob([ab], {type: mimeString});
            } catch (decodeError) {
                console.error('Error decoding base64:', decodeError);
                return null;
            }
        } catch (error) {
            console.error('Error converting Data URI to Blob:', error);
            return null; // Return null instead of empty blob
        }
    }

    // Add event listener for delete banner button
    document.getElementById('delete-banner-btn').addEventListener('click', function() {
        // Pastikan SweetAlert2 tersedia
        if (typeof Swal === 'undefined') {
            // Jika SweetAlert2 belum dimuat, muat secara dinamis
            const sweetAlertScript = document.createElement('script');
            sweetAlertScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js';
            sweetAlertScript.onload = function() {
                // Setelah script dimuat, jalankan fungsi delete banner lagi
                document.getElementById('delete-banner-btn').click();
            };
            document.head.appendChild(sweetAlertScript);
            return; // Keluar dari fungsi dan tunggu script dimuat
        }
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Banner yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send delete request to server
                fetch('{{ secure_url(route("api.banner.active")) }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    // Periksa apakah respons adalah JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // Jika respons bukan JSON, coba dapatkan teks respons untuk informasi lebih lanjut
                        return response.text().then(text => {
                            throw new Error('Respons bukan JSON: ' + response.status + '\nDetail: ' + text);
                        });
                    }
                })
                .then(data => {
                    if (data.success && data.banner && data.banner.id) {
                        // If there's an active banner, delete it
                        return fetch(`{{ secure_url('/admin/banner') }}/${data.banner.id}`, {
                             method: 'DELETE',
                             headers: {
                                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                 'Content-Type': 'application/json',
                                 'Accept': 'application/json'
                             }
                         });
                    } else {
                        // No active banner to delete
                        Swal.fire({
                            title: 'Informasi',
                            text: 'Tidak ada banner aktif untuk dihapus',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                        return Promise.reject('Tidak ada banner aktif untuk dihapus');
                    }
                })
                .then(response => {
                    // Periksa apakah respons adalah JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // Jika respons bukan JSON, coba dapatkan teks respons untuk informasi lebih lanjut
                        return response.text().then(text => {
                            throw new Error('Respons bukan JSON: ' + response.status + '\nDetail: ' + text);
                        });
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Reset form and preview
                        clearBannerImagePreview();
                        document.getElementById('banner-title').value = '';
                        document.getElementById('banner-text').value = '';
                        document.getElementById('display-banner-title').textContent = 'Layanan Service Terbaik';
                        document.getElementById('display-banner-text').textContent = 'Temukan berbagai layanan service berkualitas dengan teknisi profesional dan berpengalaman';
                        document.getElementById('display-banner-img').style.display = 'none';
                        document.getElementById('display-banner-img').src = '{{ asset("images/default-banner.jpg") }}';
                        
                        // Show success message
                        Swal.fire(
                            'Terhapus!',
                            data.message,
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'Terjadi kesalahan saat menghapus banner',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Jangan tampilkan pesan error jika error adalah 'Tidak ada banner aktif untuk dihapus'
                    // karena sudah ditampilkan sebelumnya
                    if (error.message && error.message !== 'Tidak ada banner aktif untuk dihapus') {
                        // Pastikan Swal tersedia sebelum menggunakannya
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'Terjadi kesalahan saat menghapus banner',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Fallback jika Swal tidak tersedia
                            alert('Terjadi kesalahan saat menghapus banner: ' + error.message);
                        }
                    }
                });
            }
        });
    });
    
    // Function to delete banner
    function deleteBanner() {
        // First, get the active banner
        fetch('{{ secure_url(route("api.banner.active")) }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                return response.text().then(text => {
                    throw new Error('Respons bukan JSON: ' + response.status + '\nDetail: ' + text);
                });
            }
        })
        .then(data => {
            if (data.success && data.banner && data.banner.id) {
                // If there's an active banner, delete it
                return fetch(`{{ secure_url('/admin/banner') }}/${data.banner.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
            } else {
                // No active banner to delete
                Swal.fire({
                    title: 'Informasi',
                    text: 'Tidak ada banner aktif untuk dihapus',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                return Promise.reject('Tidak ada banner aktif untuk dihapus');
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                return response.text().then(text => {
                    throw new Error('Respons bukan JSON: ' + response.status + '\nDetail: ' + text);
                });
            }
        })
        .then(data => {
            if (data.success) {
                // Reset form and preview
                clearBannerImagePreview();
                document.getElementById('banner-title').value = '';
                document.getElementById('banner-text').value = '';
                document.getElementById('banner-location').value = '';
                document.getElementById('display-banner-title').textContent = 'Layanan Service Terbaik';
                document.getElementById('display-banner-text').textContent = 'Temukan berbagai layanan service berkualitas dengan teknisi profesional dan berpengalaman';
                document.getElementById('display-banner-img').style.display = 'none';
                document.getElementById('display-banner-img').src = '{{ asset("images/default-banner.png") }}';
                
                // Reset banner background to default image
                document.querySelector('.banner-content').style.backgroundImage = '';
                document.querySelector('.banner-content').style.background = "linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('images/default-banner.png') }}')";
                document.querySelector('.banner-content').style.backgroundSize = 'cover';
                document.querySelector('.banner-content').style.backgroundPosition = 'center';
                document.querySelector('.banner-content').style.backgroundRepeat = 'no-repeat';
                
                // Hide form if open
                $('#banner-admin-form').slideUp(300);
                $('#banner-preview-overlay').fadeOut(200);
                
                // Show success message
                Swal.fire(
                    'Terhapus!',
                    data.message,
                    'success'
                );
            } else {
                Swal.fire(
                    'Error!',
                    data.message || 'Terjadi kesalahan saat menghapus banner',
                    'error'
                );
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message && error.message !== 'Tidak ada banner aktif untuk dihapus') {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Terjadi kesalahan saat menghapus banner',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Terjadi kesalahan saat menghapus banner: ' + error.message);
                }
            }
        });
    }
</script>


@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script>
// Service Detail Modal Functions
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all "Lihat Detail" buttons
    const viewButtons = document.querySelectorAll('.service-view-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-id');
            loadServiceDetail(serviceId);
        });
    });
});

// Function to load service detail data
function loadServiceDetail(serviceId) {
    // Show loading state
    const modal = new bootstrap.Modal(document.getElementById('serviceDetailModal'));
    modal.show();
    
    // Set loading content
    document.getElementById('modal-service-title').textContent = 'Memuat...';
    document.getElementById('modal-service-description').textContent = 'Memuat data layanan...';
    
    // Fetch service data from server
    fetch(`/admin/services/${serviceId}/detail`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.service) {
            updateModalContent(data.service);
        } else {
            showError('Gagal memuat detail layanan');
        }
    })
    .catch(error => {
        console.error('Error loading service detail:', error);
        showError('Terjadi kesalahan saat memuat data');
    });
}

// Function to update modal content with service data
function updateModalContent(service) {
    // Update basic info
    document.getElementById('modal-service-title').textContent = service.title || 'Nama Layanan';
    document.getElementById('modal-service-description').textContent = service.description || 'Deskripsi layanan tidak tersedia';
    document.getElementById('modal-service-time').textContent = service.estimated_time || `${service.min_time || 0}-${service.max_time || 0} jam`;
    document.getElementById('modal-service-price').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(service.price || 0)}`;
    
    // Update full description
    const fullDescElement = document.getElementById('modal-full-description');
    if (fullDescElement) {
        fullDescElement.textContent = service.full_description || service.description || 'Deskripsi lengkap layanan tidak tersedia';
    }
    
    // Update features if exists
    const featuresElement = document.getElementById('modal-service-features');
    if (featuresElement) {
        if (service.features) {
            featuresElement.innerHTML = service.features.replace(/\n/g, '<br>');
            featuresElement.parentElement.style.display = 'block';
        } else {
            featuresElement.parentElement.style.display = 'none';
        }
    }
    
    // Update service info list
    const infoList = document.querySelector('#description ul');
    if (infoList) {
        infoList.innerHTML = `
            <li><strong>Kategori:</strong> ${service.category || 'Tidak dikategorikan'}</li>
            <li><strong>Lokasi:</strong> ${service.location || 'Lokasi tidak tersedia'}</li>
            <li><strong>Estimasi Waktu:</strong> ${service.estimated_time || `${service.min_time || 0}-${service.max_time || 0} jam`}</li>
            <li><strong>Harga:</strong> Rp ${new Intl.NumberFormat('id-ID').format(service.price || 0)}</li>
        `;
    }
    
    // Update main image
    const mainImage = document.querySelector('#serviceDetailModal .service-detail-img');
    if (mainImage && service.main_image) {
        mainImage.src = `data:image/jpeg;base64,${service.main_image}`;
    } else if (mainImage) {
        mainImage.src = '{{ asset("images/default-banner.png") }}';
    }
    
    // Update thumbnails if available
    const thumbnailContainer = document.querySelector('.service-thumbnails');
    if (thumbnailContainer && service.images && service.images.length > 0) {
        let thumbnailsHtml = '';
        service.images.forEach((image, index) => {
            thumbnailsHtml += `<img src="data:image/jpeg;base64,${image}" alt="Thumbnail ${index + 1}" class="service-thumb ${index === 0 ? 'active' : ''}" onclick="changeMainImage(this.src)">`;
        });
        thumbnailContainer.innerHTML = thumbnailsHtml;
    }
}

// Function to change main image when thumbnail is clicked
function changeMainImage(imageSrc) {
    const mainImage = document.querySelector('#serviceDetailModal .service-detail-img');
    if (mainImage) {
        mainImage.src = imageSrc;
    }
    
    // Update active thumbnail
    const thumbnails = document.querySelectorAll('.service-thumb');
    thumbnails.forEach(thumb => {
        thumb.classList.remove('active');
        if (thumb.src === imageSrc) {
            thumb.classList.add('active');
        }
    });
}

// Function to show error message
function showError(message) {
    document.getElementById('modal-service-title').textContent = 'Error';
    document.getElementById('modal-service-description').textContent = message;
}
</script>
@endsection