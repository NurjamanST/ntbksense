<?php
// =========================================================================
// FILE: ntbksense/admin/views/ntbksense-maintenance.php
// FUNGSI: Menampilkan dan mengelola halaman maintenance.
// =========================================================================

// Memasukkan file yang berisi logika untuk maintenance
include "add_action_maintenance.php";
$plugin = ntbksense_get_branding();
$plugin_name  = $plugin['plugin_name'] ?? 'NTBKSense';
?>
<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>"><?php echo esc_html($plugin_name); ?></a> &gt; <span>Maintenance</span>
    </div>
    
    <!-- Box Database GEO IP -->
    <?php include "database_geo_ip.php"; ?>

    <!-- Box Pemeriharaan Tabel -->
    <?php include "pemeliharaan_tabel.php"; ?>


    <!-- Box Tes GEO IP -->
    <?php include "tes_geo_ip.php"; ?>
    
</div>


<?php 
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>
