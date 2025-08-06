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