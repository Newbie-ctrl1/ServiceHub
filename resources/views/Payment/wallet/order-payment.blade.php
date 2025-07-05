@extends('Payment.index')

@section('title', 'Pembayaran Pesanan - ServiceHub Payment')

@section('content')
<!-- Order Payment Section -->
<div class="topup-section">
    <h2 class="section-title">Pembayaran Pesanan</h2>
    
    @if(session('error'))
    <div class="alert alert-danger mb-4">
        {{ session('error') }}
    </div>
    @endif
    
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif
    
    <!-- Order Details -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Pesanan #{{ $order->order_number }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Layanan</h6>
                    <p>{{ $service->title }}</p>
                    <p class="small text-muted">{{ $service->short_description }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Jadwal</h6>
                    <p>{{ $order->scheduled_at ? $order->scheduled_at->format('d M Y, H:i') : 'Belum dijadwalkan' }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Status</h6>
                    <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'processing' ? 'info' : ($order->status == 'completed' ? 'success' : 'danger')) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Catatan</h6>
                    <p>{{ $order->notes ?? 'Tidak ada catatan' }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                        <div>
                            <h6 class="fw-bold mb-0">Total Pembayaran</h6>
                        </div>
                        <div>
                            <span class="fs-4 fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Method -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Metode Pembayaran</h5>
        </div>
        <div class="card-body">
            <div class="payment-method-option mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="wallet" checked>
                    <label class="form-check-label d-flex justify-content-between align-items-center" for="wallet">
                        <div>
                            <i class="fas fa-wallet me-2 text-primary"></i> ServicePay Wallet
                        </div>
                        <div>
                            <span class="fw-bold">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</span>
                        </div>
                    </label>
                </div>
            </div>
            
            @if($wallet->balance < $order->total_price)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i> Saldo Anda tidak mencukupi untuk melakukan pembayaran ini.
                <a href="{{ route('Payment.topup') }}" class="alert-link">Top up sekarang</a>.
            </div>
            @endif
        </div>
    </div>
    
    <!-- Payment Button -->
    <form action="{{ route('Payment.order.process') }}" method="POST">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <button type="submit" class="btn btn-primary w-100 py-3" {{ $wallet->balance < $order->total_price ? 'disabled' : '' }}>
            <i class="fas fa-check-circle me-2"></i> Bayar Sekarang
        </button>
    </form>
    
    <div class="text-center mt-3">
        <a href="{{ url()->previous() }}" class="btn btn-link text-muted">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('additional_css')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: none;
    }
    
    .card-header {
        border-top-left-radius: 10px !important;
        border-top-right-radius: 10px !important;
        padding: 15px 20px;
    }
    
    .form-check-label {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .form-check-input:checked + .form-check-label {
        background-color: #e3f2fd;
    }
</style>
@endsection