<?php
// =========================================================================
// FILE: ntbksense/admin/views/ntbksense-beranda.php
// =========================================================================
?>
<div class="wrap" id="ntbksense-template-builder-wrapper">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR."admin/views/Layout/navbar.php"; ?>

    <!-- START: Breadcrumb Kustom -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Template Builder</span>
    </div>
    <!-- END: Breadcrumb Kustom -->
     
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p><?php _e('Selamat datang di Dasbor NTBKSense. Dari sini Anda dapat mengelola semua fitur yang disediakan oleh plugin.', 'ntbksense'); ?></p>

    <div class="metabox-holder">
        <div class="postbox">
            <h2 class="hndle"><span><?php _e('Status Plugin', 'ntbksense'); ?></span></h2>
            <div class="inside">
                <?php $settings = get_option('ntbksense_settings'); ?>
                <ul>
                    <li><?php 
                    if (defined('NTBKSENSE_VERSION')) {
                        printf(__('Versi Plugin: <strong>%s</strong>', 'ntbksense'), NTBKSENSE_VERSION);
                    } else {
                        _e('Versi Plugin: <strong>Tidak Diketahui</strong>', 'ntbksense');
                    }
                    ?></li>
                    <li><?php 
                    if (isset($settings['maintenance_mode']) && $settings['maintenance_mode']) {
                        _e('Mode Pemeliharaan: <strong>Aktif</strong>', 'ntbksense');
                    } else {
                        _e('Mode Pemeliharaan: <strong>Tidak Aktif</strong>', 'ntbksense');
                    }
                     ?></li>
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

<style>
    /* General Layout & Main Content Box */
        #ntbksense-template-builder-wrapper {
            background-color: #f0f0f1;
            padding: 0;
            margin: 0px 0px 0px -20px; /* Override default .wrap margin */
        }
        .ntb-main-content {
            background: #fff;
            border: 1px solid #c3c4c7;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            padding: 20px;
            margin: 20px;
        }
        .ntb-main-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Navbar Styling */
        .ntb-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #ffffff;
            border-bottom: 1px solid #c3c4c7;
        }
        .ntb-navbar-title { font-size: 16px; font-weight: 600; color: #1d2327; }
        .ntb-navbar-version { font-size: 12px; color: #646970; margin-left: 8px; background-color: #f0f0f1; padding: 2px 6px; border-radius: 4px; }
        .ntb-navbar-right .ntb-navbar-icon { color: #50575e; text-decoration: none; margin-left: 15px; }
        .ntb-navbar-right .ntb-navbar-icon .dashicons { font-size: 20px; vertical-align: middle; }

        /* Breadcrumb Styling */
        .ntb-breadcrumb { padding: 15px 20px; color: #50575e; font-size: 14px; background-color: #fff; border-bottom: 1px solid #e0e0e0; }
        .ntb-breadcrumb a { text-decoration: none; color: #0073aa; }
        .ntb-breadcrumb a:hover { text-decoration: underline; }
        .ntb-breadcrumb span { color: #1d2327; font-weight: 600; }
</style>