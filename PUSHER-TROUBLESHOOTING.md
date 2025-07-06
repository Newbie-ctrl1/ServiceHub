# Panduan Troubleshooting Koneksi Pusher

## Masalah: URL WebSocket Tidak Valid

Jika Anda melihat pesan error seperti berikut di konsol browser:

```
Pusher connection state: connected
Expected WebSocket URL: wss://ws-ap1.pusher.com
Actual WebSocket URL: tidak tersedia (koneksi belum dibuat)
```

Ini menunjukkan bahwa meskipun Pusher melaporkan status "connected", koneksi WebSocket sebenarnya belum dibuat. Berikut adalah langkah-langkah untuk mendiagnosis dan memperbaiki masalah ini.

## Langkah 1: Periksa Konfigurasi Pusher di Railway

Pastikan variabel lingkungan berikut diatur dengan benar di dashboard Railway:

- `PUSHER_APP_ID`: ID aplikasi Pusher Anda
- `PUSHER_APP_KEY`: Kunci API Pusher Anda
- `PUSHER_APP_SECRET`: Secret API Pusher Anda
- `PUSHER_APP_CLUSTER`: Cluster Pusher Anda (misalnya: ap1, eu, us2, dll)

## Langkah 2: Gunakan Tool Diagnostik Pusher

Aplikasi ini dilengkapi dengan tool diagnostik Pusher yang dapat membantu mengidentifikasi masalah koneksi. Untuk mengakses tool ini:

1. Tambahkan parameter `?debug=1` ke URL aplikasi, misalnya: `https://your-app.railway.app/chat?debug=1`
2. Klik tombol "🔍 Diagnosa Pusher" yang muncul di pojok kanan bawah halaman
3. Tool akan menjalankan serangkaian tes dan menampilkan hasil diagnostik

## Langkah 3: Periksa Konsol Browser

Buka konsol browser (F12 atau klik kanan > Inspect > Console) dan cari pesan error terkait Pusher. Beberapa pesan error umum dan solusinya:

### "Pusher key is missing in configuration"

- **Penyebab**: Variabel lingkungan `PUSHER_APP_KEY` tidak diatur atau tidak diambil dengan benar
- **Solusi**: Pastikan variabel lingkungan diatur di Railway dan aplikasi di-restart

### "Pusher cluster is missing in configuration"

- **Penyebab**: Variabel lingkungan `PUSHER_APP_CLUSTER` tidak diatur atau tidak diambil dengan benar
- **Solusi**: Pastikan variabel lingkungan diatur di Railway dan aplikasi di-restart

### "Invalid Pusher cluster format"

- **Penyebab**: Format cluster tidak valid (harus seperti ap1, eu, us2, dll)
- **Solusi**: Periksa dan perbaiki nilai `PUSHER_APP_CLUSTER` di Railway

### "WebSocket URL format mismatch"

- **Penyebab**: URL WebSocket yang digunakan tidak sesuai dengan yang diharapkan
- **Solusi**: Periksa nilai cluster dan pastikan tidak ada proxy atau firewall yang memblokir koneksi WebSocket

## Langkah 4: Coba Cluster Alternatif

Jika cluster yang dikonfigurasi tidak berfungsi, aplikasi akan mencoba cluster alternatif secara otomatis. Anda juga dapat mencoba cluster alternatif secara manual:

1. Buka konsol browser
2. Jalankan perintah berikut untuk mencoba cluster alternatif:

```javascript
localStorage.setItem('pusher_successful_cluster', 'ap1'); // Ganti 'ap1' dengan cluster yang ingin dicoba
location.reload(); // Muat ulang halaman
```

## Langkah 5: Periksa Cache Konfigurasi

Jika Anda baru saja mengubah konfigurasi Pusher, cache konfigurasi mungkin perlu dihapus:

1. Jalankan perintah berikut di terminal Railway:

```bash
php artisan config:clear
```

2. Restart aplikasi di Railway

## Langkah 6: Periksa Koneksi Jaringan

Pastikan tidak ada masalah jaringan yang memblokir koneksi WebSocket:

1. Periksa apakah domain `*.pusher.com` dapat diakses dari jaringan Anda
2. Pastikan port 443 (WSS) tidak diblokir oleh firewall
3. Jika menggunakan VPN, coba matikan dan tes kembali

## Langkah 7: Verifikasi dengan Endpoint Debug

Aplikasi ini memiliki endpoint debug untuk memverifikasi konfigurasi Pusher:

```
https://[your-railway-domain]/debug-pusher
```

Endpoint ini akan menampilkan nilai konfigurasi Pusher yang digunakan aplikasi. Pastikan nilai `key` dan `cluster` tidak kosong.

## Langkah 8: Periksa Log Aplikasi

Periksa log aplikasi di Railway untuk informasi lebih lanjut tentang masalah koneksi Pusher.

## Langkah 9: Fallback ke Polling

Jika koneksi Pusher tetap tidak berfungsi, aplikasi akan secara otomatis beralih ke mode polling. Anda dapat memverifikasi ini dengan melihat pesan "Koneksi real-time gagal. Menggunakan mode polling." di aplikasi.

## Langkah 10: Hubungi Support

Jika semua langkah di atas tidak berhasil, hubungi support Pusher atau Railway untuk bantuan lebih lanjut.

## Referensi

- [Dokumentasi Pusher](https://pusher.com/docs)
- [Troubleshooting Pusher](https://pusher.com/docs/channels/library_auth_reference/pusher-websockets-protocol/#error-codes)
- [Dokumentasi Laravel Broadcasting](https://laravel.com/docs/broadcasting)