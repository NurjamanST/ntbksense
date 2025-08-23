<?php
// File: wp-content/plugins/ntbksense/api/template_builder/update.php

require_once( '../../../../../wp-load.php' );

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

global $wpdb;
$table_name = $wpdb->prefix . 'acp_template_builder';

// Ambil data JSON yang dikirim
$data = json_decode(file_get_contents("php://input"), true);

// Validasi data
if (empty($data['id']) || empty($data['name'])) {
    wp_send_json_error(['message' => 'ID dan Nama template diperlukan.'], 400);
    return;
}

$id = intval($data['id']);

// Siapkan data untuk diperbarui
$update_data = [
    'name'       => sanitize_text_field($data['name']),
    'html'    => $data['html'], // Konten tidak di-escape agar HTML/PHP tetap utuh
    'update_at' => current_time('mysql'),
];

// Siapkan format data
$data_format = ['%s', '%s', '%s']; 

// Kondisi WHERE
$where = ['id' => $id];
$where_format = ['%d'];

// Lakukan pembaruan
$result = $wpdb->update($table_name, $update_data, $where, $data_format, $where_format);

if ($result !== false) {
    wp_send_json_success(['message' => 'Template berhasil diperbarui.']);
} else {
    wp_send_json_error(['message' => 'Gagal memperbarui template. Error: ' . $wpdb->last_error], 500);
}
