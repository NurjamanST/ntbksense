<?php
// File: wp-content/plugins/ntbksense/api/landing_page/duplicate.landing.php
// =========================================================================
// API ENDPOINT UNTUK DUPLIKASI LANDING PAGE (DENGAN SLUG KUSTOM)
// =========================================================================

// Memuat lingkungan WordPress untuk mengakses fungsi dan database
require_once( '../../../../../wp-load.php' );

// Mengatur header untuk memastikan respons dalam format JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

global $wpdb;
$table_name = $wpdb->prefix . 'acp_landings';

// Mengambil data ID yang dikirim dari sisi klien (JavaScript)
$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (empty($data['ids']) || !is_array($data['ids'])) {
    wp_send_json_error(['message' => 'Tidak ada ID yang valid untuk diduplikasi.']);
    return;
}

$ids_to_duplicate = array_map('intval', $data['ids']);
// Ambil slug kustom jika ada, jika tidak, gunakan array kosong
$custom_slugs = isset($data['slugs']) && is_array($data['slugs']) ? $data['slugs'] : [];
$duplicated_count = 0;
$errors = [];

foreach ($ids_to_duplicate as $index => $id) {
    // 1. Ambil data asli dari database berdasarkan ID
    $original = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);

    if (!$original) {
        $errors[] = "ID landing page {$id} tidak ditemukan.";
        continue;
    }

    // 2. Siapkan data baru untuk duplikasi
    $new_data = $original;
    unset($new_data['id']);         // Hapus ID lama agar database membuat ID baru
    unset($new_data['created_at']); // Biarkan database mengatur timestamp baru

    // 3. Buat slug dan judul yang unik
    $original_slug = $original['slug'];
    $new_title = '[DUPLIKAT] ' . $original['title'];
    
    // --- LOGIKA BARU: Gunakan slug kustom atau generate otomatis ---
    if (isset($custom_slugs[$index]) && !empty($custom_slugs[$index])) {
        // Jika ada slug kustom (dari aksi tunggal), gunakan itu
        $new_slug = sanitize_title($custom_slugs[$index]);
    } else {
        // Jika tidak ada (aksi massal), generate otomatis seperti sebelumnya
        $new_slug = $original_slug . '-copy-' . time() . rand(10, 99);
    }
    // --- AKHIR LOGIKA BARU ---

    // Pastikan slug yang baru benar-benar unik untuk menghindari error database
    $counter = 1;
    $final_slug = $new_slug;
    while ($wpdb->get_var($wpdb->prepare("SELECT slug FROM $table_name WHERE slug = %s", $final_slug))) {
        $final_slug = $new_slug . '-' . $counter;
        $counter++;
    }

    $new_data['slug'] = $final_slug;
    $new_data['title'] = $new_title;
    $new_data['status'] = '0'; // Set duplikat sebagai tidak aktif secara default

    // 4. Masukkan data baru ke dalam database
    $result = $wpdb->insert($table_name, $new_data);

    if ($result) {
        $duplicated_count++;
    } else {
        $errors[] = "Gagal menduplikasi landing page dengan ID {$id}. Error: " . $wpdb->last_error;
    }
}

// 5. Kirim respons kembali ke klien
if ($duplicated_count > 0) {
    $message = "{$duplicated_count} item berhasil diduplikasi.";
    if (!empty($errors)) {
        $message .= " Namun, terjadi beberapa kesalahan: " . implode(', ', $errors);
    }
    wp_send_json_success(['message' => $message]);
} else {
    wp_send_json_error(['message' => 'Tidak ada item yang berhasil diduplikasi. Kesalahan: ' . implode(', ', $errors)]);
}
