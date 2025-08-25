<?php
// =========================================================================
// FILE: ntbksense/includes/class-ntbksense-deactivator.php
// =========================================================================

/**
 * Dijalankan selama deaktivasi plugin
 *
 * @package    Ntbksense
 * @subpackage Ntbksense/includes
 * @author     Nama Anda <email@example.com>
 */
class NTBKSense_Deactivator {

    /**
     * Metode singkat yang dijalankan selama deaktivasi plugin.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        global $wpdb;
        
        // Ganti dengan nama-nama tabel Anda
        $table_names = array(
            $wpdb->prefix . 'ntbk_landings',
            $wpdb->prefix . 'ntbk_logs',
            $wpdb->prefix . 'ntbk_template_builder',
            $wpdb->prefix . 'ntbk_ads_settings',
            // Tambahkan nama tabel lain jika ada
        );

        foreach ( $table_names as $table_name ) {
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        }


        // Menghapus jadwal cron yang dibuat oleh plugin.
        // Ini adalah praktik yang baik untuk mencegah tugas yatim piatu (orphaned tasks).
        wp_clear_scheduled_hook('ntbksense_auto_post_event');

        // Membersihkan aturan rewrite.
        flush_rewrite_rules();
    }
}
?>