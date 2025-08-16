<?php
// =========================================================================
// FILE: ntbksense/admin/views/AutoPost/add_action_autopost.php
// FUNGSI: Menangani logika backend, aset, dan persiapan variabel untuk Auto Post.
// =========================================================================

// Menambahkan aset khusus untuk halaman ini
add_action('admin_footer', function() {
    $screen = get_current_screen();
    if ( 'ntbksense_page_ntbksense-auto-post' !== $screen->id ) {
        return;
    }
?>
    <!-- Bootstrap 5 for layout -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script>
        jQuery(document).ready(function($) {
            // Logika untuk menambah dan menghapus field Kunci API
            $('#add-api-key').on('click', function(e) {
                e.preventDefault();
                const newField = `
                    <div class="input-group mt-2">
                        <input type="text" class="form-control" name="kunci_api[]">
                        <button class="btn btn-danger remove-api-key" type="button">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`;
                $('#api-keys-container').append(newField);
            });

            $(document).on('click', '.remove-api-key', function() {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
<?php
});

// Mengambil data pengguna untuk dropdown
$users = get_users(['fields' => ['ID', 'display_name']]);

// Mengambil kategori untuk dropdown
$categories = get_categories(['hide_empty' => false]);
?>
