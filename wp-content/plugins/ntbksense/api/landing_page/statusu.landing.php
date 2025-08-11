<?php
// Set header sebagai JSON di paling awal
header("Content-Type: application/json; charset=UTF-8");

// --- LANGKAH 1: BOOTSTRAP WORDPRESS ---
// Path ini akan mencari root WordPress dengan naik 6 level dari file ini.
// Sesuaikan angka '6' jika struktur folder lo berbeda.
$wp_load_path = dirname(__FILE__, 6) . '/wp-load.php';

if (!file_exists($wp_load_path)) {
    http_response_code(500);
    echo json_encode(["message" => "FATAL ERROR: File wp-load.php tidak ditemukan. Cek lagi path-nya."]);
    exit;
}
// Memuat seluruh environment WordPress, termasuk koneksi database ($wpdb)
require_once($wp_load_path);

// --- LANGKAH 2: PROSES LOGIKA UPDATE ---
// Set header CORS setelah WordPress dimuat
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, PATCH, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Gunakan global $wpdb yang sudah disediakan WordPress
global $wpdb;

// 1. Ambil dan validasi ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "ID tidak valid atau tidak disediakan."]);
    exit;
}

// Definisikan nama tabel dengan prefix WordPress
$table_name = $wpdb->prefix . 'acp_landings';

// 2. Ambil status saat ini dari database menggunakan $wpdb
$data = $wpdb->get_row($wpdb->prepare("SELECT status FROM $table_name WHERE id = %d", $id));

if (is_null($data)) {
    http_response_code(404); // Not Found
    echo json_encode(["message" => "Data dengan ID $id tidak ditemukan."]);
    exit;
}

$current_status = $data->status;

// 3. Logika untuk membalik status
$new_status = ($current_status == 1) ? 0 : 1;

// 4. Update status di database menggunakan $wpdb->update (lebih aman)
$result = $wpdb->update(
    $table_name,
    ['status' => $new_status], // Data yang di-update
    ['id' => $id],             // WHERE clause
    ['%d'],                    // Format data untuk 'status' (integer)
    ['%d']                     // Format data untuk 'id' (integer)
);

if ($result === false) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["message" => "Gagal mengubah status di database."]);
} else {
    http_response_code(200); // OK
    echo json_encode([
        "message" => "Status berhasil diubah.",
        "status_baru" => $new_status
    ]);
}
