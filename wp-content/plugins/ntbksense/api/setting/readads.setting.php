<?php

header("Content-Type: application/json; charset=UTF-8");

$wp_load_path = dirname(__FILE__, 6) . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'FATAL ERROR: Gagal memuat environment WordPress.']);
    exit;
}
require_once($wp_load_path);

$table_name = $wpdb->prefix . 'ntbk_ads_settings';

$landing_pages = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A);

http_response_code(200);
// Bungkus dalam 'data' agar cocok dengan JavaScript DataTables
echo json_encode(['data' => $landing_pages]);
