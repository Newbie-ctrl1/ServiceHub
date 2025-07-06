@extends('Payment.index')

@section('title', 'Top Up - ServiceHub Payment')

@section('content')
<!-- Top Up Section -->
<div class="topup-section">
    <h2 class="section-title">Top Up</h2>
    <div class="topup-options">
        <div class="topup-option">
            <div class="topup-amount">Rp 50,000</div>
        </div>
        <div class="topup-option">
            <div class="topup-amount">Rp 100,000</div>
        </div>
        <div class="topup-option">
            <div class="topup-amount">Rp 200,000</div>
        </div>
        <div class="topup-option">
            <div class="topup-amount">Rp 500,000</div>
        </div>
        <div class="topup-option">
            <div class="topup-amount">Rp 600,000</div>
        </div>
        <div class="topup-option">
            <div class="topup-amount">Rp 700,000</div>
        </div>
       
        <div class="topup-option">
            <div class="topup-amount">Rp 800,000</div>
        </div>
       
        <div class="topup-option">
            <div class="topup-amount">Rp 900,000</div>
        </div>
       
    </div>
    <div class="topup-custom">
        <input type="text" class="topup-custom-input" placeholder="Enter custom amount" id="custom-amount">
    </div>
    
    <!-- Payment Method Selection -->
    <div class="payment-methods mt-4">
        <h3 class="h5 mb-3">Pilih Metode Pembayaran</h3>
        
        <div class="payment-method-options">
            <div class="payment-method-option mb-2">
                <input type="radio" class="btn-check" name="payment-method" id="visa" autocomplete="off" checked>
                <label class="btn btn-outline-primary w-100 text-start" for="visa">
                    <i class="fab fa-cc-visa me-2"></i> Visa ending in 4589
                </label>
            </div>
            
            <div class="payment-method-option mb-2">
                <input type="radio" class="btn-check" name="payment-method" id="dana" autocomplete="off">
                <label class="btn btn-outline-primary w-100 text-start" for="dana">
                    <i class="fas fa-credit-card me-2"></i> DANA
                </label>
            </div>
            
            <div class="payment-method-option mb-2">
                <input type="radio" class="btn-check" name="payment-method" id="ovo" autocomplete="off">
                <label class="btn btn-outline-primary w-100 text-start" for="ovo">
                    <i class="fas fa-wallet me-2"></i> OVO
                </label>
            </div>
            
            <div class="payment-method-option mb-2">
                <input type="radio" class="btn-check" name="payment-method" id="bank" autocomplete="off">
                <label class="btn btn-outline-primary w-100 text-start" for="bank">
                    <i class="fas fa-money-bill-wave me-2"></i> Bank Transfer (BCA)
                </label>
            </div>
        </div>
        
        <form action="{{ route('payment.topup.process') }}" method="POST">
            @csrf
            <input type="hidden" name="amount" id="amount-input">
            <input type="hidden" name="payment_method" id="payment-method-input" value="visa">
            <button type="submit" class="topup-button mt-3 w-100">Top Up</button>
        </form>
        
        @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('additional_css')
<style>
    .payment-method-options {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .payment-method-option .btn {
        padding: 12px 15px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .payment-method-option .btn-check:checked + .btn {
        background-color: #e3f2fd;
        border-color: #1e88e5;
        color: #1e88e5;
        font-weight: 500;
    }
    
    .payment-method-option i {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }
</style>
@endsection

@section('scripts')
<script>
    // Top Up Option Selection
    const topupOptions = document.querySelectorAll('.topup-option');
    const customInput = document.querySelector('.topup-custom-input');
    const amountInput = document.querySelector('#amount-input');
    const paymentMethodInput = document.querySelector('#payment-method-input');
    
    // Set initial value for amount input
    if (customInput.value) {
        amountInput.value = customInput.value.replace(/\D/g, '');
    }
    
    topupOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            topupOptions.forEach(opt => opt.classList.remove('selected'));
            // Add selected class to clicked option
            this.classList.add('selected');
            // Get amount text and set it to input
            const amountText = this.querySelector('.topup-amount').textContent;
            const cleanAmount = amountText.replace('Rp ', '').replace(/\./g, '').replace(',', '');
            customInput.value = amountText.replace('Rp ', '');
            amountInput.value = cleanAmount;
        });
    });
    
    // Update hidden input when custom amount changes
    customInput.addEventListener('input', function() {
        const cleanAmount = this.value.replace(/\D/g, '');
        amountInput.value = cleanAmount;
    });
    
    // Update payment method when radio buttons change
    const paymentMethods = document.querySelectorAll('input[name="payment-method"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            paymentMethodInput.value = this.id;
        });
    });
</script>
@endsection