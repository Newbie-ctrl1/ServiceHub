@extends('Payment.index')

@section('title', 'ServiceHub Payment')

@section('content')
<!-- Wallet Management Section -->
<div class="topup-section">
    <h2 class="section-title">ServiceHub Payment</h2>
    
    <!-- Total Balance -->
    <div class="mb-4 p-4 bg-primary text-white rounded">
        <h3 class="h5 mb-2">Total Saldo</h3>
        <div class="d-flex align-items-center">
            <div class="h2 mb-0 fw-bold">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</div>
            <div class="ms-auto">
                <i class="fas fa-wallet fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <!-- ServicePay Payment System -->
    <div class="mb-4">
        <h3 class="h5 mb-3">ServicePay</h3>
        <div class="d-flex align-items-center p-3 bg-light rounded mb-3">
            <div class="me-3">
                <i class="fas fa-credit-card fa-2x text-primary"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold">ServicePay Balance</div>
                <div class="small text-muted">Available for all services</div>
                <div class="fw-bold text-success mt-1">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</div>
            </div>
            <div>
                <a href="{{ route('payment.topup') }}" class="btn btn-sm btn-primary">Top Up</a>
            </div>
        </div>
        

    </div>
    
    <!-- Recent Transactions -->
    <div class="mb-4">
        <h3 class="h5 mb-3">Transaksi ServicePay</h3>
        
        <div class="list-group">
            @forelse($transactions as $transaction)
            <div class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">{{ $transaction->description }}</h6>
                    <small class="{{ $transaction->transaction_type == 'topup' ? 'text-success' : 'text-danger' }}">
                        {{ $transaction->transaction_type == 'topup' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </small>
                </div>
                <p class="mb-1 small">Via {{ ucfirst($transaction->payment_method) }}</p>
                <small class="text-muted">{{ $transaction->created_at->format('d M Y, H:i') }}</small>
            </div>
            @empty
            <div class="list-group-item">
                <p class="mb-0 text-center">Belum ada transaksi</p>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-3">
            <a href="{{ route('payment.transaction') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Transaksi</a>
        </div>
    </div>
</div>
@endsection

@section('additional_css')
<style>
    .form-switch .form-check-input {
        width: 3em;
    }
    
    .form-check-input:checked {
        background-color: #1e88e5;
        border-color: #1e88e5;
    }
    
    /* Styling untuk saldo dan transaksi */
    .bg-primary {
        background: linear-gradient(135deg, #1e88e5, #0d47a1) !important;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    
    .text-success {
        color: #2e7d32 !important;
    }
    
    .text-danger {
        color: #c62828 !important;
    }
    
    .fw-bold.text-success {
        font-size: 1.1rem;
    }
</style>
@endsection