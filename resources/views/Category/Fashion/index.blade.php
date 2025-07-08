@extends('layouts.index')

@section('title', 'Jasa Service')

@section('content')

<!-- Custom CSS for Gadget Page -->
<style>
/* Base Styles */
.gadget-store-content {
    padding: 30px 0;
}

/* Hero Section Styles */
.hero-section {
    margin-bottom: 50px;
}

.featured-item, .secondary-item {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    height: 500px;
    transition: all 0.4s ease;
}

.secondary-item {
    height: 500px;
}

.featured-item:hover, .secondary-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.featured-item-overlay, .secondary-item-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%);
    z-index: 1;
}

.featured-img, .secondary-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease;
}

.featured-item:hover .featured-img, .secondary-item:hover .secondary-img {
    transform: scale(1.05);
}

.featured-content, .secondary-content {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 30px;
    z-index: 2;
    color: white;
}

.badge-premium {
    display: inline-block;
    background: linear-gradient(45deg, #FFD700, #FFA500);
    color: #000;
    padding: 8px 15px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 15px;
    box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    animation: pulse 2s infinite;
    display: flex;
    align-items: center;
    gap: 5px;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(255, 215, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 215, 0, 0);
    }
}

.badge-premium i {
    margin-right: 5px;
    font-size: 12px;
}

