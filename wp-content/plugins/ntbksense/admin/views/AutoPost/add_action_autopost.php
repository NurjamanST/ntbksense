<?php
// =========================================================================
// FILE: ntbksense/admin/views/AutoPost/add_action_autopost.php
// FUNGSI: Menangani SEMUA logika frontend untuk halaman Auto Post.
// VERSI: Dirombak untuk proses real-time.
// =========================================================================

// Menambahkan aset khusus untuk halaman ini
add_action('admin_footer', function () {
    $screen = get_current_screen();
    if ('ntbksense_page_ntbksense-auto-post' !== $screen->id) {
        return;
    }
?>
    <!-- Aset (CSS/JS) dari CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>

    <script type="text/javascript">
        // [PERBAIKAN] Ganti 'const' menjadi 'var' untuk menghindari konflik
        var ntbksense_data = {
            pluginUrl: '<?php echo esc_url(NTBKSENSE_PLUGIN_URL); ?>', // Pastikan konstanta ini ada
            nonce: '<?php echo wp_create_nonce('ntbksense_autopost_nonce'); ?>'
        };

        jQuery(document).ready(function($) {

            // =========================================================================
            // FUNGSI-FUNGSI BANTU
            // =========================================================================

            function showNotification(message, type) {
                $.notify(message, {
                    className: type,
                    globalPosition: 'top center',
                    autoHideDelay: 5000
                });
            }

            // [PERBAIKAN] Fungsi log baru yang terinspirasi dari kode lo
            function appendLog(message, type = 'info') {
                // [PERBAIKAN UTAMA] Sesuaikan ID dengan HTML lo
                const $logContainer = $('#log-output');
                if (!$logContainer.length) return;

                // Jika ini pesan pertama, siapkan kontainer ul
                if ($logContainer.find('ul.list-group').length === 0) {
                    $logContainer.html('<ul class="list-group" style="max-height: 300px; overflow-y: auto;"></ul>');
                }

                const $ul = $logContainer.find('ul.list-group');

                const itemClass = {
                    success: 'list-group-item-success',
                    error: 'list-group-item-danger',
                    info: 'list-group-item-info'
                } [type];

                const logEntry = `<li class="list-group-item ${itemClass}">${message}</li>`;
                $ul.append(logEntry);
                // Auto-scroll ke log terbaru
                $ul.scrollTop($ul[0].scrollHeight);
            }

            function updateSliderLabel(sliderId, labelId, textTemplate) {
                const slider = $('#' + sliderId);
                const label = $('#' + labelId);
                if (!slider.length || !label.length) return;

                function updateText() {
                    const value = slider.val();
                    const newText = textTemplate.replace('{value}', value);
                    label.text(newText);
                }
                slider.on('input', updateText);
                updateText();
            }

            // =========================================================================
            // INISIALISASI & EVENT HANDLER UI
            // =========================================================================

            updateSliderLabel('multi_thread', 'multi_thread_label', 'Multi-thread ({value} thread)');
            updateSliderLabel('upaya_percobaan', 'upaya_percobaan_label', 'Upaya Percobaan ({value}x Upaya Percobaan)');
            updateSliderLabel('delay', 'delay_label', 'Delay ({value} second)');

            $('#add-api-key-link').on('click', function(e) {
                e.preventDefault();
                const newField = `
                    <div class="input-group mt-2">
                        <input type="text" class="form-control" name="kunci_api[]" placeholder="Kunci API">
                        <button class="btn btn-danger remove-api-key" type="button"><i class="fas fa-trash"></i></button>
                    </div>`;
                $('#api-keys-container').append(newField);
            });

            $(document).on('click', '.remove-api-key', function() {
                $(this).closest('.input-group').remove();
            });

            // Logika untuk modal "Generate Keywords"
            $('#generateKeywordsModal').on('show.bs.modal', function() {
                const $apiKeySelector = $('#api_key_selector');
                $apiKeySelector.empty();
                const $apiKeys = $('input[name="kunci_api[]"]');
                if ($apiKeys.length === 0 || $apiKeys.first().val().trim() === '') {
                    $apiKeySelector.append($('<option>', {
                        value: '',
                        text: 'Harap isi Kunci API di halaman utama!'
                    }));
                    $apiKeySelector.prop('disabled', true);
                    $('#generate-keywords-submit').prop('disabled', true);
                    return;
                }
                $apiKeySelector.prop('disabled', false);
                $('#generate-keywords-submit').prop('disabled', false);
                $apiKeys.each(function() {
                    const apiKey = $(this).val().trim();
                    if (apiKey) {
                        const shortKey = apiKey.substring(0, 4) + '...' + apiKey.substring(apiKey.length - 4);
                        $apiKeySelector.append($('<option>', {
                            value: apiKey,
                            text: `Gunakan: ${shortKey}`
                        }));
                    }
                });
            });

            $(document).on('click', '#generate-keywords-submit', function() {
                const total = $('#total_keyword').val();
                const keyword = $('#base_keyword').val();
                const apiKey = $('#api_key_selector').val();
                const modalElement = document.getElementById('generateKeywordsModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                const submitButton = $(this);

                if (!keyword || !apiKey) {
                    showNotification('Harap pilih Kunci API dan masukkan Kata Kunci Dasar.', 'error');
                    return;
                }

                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Generating...');

                $.ajax({
                        url: ntbksense_data.pluginUrl + 'api/autoPost/keyword.auto.php',
                        type: 'POST',
                        data: {
                            security: ntbksense_data.nonce,
                            api_key: apiKey,
                            base_keyword: keyword,
                            total_keyword: total
                        },
                        dataType: 'json'
                    })
                    .done(function(response) {
                        if (response.success && response.data.keywords) {
                            showNotification(response.data.message, 'success');
                            $('#kata_kunci').val(response.data.keywords);
                            modal.hide();
                        } else {
                            showNotification(response.data.message || 'Gagal membuat kata kunci.', 'error');
                        }
                    })
                    .fail(function() {
                        showNotification('Koneksi ke server gagal saat membuat kata kunci.', 'error');
                    })
                    .always(function() {
                        submitButton.prop('disabled', false).html('<i class="fas fa-check"></i> Submit');
                    });
            });
            // =========================================================================
            // HANDLER PENGIRIMAN FORM UTAMA
            // =========================================================================
            $('#autoPostForm').submit(function(e) {
                e.preventDefault();
                const $form = $(this);
                const $submitButton = $form.find('#btn-post');
                const originalButtonHtml = $submitButton.html();
                const $logContainer = $('#autopost-logs');

                // 1. Ambil semua kata kunci dan pecah jadi array
                const keywords = $('#kata_kunci').val().trim().split('\n').filter(k => k.trim() !== '');

                // Validasi awal
                const apiKey = $('input[name="kunci_api[]"]').val().trim();
                if (!apiKey || keywords.length === 0) {
                    showNotification('Kunci API dan Kata Kunci tidak boleh kosong.', 'error');
                    return;
                }

                $submitButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
                // Siapkan kontainer log
                $logContainer.html('<ul class="list-group" style="max-height: 300px; overflow-y: auto;"></ul>');

                let successCount = 0;
                let currentIndex = 0;

                // 2. Buat fungsi untuk memproses satu kata kunci
                function processNextKeyword() {
                    // Jika semua kata kunci sudah diproses, hentikan
                    if (currentIndex >= keywords.length) {
                        const finalMessage = `Proses selesai. ${successCount} dari ${keywords.length} artikel berhasil dibuat.`;
                        showNotification(finalMessage, 'success');
                        appendLog(`<strong>${finalMessage}</strong>`, 'info');
                        $submitButton.prop('disabled', false).html(originalButtonHtml);
                        return;
                    }

                    const currentKeyword = keywords[currentIndex];
                    appendLog(`Memproses: "${currentKeyword}"...`, 'info');

                    // 3. Ambil semua data form, tapi ganti kata kuncinya
                    let formData = $form.serializeArray();
                    formData = formData.filter(item => item.name !== 'kata_kunci');
                    formData.push({
                        name: 'kata_kunci',
                        value: currentKeyword
                    });
                    formData.push({
                        name: 'security',
                        value: ntbksense_data.nonce
                    });

                    // 4. Kirim AJAX untuk SATU kata kunci
                    $.ajax({
                            url: ntbksense_data.pluginUrl + 'api/autoPost/index.auto.php',
                            type: 'POST',
                            data: $.param(formData),
                            dataType: 'json'
                        })
                        .done(function(response) {
                            if (response.success) {
                                successCount++;
                                appendLog(response.data.log, 'success');
                            } else {
                                appendLog(response.data.log, 'error');
                            }
                        })
                        .fail(function(jqXHR) {
                            const errorMsg = jqXHR.responseJSON?.data?.log || `Gagal memproses "${currentKeyword}".`;
                            appendLog(errorMsg, 'error');
                        })
                        .always(function() {
                            // 5. Panggil fungsi lagi untuk kata kunci berikutnya
                            currentIndex++;
                            const delay = parseInt($('#delay').val(), 10) * 1000;
                            setTimeout(processNextKeyword, delay);
                        });
                }

                // Mulai proses dari kata kunci pertama
                processNextKeyword();
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