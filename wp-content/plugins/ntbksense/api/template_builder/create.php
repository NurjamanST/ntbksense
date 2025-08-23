<?php
// File: wp-content/plugins/ntbksense/api/template_builder/create.php

require_once( '../../../../../wp-load.php' );

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

global $wpdb;
$table_name = $wpdb->prefix . 'acp_template_builder';

// Ambil data JSON yang dikirim dari JavaScript
$data = json_decode(file_get_contents("php://input"), true);

// Validasi dasar: pastikan nama template tidak kosong
if (empty($data['name'])) {
    wp_send_json_error(['message' => 'Nama template tidak boleh kosong.'], 400);
    return;
}

// Siapkan data untuk dimasukkan ke database
$insert_data = [
    'name'              => sanitize_text_field($data['name']),
    'icon_url'              => esc_url_raw($data['icon_url'] ?? ''),
    'status'            => 'draft', // Status default saat pertama kali dibuat
    'bootstrap_version' => sanitize_text_field($data['bootstrap_version'] ?? '5.3.1'),
    'html'           => '<!-- Mulai tulis kode template Anda di sini -->', // Konten default
    'created_at'        => current_time('mysql'),
    'update_at'        => current_time('mysql'),
];

// Masukkan data ke database
$result = $wpdb->insert($table_name, $insert_data);

if ($result) {
    // Jika berhasil, kirim kembali ID dari data yang baru dibuat
    $new_id = $wpdb->insert_id;
    wp_send_json_success([
        'message' => 'Draft template berhasil dibuat.',
        'id'      => $new_id
    ]);
} else {
    wp_send_json_error(['message' => 'Gagal menyimpan draft template ke database. Error: ' . $wpdb->last_error], 500);
}