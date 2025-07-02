@extends('Payment.index')

@section('title', 'Transaction History - ServiceHub Payment')

@section('content')
<!-- Notification History -->
<div class="notification-list">
    <h2 class="section-title">Histori Transaksi</h2>
    
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger mb-4">
        {{ session('error') }}
    </div>
    @endif
    
    @forelse($transactions as $transaction)
    <div class="notification-item">
        <div class="notification-header">
            <div class="notification-title">{{ $transaction->description }}</div>
            <div class="notification-time">{{ $transaction->created_at->diffForHumans() }}</div>
        </div>
        <div class="notification-content">{{ $transaction->transaction_type == 'topup' ? 'Top up via ' . ucfirst($transaction->payment_method) : 'Payment via ServicePay' }}</div>
        <div class="notification-amount {{ $transaction->transaction_type == 'topup' ? 'credit' : 'debit' }}">
            {{ $transaction->transaction_type == 'topup' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
        </div>
    </div>
    @empty
    <div class="text-center p-4 bg-light rounded">
        <p class="mb-0">Belum ada transaksi</p>
    </div>
    @endforelse
    
    <div class="d-flex justify-content-center mt-4">
        {{ $transactions->links() }}
    </div>
</div>

@endsection

@section('additional_css')
<style>
    .notification-item {
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .notification-title {
        font-weight: 600;
        color: #333;
    }
    
    .notification-time {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .notification-content {
        margin-bottom: 10px;
        color: #555;
    }
    
    .notification-amount {
        font-weight: 600;
        text-align: right;
    }
    
    .credit {
        color: #2e7d32;
    }
    
    .debit {
        color: #c62828;
    }
    
    .pagination {
        justify-content: center;
    }
</style>
@endsection