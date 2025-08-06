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
        // Menghapus jadwal cron yang dibuat oleh plugin.
        // Ini adalah praktik yang baik untuk mencegah tugas yatim piatu (orphaned tasks).
        wp_clear_scheduled_hook('ntbksense_auto_post_event');

        // Membersihkan aturan rewrite.
        flush_rewrite_rules();
    }
}
?>