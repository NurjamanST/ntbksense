<?php

/**
 * =========================================================================
 * FILE: api/page-generator/bulk-delete-pages.php
 * FUNGSI: Menerima request untuk menghapus 3 halaman penting.
 * =========================================================================
 */

@set_time_limit(300);
header("Content-Type: application/json; charset=UTF-8");

// --- Memuat Environment WordPress ---
$wp_load_path = dirname(__FILE__, 6) . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'FATAL ERROR: Gagal memuat environment WordPress.']);
    exit;
}
require_once($wp_load_path);

// --- Keamanan Utama ---
if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ntbksense_page_gen_nonce')) {
    wp_send_json_error(['message' => 'Validasi keamanan gagal!'], 403);
}
if (!current_user_can('delete_pages')) {
    wp_send_json_error(['message' => 'Anda tidak memiliki izin untuk menghapus halaman.'], 403);
}

// --- Proses Utama ---
$pages_to_delete = [
    'Privacy Policy',
    'Disclaimer',
    'Terms & Conditions'
];

$deleted_count = 0;

foreach ($pages_to_delete as $page_title) {
    // Cari halaman berdasarkan judul
    $page_object = get_page_by_title($page_title, OBJECT, 'page');

    // Jika halaman ditemukan, hapus
    if ($page_object) {
        $result = wp_delete_post($page_object->ID, true); // true = hapus permanen
        if ($result) {
            $deleted_count++;
        }
    }
}

if ($deleted_count > 0) {
    wp_send_json_success(['message' => "{$deleted_count} halaman berhasil dihapus."]);
} else {
    wp_send_json_error(['message' => 'Tidak ada halaman yang dihapus (kemungkinan sudah tidak ada).'], 404);
}
