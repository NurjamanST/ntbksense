<?php
    // =========================================================================
    // FILE: ntbksense/admin/views/Template/ntbksense-template-builder.php
    // =========================================================================

    /**
     * Tampilan untuk halaman admin Template Builder.
     * Menggunakan DataTables.js untuk menampilkan daftar template.
     */

    // Menambahkan aset dan skrip DataTables ke footer halaman admin.
    add_action('admin_footer', function() {
        // Periksa apakah kita berada di halaman yang benar sebelum memuat aset
        $screen = get_current_screen();
        // Periksa apakah ini adalah halaman Template Builder
        if ( 'ntbksense_page_ntbksense-template-builder' !== $screen->id ) {
            return;
        }
    ?>
        <!-- Bootstrap 5 for styling -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- DataTables CSS & JS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <!-- Inisialisasi jQuery -->
        <script>
            jQuery(document).ready(function($) {
                $('#ntb-template-builder-table').DataTable({
                    "lengthMenu": [5, 10, 25, 50, 100],
                    "language": {
                        "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                        "zeroRecords": "<div class='text-center py-5'><span class='dashicons dashicons-warning text-danger' style='font-size: 3rem; width: auto; height: auto;'></span><p class='mt-3 mb-0 text-danger fw-bold'>Tidak ada data yang tersedia</p></div>",
                        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                        "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                        "infoFiltered": "(Difilter dari _MAX_ total entri)",
                        "search": "",
                        "searchPlaceholder": "Cari...",
                        "paginate": {
                            "first":    "Pertama",
                            "last":     "Terakhir",
                            "next":     "&raquo;",
                            "previous": "&laquo;"
                        },
                    },
                    "columnDefs": [ {
                        "targets": 0, // Target the first column (checkbox)
                        "orderable": false
                    } ]
                });
                // Fungsionalitas untuk checkbox "Pilih Semua"
                $('#select-all-templates').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    $('#ntb-template-builder-table tbody input[type="checkbox"]').prop('checked', isChecked);
                });

                // **NEW:** Logika untuk tombol upload ikon di modal
                $('#upload_icon_button').on('click', function(e) {
                    e.preventDefault();
                    $('#template_icon_file').click();
                });

                $('#template_icon_file').on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        $('#template_icon').val(file.name);
                    }
                });
            });
        </script>
    <?php
    });

    // --- START: Data Dummy untuk Template ---
    $template_data = [];
    $template_name = ['Template Kustom', 'Template Baru', 'Template Default', 'Template Premium', 'Template Spesial'];
    $statuses = ['<span class="badge bg-success">Aktif</span>', '<span class="badge bg-secondary">Tidak Aktif</span>'];
    $bs_versions = ['5.3.1', '5.2.0', '4.6.0'];

    for ($i = 1; $i <= 100; $i++) {
        $created_timestamp = time() - rand(0, 30 * 24 * 60 * 60); // Tanggal acak dalam 30 hari terakhir
        $updated_timestamp = $created_timestamp + rand(0, 5 * 24 * 60 * 60); // Update acak dalam 5 hari setelah dibuat
        $template_data[] = [
            'id'                => $i,
            'name'              => $template_name[array_rand($template_name)] . " " . $i,
            'status'            => $statuses[array_rand($statuses)],
            'bootstrap_version' => $bs_versions[array_rand($bs_versions)],
            'created_date'      => date('Y/m/d H:i', $created_timestamp),
            'updated_date'      => date('Y/m/d H:i', $updated_timestamp),
        ];
    }
    // --- END: Data Dummy ---
?>