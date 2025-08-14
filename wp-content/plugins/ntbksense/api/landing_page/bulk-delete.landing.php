<?php
// Set header sebagai JSON di paling awal
header("Content-Type: application/json; charset=UTF-8");

// Memuat environment WordPress dengan aman
$wp_load_path = dirname(__FILE__, 6) . '/wp-load.php'; // Sesuaikan angka 6 jika perlu
if (!file_exists($wp_load_path)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'data' => ['message' => 'FATAL ERROR: Gagal memuat environment WordPress.']]);
    exit;
}
require_once($wp_load_path);

// Memuat file Class Template
$template_class_path = dirname(__FILE__) . '/template.landing.php';
if (!file_exists($template_class_path)) {
    wp_send_json_error(['message' => 'FATAL ERROR: File class template.landing.php tidak ditemukan.']);
    exit;
}
require_once($template_class_path);

// Ambil data JSON dari body request
$data = json_decode(file_get_contents('php://input'), true);

// Validasi array IDs
$ids_to_delete = isset($data['ids']) && is_array($data['ids']) ? $data['ids'] : [];
if (empty($ids_to_delete)) {
    wp_send_json_error(['message' => 'Tidak ada ID yang dipilih untuk dihapus.']);
    exit;
}

global $wpdb;
$template = new Template($wpdb);
$success_count = 0;
$error_count = 0;

// Loop melalui setiap ID dan hapus
foreach ($ids_to_delete as $id) {
    // Pastikan ID adalah integer yang valid
    $template->id = (int)$id;
    if ($template->id > 0) {
        if ($template->delete()) {
            $success_count++;
        } else {
            $error_count++;
        }
    } else {
        $error_count++;
    }
}

// Kirim respon akhir
if ($error_count > 0) {
    wp_send_json_error(['message' => "Proses selesai. Berhasil dihapus: {$success_count}, Gagal: {$error_count}."]);
} else {
    wp_send_json_success(['message' => "{$success_count} item berhasil dihapus."]);
}
