{{-- Payment Panel Component --}}
<div class="payment-panel" id="paymentPanel" style="display: none;">
    <div class="payment-header">
        <h3>Kirim Pembayaran</h3>
        <button class="close-payment" id="closePayment">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="payment-recipient" id="paymentRecipient" style="display: none;">
        <div class="recipient-info">
            <i class="fas fa-user"></i>
            <span>Mengirim ke: <strong id="recipientName"></strong></span>
        </div>
    </div>
    
    <div class="payment-content">
        <div class="payment-form">
            <div class="form-group">
                <label for="paymentAmount">Jumlah Pembayaran</label>
                <div class="amount-input">
                    <span class="currency">Rp</span>
                    <input type="number" id="paymentAmount" placeholder="0" min="1000" step="1000">
                </div>
            </div>
            
            <div class="form-group">
                <label for="paymentNote">Catatan (Opsional)</label>
                <textarea id="paymentNote" placeholder="Tambahkan catatan untuk pembayaran ini..."></textarea>
            </div>
            
            <div class="payment-methods">
                <h4>Metode Pembayaran</h4>
                <div class="method-options">
                    <div class="method-option active" data-method="wallet">
                        <i class="fas fa-wallet"></i>
                        <span>ServiceHub Wallet</span>
                        <div class="method-balance">Saldo: Rp {{ number_format(auth()->user()->wallet ? auth()->user()->wallet->balance : 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="method-option" data-method="bank">
                        <i class="fas fa-university"></i>
                        <span>Transfer Bank</span>
                        <div class="method-info">BCA •••• 1234</div>
                    </div>
                    <div class="method-option" data-method="card">
                        <i class="fas fa-credit-card"></i>
                        <span>Kartu Kredit</span>
                        <div class="method-info">Visa •••• 5678</div>
                    </div>
                </div>
            </div>
            
            <div class="payment-summary">
                <div class="summary-row">
                    <span>Jumlah</span>
                    <span id="summaryAmount">Rp 0</span>
                </div>
                <div class="summary-row">
                    <span>Biaya Admin</span>
                    <span id="summaryFee">Rp 0</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="summaryTotal">Rp 0</span>
                </div>
            </div>
            
        </div>
    </div>
    
    <div class="payment-actions">
        <button class="btn-cancel" id="cancelPayment">Batal</button>
        <button class="btn-send-payment" id="sendPayment" disabled>
            <i class="fas fa-paper-plane"></i>
            Kirim Pembayaran
        </button>
    </div>
</div>

<script>
// Payment panel functionality
document.addEventListener('DOMContentLoaded', function() {
    const paymentPanel = document.getElementById('paymentPanel');
    const closePayment = document.getElementById('closePayment');
    const cancelPayment = document.getElementById('cancelPayment');
    const paymentAmount = document.getElementById('paymentAmount');
    const sendPayment = document.getElementById('sendPayment');
    const methodOptions = document.querySelectorAll('.method-option');
    
    // Close payment panel
    if (closePayment) {
        closePayment.addEventListener('click', function() {
            paymentPanel.style.display = 'none';
        });
    }
    
    if (cancelPayment) {
        cancelPayment.addEventListener('click', function() {
            paymentPanel.style.display = 'none';
        });
    }
    
    // Payment method selection
    methodOptions.forEach(option => {
        option.addEventListener('click', function() {
            methodOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            updatePaymentSummary();
        });
    });
    
    // Amount input handler
    if (paymentAmount) {
        paymentAmount.addEventListener('input', function() {
            updatePaymentSummary();
            
            const amount = parseInt(this.value) || 0;
            sendPayment.disabled = amount < 1000;
        });
    }
    
    // Update payment summary
    function updatePaymentSummary() {
        const amount = parseInt(paymentAmount.value) || 0;
        const activeMethod = document.querySelector('.method-option.active');
        const method = activeMethod ? activeMethod.dataset.method : 'wallet';
        
        let fee = 0;
        if (method === 'bank') {
            fee = Math.max(2500, amount * 0.005); // Minimum Rp 2.500 or 0.5%
        } else if (method === 'card') {
            fee = amount * 0.029; // 2.9%
        }
        
        const total = amount + fee;
        
        document.getElementById('summaryAmount').textContent = formatCurrency(amount);
        document.getElementById('summaryFee').textContent = formatCurrency(fee);
        document.getElementById('summaryTotal').textContent = formatCurrency(total);
    }
    
    // Format currency
    function formatCurrency(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }
    
    // Send payment message to chat
    function sendPaymentMessage(amount, note, method) {
        if (!window.chatApp || !window.chatApp.currentReceiverId) {
            return;
        }
        
        // Format method name
        const methodNames = {
            'wallet': 'ServiceHub Wallet',
            'bank': 'Transfer Bank',
            'card': 'Kartu Kredit'
        };
        
        // Create payment message
        let paymentMessage = `💰 PEMBAYARAN TERKIRIM\n\n`;
        paymentMessage += `💵 Jumlah: ${formatCurrency(amount)}\n`;
        paymentMessage += `💳 Metode: ${methodNames[method] || method}\n`;
        
        if (note && note.trim()) {
            paymentMessage += `📝 Catatan: ${note.trim()}\n`;
        }
        
        paymentMessage += `\n✅ Pembayaran telah berhasil diproses`;
        
        // Send payment message using direct API call
        const formData = new FormData();
        formData.append('receiver_id', window.chatApp.currentReceiverId);
        formData.append('message', paymentMessage);
        
        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add message to chat UI
                if (window.chatApp && typeof window.chatApp.addMessage === 'function') {
                    window.chatApp.addMessage(paymentMessage, true, null);
                }
            }
        })
        .catch(error => {
            console.error('Error sending payment message:', error);
        });
    }
    
    // Update wallet balance display
    function updateWalletBalance() {
        fetch('/payment/wallet-balance', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const balanceElement = document.querySelector('.method-balance');
                if (balanceElement) {
                    balanceElement.textContent = 'Saldo: Rp ' + data.balance.toLocaleString('id-ID');
                }
            }
        })
        .catch(error => {
            console.error('Error updating wallet balance:', error);
        });
    }
    
    // Send payment handler
    if (sendPayment) {
        sendPayment.addEventListener('click', function() {
            const amount = parseInt(paymentAmount.value) || 0;
            const note = document.getElementById('paymentNote').value;
            const method = document.querySelector('.method-option.active').dataset.method;
            
            if (amount >= 1000) {
                // Check if receiver is selected
                if (!window.chatApp || !window.chatApp.currentReceiverId) {
                    if (window.chatApp) {
                        window.chatApp.showNotification('Pilih kontak terlebih dahulu untuk mengirim pembayaran!');
                    }
                    return;
                }
                
                // Check wallet balance for wallet method
                if (method === 'wallet') {
                    const walletBalance = {{ auth()->user()->wallet ? auth()->user()->wallet->balance : 0 }};
                    if (amount > walletBalance) {
                        if (window.chatApp) {
                            window.chatApp.showNotification('Saldo wallet tidak mencukupi!');
                        }
                        return;
                    }
                }
                
                // Process payment
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                
                // Prepare payment data
                const paymentData = {
                    amount: amount,
                    description: note || 'Pembayaran melalui chat',
                    payment_method: method,
                    receiver_id: window.chatApp ? window.chatApp.currentReceiverId : null,
                    reference_id: 'CHAT' + Date.now() + '{{ auth()->id() }}',
                    _token: '{{ csrf_token() }}'
                };
                
                // Send payment request
                fetch('{{ route("payment.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(paymentData)
                })
                .then(response => {
                    // Check if response is ok
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response is not JSON');
                    }
                    
                    return response.json();
                })
                .then(data => {
                    // Reset button
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Pembayaran';
                    
                    if (data.success) {
                        // Close panel
                        paymentPanel.style.display = 'none';
                        
                        // Reset form
                        paymentAmount.value = '';
                        document.getElementById('paymentNote').value = '';
                        updatePaymentSummary();
                        
                        // Update wallet balance display
                        updateWalletBalance();
                        
                        // Send payment message to chat
                        sendPaymentMessage(amount, note, method);
                        
                        // Show success notification
                        if (window.chatApp) {
                            window.chatApp.showNotification('Pembayaran berhasil dikirim!');
                        }
                    } else {
                        // Show error notification
                        if (window.chatApp) {
                            window.chatApp.showNotification(data.message || 'Pembayaran gagal!');
                        }
                    }
                })
                .catch(error => {
                    console.error('Payment error:', error);
                    
                    // Reset button
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Pembayaran';
                    
                    // Show error notification
                    if (window.chatApp) {
                        window.chatApp.showNotification('Terjadi kesalahan saat memproses pembayaran!');
                    }
                });
            }
        });
    }
});
</script>