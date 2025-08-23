<?php
// =========================================================================
// FILE: ntbksense/admin/views/ntbksense-maintenance.php
// FUNGSI: Menampilkan dan mengelola halaman maintenance.
// =========================================================================

// Memasukkan file yang berisi logika untuk maintenance
include "add_action_maintenance.php";

// Logika untuk Tes GEO IP
$geo_ip_result = null;
if ( isset( $_POST['ntbksense_test_ip_nonce'] ) && wp_verify_nonce( $_POST['ntbksense_test_ip_nonce'], 'ntbksense_test_ip_action' ) && ! empty( $_POST['ip_address'] ) ) {
    $ip_address = sanitize_text_field( $_POST['ip_address'] );
    // Menggunakan API eksternal untuk mendapatkan data GEO IP
    $api_url = 'http://ip-api.com/json/' . $ip_address;
    $response = wp_remote_get( $api_url );

    if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
        $body = wp_remote_retrieve_body( $response );
        $geo_ip_result = json_decode( $body, true );
    } else {
        $geo_ip_result = ['status' => 'fail', 'message' => 'Gagal terhubung ke API GEO IP.'];
    }
}
?>
<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Maintenance</span>
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