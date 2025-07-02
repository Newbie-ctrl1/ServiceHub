<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;
use App\Models\Service;

class HomeController extends Controller
{
    
    public function index()
    {
        // Ambil semua banner yang ada di database (prioritaskan yang aktif dan terbaru)
        $allBanners = Banner::whereNotNull('user_id')
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Jika tidak ada banner dengan user_id, ambil semua banner
        if ($allBanners->isEmpty()) {
            $allBanners = Banner::orderBy('is_active', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Tetap ambil satu banner aktif untuk kompatibilitas dengan kode yang ada
        $activeBanner = $allBanners->where('is_active', true)->first() ?: $allBanners->first();
        
        // Ambil layanan berdasarkan kategori dan lokasi yang berbeda untuk simulasi toko
        $featuredServices = Service::whereNotNull('category')
            ->whereNotNull('location')
            ->get()
            ->groupBy(function($item) {
                return $item->category . '_' . $item->location;
            })
            ->map(function($group) {
                return $group->first(); // Ambil layanan pertama dari setiap grup
            })
            ->take(6)
            ->values();
        
        // Jika tidak ada layanan dengan kategori dan lokasi, ambil layanan biasa
        if ($featuredServices->isEmpty()) {
            $featuredServices = Service::limit(6)->get();
        }
        
        return view('home', compact('activeBanner', 'allBanners', 'featuredServices'));
    }
        
    function tes(){
        $data = DB::connection()->table('role')->get();
        return $data;
    }
    
}
