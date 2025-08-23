<?php
// =========================================================================
// FILE: ntbksense/admin/views/PrivacyPolicy/add_action_privacy_policy.php
// FUNGSI: Menangani logika frontend untuk fitur Generate Semua Halaman.
// =========================================================================

// Menambahkan aset khusus untuk halaman ini
add_action('admin_footer', function () {
    $screen = get_current_screen();
    // Sesuaikan slug halaman ini jika perlu
    if ('ntbksense_page_ntbksense-privacy-policy' !== $screen->id) {
        return;
    }
?>
    <!-- Aset (CSS/JS) dari CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>

    <script type="text/javascript">
        var ntbksense_page_gen_data = {
            pluginUrl: '<?php echo esc_url(NTBKSENSE_PLUGIN_URL); ?>',
            nonce: '<?php echo wp_create_nonce("ntbksense_page_gen_nonce"); ?>'
        };

        jQuery(document).ready(function($) {
            function showNotification(message, type) {
                if ($.notify) {
                    $.notify(message, {
                        className: type,
                        globalPosition: 'top center',
                        autoHideDelay: 5000
                    });
                } else {
                    alert(message);
                }
            }

            // Handler untuk tombol Generate Semua Halaman
            $('#generate-privacy-btn').on('click', function() {
                const $button = $(this);
                const originalButtonHtml = $button.html();
                const $logContainer = $('#page-gen-logs');

                // [PERBAIKAN] API key di-set langsung di sini.
                // GANTI TULISAN INI DENGAN API KEY LO YANG ASLI.
                const apiKey = 'AIzaSyC1uA1tWZyGec0TVnC5gm_nofIfKSSwZ7E';

                if (!apiKey) {
                    showNotification('Harap set Kunci API di dalam file add_action_privacy_policy.php.', 'error');
                    return;
                }

                $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
                $logContainer.html('<p>Memulai proses... Ini mungkin memakan waktu beberapa menit.</p><ul class="list-group"></ul>');

                $.ajax({
                        url: ntbksense_page_gen_data.pluginUrl + 'api/page-generator/create-single-page.php',
                        type: 'POST',
                        data: {
                            security: ntbksense_page_gen_data.nonce,
                            api_key: apiKey
                        },
                        dataType: 'json'
                    })
                    .done(function(response) {
                        if (response.success) {
                            showNotification(response.data.message, 'success');
                            const logs = response.data.logs;
                            let logHtml = '';
                            logs.forEach(log => {
                                const itemClass = log.includes("Sukses") ? 'list-group-item-success' : 'list-group-item-warning';
                                logHtml += `<li class="list-group-item ${itemClass}">${log}</li>`;
                            });
                            $logContainer.find('ul').html(logHtml);
                            setTimeout(() => location.reload(), 3000);
                        } else {
                            showNotification(response.data.message || 'Terjadi kesalahan.', 'error');
                        }
                    })
                    .fail(function() {
                        showNotification('Koneksi ke server gagal.', 'error');
                    })
                    .always(function() {
                        $button.prop('disabled', false).html('<i class="fas fa-bolt"></i> Generate Privacy Policy');
                    });
            });

            // Handler untuk tombol Reset
            $('#reset-privacy-btn').on('click', function() {
                const $button = $(this);
                const originalButtonHtml = $button.html();

                if (!confirm('Anda yakin ingin menghapus halaman Privacy Policy, Disclaimer, dan Terms & Conditions? Aksi ini tidak bisa dibatalkan.')) {
                    return;
                }

                $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menghapus...');

                $.ajax({
                        url: ntbksense_page_gen_data.pluginUrl + 'api/page-generator/delete-single-page.php',
                        type: 'POST',
                        data: {
                            security: ntbksense_page_gen_data.nonce,
                        },
                        dataType: 'json'
                    })
                    .done(function(response) {
                        if (response.success) {
                            showNotification(response.data.message, 'success');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showNotification(response.data.message || 'Gagal menghapus halaman.', 'error');
                        }
                    })
                    .fail(function() {
                        showNotification('Koneksi ke server gagal.', 'error');
                    })
                    .always(function() {
                        $button.prop('disabled', false).html(originalButtonHtml);
                    });
            });
        });
    </script>
<?php
});
?>