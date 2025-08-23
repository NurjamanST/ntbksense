<?php
// =========================================================================
// FILE: ntbksense/admin/views/PrivacyPolicy/ntbksense-privacy-policy.php
// FUNGSI: Menampilkan halaman Privacy Policy Generator.
// =========================================================================

// Memuat logika, aset, dan persiapan variabel
include "add_action_privacy_policy.php";
?>

<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Privacy Policy Generator</span>
    </div>

    <!-- Main Content -->
    <div class="ntb-main-content">
        <h2 class="ntb-main-title">
            <span class="dashicons dashicons-shield"></span> Privacy Policy Generator
        </h2>
        <hr>

        <?php
        // Cek apakah halaman Privacy Policy sudah ada
        if (get_page_by_title('Privacy Policy', OBJECT, 'page')) :
        ?>
            <!-- Tampilan JIKA halaman sudah ada -->
            <div class="alert alert-success d-flex align-items-center justify-content-between" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3"></i>
                    <div>
                        <strong>Halaman Privacy Policy sudah ada.</strong> Anda bisa melihat atau mengeditnya.
                    </div>
                </div>
                <!-- [PERBAIKAN] Tombol Reset yang benar -->
                <button type="button" class="button button-secondary" id="reset-privacy-btn">
                    <i class="fas fa-trash-alt"></i> Reset Privacy Policy
                </button>
            </div>

        <?php else : ?>

            <!-- Tampilan JIKA halaman belum ada -->
            <p>Klik tombol di bawah untuk membuat halaman Privacy Policy secara otomatis menggunakan AI.</p>
            <button type="button" class="button button-primary d-flex align-items-center" id="generate-privacy-btn">
                <span class="dashicons dashicons-shield"></span>
                <span>Generate Privacy Policy</span>
            </button>

        <?php endif; ?>
    </div>
</div>

<?php
// Memuat stylesheet global
include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>