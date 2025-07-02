@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/notification-icons.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="notification-container">
    <div class="notification-header">
        <h2><i class="fa fa-bell"></i> Notifikasi</h2>
        <div class="notification-actions">
            <button class="notification-action-btn" id="refresh-btn" title="Refresh"><i class="fa fa-sync-alt"></i></button>
            <button class="notification-action-btn" id="mark-all-read" title="Mark all as read"><i class="fa fa-check-double"></i></button>
            <button class="notification-action-btn" title="Settings"><i class="fa fa-cog"></i></button>
        </div>
    </div>
    
    <div class="notification-filters">
        <button class="filter-btn active" data-type="all"><i class="fa fa-inbox"></i> Semua</button>
        <button class="filter-btn" data-type="order"><i class="fa fa-shopping-cart"></i> Pesanan</button>
        <button class="filter-btn" data-type="wallet"><i class="fa fa-wallet"></i> Wallet</button>
        <button class="filter-btn" data-type="service"><i class="fa fa-tools"></i> Layanan</button>
        <button class="filter-btn" data-type="payment"><i class="fa fa-credit-card"></i> Pembayaran</button>
    </div>
    
    <ul class="notification-list" id="notification-list">
        <!-- Notifications will be loaded dynamically -->
    </ul>
    
    <!-- Empty state (hidden by default, show when no notifications) -->
    <div class="empty-notifications" style="display: none;">
        <i class="fa fa-bell-slash"></i>
        <h3>Tidak Ada Notifikasi</h3>
        <p>Anda belum memiliki notifikasi saat ini. Notifikasi baru akan muncul di sini.</p>
    </div>
</div>

<script>
    // Notification management JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentFilter = 'all';
        
        // Load notifications on page load
        loadNotifications();
        
        // Refresh button
        document.getElementById('refresh-btn').addEventListener('click', function() {
            this.querySelector('i').classList.add('fa-spin');
            loadNotifications();
            setTimeout(() => {
                this.querySelector('i').classList.remove('fa-spin');
            }, 1000);
        });
        
        // Mark all as read
        document.getElementById('mark-all-read').addEventListener('click', function() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error:', error));
        });
        
        // Filter buttons
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-type');
                loadNotifications();
            });
        });
        
        // Load notifications function
        function loadNotifications() {
            const url = currentFilter === 'all' ? '/notifications/get' : `/notifications/get?type=${currentFilter}`;
            
            fetch(url)
            .then(response => response.json())
            .then(data => {
                renderNotifications(data.notifications);
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
        }
        
        // Render notifications
        function renderNotifications(notifications) {
            const notificationList = document.getElementById('notification-list');
            const emptyState = document.querySelector('.empty-notifications');
            
            if (notifications.length === 0) {
                notificationList.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }
            
            emptyState.style.display = 'none';
            
            notificationList.innerHTML = notifications.map(notification => {
                const isUnread = !notification.is_read;
                const iconClass = getNotificationIcon(notification.type);
                const colorClass = getNotificationColor(notification.type);
                
                return `
                    <li class="notification-item ${isUnread ? 'unread' : ''}" data-id="${notification.id}">
                        <div class="notification-icon ${colorClass}">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">${notification.title}</h3>
                            <p class="notification-message">${notification.message}</p>
                            <div class="notification-time">
                                <i class="fa fa-clock"></i> ${notification.relative_time}
                            </div>
                        </div>
                        <div class="notification-actions">
                            ${isUnread ? '<button class="notification-action-btn mark-read" title="Mark as read"><i class="fa fa-check"></i></button>' : ''}
                            <button class="notification-action-btn delete" title="Delete"><i class="fa fa-trash"></i></button>
                        </div>
                    </li>
                `;
            }).join('');
            
            // Attach event listeners to new elements
            attachEventListeners();
        }
        
        // Attach event listeners
        function attachEventListeners() {
            // Mark as read functionality
            document.querySelectorAll('.mark-read').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const notificationItem = this.closest('.notification-item');
                    const notificationId = notificationItem.getAttribute('data-id');
                    
                    fetch(`/notifications/${notificationId}/mark-read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notificationItem.classList.remove('unread');
                            this.remove();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
            
            // Delete notification functionality
            document.querySelectorAll('.delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const notificationItem = this.closest('.notification-item');
                    const notificationId = notificationItem.getAttribute('data-id');
                    
                    fetch(`/notifications/${notificationId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notificationItem.style.height = notificationItem.offsetHeight + 'px';
                            notificationItem.classList.add('removing');
                            setTimeout(() => {
                                notificationItem.style.height = '0';
                                notificationItem.style.padding = '0';
                                notificationItem.style.margin = '0';
                                notificationItem.style.opacity = '0';
                                setTimeout(() => {
                                    notificationItem.remove();
                                    
                                    // Check if list is empty
                                    const notificationList = document.querySelector('.notification-list');
                                    if (notificationList.children.length === 0) {
                                        document.querySelector('.empty-notifications').style.display = 'block';
                                    }
                                }, 300);
                            }, 10);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        }
        
        // Get notification icon based on type
        function getNotificationIcon(type) {
            const icons = {
                'order_new': 'fa fa-shopping-cart',
                'order_status': 'fa fa-truck',
                'payment_success': 'fa fa-credit-card',
                'payment_failed': 'fa fa-exclamation-triangle',
                'wallet_topup': 'fa fa-wallet',
                'wallet_transfer_in': 'fa fa-arrow-down',
                'wallet_transfer_out': 'fa fa-arrow-up',
                'wallet_failed': 'fa fa-times-circle',
                'service_created': 'fa fa-plus-circle',
                'service_updated': 'fa fa-edit',
                'service_deleted': 'fa fa-trash-alt'
            };
            return icons[type] || 'fa fa-bell';
        }
        
        // Get notification color based on type
        function getNotificationColor(type) {
            const colors = {
                'order_new': 'info',
                'order_status': 'warning',
                'payment_success': 'success',
                'payment_failed': 'danger',
                'wallet_topup': 'success',
                'wallet_transfer_in': 'success',
                'wallet_transfer_out': 'info',
                'wallet_failed': 'danger',
                'service_created': 'success',
                'service_updated': 'info',
                'service_deleted': 'danger'
            };
            return colors[type] || 'info';
        }
    });
</script>
@endsection