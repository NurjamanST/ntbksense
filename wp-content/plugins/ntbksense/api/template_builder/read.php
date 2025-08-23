<?php
// File: wp-content/plugins/ntbksense/api/template_builder/read.php

require_once( '../../../../../wp-load.php' );

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

global $wpdb;
$table_name = $wpdb->prefix . 'acp_template_builder';

// Cek apakah ada permintaan untuk ID spesifik
$template_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($template_id > 0) {
    // Ambil satu template berdasarkan ID
    $template = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $template_id), ARRAY_A);
    if ($template) {
        wp_send_json_success($template);
    } else {
        wp_send_json_error(['message' => 'Template tidak ditemukan.'], 404);
    }
} else {
    // Ambil semua template
    $templates = $wpdb->get_results("SELECT id, name, status, bootstrap_version, created_at, update_at FROM $table_name ORDER BY update_at DESC", ARRAY_A);
    wp_send_json_success($templates);
}
