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

        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <strong>Semua halaman telah diposting.</strong> Anda tidak dapat mempostingnya lagi.
            </div>
        </div>

        <button type="button" class="button button-secondary">Reset Log</button>
    </div>
    
    <center>Or</center>
    
    <!-- Main Content -->
    <div class="ntb-main-content">
        <h2 class="ntb-main-title">
            <span class="dashicons dashicons-shield"></span> Privacy Policy Generator
        </h2>
        <hr>

        <button type="button" class="button button-primary d-flex align-items-center">
            <span class="dashicons dashicons-shield "></span>
            <span>Generate Privacy Policy</span>
        </button>
    </div>
</div>

<?php 
    // Memuat stylesheet global
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>
