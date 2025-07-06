# Panduan Deployment ke Railway dengan Pusher

## Konfigurasi Pusher di Railway

Untuk memastikan fitur chat real-time berfungsi dengan baik di Railway, ikuti langkah-langkah berikut:

### 1. Variabel Lingkungan Pusher

Pastikan untuk mengatur variabel lingkungan Pusher berikut di dashboard Railway:

- `PUSHER_APP_ID`: ID aplikasi Pusher Anda
- `PUSHER_APP_KEY`: Kunci API Pusher Anda
- `PUSHER_APP_SECRET`: Secret API Pusher Anda
- `PUSHER_APP_CLUSTER`: Cluster Pusher Anda (misalnya: ap1, eu, us2, dll)

### 2. Variabel Lingkungan Vite untuk Pusher

Tambahkan juga variabel lingkungan berikut untuk Vite:

- `VITE_PUSHER_APP_KEY`: Sama dengan nilai `PUSHER_APP_KEY`
- `VITE_PUSHER_APP_CLUSTER`: Sama dengan nilai `PUSHER_APP_CLUSTER`

### 3. Verifikasi Konfigurasi

Setelah deployment, Anda dapat memverifikasi konfigurasi Pusher dengan mengakses endpoint debug berikut:

```
https://[your-railway-domain]/debug-pusher
```

Endpoint ini akan menampilkan nilai konfigurasi Pusher yang digunakan aplikasi. Pastikan nilai `key` dan `cluster` tidak kosong.

### 4. Menggunakan Tool Diagnostik Pusher

Aplikasi ini dilengkapi dengan tool diagnostik Pusher yang dapat membantu mengidentifikasi dan memperbaiki masalah koneksi:

1. **Pusher Diagnostics Tool**: Alat diagnostik visual yang dapat diakses dengan menambahkan parameter `?debug=1` ke URL aplikasi. Tombol "🔍 Diagnosa Pusher" akan muncul di pojok kanan bawah halaman.

2. **Pusher Connection Helper**: Library JavaScript yang menambahkan logika fallback dan retry yang lebih kuat untuk koneksi Pusher. Library ini secara otomatis:
   - Mencoba cluster alternatif jika cluster utama gagal
   - Menyimpan cluster yang berhasil di localStorage untuk penggunaan berikutnya
   - Menangani reconnect secara otomatis jika koneksi terputus
   - Memberikan informasi diagnostik yang lebih detail

3. **Panduan Troubleshooting**: Dokumen panduan lengkap untuk mendiagnosis dan memperbaiki masalah koneksi Pusher tersedia di file `PUSHER-TROUBLESHOOTING.md`.

### 5. Troubleshooting

Jika Anda melihat pesan error seperti "Pusher key is missing in configuration" atau "API key not configured properly", periksa hal-hal berikut:

1. **Variabel Lingkungan**: Pastikan semua variabel lingkungan Pusher diatur dengan benar di dashboard Railway
2. **Cache Konfigurasi**: Jika Anda baru saja mengubah variabel lingkungan, coba restart aplikasi di Railway untuk memastikan perubahan diterapkan
3. **Nilai Variabel**: Pastikan nilai variabel lingkungan tidak mengandung spasi tambahan atau karakter khusus
4. **Cluster**: Pastikan `PUSHER_APP_CLUSTER` diatur dengan benar (misalnya: ap1, eu, us2, dll)
5. **Koneksi WebSocket**: Periksa apakah URL WebSocket yang digunakan sesuai dengan yang diharapkan

### 6. Pengujian Koneksi Pusher

Untuk menguji koneksi Pusher:

1. Buka aplikasi di browser
2. Buka konsol browser (F12 atau klik kanan > Inspect > Console)
3. Periksa pesan log yang terkait dengan Pusher
4. Gunakan tool diagnostik Pusher dengan menambahkan `?debug=1` ke URL
5. Jika ada error, catat pesan error dan periksa konfigurasi yang sesuai

### 7. Catatan Penting

- Aplikasi ini menggunakan `config()` untuk mengakses konfigurasi Pusher, bukan `env()` langsung, untuk memastikan kompatibilitas dengan cache konfigurasi
- Konfigurasi build di `nixpacks.toml` telah diperbarui untuk menghapus cache konfigurasi dengan `php artisan config:clear`
- Aplikasi dilengkapi dengan mekanisme fallback ke polling jika koneksi Pusher gagal
- Jika mengubah konfigurasi di `.env.railway`, pastikan untuk me-rebuild aplikasi di Railway