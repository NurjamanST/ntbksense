<?php
// =========================================================================
// FILE: ntbksense/admin/views/LandingPage/ntbksense-landing-page.php
// FUNGSI: Menampilkan halaman informasi Landing Page.
// =========================================================================

    include "add_action_landingpage.php"; // Memastikan semua aksi yang diperlukan sudah ditambahkan
?>
<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- START: Breadcrumb Kustom -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Landing Page</span>
    </div>
    <!-- END: Breadcrumb Kustom -->

    <div class="ntb-main-content">
        <h2 class="ntb-main-title"><span class="dashicons dashicons-editor-ul"></span> Landing Page</h2>
        <hr>
        <!-- Tombol Aksi Utama -->
        <div class="ntb-actions-bar">
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-create-landing')); ?>" class="button ntb-btn-primary">
                <span class="dashicons dashicons-plus-alt"></span>LP Baru
            </a>
            <a href="#" class="button ntb-btn-danger" id="ntb-bulk-delete-btn"><span class="dashicons dashicons-trash"></span>Hapus</a>
            <a href="#" class="button ntb-btn-success" id="ntb-bulk-export-btn"><span class="dashicons dashicons-upload"></span>Ekspor</a>
            <a href="#" class="button"><span class="dashicons dashicons-download"></span>Impor</a>
            <a href="#" class="button ntb-btn-warning" id="ntb-bulk-duplicate-btn"><span class="dashicons dashicons-admin-page"></span>Duplikat</a>
        </div>

        <!-- Tabel HTML Standar untuk DataTables -->
        <table id="ntb-landing-page-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-lp" />&nbsp;&nbsp;&nbsp;</th>
                    <th>Templat</th>
                    <th>Judul Templat</th>
                    <th>URL LP</th>
                    <th>Parameter</th>
                    <th>Status</th>
                    <th>Tampilan Perangkat</th>
                    <th>Tanggal Dibuat (WIB)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Kodingan cURL asli lo, TIDAK DIUBAH
                $ApiUrl = esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/read.landing.php');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $ApiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                $data = json_decode($response, true);
                // var_dump($data); // Debugging line to check the structure of $data

                $home = home_url();

                if (isset($data) && is_array($data)) {
                    foreach ($data['data'] as $row) {
                        // echo $row['id'];

                ?>
                        <tr>
                            <td><input type="checkbox" name="lp[]" value="<?= $row['id'] ?? '' ?>" /></td>
                            <td>
                                <div class="d-flex w-100 justify-content-start align-items-center">
                                    <div class="d-flex flex-column">
                                        <span><?= $row['template_name'] ?? 'Tanpa Nama Template' ?></span>
                                        <div class="row-actions">
                                            <a style="text-decoration:none;" href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-edit-lp&id=' . ($row['id'] ?? ''))); ?>" class="text-primary">
                                                <span class="dashicons dashicons-edit"></span>
                                            </a>
                                            <a  style="text-decoration:none;" href="#" 
                                                class="text-secondary ntb-duplicate-btn" 
                                                data-id="<?php echo esc_attr($row['id'] ?? ''); ?>" 
                                                title="Duplikat">
                                                <span class="dashicons dashicons-admin-page"></span>
                                            </a>
                                            <a
                                                href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=ntbksense-landing-page&action=delete&id=' . ($row['id'] ?? '')), 'ntb_delete_lp_nonce')); ?>"
                                                class="text-danger ntb-delete-btn"
                                                data-id="<?php echo esc_attr($row['id'] ?? ''); ?>"
                                                title="Delete">
                                                <span class="dashicons dashicons-trash"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p><?php echo htmlspecialchars($row['title'] ?? 'Tanpa Judul'); ?></p>
                            </td>
                            <td>
                                <div class="url-lp-input-group">
                                    <input type="text" value="<?= $home ?>/<?= $row['slug'] ?>" data-ads-url="<?= $home ?>/<?= $row['slug'] ?>?mode=ads" disabled class="url-lp-input">
                                    <button class="url-lp-action-btn copy-btn" title="Salin URL Public">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy">
                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2v2" />
                                        </svg>
                                    </button>
                                    <button class="url-lp-action-btn copy-btn copydanger-btn" title="Salin URL Adsense">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy">
                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2v2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="ntb-param-group">
                                    <input type="text" value="<?= $row['parameter_key'] ?>=<?= $row['parameter_value'] ?>" disabled class="ntb-param-input">
                                    <button class="ntb-param-copy-btn" title="Salin Parameter">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy">
                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2v2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <label class="ntb-switch">
                                    <input type="checkbox" class="ntb-status-toggle" data-id="<?php echo $row['id'] ?? ''; ?>" <?php checked(($row['status'] ?? '0'), '1'); ?>>
                                    <span class="ntb-slider"></span>
                                </label>
                            </td>
                            <td><small><?php echo htmlspecialchars($row['device_view'] ?? '-'); ?></small></td>
                            <td><small><?php echo htmlspecialchars($row['created_at'] ?? '-'); ?></small></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- START: Elemen Notifikasi (Tambahkan ini) -->
    <div id="ntb-copy-notification"></div>
    <!-- END: Elemen Notifikasi -->
</div>

<?php 
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>