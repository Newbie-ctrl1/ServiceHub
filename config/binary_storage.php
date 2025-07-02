<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Binary Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi ini digunakan untuk menangani penyimpanan data binary (BLOB)
    | di database MySQL. Pastikan untuk mengatur batas ukuran maksimum file
    | yang dapat diunggah untuk mencegah masalah performa database.
    |
    */
    
    // Batas ukuran maksimum file (dalam bytes)
    'max_file_size' => 2048 * 1024, // 2MB
    
    // Tipe file yang diizinkan untuk diunggah
    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ],
    
    // Apakah menggunakan encoding base64 untuk penyimpanan
    // Jika true, data akan dienkode ke base64 sebelum disimpan ke database
    // Ini akan meningkatkan ukuran data sekitar 33% tetapi lebih kompatibel dengan charset database
    'use_base64_encoding' => false,
    
    // Apakah menggunakan kompresi untuk mengurangi ukuran data
    // Jika true, data akan dikompresi sebelum disimpan ke database
    'use_compression' => false,
    
    // Tingkat kompresi (0-9, di mana 9 adalah kompresi maksimum)
    'compression_level' => 6,
];