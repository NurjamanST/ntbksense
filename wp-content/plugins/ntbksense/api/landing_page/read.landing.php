<?php

// Allow CORS kalau dipanggil dari WordPress
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");


// include('../../config/config.php');
include_once('../../../../../wp-load.php');

global $wpdb;

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Pastikan hanya metode GET yang diizinkan
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'Method not allowed.']);
    exit;
}

// Tentukan nama tabel dengan prefix WordPress secara dinamis
$table_name = 'wp_acp_landings'; // Ganti 'wp_' dengan $wpdb->prefix jika prefix tabel lo dinamis

try {
    if ($id !== null) {
        // --- LOGIKA UNTUK MENGAMBIL SATU DATA BERDASARKAN ID ---

        // Gunakan $wpdb->prepare untuk query yang aman
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id);

        // Gunakan $wpdb->get_row untuk mengambil satu baris
        $landing_page = $wpdb->get_row($query, ARRAY_A); // ARRAY_A untuk hasil array asosiatif

        if ($landing_page) {
            http_response_code(200);
            // Karena ini cuma satu data, tidak perlu dibungkus 'data'
            echo json_encode($landing_page);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Landing page not found.']);
        }
    } else {
        // --- LOGIKA UNTUK MENGAMBIL SEMUA DATA ---

        // Gunakan $wpdb->get_results untuk mengambil banyak baris
        $landing_pages = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A);

        http_response_code(200);
        // Bungkus dalam 'data' agar cocok dengan JavaScript DataTables
        echo json_encode(['data' => $landing_pages]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'message' => 'Terjadi kesalahan pada server.',
        'error' => $e->getMessage()
    ]);
}

exit;
