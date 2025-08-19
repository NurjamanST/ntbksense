<?php
// File: wp-content/plugins/ntbksense/api/landing_page/export.landing.php
// =========================================================================
// API ENDPOINT UNTUK EKSPOR LANDING PAGE
// =========================================================================

// Memuat lingkungan WordPress
require_once( '../../../../../wp-load.php' );

// Mengambil data ID dari input
$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (empty($data['ids']) || !is_array($data['ids'])) {
    header('Content-Type: application/json');
    wp_send_json_error(['message' => 'Tidak ada ID yang dipilih untuk diekspor.'], 400);
    return;
}

global $wpdb;
$table_name = $wpdb->prefix . 'acp_landings';
$ids_to_export = array_map('intval', $data['ids']);
$placeholders = implode(',', array_fill(0, count($ids_to_export), '%d'));

// Mengambil semua data yang relevan dari database berdasarkan ID yang dipilih
$results = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM $table_name WHERE id IN ($placeholders)", $ids_to_export),
    ARRAY_A
);

if (empty($results)) {
    header('Content-Type: application/json');
    wp_send_json_error(['message' => 'Data tidak ditemukan untuk ID yang dipilih.'], 404);
    return;
}

// Menyiapkan nama file dan header untuk unduhan
$filename = 'LandingPage-ntbksense-export-' . date('Y-m-d') . '.json';
header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Mengirimkan data sebagai JSON
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;
