<?php
// =========================================================================
// FILE: ntbksense/admin/views/Lisensi/add_action_lisensi.php
// FUNGSI: Menangani logika backend, aset, dan persiapan variabel untuk halaman lisensi.
// =========================================================================

// Menambahkan aset khusus untuk halaman ini
add_action('admin_footer', function() {
    $screen = get_current_screen();
    if ( 'ntbksense_page_ntbksense-lisensi' !== $screen->id ) {
        return;
    }
?>
    <!-- Bootstrap 5 for layout -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<?php
});

// Data dummy untuk lisensi
$license_data = [
    'email' => 'ad***********@gmail.com',
    'key'   => '5c75********************65ab',
    'registration_date' => '2025-05-19 12:49:25',
    'expiry_date' => '2026-05-17 23:59:59',
    'server' => 'https://ntbksense.id',
    'status' => 'Aktif'
];
?>