.featured-content h2 {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.featured-meta {
    display: flex;
    gap: 30px;
    margin-bottom: 20px;
}

.price-label, .timer-label, .creator-label, .meta-label {
    font-size: 14px;
    opacity: 0.8;
    margin-bottom: 5px;
}

.price-value, .meta-value {
    font-size: 18px;
    font-weight: 600;
}

.timer-countdown {
    font-size: 18px;
    font-weight: 600;
}

.service-location {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 14px;
    margin-top: 10px;
}

.service-location i {
    color: #007bff;
    font-size: 12px;
}

.time-block {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    padding: 2px 6px;
    border-radius: 4px;
    margin: 0 2px;
}

.time-block small {
    font-size: 12px;
    opacity: 0.8;
}

.featured-creator {
    display: flex;
    align-items: center;
    gap: 15px;
}

.creator-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.creator-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.creator-name {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.featured-actions {
    display: flex;
    gap: 15px;
}

.btn-primary {
    background: linear-gradient(45deg, #4a6cf7, #6a8bff);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(74, 108, 247, 0.3);
    display: inline-flex;
    align-items: center;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #3a5ce7, #5a7bef);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(74, 108, 247, 0.4);
    color: white;
    text-decoration: none;
}

.btn-favorite, .btn-share {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-favorite:hover, .btn-share:hover {
    background: white;
    color: #4a6cf7;
    transform: translateY(-3px);
}

.secondary-content h3 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.secondary-price {
    font-size: 16px;
    margin-bottom: 20px;
    opacity: 0.9;
}

.btn-outline {
    border: 2px solid white;
    background: transparent;
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-outline:hover {
    background: white;
    color: #333;
    transform: translateY(-3px);
    text-decoration: none;
}

/* Trending Section Styles */
.trending-section {
    margin-bottom: 50px;
}

.section-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
    color: #333;
}

.section-subtitle {
    color: #666;
    margin-bottom: 0;
}

.btn-view-more {
    color: #4a6cf7;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-view-more:hover {
    color: #3a5ce7;
    transform: translateX(5px);
    text-decoration: none;
}

/* Card Styles */
.gadget-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.4s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.gadget-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

/* Service Card Styles */
.service-card {
    border-left: 4px solid #4a6cf7;
    position: relative;
}

.gadget-card-img {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.gadget-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease;
}

.gadget-card:hover .gadget-card-img img {
    transform: scale(1.05);
}

.card-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #4a6cf7;
    color: white;
    padding: 5px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    z-index: 2;
}

.card-badge.hot {
    background: #ff5e5e;
}

.card-badge.sale {
    background: #34bf49;
}

.card-badge.service {
    background: linear-gradient(45deg, #4a6cf7, #6a8bff);
    padding: 5px 15px;
    font-weight: 700;
    letter-spacing: 0.5px;
    box-shadow: 0 5px 15px rgba(74, 108, 247, 0.3);
}

.card-badge.service.hot {
    background: linear-gradient(45deg, #ff5e5e, #ff8a8a);
    box-shadow: 0 5px 15px rgba(255, 94, 94, 0.3);
}

.card-badge.service.sale {
    background: linear-gradient(45deg, #34bf49, #5ad86f);
    box-shadow: 0 5px 15px rgba(52, 191, 73, 0.3);
}

.card-actions {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
    z-index: 2;
}

.gadget-card:hover .card-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.action-btn:hover {
    background: #4a6cf7;
    color: white;
    transform: scale(1.1);
}

.gadget-card-content {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.creator-tag {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.creator-img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.creator-tag span {
    font-size: 14px;
    color: #666;
}

.gadget-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
}

.gadget-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.meta-item i {
    color: #4a6cf7;
    font-size: 16px;
}

.btn-bid {
    background: linear-gradient(45deg, #4a6cf7, #6a8bff);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    text-align: center;
    margin-top: auto;
    box-shadow: 0 5px 15px rgba(74, 108, 247, 0.2);
}

.btn-bid:hover {
    background: linear-gradient(45deg, #3a5ce7, #5a7bef);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(74, 108, 247, 0.3);
    color: white;
    text-decoration: none;
}

.service-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.service-btn:after {
    content: '\f061';
    font-family: 'Font Awesome 5 Free';
    margin-left: 10px;
    font-size: 14px;
    transition: transform 0.3s ease;
}

.service-btn:hover:after {
    transform: translateX(5px);
}

.service-description {
    margin-bottom: 15px;
    color: #666;
    font-size: 14px;
    line-height: 1.6;
}

.old-price {
    text-decoration: line-through;
    color: #999;
    font-size: 0.9em;
    margin-right: 5px;
    font-weight: 400;
}

/* Responsive Adjustments */
@media (max-width: 991px) {
    .featured-item, .secondary-item {
        height: 400px;
    }
    
    .featured-content h2 {
        font-size: 26px;
    }
    
    .secondary-content h3 {
        font-size: 22px;
    }
    
    .section-title {
        font-size: 24px;
    }
}

@media (max-width: 767px) {
    .featured-item {
        margin-bottom: 20px;
    }
    
    .featured-meta {
        flex-direction: column;
        gap: 15px;
    }
    
    .gadget-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Main content section -->
<div class="gadget-store-content" id="content">
    <!-- Hero Section with Glassmorphism Effect -->
    <div class="hero-section mb-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="featured-item">
                    <div class="featured-item-overlay"></div>
                    <img src="{{ secure_asset('images/Konsultasi_gratis.png') }}" alt="featured-service" class="featured-img">
                    <div class="featured-content">
                        
                        <div class="featured-creator d-flex align-items-center mb-4">
                            <div class="creator-avatar">
                                <img src="{{ secure_asset('images/Avatar_KOnsultasi.png')}}" alt="technician">
                            </div>

                        </div>
                        <div class="featured-actions">
                            <a href="/consult" class="btn-primary">Konsultasi Gratis <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="secondary-item">
                    <div class="secondary-item-overlay"></div>
                    <img src="{{ secure_asset('images/Fashion.jpg') }}" alt="secondary-service" class="secondary-img">
                </div>
            </div>
        </div>
    </div>

    <!-- Trending Section with Modern Cards -->
    <div class="trending-section">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <div class="section-heading">
                <h3 class="section-title">Layanan Fashion</h3>
                <p class="section-subtitle">Temukan berbagai layanan fashion dan styling terbaik</p>
            </div>

        </div>

        <div class="trending-grid">
            <div class="row g-4">
                @forelse($fashionServices as $service)
                <div class="col-md-4">
                    <div class="gadget-card service-card" data-service-id="{{ $service->id }}">
                        <div class="gadget-card-img">
                            @if($service->main_image)
                                <img src="data:image/jpeg;base64,{{ base64_encode($service->main_image) }}" alt="{{ $service->title }}" class="img-fluid">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" alt="{{ $service->title }}" class="img-fluid">
                            @endif
                            
                            @if($service->badge)
                                <div class="card-badge service {{ strtolower($service->badge) }}">{{ $service->badge }}</div>
                            @else
                                <div class="card-badge service">Service</div>
                            @endif
                            
                            <div class="card-actions">
                                <button class="action-btn"><i class="far fa-heart"></i></button>
                                <button class="action-btn"><i class="fas fa-share-alt"></i></button>
                            </div>
                        </div>
                        <div class="gadget-card-content">
                            <div class="creator-tag">
                                @if($service->user && $service->user->profile_photo)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($service->user->profile_photo) }}" alt="{{ $service->user->name }}" class="creator-img">
                                @else
                                    <img src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="technician" class="creator-img">
                                @endif
                                <span>{{ $service->user ? $service->user->name : 'Teknisi Berpengalaman' }}</span>
                            </div>
                            <h4 class="gadget-title">{{ $service->title }}</h4>
                            <div class="service-description">
                                <p>{{ $service->short_description ?: $service->description }}</p>
                            </div>
                            <div class="gadget-meta">
                                <div class="meta-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <div>
                                        <span class="meta-label">Mulai Dari</span>
                                        <span class="meta-value">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <span class="meta-label">Estimasi</span>
                                        <span class="meta-value">{{ $service->min_time }}-{{ $service->max_time }} hari</span>
                                    </div>
                                </div>
                            </div>
                            @if($service->location)
                                <div class="service-location mb-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $service->location }}</span>
                                </div>
                            @endif
                            <a href="#" class="btn-bid service-btn" onclick="confirmBooking({{ $service->user ? $service->user->id : 'null' }}, '{{ $service->title }}')">Beli Sekarang</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-tshirt fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum Ada Layanan Fashion</h4>
                        <p class="text-muted">Saat ini belum ada layanan dalam kategori Fashion. Silakan cek kembali nanti.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>



<!-- Custom JS for Gadget Page -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hover effects for cards
        const cards = document.querySelectorAll('.gadget-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Favorite button functionality
        const favButtons = document.querySelectorAll('.btn-favorite, .action-btn:first-child');
        
        favButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('i');
                
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.style.color = '#ff5e5e';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.style.color = '';
                }
            });
        });
    });

    function confirmBooking(userId, serviceTitle) {
    if (confirm(`Apakah Anda yakin ingin memesan layanan "${serviceTitle}"?`)) {
        // Cari service_id berdasarkan serviceTitle dan userId
        const serviceCards = document.querySelectorAll('.service-card');
        let serviceId = null;
        let serviceCard = null;
        
        serviceCards.forEach(card => {
             const cardTitle = card.querySelector('.gadget-title')?.textContent?.trim();
             const cardUserId = card.querySelector('[onclick*="confirmBooking"]')?.getAttribute('onclick')?.match(/confirmBooking\((\d+)/)?.[1];
             
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

// Function untuk mengirim detail produk ke chat
function sendProductDetailsToChat(userId, serviceCard, orderNumber) {
    if (!serviceCard || !userId) return;
    
    // Ekstrak detail produk dari kartu
    const title = serviceCard.querySelector('.gadget-title')?.textContent?.trim() || '';
    const description = serviceCard.querySelector('.service-description p')?.textContent?.trim() || '';
    const price = serviceCard.querySelector('.meta-value')?.textContent?.trim() || '';
    const time = serviceCard.querySelectorAll('.meta-value')[1]?.textContent?.trim() || '';
    const location = serviceCard.querySelector('.service-location span')?.textContent?.trim() || '';
    const creatorName = serviceCard.querySelector('.creator-tag span')?.textContent?.trim() || '';
    
    // Format pesan detail produk
    let productMessage = `🛍️ DETAIL PESANAN #${orderNumber}\n\n`;
    productMessage += `📦 Layanan: ${title}\n`;
    productMessage += `📝 Deskripsi: ${description}\n`;
    productMessage += `💰 Harga: ${price}\n`;
    productMessage += `⏱️ Estimasi: ${time}\n`;
    if (location) {
        productMessage += `📍 Lokasi: ${location}\n`;
    }
    productMessage += `👨‍🔧 Teknisi: ${creatorName}\n\n`;
    productMessage += `✅ Pesanan Anda telah dikonfirmasi!\n`;
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

