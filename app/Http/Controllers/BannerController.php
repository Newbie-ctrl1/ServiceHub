<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();
        return response()->json($banners);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:2048',
                'title' => 'required|string|max:255',
                'text' => 'required|string',
                'location' => 'nullable|string|max:255',
            ]);

            // Nonaktifkan banner lama milik user ini
            Banner::where('user_id', auth()->id())->update(['is_active' => false]);
            
            $banner = new Banner();
            $banner->user_id = auth()->id(); // Set user_id untuk isolasi data
            
            if ($request->hasFile('image')) {
                $banner->image = file_get_contents($request->file('image')->getRealPath());
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gambar banner diperlukan'
                ], 422);
            }
            
            $banner->title = $request->title;
            $banner->text = $request->text;
            $banner->location = $request->location;
            $banner->is_active = true; // Set as active by default
            
            $banner->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Banner berhasil disimpan',
                'banner' => [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'text' => $banner->text,
                    'location' => $banner->location,
                    'image' => base64_encode($banner->image)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'image' => 'nullable|image|max:2048',
                'title' => 'required|string|max:255',
                'text' => 'required|string',
                'location' => 'nullable|string|max:255',
            ]);

            // Pastikan hanya banner milik user yang sedang login yang bisa diupdate
            $banner = Banner::where('id', $id)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();
            
            if ($request->hasFile('image')) {
                $banner->image = file_get_contents($request->file('image')->getRealPath());
            }
            
            $banner->title = $request->title;
            $banner->text = $request->text;
            $banner->location = $request->location;
            
            $banner->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Banner berhasil diperbarui',
                'banner' => [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'text' => $banner->text,
                    'location' => $banner->location,
                    'image' => base64_encode($banner->image)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Pastikan hanya banner milik user yang sedang login yang bisa dihapus
            $banner = Banner::where('id', $id)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();
            $banner->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Banner berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus banner: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get the active banner.
     */
    public function getActiveBanner()
    {
        try {
            // Hanya ambil banner milik user yang sedang login
            $banner = Banner::where('user_id', auth()->id())
                          ->where('is_active', true)
                          ->latest()
                          ->first();
            
            if (!$banner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada banner aktif'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'banner' => [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'text' => $banner->text,
                    'location' => $banner->location,
                    'image' => base64_encode($banner->image)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil banner: ' . $e->getMessage()
            ], 500);
        }
    }
}
