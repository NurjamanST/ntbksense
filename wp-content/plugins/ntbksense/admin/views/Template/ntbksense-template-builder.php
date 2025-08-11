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
<div class="wrap" id="ntbksense-template-builder-wrapper">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR."admin/views/Layout/navbar.php"; ?>

    <!-- START: Breadcrumb Kustom -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Template Builder</span>
    </div>
    <!-- END: Breadcrumb Kustom -->
    
    <div class="ntb-main-content">
        <h2 class="ntb-main-title"><span class="dashicons dashicons-layout"></span> Template Builder</h2>
        <hr>
        <!-- Tombol Aksi Utama -->
        <div class="ntb-actions-bar">
           <!-- **MODIFIED:** Changed to button and added Bootstrap modal attributes -->
            <button type="button" class="button ntb-btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                <span class="dashicons dashicons-plus-alt"></span> Template Baru
            </button>
        </div>

        <!-- Tabel HTML Standar untuk DataTables -->
        <table id="ntb-template-builder-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-templates" /></th>
                    <th>Template</th>
                    <th>Status</th>
                    <th>Versi Bootstrap</th>
                    <th>Tanggal dibuat</th>
                    <th>Tanggal update</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($template_data)) : ?>
                    <?php foreach ($template_data as $item) : ?>
                        <tr>
                            <td><input type="checkbox" name="template_id[]" value="<?php echo esc_attr($item['id']); ?>" /></td>
                            <td>
                                <strong><a style="text-decoration:none;" href="#"><?php echo esc_html($item['name']); ?></a></strong>
                                <div class="row-actions">
                                    <a style="text-decoration:none;" href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-edit-tb&id=' . $item['id'])); ?>" class="text-primary">
                                        <span class="dashicons dashicons-edit"></span>
                                    </a>
                                    <!-- <a style="text-decoration:none;" href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-duplicate-tb&id=' . $item['id'])); ?>" class="text-secondary">
                                        <span class="dashicons dashicons-admin-page"></span>
                                    </a> -->
                                    <a style="text-decoration:none;" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=ntbksense-template_builder&action=delete&id=' . $item['id']), 'ntb_delete_lp_nonce')); ?>" class="text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus Template Builder ini?');">
                                        <span class="dashicons dashicons-trash"></span>
                                    </a>
                                </div>
                            </td>
                            <td><?php echo $item['status']; // Badge HTML, tidak perlu di-escape ?></td>
                            <td><?php echo esc_html($item['bootstrap_version']); ?></td>
                            <td><?php echo esc_html($item['created_date']); ?></td>
                            <td><?php echo esc_html($item['updated_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Modal untuk Tambah Template Baru -->
    <!-- **NEW:** Modal for Creating a New Template -->
    <div class="modal fade" id="createTemplateModal" tabindex="-1" aria-labelledby="createTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTemplateModalLabel">Buat Draft Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-template-form">
                        <div class="mb-3">
                            <label for="template_name" class="form-label">Nama Template</label>
                            <input type="text" class="form-control" id="template_name" name="template_name" placeholder="Xnxx New">
                        </div>
                        <div class="mb-3">
                            <label for="template_icon" class="form-label">Ikon Template</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="template_icon" name="template_icon" placeholder="https://example.com/icon.png">
                                <!-- **NEW:** Hidden file input -->
                                <input type="file" id="template_icon_file" name="template_icon_file" style="display: none;" accept="image/*">
                                <button class="btn btn-primary" type="button" id="upload_icon_button">
                                    <span class="dashicons dashicons-cloud-upload"></span>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="bootstrap_version" class="form-label">Versi Bootstrap</label>
                            <select class="form-select" id="bootstrap_version" name="bootstrap_version">
                                <option selected>5.3.1</option>
                                <option>5.2.0</option>
                                <option>4.6.0</option>
                                <option>Tidak menggunakan</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">
                        <span class="dashicons dashicons-arrow-right-alt2" style="vertical-align: middle; margin-top: -2px;"></span> Buat Draft
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* General Layout & Main Content Box */
    #ntbksense-template-builder-wrapper {
        background-color: #f0f0f1;
        padding: 0;
        margin: 0px 0px 0px -20px; /* Override default .wrap margin */
    }
    .ntb-main-content {
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 20px;
        margin: 20px;
    }
    .ntb-main-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Navbar Styling */
    .ntb-navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #ffffff;
        border-bottom: 1px solid #c3c4c7;
    }
    .ntb-navbar-title { font-size: 16px; font-weight: 600; color: #1d2327; }
    .ntb-navbar-version { font-size: 12px; color: #646970; margin-left: 8px; background-color: #f0f0f1; padding: 2px 6px; border-radius: 4px; }
    .ntb-navbar-right .ntb-navbar-icon { color: #50575e; text-decoration: none; margin-left: 15px; }
    .ntb-navbar-right .ntb-navbar-icon .dashicons { font-size: 20px; vertical-align: middle; }

    /* Breadcrumb Styling */
    .ntb-breadcrumb { padding: 15px 20px; color: #50575e; font-size: 14px; background-color: #fff; border-bottom: 1px solid #e0e0e0; }
    .ntb-breadcrumb a { text-decoration: none; color: #0073aa; }
    .ntb-breadcrumb a:hover { text-decoration: underline; }
    .ntb-breadcrumb span { color: #1d2327; font-weight: 600; }

    /* Action Buttons Bar */
        .ntb-actions-bar { margin-bottom: 20px; }
        .ntb-actions-bar .button { margin-right: 5px; display: inline-flex; align-items: center; gap: 5px; }
        .ntb-actions-bar .button .dashicons { font-size: 18px; line-height: 1; }
        .ntb-btn-primary { background: #007bff !important; border-color: #007bff !important; color: white !important; }

    /* DataTables Styling to match WordPress UI */
        .dataTables_wrapper {
            margin-top: 15px;
        }
        .dataTables_length, .dataTables_filter {
            margin-bottom: 15px;
        }
        .dataTables_filter label {
            font-weight: 400;
            color: #3c434a;
        }
        /* START: Styles for sizing up the length menu */
        .dataTables_length {
            position: relative; /* Add this to allow z-index to work */
            z-index: 10;      /* Add this to bring the dropdown to the front */
        }
        .dataTables_length label {
            font-size: 14px; /* Increase font size of the label */
            font-weight: 400;
            color: #3c434a;
            display: inline-flex;
            align-items: center;
        }
        .dataTables_length select {
            padding: 8px 12px; 
            font-size: 14px; 
            height: auto; /* Atur tinggi sesuai keinginan, misalnya 50px */
            width: 5vw; /* Atur lebar sesuai keinginan, misalnya 100px */
            margin: 0 10px; 
            border-radius: 4px;
            border-color: #8c8f94;
        }
        /* END: Styles for sizing up the length menu */

        .dataTables_filter input[type="search",placeholder="searchPlaceholder"] {
            border-radius: 3px;
            border-color: #8c8f94;
        }
        .dataTables_paginate .paginate_button {
            padding: .25em .65em;
            margin-left: 2px;
            border: 1px solid #c3c4c7;
            border-radius: 3px;
            background: #f6f7f7;
            color: #0071a1 !important;
            text-decoration: none;
        }
        .dataTables_paginate .paginate_button.current,
        .dataTables_paginate .paginate_button.current:hover {
            background: #f0f0f1;
            border-color: #c3c4c7;
            color: #3c434a !important;
        }
        .dataTables_paginate .paginate_button:hover {
            background: #f0f0f1;
            border-color: #0071a1;
            color: #0071a1 !important;
        }
        .dataTables_paginate .paginate_button.disabled,
        .dataTables_paginate .paginate_button.disabled:hover {
            color: #a0a5aa !important;
            background: #f6f7f7;
            border-color: #e2e4e7;
        }


    /* Table Styling */
        #ntb-template-builder-table {
            border-collapse: collapse !important;
            border: 1px solid #c3c4c7;
        }
        #ntb-template-builder-table th, #ntb-template-builder-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e4e7;
        }
        #ntb-template-builder-table thead th {
            background-color: #f6f7f7;
            font-weight: 600;
            white-space: nowrap;
        }
        .dataTables_empty {
            padding: 20px !important;
        }
        .row-actions {
            visibility: hidden;
            font-size: 13px;
            color: #aaa;
        }
        tr:hover .row-actions {
            visibility: visible;
        }
        .badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 7px;
            border-radius: 10px;
        }
</style>