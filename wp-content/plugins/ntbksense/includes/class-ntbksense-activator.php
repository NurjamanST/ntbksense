<?php
// =========================================================================
// FILE: ntbksense/includes/class-ntbksense-activator.php
// =========================================================================

/**
 * Dijalankan selama aktivasi plugin
 *
 * @package    Ntbksense
 * @subpackage Ntbksense/includes
 * @author     Nama Anda <email@example.com>
 */
class NTBKSense_Activator {

    /**
     * Metode singkat yang dijalankan selama aktivasi plugin.
     *
     * @since    1.0.0
     */
    public static function activate() {
        global $wpdb;
        
        // Path ke file SQL Anda
        $sql_file = plugin_dir_path( dirname( __FILE__ ) ) . 'database/ntbksense.sql';

        // Periksa apakah file SQL ada
        if ( ! file_exists( $sql_file ) ) {
            // Berikan notifikasi error jika file tidak ditemukan
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( 'File database ntbksense.sql tidak ditemukan.' );
            return;
        }

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        // Baca isi file SQL
        $sql = file_get_contents( $sql_file );

        // Ganti placeholder prefix tabel jika ada (misal: wp_) dengan prefix database yang sebenarnya
        // $sql = str_replace( '`wp_', 'ntbk_' . $wpdb->prefix, $sql );

        // Jalankan perintah SQL
        dbDelta( $sql );

        // Menambahkan opsi versi plugin ke database.
        add_option('ntbksense_version', NTBKSENSE_VERSION);

        // Menyiapkan opsi default untuk pengaturan plugin.
        // Ini memastikan plugin memiliki konfigurasi awal yang valid.
        $default_settings = array(
            'license_key'       => '',
            'license_status'    => 'Tidak Aktif',
            'autopost_enabled'  => 0, // 0 for false
            'autopost_interval' => 'daily',
            'autopost_post_type'=> 'post',
        );
        add_option('ntbksense_settings', $default_settings);

        // Membersihkan aturan rewrite.
        // Ini penting jika Anda mendaftarkan Custom Post Types atau taksonomi.
        flush_rewrite_rules();
    }
}
?>