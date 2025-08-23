<?php
// Memuat fungsi inti WordPress
require_once( './wp-load.php' );

// URL server Laravel Anda
$laravel_url = 'http://127.0.0.1:8000/';

echo "Mencoba menghubungi server Laravel di: " . esc_url($laravel_url) . "<br><br>";

// Menggunakan fungsi WordPress untuk melakukan request
$response = wp_remote_get($laravel_url, ['timeout' => 20]);

// Memeriksa hasilnya
if ( is_wp_error( $response ) ) {
    // Jika GAGAL
    $error_message = $response->get_error_message();
    echo "<strong>Koneksi GAGAL.</strong><br>";
    echo "Pesan Error: <pre>" . esc_html($error_message) . "</pre>";
    echo "<br><strong>Solusi:</strong> Kemungkinan besar Firewall Anda memblokir koneksi. Coba matikan sementara Windows Firewall/Antivirus Anda dan jalankan tes ini lagi.";
} else {
    // Jika BERHASIL
    echo "<strong>Koneksi BERHASIL!</strong><br>";
    echo "Server Laravel merespons. Sekarang coba lagi aktivasi dari halaman plugin.";
    echo "<br><br>Respons dari server:<br>";
    echo "<pre>" . esc_html( wp_remote_retrieve_body( $response ) ) . "</pre>";
}