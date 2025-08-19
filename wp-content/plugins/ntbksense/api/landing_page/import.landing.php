<?php
// File: wp-content/plugins/ntbksense/api/landing_page/import.landing.php
// =========================================================================
// API ENDPOINT UNTUK IMPOR LANDING PAGE
// =========================================================================

// Memuat lingkungan WordPress
require_once( '../../../../../wp-load.php' );

// Mengatur header untuk respons JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Memastikan metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wp_send_json_error(['message' => 'Metode request tidak valid.'], 405);
    return;
}

global $wpdb;
$table_name = $wpdb->prefix . 'acp_landings';

// Mengambil konten file JSON dari body request
$json_data = file_get_contents("php://input");
$items = json_decode($json_data, true);

// Validasi data JSON
if (json_last_error() !== JSON_ERROR_NONE || !is_array($items)) {
    wp_send_json_error(['message' => 'File JSON tidak valid atau formatnya salah.'], 400);
    return;
}

$imported_count = 0;
$errors = [];

foreach ($items as $item) {
    // 1. Siapkan data baru, hapus ID dan tanggal lama
    $new_data = $item;
    unset($new_data['id']);
    unset($new_data['created_at']);

    // 2. Validasi kolom penting
    if (empty($new_data['slug']) || empty($new_data['title'])) {
        $errors[] = "Satu item dilewati karena tidak memiliki slug atau judul.";
        continue;
    }

    // 3. Buat slug baru yang unik untuk menghindari konflik
    $original_slug = sanitize_title($new_data['slug']);
    // $new_slug = $original_slug;
    // // $new_slug = $original_slug . '-import-' . time();

    // $counter = 1;
    // while ($wpdb->get_var($wpdb->prepare("SELECT slug FROM $table_name WHERE slug = %s", $new_slug))) {
    //     // $new_slug = $original_slug . '-import-' . time() . '-' . $counter;
    //     $new_slug = $original_slug;
    //     $counter++;
    // }
    $new_data['slug'] = $original_slug;

    // 4. Set status default menjadi tidak aktif
    $new_data['status'] = '0';

    // 5. Masukkan data ke database
    $result = $wpdb->insert($table_name, $new_data);

    if ($result) {
        $imported_count++;
    } else {
        $errors[] = "Gagal mengimpor item dengan judul '{$new_data['title']}'. Error: " . $wpdb->last_error;
    }
}

// 6. Kirim respons akhir
if ($imported_count > 0) {
    $message = "{$imported_count} item berhasil diimpor.";
    if (!empty($errors)) {
        $message .= " Catatan: " . implode(', ', $errors);
    }
    wp_send_json_success(['message' => $message]);
} else {
    $error_message = 'Tidak ada item yang berhasil diimpor.';
    if (!empty($errors)) {
        $error_message .= ' Kesalahan: ' . implode(', ', $errors);
    }
    wp_send_json_error(['message' => $error_message]);
}
