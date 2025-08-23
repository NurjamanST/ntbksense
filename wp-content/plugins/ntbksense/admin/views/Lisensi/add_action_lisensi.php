<?php
// =========================================================================
// FILE: ntbksense/admin/views/Lisensi/add_action_lisensi.php
// FUNGSI: Menangani logika backend, aset, dan persiapan variabel untuk halaman lisensi.
// =========================================================================

// --- PENGATURAN PENTING ---
// Ganti URL ini dengan alamat server lisensi Laravel Anda yang sebenarnya.
define('NTBKSENSE_LICENSE_SERVER_URL', 'http://127.0.0.1:8000/licenses'); 

/**
 * Fungsi untuk menangani aksi form (aktivasi/deaktivasi) dari halaman lisensi.
 * Dijalankan sebelum header halaman admin dimuat.
 */
function ntbksense_handle_license_actions() {
    // Pastikan kita berada di halaman yang benar dan ada aksi yang dikirim
    if (!isset($_POST['ntbksense_action'])) {
        return;
    }

    // Menangani aksi aktivasi lisensi
    if ($_POST['ntbksense_action'] === 'activate_license') {
        // Verifikasi nonce untuk keamanan
        if (!isset($_POST['ntbksense_activate_license_nonce']) || !wp_verify_nonce($_POST['ntbksense_activate_license_nonce'], 'ntbksense_activate_license_nonce')) {
            wp_die('Pemeriksaan keamanan gagal!');
        }
        
        $license_key = isset($_POST['ntbksense_license_key']) ? sanitize_text_field(trim($_POST['ntbksense_license_key'])) : '';
        if (empty($license_key)) {
             add_action('admin_notices', function() {
                echo '<div class="notice notice-error is-dismissible"><p>Kode lisensi tidak boleh kosong.</p></div>';
            });
            return;
        }

        ntbksense_activate_license($license_key);
    }

    // Menangani aksi deaktivasi lisensi
    if ($_POST['ntbksense_action'] === 'deactivate_license') {
        // Verifikasi nonce untuk keamanan
        if (!isset($_POST['ntbksense_deactivate_license_nonce']) || !wp_verify_nonce($_POST['ntbksense_deactivate_license_nonce'], 'ntbksense_deactivate_license_nonce')) {
            wp_die('Pemeriksaan keamanan gagal!');
        }
        
        ntbksense_deactivate_license();
    }
}
add_action('admin_init', 'ntbksense_handle_license_actions');

/**
 * Mengirim permintaan aktivasi ke server lisensi.
 *
 * @param string $license_key Kunci lisensi yang akan diaktifkan.
 */
function ntbksense_activate_license($license_key) {
    $api_url = NTBKSENSE_LICENSE_SERVER_URL . '/api/license/activate';
    
    // Data yang akan dikirim ke server
    $request_body = [
        'license_key' => $license_key,
        'domain'      => home_url(), // Mengambil domain situs WordPress saat ini
    ];

    $response = wp_remote_post($api_url, [
        'timeout' => 45,
        'body'    => $request_body,
    ]);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        add_action('admin_notices', function() use ($error_message) {
            echo '<div class="notice notice-error is-dismissible"><p>Gagal terhubung ke server lisensi: ' . esc_html($error_message) . '</p></div>';
        });
        return;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    $http_code = wp_remote_retrieve_response_code($response);

    if ($http_code === 200 && isset($body['success']) && $body['success']) {
        // Aktivasi berhasil
        update_option('ntbksense_license_key', $license_key);
        update_option('ntbksense_license_status', 'active');
        // Simpan data tambahan dari server
        if (isset($body['data'])) {
            update_option('ntbksense_license_data', $body['data']);
        }
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Lisensi berhasil diaktifkan. Terima kasih!</p></div>';
        });
    } else {
        // Aktivasi gagal
        $message = isset($body['message']) ? $body['message'] : 'Terjadi kesalahan yang tidak diketahui.';
        delete_option('ntbksense_license_key');
        delete_option('ntbksense_license_status');
        delete_option('ntbksense_license_data');
        add_action('admin_notices', function() use ($message) {
            echo '<div class="notice notice-error is-dismissible"><p>Aktivasi Gagal: ' . esc_html($message) . '</p></div>';
        });
    }
}

/**
 * Menangani proses deaktivasi lisensi.
 */
function ntbksense_deactivate_license() {
    $license_key = get_option('ntbksense_license_key');
    
    if (empty($license_key)) {
        return; // Tidak ada lisensi untuk dinonaktifkan
    }

    $api_url = NTBKSENSE_LICENSE_SERVER_URL . '/api/license/deactivate';
    
    $request_body = [
        'license_key' => $license_key,
        'domain'      => home_url(),
    ];

    // Kirim permintaan ke server untuk menonaktifkan lisensi
    wp_remote_post($api_url, [
        'timeout' => 45,
        'body'    => $request_body,
    ]);

    // Hapus data lisensi dari database WordPress, terlepas dari respons server
    delete_option('ntbksense_license_key');
    delete_option('ntbksense_license_status');
    delete_option('ntbksense_license_data');

    add_action('admin_notices', function() {
        echo '<div class="notice notice-success is-dismissible"><p>Lisensi telah dinonaktifkan dari situs ini.</p></div>';
    });
}


// Menambahkan aset (CSS & JS) khusus untuk halaman ini
add_action('admin_footer', function() {
    $screen = get_current_screen();
    // Pastikan aset hanya dimuat di halaman lisensi plugin kita
    if (strpos($screen->id, 'ntbksense-lisensi') === false) {
        return;
    }
?>
    <!-- Bootstrap 5 for layout -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<?php
});

?>