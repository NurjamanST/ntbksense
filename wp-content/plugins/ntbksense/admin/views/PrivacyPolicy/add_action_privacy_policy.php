<?php
// =========================================================================
// FILE: ntbksense/admin/views/PrivacyPolicy/add_action_privacy_policy.php
// FUNGSI: Menangani logika backend, aset, dan persiapan variabel untuk halaman Privacy Policy.
// =========================================================================

// Menambahkan aset khusus untuk halaman ini (jika diperlukan di masa depan)
add_action('admin_footer', function() {
    $screen = get_current_screen();
    if ( 'ntbksense_page_ntbksense-privacy-policy' !== $screen->id ) {
        return;
    }
    // Aset seperti CSS atau JS bisa ditambahkan di sini
    ?>
    <!-- Bootstrap 5 for layout -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<?php
});
?>
