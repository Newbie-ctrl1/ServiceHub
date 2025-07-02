<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            // Buat wallet baru jika belum ada
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'is_active' => true
            ]);
        }
        
        return view('payment.index', compact('wallet'));
    }

    public function topup()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        return view('payment.wallet.topup', compact('wallet'));
    }

    public function processTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|string'
        ]);
        
        $user = Auth::user();
        $wallet = $user->wallet;
        
        try {
            DB::beginTransaction();
            
            // Tambahkan saldo ke wallet
            $wallet->balance += $request->amount;
            $wallet->save();
            
            // Catat transaksi
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'transaction_type' => 'topup',
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'description' => 'Top up via ' . $request->payment_method,
                'status' => 'completed',
                'reference_id' => 'TOP' . time() . $user->id
            ]);
            
            // Create notification for wallet transaction
            NotificationService::createWalletTransactionNotification($transaction);
            
            DB::commit();
            
            return redirect()->route('payment.wallet')->with('success', 'Top up berhasil! Saldo telah ditambahkan ke akun Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function wallet()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->take(5)->get();
        
        return view('payment.wallet.wallet', compact('wallet', 'transactions'));
    }

    public function transaction()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->paginate(10);
        
        return view('payment.wallet.transaction', compact('transactions'));
    }
    
    /**
     * Get current wallet balance for AJAX requests
     */
    public function getWalletBalance()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'is_active' => true
            ]);
        }
        
        return response()->json([
            'success' => true,
            'balance' => $wallet->balance
        ]);
    }
    
    public function processPayment(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1000',
                'description' => 'required|string',
                'reference_id' => 'nullable|string',
                'payment_method' => 'required|string',
                'receiver_id' => 'nullable|exists:users,id'
            ]);
        } catch (ValidationException $e) {
             if ($request->expectsJson() || $request->ajax()) {
                 $errors = collect($e->errors())->flatten()->implode(', ');
                 return response()->json([
                     'success' => false,
                     'message' => 'Data tidak valid: ' . $errors
                 ], 422);
             }
             throw $e;
         }
        
        $user = Auth::user();
        $wallet = $user->wallet;
        
        // Create wallet if doesn't exist
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'is_active' => true
            ]);
        }
        
        // Check if payment method is wallet and balance is sufficient
        if ($request->payment_method === 'wallet' && $wallet->balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi untuk melakukan pembayaran ini.'
            ]);
        }
        
        try {
            DB::beginTransaction();
            
            // Only deduct from wallet if payment method is wallet
            if ($request->payment_method === 'wallet') {
                $wallet->balance -= $request->amount;
                $wallet->save();
            }
            
            // Record transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'receiver_id' => $request->receiver_id,
                'wallet_id' => $wallet->id,
                'transaction_type' => 'payment',
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'description' => $request->description,
                'status' => 'completed',
                'reference_id' => $request->reference_id ?? 'PAY' . time() . $user->id
            ]);
            
            // Create notification for wallet transaction
            NotificationService::createWalletTransactionNotification($transaction);
            
            DB::commit();
            
            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dilakukan!',
                    'new_balance' => $wallet->fresh()->balance
                ]);
            }
            
            return redirect()->back()->with('success', 'Pembayaran berhasil dilakukan!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Process payment for an order
     */
    public function processOrderPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);
        
        $user = Auth::user();
        $wallet = $user->wallet;
        $order = Order::findOrFail($request->order_id);
        
        // Verifikasi bahwa order milik user yang sedang login
        if ($order->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }
        
        // Cek status order
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat dibayar karena status bukan pending.');
        }
        
        // Cek saldo cukup
        if ($wallet->balance < $order->total_price) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk melakukan pembayaran ini. Silakan top up terlebih dahulu.');
        }
        
        try {
            DB::beginTransaction();
            
            // Kurangi saldo wallet
            $wallet->balance -= $order->total_price;
            $wallet->save();
            
            // Update status order menjadi processing
            $order->status = 'processing';
            $order->save();
            
            // Catat transaksi
            Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'transaction_type' => 'payment',
                'amount' => $order->total_price,
                'payment_method' => 'wallet',
                'description' => 'Pembayaran untuk pesanan #' . $order->order_number,
                'status' => 'completed',
                'reference_id' => 'ORD-' . $order->id
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Display payment page for an order
     */
    public function orderPayment($orderId)
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        $order = Order::findOrFail($orderId);
        
        // Verifikasi bahwa order milik user yang sedang login
        if ($order->user_id !== $user->id) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }
        
        // Ambil data service
        $service = $order->service;
        
        return view('payment.wallet.order-payment', compact('wallet', 'order', 'service'));
    }
}
