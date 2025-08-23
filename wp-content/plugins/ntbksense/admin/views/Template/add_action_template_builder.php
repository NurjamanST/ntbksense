<?php
    // =========================================================================
    // FILE: ntbksense/admin/views/Template/add_action_template_builder.php
    // =========================================================================

    add_action('admin_footer', function() {
        // Periksa apakah kita berada di halaman yang benar sebelum memuat aset
        $screen = get_current_screen();
        if ( 'ntbksense_page_ntbksense-template-builder' !== $screen->id ) {
            return;
        }
    ?>
        <!-- Bootstrap 5 for styling -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- DataTables CSS & JS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <!-- Inisialisasi jQuery -->
        <script>
            jQuery(document).ready(function($) {
                const apiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/template_builder/'); ?>';
                let dataTableInstance;

                // Fungsi untuk memformat status menjadi badge Bootstrap
                function formatStatus(status) {
                    if (status === 'published') {
                        return '<span class="badge bg-success">Published</span>';
                    }
                    return '<span class="badge bg-warning text-dark">Draft</span>';
                }

                // Fungsi untuk memuat dan menampilkan data template
                function loadTemplates() {
                    const tbody = $('#ntb-templates-tbody');
                    
                    // Hancurkan instance DataTables yang ada sebelum memuat data baru
                    if (dataTableInstance) {
                        dataTableInstance.destroy();
                    }
                    tbody.html('<tr><td colspan="7" style="text-align: center;">Memuat data...</td></tr>');

                    fetch(apiUrl + 'read.php')
                        .then(response => response.json())
                        .then(data => {
                            tbody.empty();
                            if (data.success && data.data.length > 0) {
                                data.data.forEach(template => {
                                    const editUrl = `<?php echo admin_url('admin.php?page=ntbksense-edit-tb&id='); ?>${template.id}`;
                                    const row = `
                                        <tr>
                                            <td><input type="checkbox" name="template_id[]" value="${template.id}" /></td>
                                            <td><strong><a href="${editUrl}">${template.name}</a></strong></td>
                                            <td>${formatStatus(template.status)}</td>
                                            <td>${template.bootstrap_version}</td>
                                            <td>${template.created_at}</td>
                                            <td>${template.update_at}</td>
                                            <td>
                                                <a href="${editUrl}" class="button button-small">Edit</a>
                                                <button class="button button-link-delete ntb-delete-btn" data-id="${template.id}">Hapus</button>
                                            </td>
                                        </tr>
                                    `;
                                    tbody.append(row);
                                });
                            } 
                            // [PERBAIKAN] Jangan tambahkan baris "tidak ada data" secara manual.
                            // Biarkan DataTables yang menanganinya melalui konfigurasi 'zeroRecords'.
                            
                            // Inisialisasi DataTables setelah tabel diisi dengan data (atau dibiarkan kosong)
                            dataTableInstance = $('#ntb-template-builder-table').DataTable({
                                "lengthMenu": [5, 10, 25, 50, 100],
                                "language": {
                                    "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                                    "zeroRecords": "<span class='text-danger fs-1'>Tidak ada data yang ditemukan..</span><br><span style='font-size:100px;'>🤨</span>",
                                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                                    "infoEmpty": "Tidak ada data yang tersedia",
                                    "infoFiltered": "(Difilter dari _MAX_ total entri)",
                                    "search": "",
                                    "searchPlaceholder": "Cari...",
                                    "paginate": { "first": "Pertama", "last": "Terakhir", "next": "Berikutnya", "previous": "Sebelumnya" },
                                },
                                "columnDefs": [{ "targets": [0, 6], "orderable": false }] // Kolom checkbox dan aksi tidak bisa di-sort
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            tbody.html('<tr><td colspan="7" style="text-align: center;">Gagal memuat data.</td></tr>');
                        });
                }

                // --- Logika untuk 'Pilih Semua' ---
                $('#select-all-templates').on('click', function() {
                    const isChecked = $(this).prop('checked');
                    $('input[name="template_id[]"]').prop('checked', isChecked);
                });

                $(document).on('click', 'input[name="template_id[]"]', function() {
                    if ($('input[name="template_id[]"]:checked').length === $('input[name="template_id[]"]').length) {
                        $('#select-all-templates').prop('checked', true);
                    } else {
                        $('#select-all-templates').prop('checked', false);
                    }
                });
                
                // --- Logika untuk Upload Ikon ---
                $('#upload_icon_button').on('click', () => $('#template_icon_file').click());

                $('#template_icon_file').on('change', function() {
                    if (this.files && this.files[0]) {
                        $('#template_icon').val(this.files[0].name);
                    }
                });

                // --- Logika untuk 'Buat Draft Template' ---
                $('#ntb-create-draft-submit').on('click', function(e) {
                    e.preventDefault();
                    
                    const $button = $(this);
                    const originalButtonText = $button.html();
                    const createApiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/template_builder/create.php'); ?>';

                    const templateData = {
                        name: $('#template_name').val(),
                        icon: $('#template_icon').val(),
                        bootstrap_version: $('#bootstrap_version').val()
                    };

                    if (!templateData.name) {
                        alert('Nama template tidak boleh kosong.');
                        return;
                    }

                    $button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Membuat...');
                    $button.prop('disabled', true);

                    fetch(createApiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(templateData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data.id) {
                            alert(data.data.message);
                            const baseUrl = '<?php echo admin_url('admin.php'); ?>';
                            window.location.href = `${baseUrl}?page=ntbksense-edit-tb&id=${data.data.id}`;
                        } else {
                            alert('Gagal! ' + (data.data.message || 'Respons API tidak valid.'));
                            $button.html(originalButtonText).prop('disabled', false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Cek konsol untuk detail.');
                        $button.html(originalButtonText).prop('disabled', false);
                    });
                });
                
                // --- Logika untuk tombol Hapus per baris ---
                $(document).on('click', '.ntb-delete-btn', function() {
                    if (!confirm('Apakah Anda yakin ingin menghapus template ini?')) {
                        return;
                    }
                    const id = $(this).data('id');
                    fetch(apiUrl + 'delete.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ ids: [id] }) // Kirim sebagai array
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.data.message);
                            loadTemplates(); // Muat ulang data tabel setelah berhasil dihapus
                        } else {
                            alert('Gagal: ' + data.data.message);
                        }
                    });
                });

                // Panggil fungsi untuk memuat data saat halaman pertama kali dibuka
                loadTemplates();
            });
        </script>
    <?php
    });
?>
