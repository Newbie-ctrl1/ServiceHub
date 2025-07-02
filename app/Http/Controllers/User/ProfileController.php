<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil
     */
    public function edit()
    {
        return view('user.edit-profile');
    }

    /**
     * Memperbarui profil pengguna
     */
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Update nama
        $user->name = $request->name;

        // Jika ada file foto yang diupload
        if ($request->hasFile('profile_photo')) {
            // Simpan foto langsung ke database sebagai BLOB
            $imageFile = $request->file('profile_photo');
            $user->profile_photo = file_get_contents($imageFile->getRealPath());
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menghapus foto profil
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        // Hapus foto dari database dengan mengatur nilai menjadi null
        if ($user->profile_photo) {
            $user->profile_photo = null;
            $user->save();
        }

        return redirect()->route('profile')->with('success', 'Foto profil berhasil dihapus!');
    }
}