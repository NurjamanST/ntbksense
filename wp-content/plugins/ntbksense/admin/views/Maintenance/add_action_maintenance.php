<?php
// =========================================================================
// FILE: ntbksense/admin/views/Maintenance/add_action_maintenance.php
// FUNGSI: Menangani logika backend, aset, dan persiapan variabel untuk halaman maintenance.
// =========================================================================

add_action("admin_footer", function () {
    $screen = get_current_screen();
    if ("ntbksense_page_ntbksense-maintenance" !== $screen->id) {
        return;
    }
    ?>
    <!-- Bootstrap 5 CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.33.0/min/vs/loader.min.js"></script>

    <script>
    jQuery(document).ready(function($) {
        // Nonce for AJAX security
        var maintNonce = '<?php echo wp_create_nonce(
            "ntbksense_maintenance_nonce",
        ); ?>';
          
        require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.33.0/min/vs' }});
        // Initialize DataTable for Pemeliharaan Tabel (Maintenance Tables list)
        var maintTable = $('#ntb-maintenance-table').DataTable({
            ajax: {
                url: ajaxurl,
                type: 'POST',
                data: function() {
                  return { action: 'ntbksense_maint_tables', security: maintNonce };
                },
                dataSrc: function(json) {
                  if (!json.success || !json.data) return [];
                  return json.data.tables || [];
                }
            },
            columns: [
                { data: 'name' },
                { data: 'engine' },
                { data: 'size' },
                { data: 'overhead' },
                { data: 'rows' }
            ],
            pageLength: 20,
            searching: false,
            info: false,
            lengthChange: false
        });

        function showLoadingIndicator(button) {
            $(button).prop('disabled', true).css('cursor', 'not-allowed').text('Loading...');
        }

        function hideLoadingIndicator(button, originalText) {
            $(button).prop('disabled', false).css('cursor', 'pointer').text(originalText);
        }

        function handleAjaxRequest(action, button, confirmMessage, successMessage, errorMessage) {
            if (!confirm(confirmMessage)) return;

            showLoadingIndicator(button);
            $.post(ajaxurl, { action: action, security: maintNonce }, function(resp) {
                hideLoadingIndicator(button, $(button).data('original-text')); // Reset button state

                if (resp.success) {
                    alert(successMessage || 'Operation completed successfully.');
                    maintTable.ajax.reload();
                } else {
                    alert(errorMessage || 'Operation failed.');
                }
            });
        }

        // Button: Atur Ulang Log Trafik
        $('#ntb-log-trafik').on('click', function(e) {
            e.preventDefault();
            $(this).data('original-text', $(this).text()); // Store original button text
            handleAjaxRequest('ntbksense_maint_reset_traffic', this, 'Yakin ingin mereset log trafik?', 'Log trafik berhasil direset.', 'Gagal mereset log trafik.');
        });

        // Button: Hapus Revisi Artikel
        $('#ntb-article-revision').on('click', function(e) {
            e.preventDefault();
            $(this).data('original-text', $(this).text());
            handleAjaxRequest('ntbksense_maint_del_revisions', this, 'Yakin ingin menghapus semua revisi artikel?', 'Hapus revisi artikel selesai.', 'Gagal menghapus revisi artikel.');
        });

        // Button: Optimalkan Database
        $('#ntb-optimize-database').on('click', function(e) {
            e.preventDefault();
            $(this).data('original-text', $(this).text());
            handleAjaxRequest('ntbksense_maint_optimize', this, 'Yakin ingin mengoptimalkan database?', 'OPTIMIZE database selesai.', 'Gagal mengoptimalkan database.');
        });

        // Form: Tes GEO IP
        $('form[action="#ntb-geo-ip-results"]').on('submit', function(e) {
            e.preventDefault();
            var ip = $('#ip_address').val().trim();
            var ua = $('#user_agent').val().trim();
            if (!ip) {
                alert('Mohon isi IP address.');
                return;
            }
            showLoadingIndicator($(this).find('button[type="submit"]'));
            $.post(ajaxurl, { action: 'ntbksense_geoip_lookup', security: maintNonce, ip: ip, ua: ua }, function(resp) {
                hideLoadingIndicator($(this).find('button[type="submit"]'), 'Tes Sekarang'); // Reset button state

                if (resp.success) {
                    var geoInfo = resp.data.geo;
                    var userAgent = resp.data.ua;
                    // Tampilkan hasil ke dalam container hasil

                    require(['vs/editor/editor.main'], function() {
                        // Inisialisasi Editor untuk Database GEO
                        monaco.editor.create(document.getElementById('geo-editor-container'), {
                            value: JSON.stringify(geoInfo, null, 4), // Menggunakan data contoh baru
                            language: 'json',
                            theme: 'vs-dark',
                            readOnly: true,
                            automaticLayout: true,
                            minimap: { enabled: false }
                        });

                        // Inisialisasi Editor untuk Info Perangkat
                        // monaco.editor.create(document.getElementById('device-editor-container'), {
                        //     value: JSON.stringify(deviceInfoData, null, 4),
                        //     language: 'json',
                        //     theme: 'vs-dark',
                        //     readOnly: true,
                        //     automaticLayout: true,
                        //     minimap: { enabled: false }
                        // });
                    });
                    // Scroll ke bagian hasil
                    document.getElementById('ntb-geo-ip-results').scrollIntoView();
                } else {
                    alert(resp.data.message || 'Tes GEO IP gagal.');
                }
            }.bind(this)); // Bind the context to maintain 'this'
        });
    });
    </script>
<?php
});

