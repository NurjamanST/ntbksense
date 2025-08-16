<?php
// Set header sebagai JSON di paling awal
header("Content-Type: application/json; charset=UTF-8");

// Memuat environment WordPress dengan aman
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
function handle_ntbksense_database_settings_update()
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

    // 2. Kumpulkan dan bersihkan semua data dari form ($_POST).
    $data_to_save = [
        'id_publisher' => sanitize_text_field($_POST['id_publisher'] ?? ''),
        'opacity'  => (int)($_POST['opacity'] ?? 100),
        'jumlah_iklan' => (int)($_POST['jumlah_iklan'] ?? 5),
        'mode_tampilan' => sanitize_text_field($_POST['mode_tampilan'] ?? 'Berurutan'),
        'posisi'  => sanitize_text_field($_POST['posisi'] ?? 'Fixed'),
        'margin_top'  => (int)($_POST['margin_top'] ?? 5),
        'margin_bottom' => (int)($_POST['margin_bottom'] ?? 5),
        'kode_iklan_1' => $_POST['kode_iklan_1'] ?? '',
        'kode_iklan_2' => $_POST['kode_iklan_2'] ?? '',
        'kode_iklan_3' => $_POST['kode_iklan_3'] ?? '',
        'kode_iklan_4' => $_POST['kode_iklan_4'] ?? '',
        'kode_iklan_5' => $_POST['kode_iklan_5'] ?? '',
        'refresh_blank' => (int)($_POST['refresh_blank'] ?? 0),
        'sembunyikan_vinyet' => (int)($_POST['sembunyikan_vinyet'] ?? 0),
        'sembunyikan_anchor' => (int)($_POST['sembunyikan_anchor'] ?? 0),
        'sembunyikan_elemen_blank' => (int)($_POST['sembunyikan_elemen_blank'] ?? 0),
        'tampilkan_close_ads' => (int)($_POST['tampilkan_close_ads'] ?? 0),
        'auto_scroll' => (int)($_POST['auto_scroll'] ?? 0),
        'jenis_pengalihan'  => sanitize_text_field($_POST['jenis_pengalihan'] ?? '302 Temporary - PHP'),
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

    // 3. Cek apakah data sudah ada di tabel
    $existing_id = $wpdb->get_var("SELECT id FROM $table_name LIMIT 1");

    if ($existing_id) {
        $result = $wpdb->update($table_name, $data_to_save, ['id' => $existing_id]);
    } else {
        $result = $wpdb->insert($table_name, $data_to_save);
    }

    // 4. Kirim balasan ke JavaScript.
    if ($result === false) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyimpan ke database. Cek error SQL.',
            'db_error' => $wpdb->last_error
        ]);
    } else {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Pengaturan berhasil disimpan.']);
    }

//     if ($result === false) {
//         // error SQL
//     } elseif ($result === 0) {
//         echo json_encode(['success' => true, 'message' => 'Tidak ada perubahan, data sama.']);
//     } else {
//         echo json_encode(['success' => true, 'message' => 'Pengaturan berhasil diperbarui.']);
//     }
    exit;
}

// Panggil fungsi utama
handle_ntbksense_database_settings_update();
