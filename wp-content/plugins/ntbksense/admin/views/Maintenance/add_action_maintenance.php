<?php
// =========================================================================
// FILE: ntbksense/admin/views/Maintenance/add_action_maintenance.php
// FUNGSI: Menangani logika backend, aset, dan persiapan variabel untuk halaman maintenance.
// =========================================================================

// Menambahkan aset khusus untuk halaman ini
add_action('admin_footer', function() {
    $screen = get_current_screen();
    if ( 'ntbksense_page_ntbksense-maintenance' !== $screen->id ) {
        return;
    }
?>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    
<?php
});
?>
