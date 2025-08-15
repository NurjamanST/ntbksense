<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/ntbksense-settings.php
// FUNGSI: Menampilkan form pengaturan global.
// =========================================================================

// Memuat logika, aset, dan persiapan variabel
include "add_action_settings.php";
?>

<div class="wrap" id="ntbksense-settings-wrapper">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Settings</span>
    </div>

    <div class="ntb-main-content">
        <form id="settingsForm" method="post">
            <div class="row">
                <!-- Kolom Kiri: Pengaturan -->
                <div class="col-lg-8">
                    <ul class="nav nav-tabs ntb-nav-tabs" id="settingsTabs" role="tablist">                        
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pengaturan-iklan-tab" data-bs-toggle="tab" data-bs-target="#pengaturan-iklan" type="button" role="tab" aria-controls="pengaturan-iklan" aria-selected="true"><span class="dashicons dashicons-megaphone"></span> Pengaturan Iklan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="advance-tab" data-bs-toggle="tab" data-bs-target="#advance" type="button" role="tab" aria-controls="advance" aria-selected="false"><span class="dashicons dashicons-admin-settings"></span> Advance</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="header-footer-tab" data-bs-toggle="tab" data-bs-target="#header-footer" type="button" role="tab" aria-controls="header-footer" aria-selected="false"><span class="dashicons dashicons-editor-code"></span> Header & Footer</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="plugin-tab" data-bs-toggle="tab" data-bs-target="#plugin" type="button" role="tab" aria-controls="plugin" aria-selected="false"><span class="dashicons dashicons-admin-plugins"></span> Plugin</button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link" id="keamanan-tab" data-bs-toggle="tab" data-bs-target="#keamanan" type="button" role="tab" aria-controls="keamanan" aria-selected="false"><span class="dashicons dashicons-lock"></span> Keamanan</button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link" id="optimasi-tab" data-bs-toggle="tab" data-bs-target="#optimasi" type="button" role="tab" aria-controls="optimasi" aria-selected="false"><span class="dashicons dashicons-performance"></span> Optimasi</button>
                        </li>
                    </ul>

                    <div class="tab-content ntb-tab-content" id="settingsTabsContent">
                        <!-- Tab: Pengaturan Iklan -->
                        <?php include "ads_settings.php"; ?>

                        <!-- Tab: Advance -->
                        <?php include "advance_settings.php"; ?>
                        
                        <!-- Tab: Header & Footer -->
                        <?php include "header_footer.php"; ?>

                        <!-- Placeholder untuk tab lainnya -->
                        <div class="tab-pane fade" id="plugin" role="tabpanel"><p>Pengaturan Plugin akan muncul di sini.</p></div>
                        <div class="tab-pane fade" id="keamanan" role="tabpanel"><p>Pengaturan Keamanan akan muncul di sini.</p></div>
                        <div class="tab-pane fade" id="optimasi" role="tabpanel"><p>Pengaturan Optimasi akan muncul di sini.</p></div>
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

            <!-- Tombol Aksi Form -->
            <div class="ntb-form-actions">
                <button type="submit" class="button button-primary btn-update-settings">Simpan Pengaturan</button>
                <a href="#" class="button button-secondary next-tab" data-next="#advance">Selanjutnya &rarr;</a>
            </div>
        </form>
    </div>
</div>

<style>
    /* General Layout & Main Content Box */
    #ntbksense-settings-wrapper {
        background-color: #f0f0f1;
        padding: 0;
        margin: 0px 0px 0px -20px;
    }

    .ntb-main-content {
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        padding: 20px;
        margin: 20px;
    }

    /* Navbar & Breadcrumb */
    .ntb-navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #ffffff;
        border-bottom: 1px solid #c3c4c7;
    }

    .ntb-navbar-title {
        font-size: 16px;
        font-weight: 600;
    }

    .ntb-breadcrumb {
        padding: 15px 20px;
        color: #50575e;
        font-size: 14px;
        background-color: #fff;
        border-bottom: 1px solid #e0e0e0;
    }

    .ntb-breadcrumb a {
        text-decoration: none;
        color: #0073aa;
    }

    .ntb-breadcrumb span {
        font-weight: 600;
    }

    /* Tabs Styling */
    .ntb-nav-tabs {
        border-bottom: 1px solid #ddd;
    }

    .ntb-nav-tabs .nav-link {
        border: 1px solid transparent;
        border-bottom: none;
        color: #555;
        font-size: 13px;
        padding: 8px 15px;
    }

    .ntb-nav-tabs .nav-link .dashicons {
        font-size: 16px;
        vertical-align: text-top;
        margin-right: 5px;
    }

    .ntb-nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #ddd #ddd #fff;
        color: #0073aa;
        font-weight: 600;
    }

    .ntb-tab-content {
        background: #fff;
        padding: 20px;
        border: 1px solid #ddd;
        border-top: none;
    }

    /* Form Sections & Fields */
    .ntb-settings-section {
        padding: 20px;
        border: 1px solid #e5e5e5;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .ntb-settings-section:last-child {
        margin-bottom: 0;
    }

    .ntb-settings-section .section-title {
        font-size: 14px;
        font-weight: 600;
        margin-top: 0;
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: 600;
        font-size: 13px;
    }

    .form-control,
    .form-select {
        font-size: 13px;
    }
    .form-text a {
        text-decoration: none;
    }
    .ntb-feature-list {
        list-style: none;
        padding-left: 0;
        font-size: 13px;
    }
    .ntb-feature-list li {
        margin-bottom: 5px;
    }
    .ntb-feature-list .dashicons {
        vertical-align: middle;
        margin-right: 5px;
    }

    /* Mobile Preview */
    .ntb-preview-container {
        position: sticky;
        top: 50px;
    }

    .ntb-mobile-preview {
        width: 300px;
        /* Lebar iPhone 13 Mini */
        height: 615px;
        /* Tinggi iPhone 13 Mini */
        background: #fff;
        border: 8px solid #111;
        border-radius: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        margin: 0 auto;
        display: flex;
        flex-direction: column;
    }

    .ntb-mobile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 20px;
        color: #111;
        position: relative;
        background-color: #fff;
        border-top-left-radius: 32px;
        border-top-right-radius: 32px;
    }

    .ntb-mobile-header .time {
        font-weight: 600;
        font-size: 14px;
    }

    .ntb-mobile-header .notch {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 120px;
        height: 25px;
        background: #111;
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
    }

    .ntb-mobile-header .status-icons {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
    }

    .ntb-mobile-screen {
        background: #000;
        flex-grow: 1;
    }

    .ntb-mobile-footer {
        background-color: #f0f0f1;
        padding: 8px 12px 15px 12px;
        border-bottom-left-radius: 32px;
        border-bottom-right-radius: 32px;
    }

    .ntb-address-bar {
        background-color: #e1e1e1;
        border-radius: 8px;
        padding: 5px 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
    }

    .ntb-address-bar .url-text {
        color: #333;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .ntb-address-bar .url-text .dashicons {
        font-size: 14px;
    }

    .ntb-nav-buttons {
        display: flex;
        justify-content: space-around;
        padding-top: 15px;
        color: #007aff;
        font-size: 22px;
    }

    .ntb-nav-buttons .dashicons {
        font-size: 24px;
        height: auto;
        width: auto;
    }

    .ntb-nav-buttons .bi-box-arrow-up {
        font-size: 20px;
    }


    /* Form Actions */
    .ntb-form-actions {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }
</style>