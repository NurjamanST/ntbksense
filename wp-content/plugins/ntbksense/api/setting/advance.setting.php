<?php

// Set header sebagai JSON di paling awal
header("Content-Type: application/json; charset=UTF-8");

$wp_load_path = dirname(__FILE__, 6) . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'FATAL ERROR: Gagal memuat environment WordPress.']);
    exit;
}
require_once($wp_load_path);

/**
 * Fungsi yang akan dieksekusi saat form settings disimpan.
 */
function handle_ntbksense_ads_settings_update()
{
    // 1. Keamanan: Cek nonce dan izin user.
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ntbksense_settings_nonce')) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Validasi keamanan gagal!']);
        exit;
    }
    if (!current_user_can('manage_options')) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki izin.']);
        exit;
    }

    global $wpdb;
    // [PERBAIKAN UTAMA] Nama tabel disesuaikan
    $table_name = $wpdb->prefix . 'ntbk_ads_settings';

    // kumpulkan dan bersihkan semua data dari form ($_POST).
    $data_to_save = [
        'jenis_pengalihan'  => sanitize_text_field($_POST['jenis_pengalihan'] ?? 'Mode 302 Temporary - PHP'),
        'akses_lokasi_mode'  => sanitize_text_field($_POST['akses_lokasi_mode'] ?? 'off'),
        'akses_lokasi_countries' => isset($_POST['akses_lokasi_countries']) ? wp_json_encode(array_map('sanitize_text_field', $_POST['akses_lokasi_countries'])) : '[]',
        'blokir_ip_address'  => sanitize_textarea_field($_POST['blokir_ip_address'] ?? ''),
        'akses_asn_mode' => sanitize_text_field($_POST['akses_asn_mode'] ?? 'off'),
        'akses_asn_list' => sanitize_textarea_field($_POST['akses_asn_list'] ?? ''),
        'detektor_perangkat_tinggi' => (int)($_POST['detektor_perangkat_tinggi'] ?? 0),
        'detektor_perangkat_sedang' => (int)($_POST['detektor_perangkat_sedang'] ?? 0),
        'detektor_perangkat_rendah' => (int)($_POST['detektor_perangkat_rendah'] ?? 0),
        'simpan_log' => sanitize_text_field($_POST['simpan_log'] ?? 'Aktifkan'),
        'simpan_durasi'  => sanitize_text_field($_POST['simpan_durasi'] ?? 'Tidak (Default)'),
        'interval_durasi' => (int)($_POST['interval_durasi'] ?? 5),
    ];

    $existing_settings = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1", ARRAY_A);

    if ($existing_settings) {
        // Update existing settings
        $result = $wpdb->update($table_name, $data_to_save, ['id' => 1]);
    } else {
        // Insert new settings
        $data_to_save['id'] = 1; // Pastikan ID selalu ada
        $result = $wpdb->insert($table_name, $data_to_save);
    }

    if ($result === false) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyimpan pengaturan iklan.',
            'error' => $wpdb->last_error
        ]);
    } else {
        // Berhasil menyimpan pengaturan
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Pengaturan iklan berhasil disimpan.']);
    }
    exit;
}

handle_ntbksense_ads_settings_update();
