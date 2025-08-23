<?php
// File: wp-content/plugins/ntbksense/api/template_builder/delete.php

require_once( '../../../../../wp-load.php' );

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

global $wpdb;
$table_name = $wpdb->prefix . 'acp_template_builder';

// Ambil data JSON yang dikirim
$data = json_decode(file_get_contents("php://input"), true);

// Validasi data: pastikan 'ids' ada dan merupakan array
if (empty($data['ids']) || !is_array($data['ids'])) {
    wp_send_json_error(['message' => 'ID template tidak valid.'], 400);
    return;
}

// Sanitasi semua ID menjadi integer
$ids_to_delete = array_map('intval', $data['ids']);
$placeholders = implode(',', array_fill(0, count($ids_to_delete), '%d'));

// Hapus data dari database
$query = $wpdb->prepare("DELETE FROM $table_name WHERE id IN ($placeholders)", $ids_to_delete);
$result = $wpdb->query($query);

if ($result !== false) {
    wp_send_json_success(['message' => "{$result} template berhasil dihapus."]);
} else {
    wp_send_json_error(['message' => 'Gagal menghapus template. Error: ' . $wpdb->last_error], 500);
}
