<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/ntbksense-settings.php
// FUNGSI: Menampilkan form pengaturan global.
// VERSI DATABASE LENGKAP - DENGAN PERBAIKAN KOMPATIBILITAS
// =========================================================================

    // Menambahkan aset (CSS/JS) ke halaman
    add_action('admin_footer', function () {
        $screen = get_current_screen();
        if ('ntbksense_page_ntbksense-settings' !== $screen->id && 'toplevel_page_ntbksense-settings' !== $screen->id) {
            return;
        }
        ?>
            <!-- Aset (CSS/JS) -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <script type="text/javascript">
                const ntbksense_data = {
                    ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
                    nonce: '<?php echo wp_create_nonce('ntbksense_settings_nonce'); ?>'
                };

                jQuery(document).ready(function($) {
                    function updatePreview() {
                        const params = {
                            action: "ntbksense-landing-preview",
                            security: ntbksense_data.nonce,
                            opacity: $("#opacity").val(),
                            margin_top_ads: $("#margin_top").val(),
                            margin_bottom_ads: $("#margin_bottom").val(),
                            mode: $("#mode_tampilan").val(),
                            position: $("#posisi").val(),
                            ads1: $("#kode_iklan_1").val().trim() !== "" ? 1 : 0,
                            ads2: $("#kode_iklan_2").val().trim() !== "" ? 1 : 0,
                            ads3: $("#kode_iklan_3").val().trim() !== "" ? 1 : 0,
                            ads4: $("#kode_iklan_4").val().trim() !== "" ? 1 : 0,
                            ads5: $("#kode_iklan_5").val().trim() !== "" ? 1 : 0,
                            number_display_code: $("#jumlah_iklan").val()
                        };
                        const previewUrl = `${ntbksense_data.ajaxUrl}?${new URLSearchParams(params).toString()}`;
                        $("#ad-preview-iframe").attr("src", previewUrl);
                    }

                    function showNotification(message, type) {
                        $.notify(message, {
                            className: type,
                            globalPosition: 'top center',
                            autoHideDelay: 5000
                        });
                    }

                    $(".next-tab, .prev-tab").click(function() {
                        const targetTab = $(this).data("next") || $(this).data("prev");
                        $(`button[data-bs-target="${targetTab}"]`).tab('show');
                        window.scrollTo(0, 0);
                    });

                    $("#akses_lokasi_countries").select2({
                        placeholder: "Pilih Negara",
                        width: "100%"
                    });

                    function toggleConditionalFields() {
                        $("#akses_lokasi_mode").val() === 'off' ? $("#akses_lokasi_countries_wrapper").hide() : $("#akses_lokasi_countries_wrapper").show();
                        $("#akses_asn_mode").val() === 'off' ? $("#akses_asn_list_wrapper").hide() : $("#akses_asn_list_wrapper").show();
                    }
                    $("#akses_lokasi_mode, #akses_asn_mode").on('change', toggleConditionalFields);
                    toggleConditionalFields();

                    $("#settingsForm").submit(function(e) {
                        e.preventDefault();
                        const $submitButton = $(".btn-update-settings");
                        const originalButtonHtml = $submitButton.html();
                        $submitButton.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                        const formData = $(this).serialize();
                        const checkboxData = {
                            security: ntbksense_data.nonce,
                            sembunyikan_vinyet: $("#sembunyikan_vinyet").is(":checked") ? 1 : 0,
                            sembunyikan_anchor: $("#sembunyikan_anchor").is(":checked") ? 1 : 0,
                            sembunyikan_elemen_blank: $("#sembunyikan_elemen_blank").is(":checked") ? 1 : 0,
                            tampilkan_close_ads: $("#tampilkan_close_ads").is(":checked") ? 1 : 0,
                            auto_scroll: $("#auto_scroll").is(":checked") ? 1 : 0,
                            detektor_perangkat_tinggi: $("#detektor_perangkat_tinggi").is(":checked") ? 1 : 0,
                            detektor_perangkat_sedang: $("#detektor_perangkat_sedang").is(":checked") ? 1 : 0,
                            detektor_perangkat_rendah: $("#detektor_perangkat_rendah").is(":checked") ? 1 : 0,
                        };
                        const finalData = formData + "&" + $.param(checkboxData);

                        $.ajax({
                            url: '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/setting/ads.setting.php'); ?>',
                            type: "POST",
                            data: finalData,
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    showNotification(response.message || "Pengaturan berhasil disimpan.", "success");
                                } else {
                                    showNotification(response.message || "Terjadi kesalahan.", "error");
                                }
                            },
                            error: function(jqXHR) {
                                const errorMessage = jqXHR.responseJSON?.message || "Koneksi gagal.";
                                showNotification(errorMessage, "error");
                            },
                            complete: function() {
                                $submitButton.prop("disabled", false).html(originalButtonHtml);
                            }
                        });
                    });

                    $(document).on("input change", "#opacity, #kode_iklan_1, #kode_iklan_2, #kode_iklan_3, #kode_iklan_4, #kode_iklan_5, #posisi, #margin_top, #margin_bottom, #mode_tampilan, #jumlah_iklan", updatePreview);
                    updatePreview();
                });
            </script>
        <?php
    });

    // [INI BAGIAN "READ"-NYA - VERSI AMAN]
    global $wpdb;
    $options = [];
    if (is_object($wpdb)) {
        $table_name = $wpdb->prefix . 'ntbk_ads_settings';
        $db_result = $wpdb->get_row("SELECT * FROM {$table_name} LIMIT 1", ARRAY_A);
        if (is_array($db_result)) {
            $options = $db_result;
        }
    }

    // Daftar negara untuk akses lokasi
    $countries = ["Indonesia", "Malaysia", "Singapore", "United States", "United Kingdom", "Australia"];

    // Menampilkan form pengaturan
    $id_publisher = isset($options['id_publisher']) ? esc_attr($options['id_publisher']) : '';
    $opacity = isset($options['opacity']) ? esc_attr($options['opacity']) : 100;
    $jumlah_iklan_val = isset($options['jumlah_iklan']) ? $options['jumlah_iklan'] : 5;

    
?>