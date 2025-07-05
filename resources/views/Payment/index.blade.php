<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ServiceHub Payment')</title>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style1.css') }}">
    @yield('additional_css')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .payment-container {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .sidebar {
            width: 320px;
            background-color: #ffffff;
            border-right: 1px solid #e1e4e8;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 5;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e1e4e8;
            text-align: center;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .sidebar-subtitle {
            font-size: 13px;
            color: #666;
        }

        .sidebar-menu {
            padding: 15px;
        }

        .menu-item {
            padding: 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .menu-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .menu-item.active {
            background-color: #e5f1ff;
            border-left: 4px solid #1e88e5;
        }

        .menu-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #e9f5ff;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #1e88e5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border: 2px solid #fff;
            transition: all 0.3s ease;
        }

        .menu-item:hover .menu-icon {
            transform: scale(1.05);
        }

        .menu-text {
            flex: 1;
        }

        .menu-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .menu-description {
            font-size: 13px;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .balance-card {
            background: linear-gradient(135deg, #1e88e5, #1565c0);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(30, 136, 229, 0.2);
        }

        .balance-title {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        .balance-amount {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .balance-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .card-number {
            font-size: 14px;
            opacity: 0.8;
        }

        .topup-section {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .topup-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .topup-option {
            border: 2px solid #e1e4e8;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .topup-option:hover {
            border-color: #1e88e5;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .topup-option.selected {
            border-color: #1e88e5;
            background-color: #e5f1ff;
        }

        .topup-amount {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .topup-custom {
            margin-top: 20px;
        }

        .topup-custom-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e4e8;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .topup-custom-input:focus {
            border-color: #1e88e5;
            outline: none;
        }

        .topup-button {
            background-color: #1e88e5;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .topup-button:hover {
            background-color: #1565c0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 136, 229, 0.3);
        }

        .notification-list {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .notification-item {
            padding: 15px;
            border-radius: 10px;
            background-color: #f8f9fa;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .notification-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .notification-time {
            font-size: 12px;
            color: #999;
        }

        .notification-content {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }

        .notification-amount {
            font-weight: 600;
            color: #1e88e5;
            margin-top: 5px;
        }

        .notification-amount.debit {
            color: #e53935;
        }

        .notification-amount.credit {
            color: #43a047;
        }

        @media (max-width: 992px) {
            .payment-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e1e4e8;
            }

            .topup-options {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .topup-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">ServiceHub Wallet</div>
                <div class="sidebar-subtitle">Manage your payments easily</div>
            </div>
            <div class="sidebar-menu">
                <a href="{{ route('Payment.topup') }}" class="menu-item {{ request()->routeIs('Payment.topup') ? 'active' : '' }}">
                    <div class="menu-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="menu-text">
                        <div class="menu-title">Top Up</div>
                        <div class="menu-description">Add funds to your wallet</div>
                    </div>
                </a>
                <a href="{{ route('Payment.transaction') }}" class="menu-item {{ request()->routeIs('Payment.transaction') ? 'active' : '' }}">
                    <div class="menu-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="menu-text">
                        <div class="menu-title">Transactions</div>
                        <div class="menu-description">View your transaction history</div>
                    </div>
                </a>
                <a href="{{ route('Payment.wallet') }}" class="menu-item {{ request()->routeIs('Payment.wallet') ? 'active' : '' }}">
                    <div class="menu-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="menu-text">
                        <div class="menu-title">Wallet ServiceHub</div>
                        <div class="menu-description">Manage your wallet options</div>
                    </div>
                </a>
                <!-- Settings menu item removed -->
                <a href="{{ route('home') }}" class="logout-btn" style="display: flex; align-items: center; color: #6c757d; text-decoration: none; padding: 10px 15px; border-radius: 8px; transition: all 0.3s ease; font-weight: 500; margin-top: 15px;" onmouseover="this.style.backgroundColor='rgba(220, 53, 69, 0.1)';this.style.color='#dc3545';this.querySelector('i').style.transform='translateX(-3px)';" onmouseout="this.style.backgroundColor='';this.style.color='#6c757d';this.querySelector('i').style.transform='';">
                    <i class="fas fa-home" style="margin-right: 10px; font-size: 16px; transition: all 0.3s ease;"></i>
                    <span>Kembali ke Home</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Balance Card -->
            <div class="balance-card">
                <div class="balance-title">Your Balance</div>
                <div class="balance-amount">Rp {{ isset($wallet) ? number_format($wallet->balance, 0, ',', '.') : '0' }}</div>
                <div class="balance-card-footer">
                    <div class="card-logo"><i class="fas fa-wallet fa-2x"></i> ServicePay</div>
                </div>
            </div>

            <!-- Content Section -->
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Menu Item Selection
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                menuItems.forEach(mi => mi.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
    @yield('scripts')

</body>
</html>