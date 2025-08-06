<?php
// =========================================================================
// FILE: ntbksense/uninstall.php
// =========================================================================

/**
 * File ini dijalankan ketika pengguna mengklik "Hapus" pada plugin NTBKSense
 * dari layar Plugin di dasbor WordPress.
 *
 * @link       https://yourwebsite.com
 * @since      1.0.0
 *
 * @package    Ntbksense
 */

// Jika uninstall tidak dipanggil dari WordPress, batalkan.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Hapus opsi yang disimpan di tabel wp_options.
delete_option('ntbksense_settings');
delete_option('ntbksense_version');

// Contoh penghapusan tabel database kustom.
// Pastikan untuk mengganti 'nama_tabel_kustom' dengan nama tabel Anda yang sebenarnya.
/*
global $wpdb;
$table_name = $wpdb->prefix . 'nama_tabel_kustom';
$wpdb->query("DROP TABLE IF EXISTS {$table_name}");
*/

// Hapus semua post meta yang mungkin telah dibuat oleh plugin.
/*
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE 'ntbksense_%'");
*/

// Hapus semua CPT yang dibuat oleh plugin (jika ada).
/*
$args = array(
    'post_type' => 'landing_page', // Ganti dengan nama CPT Anda
    'posts_per_page' => -1,
    'post_status' => 'any'
);
$cpt_posts = get_posts($args);
foreach ($cpt_posts as $post) {
    wp_delete_post($post->ID, true); // true untuk menghapus secara permanen
}
*/

// Bersihkan semua jadwal cron yang tersisa.
wp_clear_scheduled_hook('ntbksense_auto_post_event');

?>