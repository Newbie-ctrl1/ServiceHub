@extends('Store.admin.index')

@section('content')
@php
    use App\Models\Service;
    use App\Models\Order;
    use App\Models\Transaction;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    
    // Statistik Dashboard - Hanya untuk layanan admin yang sedang login
    $currentUserId = Auth::id();
    $totalServices = Service::where('user_id', $currentUserId)->count();
    
    // Pesanan baru hanya untuk layanan milik admin yang sedang login
    $newOrders = Order::whereHas('service', function($query) use ($currentUserId) {
        $query->where('user_id', $currentUserId);
    })->where('status', 'pending')->count();
    
    // Pendapatan bulan ini - menggunakan transaksi pembayaran yang berkaitan dengan layanan admin
    // Untuk sementara, kita ambil semua transaksi payment yang completed
    // Idealnya perlu ada relasi yang lebih jelas antara transaction dan order/service
    $monthlyRevenue = Transaction::where('transaction_type', 'payment')
        ->where('status', 'completed')
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('amount');
    
    // TODO: Implementasi relasi yang lebih tepat antara Transaction dan Order/Service
    // untuk mendapatkan pendapatan yang akurat per admin
    
    // Data untuk tabel - Hanya pesanan untuk layanan milik admin yang sedang login
    $recentOrders = Order::with(['service', 'user'])
        ->whereHas('service', function($query) use ($currentUserId) {
            $query->where('user_id', $currentUserId);
        })
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    // Layanan populer hanya milik admin yang sedang login
    $popularServices = Service::where('user_id', $currentUserId)
        ->withCount('orders')
        ->orderBy('orders_count', 'desc')
        ->limit(5)
        ->get();
    
    // Data untuk chart status layanan
    $completedOrders = Order::whereHas('service', function($query) use ($currentUserId) {
        $query->where('user_id', $currentUserId);
    })->where('status', 'completed')->count();
    
    $processingOrders = Order::whereHas('service', function($query) use ($currentUserId) {
        $query->where('user_id', $currentUserId);
    })->where('status', 'processing')->count();
@endphp
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="admin-card-title">Dashboard Admin</h5>
                </div>
                <p class="text-muted">Selamat datang di panel admin ServiceHub. Kelola layanan service Anda dengan mudah.</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Statistik Layanan -->
        <!-- Total Layanan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100 border-left-primary shadow-sm">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <i class="fas fa-tools mr-1"></i>Total Layanan
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalServices ?? 0 }}</div>
                            <div class="text-muted small mt-1">
                                <i class="fas fa-check-circle text-success mr-1"></i>Layanan aktif
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-cogs text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesanan Baru -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100 border-left-success shadow-sm">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <i class="fas fa-shopping-cart mr-1"></i>Pesanan Baru
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $newOrders ?? 0 }}</div>
                            <div class="text-muted small mt-1">
                                <i class="fas fa-clock text-warning mr-1"></i>Menunggu konfirmasi
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-clipboard-check text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Status Layanan -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card service-status-card h-100">
                <div class="service-status-header">
                    <h5 class="service-status-title">
                        <i class="fas fa-chart-pie"></i>Status Layanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="serviceStatusChart"></canvas>
                    </div>
                    <div class="status-summary">
                        <div class="row">
                            <div class="col-4 status-item status-completed">
                                <div class="status-label">Selesai</div>
                                <div class="status-count" id="completedCount">{{ $completedOrders ?? 0 }}</div>
                            </div>
                            <div class="col-4 status-item status-processing">
                                <div class="status-label">Proses</div>
                                <div class="status-count" id="processingCount">{{ $processingOrders ?? 0 }}</div>
                            </div>
                            <div class="col-4 status-item status-pending">
                                <div class="status-label">Pending</div>
                                <div class="status-count" id="pendingCount">{{ $newOrders ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    
    <div class="row">
        <!-- Pesanan Terbaru -->
        <div class="col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h5 class="admin-card-title">Pesanan Terbaru</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Layanan</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->service->title ?? 'Layanan Dihapus' }}</td>
                                <td>{{ $order->user->name ?? 'User Tidak Ditemukan' }}</td>
                                <td>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-info">Dikonfirmasi</span>
                                            @break
                                        @case('in_progress')
                                            <span class="badge bg-primary">Sedang Dikerjakan</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Selesai</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">Belum ada pesanan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Bagian ulasan terbaru telah dihapus -->
    </div>
    
    <div class="row">
        <!-- Layanan Populer -->
        <div class="col-12 mb-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="admin-card-title">Layanan Populer</h5>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-outline-primary">Kelola Layanan</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Layanan</th>
                                <th>Harga</th>
                                <th>Pesanan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularServices as $service)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($service->main_image)
                                            <img src="data:image/jpeg;base64,{{ base64_encode($service->main_image) }}" alt="{{ $service->title }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default-banner.png') }}" alt="Service" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $service->title }}</h6>
                                            <small class="text-muted">{{ $service->category ?? 'Tidak dikategorikan' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $service->orders_count }} pesanan</span>
                                </td>
                                <td>
                                    @if($service->is_active ?? true)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">Belum ada layanan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk chart
    const completedCount = {{ $completedOrders ?? 0 }};
    const processingCount = {{ $processingOrders ?? 0 }};
    const pendingCount = {{ $newOrders ?? 0 }};
    
    // Konfigurasi chart
    const ctx = document.getElementById('serviceStatusChart').getContext('2d');
    const serviceStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Sedang Diproses', 'Pending'],
            datasets: [{
                data: [completedCount, processingCount, pendingCount],
                backgroundColor: [
                    '#28a745', // Success green
                    '#ffc107', // Warning yellow
                    '#17a2b8'  // Info blue
                ],
                borderColor: [
                    '#1e7e34',
                    '#e0a800',
                    '#138496'
                ],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%',
            animation: {
                animateRotate: true,
                duration: 1000
            }
        }
    });
    
    // Update chart jika tidak ada data
    if (completedCount === 0 && processingCount === 0 && pendingCount === 0) {
        serviceStatusChart.data.datasets[0].data = [1];
        serviceStatusChart.data.labels = ['Belum ada data'];
        serviceStatusChart.data.datasets[0].backgroundColor = ['#e9ecef'];
        serviceStatusChart.update();
    }
});
</script>
@endsection