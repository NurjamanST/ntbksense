<?php
// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// =========================================================================
// FILE: ntbksense/ntbksense.php
// =========================================================================
/**
 * Plugin Name:       NTBKSense
 * Plugin URI:        https://yourwebsite.com/ntbksense
 * Description:       Plugin dasar untuk sensasi pengguna di WordPress.
 * Version:           1.0.0
 * Author:            Kevin Ariodinoto
 * Author URI:        https://yourwebsite.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ntbksense
 * Domain Path:       /languages/
 */

// Jika file ini dipanggil secara langsung, batalkan.
if (!defined('WPINC')) {
    die;
}

/**
 * Mendefinisikan konstanta utama plugin.
 * Ini membuat kode lebih mudah dikelola dan portabel.
 */
define('NTBKSENSE_VERSION', '1.0.0');
define('NTBKSENSE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NTBKSENSE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NTBKSENSE_PLUGIN_BASENAME', plugin_basename(__FILE__));


/**
 * Fungsi ini dijalankan selama aktivasi plugin.
 * Digunakan untuk menyiapkan opsi default, membuat tabel database, dll.
 */
function activate_ntbksense()
{
    require_once NTBKSENSE_PLUGIN_DIR . 'includes/class-ntbksense-activator.php';
    NTBKSense_Activator::activate();
}

/**
 * Fungsi ini dijalankan selama deaktivasi plugin.
 * Digunakan untuk membersihkan tugas terjadwal atau data sementara.
 */
function deactivate_ntbksense()
{
    require_once NTBKSENSE_PLUGIN_DIR . 'includes/class-ntbksense-deactivator.php';
    NTBKSense_Deactivator::deactivate();
}

// Mendaftarkan hook untuk aktivasi dan deaktivasi.
register_activation_hook(__FILE__, 'activate_ntbksense');
register_deactivation_hook(__FILE__, 'deactivate_ntbksense');

/**
 * Memuat kelas inti plugin yang bertanggung jawab untuk
 * mengatur dan menjalankan semua komponen plugin.
 */
require NTBKSENSE_PLUGIN_DIR . 'includes/class-ntbksense-main.php';

/**
 * Memulai eksekusi plugin.
 * Membuat instance dari kelas utama dan memanggil metode run().
 */
function run_ntbksense()
{
    $plugin = new NTBKSense_Main();
    $plugin->run();
}

// Mulai!
run_ntbksense();

// =========================================================================
// REST API Endpoint untuk mendapatkan data pengguna yang sedang login
// =========================================================================

/**
 * Mendaftarkan endpoint custom baru ke WordPress REST API.
 * Endpoint ini akan mengembalikan data pengguna yang sedang login.
 */
add_action('rest_api_init', function () {

    /**
     * Mendaftarkan route: /wp-json/ntbksense/v1/user/current
     *
     * @param string $namespace         Namespace unik untuk API lo.
     * @param string $route             URL endpoint spesifik.
     * @param array  $args              Argumen untuk endpoint.
     */
    register_rest_route('ntbksense/v1', '/user/current', [
        'methods'  => 'GET', // Metode request yang diizinkan
        'callback' => 'get_current_user_api_callback', // Fungsi yang akan dijalankan

        // Ini bagian keamanannya. WordPress akan otomatis mengecek
        // apakah pengguna sudah login sebelum menjalankan callback.
        'permission_callback' => function () {
            return is_user_logged_in();
        }
    ]);
});

/**
 * Fungsi callback yang akan dijalankan saat endpoint di atas diakses.
 *
 * @return WP_REST_Response Objek respons yang berisi data pengguna.
 */
function get_current_user_api_callback()
{
    // Kita tidak perlu lagi mengecek is_user_logged_in() di sini,
    // karena sudah ditangani oleh 'permission_callback'.
    $user = wp_get_current_user();

    // Pilih data yang ingin ditampilkan, jangan kirim semuanya
    $response_data = [
        'id'          => $user->ID,
        'username'    => $user->user_login,
        'email'       => $user->user_email,
        'displayName' => $user->display_name,
        'roles'       => $user->roles
    ];

    // Kembalikan data sebagai respons JSON yang benar
    return new WP_REST_Response($response_data, 200);
}
