<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Cs\CsController;
use App\Http\Controllers\BannerController;

Route::get('/', function () {
    return view('landingpage');
})->name('landing');

// Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get ('/home' , [HomeController::class, 'index'])->name ('home');
    Route::get ('/Electronic' , [CategoryController::class, 'electronic'])->name ('Electronic');
    Route::get ('/Fashion' , [CategoryController::class, 'fashion'])->name ('Fashion');
    Route::get ('/Gadget' , [CategoryController::class, 'gadget'])->name ('Gadget');
    Route::get ('/Otomotif' , [CategoryController::class, 'otomotif'])->name ('Otomotif');
    Route::get ('/Other' , [CategoryController::class, 'other'])->name ('Other');

    Route::get ('/Notification' , [NotificationController::class, 'index'])->name ('Notification');
    
    // Notification API routes
    Route::get('/notifications/get', [NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
    Route::get('/Chat', [ChatController::class, 'index'])->name('Chat');
    Route::get('/Chat/{userId}', [ChatController::class, 'index'])->name('chat.user');
Route::get('/chat/messages/{userId}', [ChatController::class, 'getMessages'])->name('chat.messages');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->middleware('chat.rate.limit')->name('chat.send');


Route::get('/chat/unread-count', [ChatController::class, 'getUnreadCount'])->name('chat.unread');
Route::post('/chat/mark-read/{userId}', [ChatController::class, 'markAsRead'])->name('chat.markRead');
    // Profil pengguna
    Route::get('/profile', function () {
        return view('user.profile');
    })->name('profile');
    Route::get('/profile/edit', [\App\Http\Controllers\User\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/delete-photo', [\App\Http\Controllers\User\ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    
    // Admin Routes - Memerlukan autentikasi dan peran admin
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function() {
            return view('Store.admin.dashboard');
        })->name('admin.dashboard');
        
        Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('admin.orders.index');
        Route::post('/orders/{id}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        Route::get('/orders/{id}', [\App\Http\Controllers\OrderController::class, 'show'])->name('admin.orders.show');
        Route::delete('/orders/{id}/delete', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('admin.orders.delete');
        Route::post('/orders/{id}/send-notification', [\App\Http\Controllers\OrderController::class, 'sendNotification'])->name('admin.orders.sendNotification');
        
        // Resource routes for services
        Route::get('/services', [\App\Http\Controllers\AdminServiceController::class, 'index'])->name('admin.services.index');
        Route::get('/services/create', [\App\Http\Controllers\AdminServiceController::class, 'create'])->name('admin.services.create');
        Route::post('/services', [\App\Http\Controllers\AdminServiceController::class, 'store'])->name('admin.services.store');
        Route::get('/services/{id}/edit', [\App\Http\Controllers\AdminServiceController::class, 'edit'])->name('admin.services.edit');
        Route::put('/services/{id}', [\App\Http\Controllers\AdminServiceController::class, 'update'])->name('admin.services.update');
        Route::delete('/services/{id}', [\App\Http\Controllers\AdminServiceController::class, 'destroy'])->name('admin.services.destroy');
        Route::get('/services/{id}', [\App\Http\Controllers\AdminServiceController::class, 'show'])->name('admin.services.show');
        
        // Banner routes
        Route::get('/banner', [BannerController::class, 'index'])->name('admin.banner.index');
        Route::post('/banner', [BannerController::class, 'store'])->name('admin.banner.store');
        Route::put('/banner/{id}', [BannerController::class, 'update'])->name('admin.banner.update');
        Route::delete('/banner/{id}', [BannerController::class, 'destroy'])->name('admin.banner.destroy');
    });
});

// Banner API route - Dapat diakses tanpa autentikasi
Route::get('/api/banner/active', [BannerController::class, 'getActiveBanner'])->name('api.banner.active');

//register login 
Route::get('/tes', [HomeController::class, 'tes']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
// Public Store routes (untuk user biasa)
Route::get('/store/user', [StoreController::class, 'currentUserStore'])->name('store.user'); // Toko user yang sedang login
Route::get('/store/user/{user_id}', [StoreController::class, 'userStore'])->name('store.user.admin'); // Toko admin tertentu
Route::get('/store/service/{id}', [StoreController::class, 'getServiceJson']);

// Admin registration routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/register', [\App\Http\Controllers\AdminRegistrationController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/admin/register', [\App\Http\Controllers\AdminRegistrationController::class, 'register'])->name('admin.register.submit');
});

// Admin Store routes - Memerlukan autentikasi dan peran admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/store', [StoreController::class, 'index'])->name('store');
    Route::get('/store/create', [StoreController::class, 'create'])->name('store.create');
    Route::post('/store/store', [StoreController::class, 'store'])->name('store.store');
    Route::get('/store/edit/{id}', [StoreController::class, 'edit'])->name('store.edit');
    Route::put('/store/update/{id}', [StoreController::class, 'update'])->name('store.update');
    Route::delete('/store/delete/{id}', [StoreController::class, 'destroy'])->name('store.delete');
});

// Payment routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::get('/payment/topup', [PaymentController::class, 'topup'])->name('payment.topup');
    Route::post('/payment/topup/process', [PaymentController::class, 'processTopup'])->name('payment.topup.process');
    Route::get('/payment/transaction', [PaymentController::class, 'transaction'])->name('payment.transaction');
    Route::get('/payment/wallet', [PaymentController::class, 'wallet'])->name('payment.wallet');
    Route::get('/payment/wallet-balance', [PaymentController::class, 'getWalletBalance'])->name('payment.wallet.balance');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
});
// Order Management - require authentication
Route::middleware(['auth'])->group(function () {
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    
    // Order Payment
    Route::get('/payment/order/{id}', [PaymentController::class, 'orderPayment'])->name('payment.order');
    Route::post('/payment/order/process', [PaymentController::class, 'processOrderPayment'])->name('payment.order.process');
});


//chat
Route::get('/consult', [CsController::class, 'consult']);
Route::get('/CS', [CsController::class, 'CS']);

// Groq API Routes
Route::post('/api/groq/chat', [\App\Http\Controllers\Cs\GroqApiController::class, 'chat'])->name('api.groq.chat');
Route::get('/api/groq/chat', [\App\Http\Controllers\Cs\GroqApiController::class, 'testConnection'])->name('api.groq.test');





