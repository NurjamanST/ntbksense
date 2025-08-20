<?php
// File: wp-content/plugins/ntbksense/api/beranda/dashboard.php
// =========================================================================
// API ENDPOINT UNTUK MENGAMBIL DATA STATISTIK BERANDA
// =========================================================================

// Memuat lingkungan WordPress
require_once( '../../../../../wp-load.php' );

// Mengatur header untuk respons JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

global $wpdb;
$landing_pages_table = $wpdb->prefix . 'acp_landings';
// Tambahkan nama tabel lain di sini jika ada (misal: auto_post, laporan)
// $auto_post_table = $wpdb->prefix . 'ntbksense_auto_post';

// Inisialisasi data statistik
$stats = [
    'total_lp' => 0,
    'active_lp' => 0,
    'total_autopost' => 0, // Placeholder
    'total_laporan' => 0,  // Placeholder
];

// 1. Mengambil Statistik Landing Page
if ($wpdb->get_var("SHOW TABLES LIKE '$landing_pages_table'") === $landing_pages_table) {
    $stats['total_lp'] = (int) $wpdb->get_var("SELECT COUNT(id) FROM $landing_pages_table");
    $stats['active_lp'] = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM $landing_pages_table WHERE status = %s", '1'));
}

// 2. Mengambil 5 Landing Page Terbaru
$recent_lps = $wpdb->get_results(
    "SELECT id, title, slug, status, created_at FROM {$landing_pages_table} ORDER BY created_at DESC LIMIT 5",
    ARRAY_A
);

// Menyiapkan data respons
$response_data = [
    'stats' => $stats,
    'recent_lps' => $recent_lps,
];

// Mengirim data sebagai JSON
wp_send_json_success($response_data);
