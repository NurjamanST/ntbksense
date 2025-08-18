<?php
// =========================================================================
// FILE: ntbksense/admin/views/Lisensi/ntbksense-lisensi.php
// FUNGSI: Menampilkan halaman informasi lisensi.
// =========================================================================

include "add_action_lisensi.php";
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

        <!-- License Activation Form -->
        <div class="ntb-settings-box mb-4">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="license_key" class="form-label">Kode Lisensi</label>
                    <div class="input-group">
                        <input type="text" id="license_key" name="license_key" class="form-control" placeholder="Masukkan kode lisensi">
                        <button type="submit" class="btn btn-primary">Aktivasi</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> Lisensi sah. Terima kasih telah menggunakan layanan kami. <a href="http://ntbksense.id/lisensi" target="_blank" rel="noopener noreferrer">ntbksense.id</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="ntb-license-table">
            <div class="ntb-license-row">
                <div class="ntb-license-label">Email</div>
                <div class="ntb-license-value"><?php echo esc_html($license_data['email']); ?></div>
            </div>
            <div class="ntb-license-row">
                <div class="ntb-license-label">Kode Lisensi</div>
                <div class="ntb-license-value"><?php echo esc_html($license_data['key']); ?></div>
            </div>
            <div class="ntb-license-row">
                <div class="ntb-license-label">Tanggal Registrasi</div>
                <div class="ntb-license-value"><?php echo esc_html($license_data['registration_date']); ?></div>
            </div>
            <div class="ntb-license-row">
                <div class="ntb-license-label">Tanggal Kadarluarsa</div>
                <div class="ntb-license-value"><?php echo esc_html($license_data['expiry_date']); ?></div>
            </div>
            <div class="ntb-license-row">
                <div class="ntb-license-label">Server</div>
                <div class="ntb-license-value"><a href="<?php echo esc_url($license_data['server']); ?>" target="_blank"><?php echo esc_html($license_data['server']); ?></a></div>
            </div>
            <div class="ntb-license-row">
                <div class="ntb-license-label">Status</div>
                <div class="ntb-license-value">
                    <span class="badge bg-success"><?php echo esc_html($license_data['status']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>