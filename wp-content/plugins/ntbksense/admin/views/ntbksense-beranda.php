<?php
// =========================================================================
// FILE: ntbksense/admin/views/ntbksense-beranda.php
// =========================================================================
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p><?php _e('Selamat datang di Dasbor NTBKSense. Dari sini Anda dapat mengelola semua fitur yang disediakan oleh plugin.', 'ntbksense'); ?></p>

    <div class="metabox-holder">
        <div class="postbox">
            <h2 class="hndle"><span><?php _e('Status Plugin', 'ntbksense'); ?></span></h2>
            <div class="inside">
                <?php $settings = get_option('ntbksense_settings'); ?>
                <ul>
                    <li><?php printf(__('Status Lisensi: <strong>%s</strong>', 'ntbksense'), esc_html($settings['license_status'] ?? 'Tidak Aktif')); ?></li>
                    <li><?php printf(__('Auto Post: <strong>%s</strong>', 'ntbksense'), !empty($settings['autopost_enabled']) ? 'Aktif' : 'Tidak Aktif'); ?></li>
                </ul>
            </div>
        </div>

        <div class="postbox">
            <h2 class="hndle"><span><?php _e('Tautan Cepat', 'ntbksense'); ?></span></h2>
            <div class="inside">
                <ul>
                    <li><a href="<?php echo admin_url('admin.php?page=ntbksense-settings'); ?>"><?php _e('Buka Halaman Pengaturan', 'ntbksense'); ?></a></li>
                    <li><a href="<?php echo admin_url('admin.php?page=ntbksense-laporan'); ?>"><?php _e('Lihat Laporan', 'ntbksense'); ?></a></li>
                    <li><a href="<?php echo admin_url('admin.php?page=ntbksense-auto-post'); ?>"><?php _e('Kelola Auto Post', 'ntbksense'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>