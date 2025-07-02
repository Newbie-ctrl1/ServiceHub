<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceImage;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index()
    {
        // Untuk admin, hanya tampilkan layanan mereka sendiri
        $services = Service::where('user_id', Auth::id())->get();
        return view('Store.admin.product', compact('services'));
    }
    

    
    public function currentUserStore()
    {
        // Redirect ke toko user yang sedang login
        return redirect()->route('store.user.admin', Auth::id());
    }
    
    public function userStore($user_id)
    {
        // Menampilkan toko spesifik admin dengan banner dan produk mereka
        $admin = User::where('id', $user_id)->where('role', User::ROLE_ADMIN)->firstOrFail();
        $services = Service::where('user_id', $user_id)->get();
        
        // Hanya banner khusus admin, jika tidak ada akan menggunakan default-banner.png
        $activeBanner = Banner::where('user_id', $user_id)->where('is_active', true)->latest()->first();
        
        return view('Store.user.admin-store', compact('services', 'admin', 'activeBanner'));
    }
    
    public function getServiceJson($id)
    {
        $service = Service::with('additionalImages')->findOrFail($id);
        
        // Format data untuk respons JSON
        $responseData = [
            'id' => $service->id,
            'title' => $service->title,
            'short_description' => $service->short_description,
            'description' => $service->description,
            'price' => $service->price,
            'price_formatted' => number_format($service->price, 0, ',', '.'),
            'min_time' => $service->min_time,
            'max_time' => $service->max_time,
            'highlights' => $service->highlights,
            'badge' => $service->badge,
            'location' => $service->location,
            'main_image_base64' => base64_encode($service->main_image),
            'additional_images' => []
        ];
        
        // Tambahkan gambar tambahan jika ada
        if ($service->additionalImages->count() > 0) {
            foreach ($service->additionalImages as $image) {
                $responseData['additional_images'][] = [
                    'id' => $image->id,
                    'image_base64' => base64_encode($image->image)
                ];
            }
        }
        
        return response()->json($responseData);
    }
    
    public function edit($id)
    {
        // Pastikan admin hanya bisa mengedit layanan mereka sendiri
        $service = Service::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('Store.admin.edit-service', compact('service'));
    }
    
    public function update(Request $request, $id)
    {
        // Pastikan admin hanya bisa mengupdate layanan mereka sendiri
        $service = Service::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'min_time' => 'required|integer',
            'max_time' => 'required|integer',
            'highlights' => 'nullable|array',
            'badge' => 'nullable|string|in:Tersedia,Terbatas,Promo',
            'location' => 'nullable|string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle main image upload - simpan langsung ke database sebagai BLOB
        if ($request->hasFile('main_image')) {
            $imageFile = $request->file('main_image');
            $validated['main_image'] = file_get_contents($imageFile->getRealPath());
        }
        
        // Handle additional images upload
        if ($request->hasFile('additional_images')) {
            // Hapus gambar tambahan lama
            $service->additionalImages()->delete();
            
            // Simpan gambar tambahan baru
            foreach ($request->file('additional_images') as $image) {
                $service->additionalImages()->create([
                    'image' => file_get_contents($image->getRealPath())
                ]);
            }
        }
        
        // Update service
        $service->update($validated);
        
        return redirect()->route('store')->with('success', 'Layanan berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        // Delete main image
        if ($service->main_image) {
            Storage::disk('public')->delete($service->main_image);
        }
        
        // Delete additional images
        if ($service->additional_images) {
            foreach ($service->additional_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $service->delete();
        
        return redirect()->route('store')->with('success', 'Layanan berhasil dihapus');
    }
    
    public function create()
    {
        return view('Store.admin.create-service');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'min_time' => 'required|integer',
            'max_time' => 'required|integer',
            'highlights' => 'nullable|array',
            'badge' => 'nullable|string|in:Tersedia,Terbatas,Promo',
            'category' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle main image upload - simpan langsung ke database sebagai BLOB
        if ($request->hasFile('main_image')) {
            $imageFile = $request->file('main_image');
            $validated['main_image'] = file_get_contents($imageFile->getRealPath());
        }
        
        // Tambahkan user_id dari admin yang sedang login
        $validated['user_id'] = Auth::id();
        
        // Create service
        $service = Service::create($validated);
        
        // Handle additional images upload - simpan ke tabel service_images
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                // Simpan gambar sebagai BLOB di tabel service_images
                $service->additionalImages()->create([
                    'image' => file_get_contents($image->getRealPath())
                ]);
            }
        }
        
        return redirect()->route('store')->with('success', 'Layanan berhasil ditambahkan');
    }
}