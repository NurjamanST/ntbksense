<?php
// =========================================================================
// FILE: ntbksense/admin/views/Lisensi/ntbksense-lisensi.php
// FUNGSI: Menampilkan dan mengelola halaman lisensi.
// =========================================================================

// Memasukkan file yang berisi logika untuk aktivasi/deaktivasi
include "add_action_lisensi.php";

// Mengambil data lisensi dari database WordPress
$license_key    = get_option('ntbksense_license_key');
$license_status = get_option('ntbksense_license_status');
$license_data   = get_option('ntbksense_license_data', []);

?>
<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Lisensi</span>
    </div>
    
    <!-- Main Content -->
    <div class="ntb-main-content">
        <h4 class="ntb-main-title"><i class="fas fa-key"></i> Lisensi</h4>
        <hr>

        <?php if ($license_status === 'active' && !empty($license_data)) : ?>
            <!-- TAMPILAN JIKA LISENSI AKTIF -->
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle"></i> Lisensi sah. Terima kasih telah menggunakan Plugin NTBKSense Beta Version.
            </div>

            <div class="ntb-license-table">
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Email</div>
                    <div class="ntb-license-value"><?php echo esc_html($license_data['email'] ?? 'N/A'); ?></div>
                </div>
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Kode Lisensi</div>
                    <div class="ntb-license-value"><code><?php echo esc_html($license_key); ?></code></div>
                </div>
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Tanggal Registrasi</div>
                    <div class="ntb-license-value"><?php echo esc_html(date('d F Y', strtotime($license_data['registration_date'] ?? 'now'))); ?></div>
                </div>
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Tanggal Kadaluwarsa</div>
                    <div class="ntb-license-value"><?php echo esc_html($license_data['expiry_date'] ? date('d F Y', strtotime($license_data['expiry_date'])) : 'Seumur Hidup'); ?></div>
                </div>
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Status</div>
                    <div class="ntb-license-value">
                        <span class="badge bg-success">Aktif</span>
                    </div>
                </div>
            </div>

            <form action="" method="post" class="mt-4">
                <?php wp_nonce_field('ntbksense_deactivate_license_nonce', 'ntbksense_deactivate_license_nonce'); ?>
                <input type="hidden" name="ntbksense_action" value="deactivate_license">
                <button type="submit" class="btn btn-danger">Nonaktifkan Lisensi</button>
            </form>

        <?php else : ?>
            <!-- TAMPILAN JIKA LISENSI TIDAK AKTIF -->
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Lisensi tidak aktif. Silakan aktivasi untuk menikmati semua fitur.
            </div>

            <div class="ntb-settings-box mb-4">
                <form action="" method="post">
                    <?php wp_nonce_field('ntbksense_activate_license_nonce', 'ntbksense_activate_license_nonce'); ?>
                    <!-- INI BAGIAN PENTING YANG HILANG -->
                    <input type="hidden" name="ntbksense_action" value="activate_license">
                    <div class="mb-3">
                        <label for="license_key" class="form-label">Kode Lisensi</label>
                        <div class="input-group">
                            <input type="text" id="license_key" name="ntbksense_license_key" class="form-control" placeholder="Masukkan kode lisensi Anda">
                            <button type="submit" class="btn btn-primary">Aktivasi</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php 
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>
