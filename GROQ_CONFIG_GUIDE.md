# Panduan Konfigurasi Groq API

## Perubahan dari env() ke config()

Sebelumnya, aplikasi menggunakan `env()` langsung untuk mengakses API key Groq. Sekarang telah diubah menggunakan `config()` untuk praktik yang lebih baik.

## Konfigurasi di services.php

File `config/services.php` sekarang berisi konfigurasi Groq:

```php
'groq' => [
    'api_key' => env('GROQ_API_KEY'),
    'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
    'model' => env('GROQ_MODEL', 'llama3-70b-8192'),
    'timeout' => env('GROQ_TIMEOUT', 30),
],
```

## Environment Variables

Tambahkan ke file `.env`:

```env
# Groq API Configuration
GROQ_API_KEY=your_actual_api_key_here
GROQ_BASE_URL=https://api.groq.com/openai/v1
GROQ_MODEL=llama3-70b-8192
GROQ_TIMEOUT=30
```

## Penggunaan dalam Controller

Sekarang menggunakan:

```php
// Sebelum
$apiKey = env('GROQ_API_KEY');

// Sesudah
$apiKey = config('services.groq.api_key');
$model = config('services.groq.model');
$baseUrl = config('services.groq.base_url');
$timeout = config('services.groq.timeout');
```

## Keuntungan Menggunakan config()

1. **Caching**: Config dapat di-cache untuk performa yang lebih baik
2. **Centralized**: Semua konfigurasi service di satu tempat
3. **Default Values**: Dapat memberikan nilai default
4. **Type Safety**: Lebih mudah untuk validasi tipe data
5. **Testing**: Lebih mudah untuk mock dalam testing

## Cara Menggunakan Cache Config

Untuk production, jalankan:

```bash
php artisan config:cache
```

Untuk development, clear cache jika diperlukan:

```bash
php artisan config:clear
```

## Contoh Penggunaan Lanjutan

Jika ingin menambah konfigurasi lain:

```php
'groq' => [
    'api_key' => env('GROQ_API_KEY'),
    'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
    'model' => env('GROQ_MODEL', 'llama3-70b-8192'),
    'timeout' => env('GROQ_TIMEOUT', 30),
    'max_tokens' => env('GROQ_MAX_TOKENS', 1000),
    'temperature' => env('GROQ_TEMPERATURE', 0.7),
],
```

Dan gunakan dalam controller:

```php
$payload = [
    'model' => config('services.groq.model'),
    'messages' => $messages,
    'temperature' => config('services.groq.temperature'),
    'max_tokens' => config('services.groq.max_tokens'),
];
```