<!-- START: Navbar Kustom -->
    <div class="ntb-navbar">
        <div class="ntb-navbar-left">
            <!-- Logo -->
            <img src="<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'assets/images/logotasik.png'); ?>" alt="Logo" class="ntb-navbar-logo">
            <span class="ntb-navbar-title">NTBKSense</span>
            <span class="ntb-navbar-version">v1.0.0</span>
        </div>
        <div class="ntb-navbar-right">
            <!-- Beranda -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>" class="ntb-navbar-icon">
                <span class="dashicons dashicons-admin-home"></span>
            </a>
            <!-- Landing Page -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-landing-page')); ?>" class="ntb-navbar-icon">
                <span class="dashicons dashicons-admin-page"></span>
            </a>
            <!-- Table Builder -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-template-builder')); ?>" class="ntb-navbar-icon">
                <span class="dashicons dashicons-editor-table"></span>
            </a>
            <!-- Setting -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-settings')); ?>" class="ntb-navbar-icon">
                <span class="dashicons dashicons-admin-generic"></span>
            </a>
            <!-- Laporan -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-laporan')); ?>" class="ntb-navbar-icon">
                <span class="dashicons dashicons-chart-bar"></span>
            </a>             
            <!-- Auto Post -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-auto-post')); ?>" class="ntb-navbar-icon">
                <span class="dashicons dashicons-admin-post"></span>
            </a>
            <!-- Lisensi -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-lisensi')); ?>" class="ntb-navbar-icon">
                <span class="dashicons dashicons-admin-network"></span>
            </a>
            <!-- Privacy Policy Generator -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-privacy-policy')); ?>" class="ntb-navbar-icon">
                <span class="dashicons dashicons-shield-alt"></span>
            </a>             
            <!-- Maintenance -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-maintenance')); ?>" class="ntb-navbar-icon">
                <span class="dashicons dashicons-admin-tools"></span>
            </a>

        </div>
    </div>
<!-- END: Navbar Kustom -->
 <style>
    /* Navbar Styling */
        .ntb-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 25px;
            background-color: #ffffff;
            border-bottom: 1px solid #c3c4c7;
        }
        .ntb-navbar-logo {
            width: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .ntb-navbar-title { font-size: 16px; font-weight: 600; color: #1d2327; }
        .ntb-navbar-version { font-size: 12px; color: #646970; margin-left: 8px; background-color: #f0f0f1; padding: 2px 6px; border-radius: 4px; }
        .ntb-navbar-right .ntb-navbar-icon { color: #50575e; text-decoration: none; margin-left: 15px; }
        .ntb-navbar-right .ntb-navbar-icon .dashicons { font-size: 20px; vertical-align: middle; }
 </style>