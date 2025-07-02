@extends('Store.admin.index')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="admin-card-title">Kelola Pesanan</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari pesanan...">
                            <button class="btn btn-outline-primary" type="button"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                <li><a class="dropdown-item" href="#">Semua Pesanan</a></li>
                                <li><a class="dropdown-item" href="#">Menunggu Konfirmasi</a></li>
                                <li><a class="dropdown-item" href="#">Dalam Proses</a></li>
                                <li><a class="dropdown-item" href="#">Selesai</a></li>
                                <li><a class="dropdown-item" href="#">Dibatalkan</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Alert Sukses -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <!-- Tabel Pesanan -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">{{ $order->order_number }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            @if($order->user && $order->user->profile_photo)
                                                <img src="data:image/jpeg;base64,{{ base64_encode($order->user->profile_photo) }}" alt="{{ $order->user->name }}" class="rounded-circle" width="32" height="32">
                                            @else
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <span class="text-white small">{{ substr($order->user->name ?? 'U', 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $order->user->name ?? 'User' }}</div>
                                            <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $order->service->title ?? 'Layanan Tidak Ditemukan' }}</div>
                                        <small class="text-muted">{{ $order->service->category ?? 'Electronic' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($order->status) {
                                            'pending' => 'warning',
                                            'processing' => 'info', 
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                        $statusText = match($order->status) {
                                            'pending' => 'Menunggu',
                                            'processing' => 'Diproses',
                                            'completed' => 'Selesai', 
                                            'cancelled' => 'Dibatalkan',
                                            default => ucfirst($order->status)
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($order->status === 'pending')
                                                <li><a class="dropdown-item" href="#" onclick="processOrder({{ $order->id }})"><i class="fas fa-play me-2"></i>Proses</a></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="cancelOrder({{ $order->id }})"><i class="fas fa-times me-2"></i>Batalkan</a></li>
                                            @elseif($order->status === 'processing')
                                                <li><a class="dropdown-item text-success" href="#" onclick="completeOrder({{ $order->id }})"><i class="fas fa-check me-2"></i>Selesaikan</a></li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteOrder({{ $order->id }})"><i class="fas fa-trash me-2"></i>Hapus Order</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-clipboard-list text-muted mb-3" style="font-size: 32px;"></i>
                                    <p class="mb-0">Belum ada pesanan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pesanan -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailModalLabel">Detail Pesanan #ORD-001</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-2">Informasi Pelanggan</h6>
                        <p class="mb-1"><strong>Nama:</strong> Nama Pelanggan</p>
                        <p class="mb-1"><strong>Email:</strong> email@example.com</p>
                        <p class="mb-1"><strong>Telepon:</strong> 081234567890</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-2">Informasi Pesanan</h6>
                        <p class="mb-1"><strong>ID Pesanan:</strong> #ORD-001</p>
                        <p class="mb-1"><strong>Tanggal:</strong> 01 Jan 2023</p>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge bg-warning">Menunggu</span></p>
                    </div>
                </div>
                
                <h6 class="mb-3">Detail Layanan</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Layanan</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nama Layanan</td>
                                <td>Deskripsi singkat layanan</td>
                                <td>Rp 250.000</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Total</th>
                                <th>Rp 250.000</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <h6 class="mb-3 mt-4">Catatan Pelanggan</h6>
                <div class="p-3 bg-light rounded">
                    <p class="mb-0">Tidak ada catatan dari pelanggan.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success">Terima Pesanan</button>
                <button type="button" class="btn btn-danger">Tolak Pesanan</button>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        color: #555;
    }
    
    .badge {
        font-weight: 500;
        padding: 5px 10px;
    }
</style>
<script>
// Fungsi untuk memproses pesanan
function processOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin memproses pesanan ini?')) {
        updateOrderStatusWithNotification(orderId, 'processing', 'Pesanan Anda sedang diproses oleh admin toko.');
    }
}

// Fungsi untuk membatalkan pesanan
function cancelOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        updateOrderStatusWithNotification(orderId, 'cancelled', 'Pesanan Anda telah dibatalkan oleh admin toko.');
    }
}

// Fungsi untuk menyelesaikan pesanan
function completeOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin menyelesaikan pesanan ini?')) {
        updateOrderStatusWithNotification(orderId, 'completed', 'Pesanan Anda telah selesai. Terima kasih telah menggunakan layanan kami!');
    }
}

// Fungsi untuk menghapus pesanan
function deleteOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan.')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        fetch(`/admin/orders/${orderId}/delete`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 419) {
                    throw new Error('CSRF token expired. Silakan refresh halaman.');
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response bukan JSON. Mungkin terjadi redirect atau error server.');
            }
            
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Pesanan berhasil dihapus!');
                location.reload();
            } else {
                alert('Gagal menghapus pesanan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error deleting order:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    }
}

// Fungsi untuk mengirim notifikasi ke chat pelanggan
function sendOrderNotificationToChat(orderId, message) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token tidak ditemukan untuk notifikasi');
        return;
    }

    fetch(`/admin/orders/${orderId}/send-notification`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            message: message
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            console.warn('Notifikasi endpoint tidak mengembalikan JSON');
            return { success: true };
        }
    })
    .then(data => {
        if (data.success) {
            console.log('Notifikasi berhasil dikirim ke chat pelanggan');
        } else {
            console.error('Gagal mengirim notifikasi:', data.message);
        }
    })
    .catch(error => {
        console.error('Error sending notification:', error);
    });
}

// Fungsi untuk update status dengan notifikasi
function updateOrderStatusWithNotification(orderId, newStatus, notificationMessage) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
        return;
    }

    fetch(`/admin/orders/${orderId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 419) {
                throw new Error('CSRF token expired. Silakan refresh halaman.');
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response bukan JSON. Mungkin terjadi redirect atau error server.');
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Kirim notifikasi ke chat pelanggan
            sendOrderNotificationToChat(orderId, notificationMessage);
            
            alert('Status pesanan berhasil diupdate!');
            
            // Reload halaman untuk menampilkan perubahan
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert('Gagal mengupdate status: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error updating order status:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
}



// Helper function untuk mendapatkan teks status
function getStatusText(status) {
    const statusMap = {
        'pending': 'Menunggu',
        'processing': 'Diproses',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan'
    };
    return statusMap[status] || status;
}
</script>

@endsection