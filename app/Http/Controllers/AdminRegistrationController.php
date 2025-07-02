<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminRegistrationController extends Controller
{
    /**
     * Menampilkan form pendaftaran admin
     */
    public function showRegistrationForm()
    {
        // Redirect to home if user is not logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }
        
        // If user is already an admin, redirect to admin dashboard
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('info', 'Anda sudah terdaftar sebagai admin.');
        }
        
        return view('admin.register');
    }
    
    /**
     * Memproses pendaftaran admin
     */
    public function register(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:10',
        ]);
        
        // Simpan permintaan pendaftaran admin
        // Dalam implementasi nyata, ini bisa disimpan ke tabel terpisah
        // atau mengirim email ke admin yang sudah ada
        
        // Untuk demo, kita langsung update role user menjadi admin
        $user = Auth::user();
        $user->role = User::ROLE_ADMIN;
        $user->save();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Selamat! Anda sekarang adalah admin toko.');
    }
}