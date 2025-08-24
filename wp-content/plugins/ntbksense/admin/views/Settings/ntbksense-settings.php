<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/ntbksense-settings.php
// FUNGSI: Menampilkan form pengaturan global.
// =========================================================================

// Memuat logika, aset, dan persiapan variabel

?>

<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>"><?php echo esc_html($plugin_name) ?></a> &gt; <span>Settings</span>
    </div>

    <div class="ntb-main-content">
        <div class="row">
            <!-- Kolom Kiri: Pengaturan -->
            <div class="col-lg-8">
                <ul class="nav nav-tabs ntb-nav-tabs" id="settingsTabs" role="tablist">                        
                    <li class="nav-item" role="presentation">
                        <button style="font-size:14px;" class="nav-link active" id="pengaturan-iklan-tab" data-bs-toggle="tab" data-bs-target="#pengaturan-iklan" type="button" role="tab" aria-controls="pengaturan-iklan" aria-selected="true"><span class="dashicons dashicons-megaphone"></span> Pengaturan Iklan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button style="font-size:14px;" class="nav-link" id="advance-tab" data-bs-toggle="tab" data-bs-target="#advance" type="button" role="tab" aria-controls="advance" aria-selected="false"><span class="dashicons dashicons-admin-settings"></span> Advance</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button style="font-size:14px;" class="nav-link" id="header-footer-tab" data-bs-toggle="tab" data-bs-target="#header-footer" type="button" role="tab" aria-controls="header-footer" aria-selected="false"><span class="dashicons dashicons-editor-code"></span> Header & Footer</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button style="font-size:14px;" class="nav-link" id="plugin-tab" data-bs-toggle="tab" data-bs-target="#plugin" type="button" role="tab" aria-controls="plugin" aria-selected="false"><span class="dashicons dashicons-admin-plugins"></span> Plugin</button>
                    </li>
                        <li class="nav-item" role="presentation">
                        <button style="font-size:14px;" class="nav-link" id="keamanan-tab" data-bs-toggle="tab" data-bs-target="#keamanan" type="button" role="tab" aria-controls="keamanan" aria-selected="false"><span class="dashicons dashicons-lock"></span> Keamanan</button>
                    </li>
                        <li class="nav-item" role="presentation">
                        <button style="font-size:14px;" class="nav-link" id="optimasi-tab" data-bs-toggle="tab" data-bs-target="#optimasi" type="button" role="tab" aria-controls="optimasi" aria-selected="false"><span class="dashicons dashicons-performance"></span> Optimasi</button>
                    </li>
                </ul>

                <div class="tab-content ntb-tab-content" id="settingsTabsContent">
                    <!-- Tab: Pengaturan Iklan -->                    
                    <?php include "ads_settings.php"; ?>

                    <!-- Tab: Advance -->
                    <?php include "advance_settings.php"; ?>
                    
                    <!-- Tab: Header & Footer -->
                    <?php include "header_footer.php"; ?>

                    <!-- Plugin -->
                    <?php include "plugin_settings.php"; ?>

                    <!-- Tab: Keamanan -->
                    <?php include "security_settings.php"; ?>
                    
                    <!-- Tab: Optimasi -->
                    <?php include "optimization_settings.php"; ?>
                </div>
            </div>

            <!-- Kolom Kanan: Pratinjau -->
            <div class="col-lg-4">
                <div class="ntb-preview-container">
                    <div class="ntb-mobile-preview">
                        <div class="ntb-mobile-header">
                            <span class="time">11:39 AM</span>
                            <div class="notch"></div>
                            <div class="status-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reception-4" viewBox="0 0 16 16"><path d="M0 11.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2zm4-3a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-5zm4-3a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-8zm4-3a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-11z"/></svg>
                                <span>5G</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-battery-full" viewBox="0 0 16 16"><path d="M2 6h10v4H2V6z"/><path d="M2 4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H2zm13 1a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-1v-4h1z"/></svg>
                            </div>
                        </div>
                        <div class="ntb-mobile-screen"><iframe id="ad-preview-iframe" src="about:blank" style="width: 100%; height: 100%; border: none;"></iframe></div>
                        <div class="ntb-mobile-footer">
                            <div class="ntb-address-bar">
                                <span>Aa</span>
                                <div class="url-text"><span class="dashicons dashicons-lock"></span> www.example.com</div>
                                <span class="dashicons dashicons-image-rotate"></span>
                            </div>
                            <div class="ntb-nav-buttons">
                                <span class="dashicons dashicons-arrow-left-alt2"></span>
                                <span class="dashicons dashicons-arrow-right-alt2"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1h-2z"/><path fill-rule="evenodd" d="M7.646.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 1.707V10.5a.5.5 0 0 1-1 0V1.707L5.354 3.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>
                                <span class="dashicons dashicons-book-alt"></span>
                                <span class="dashicons dashicons-images-alt"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>
