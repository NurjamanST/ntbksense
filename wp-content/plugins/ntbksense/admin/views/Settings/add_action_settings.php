<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/add_action_settings.php
// FUNGSI: Menangani logika backend, aset, dan persiapan variabel untuk halaman pengaturan.
// =========================================================================

// Menambahkan aset (CSS/JS) ke halaman
add_action('admin_footer', function () {
    $screen = get_current_screen();
    if ('toplevel_page_ntbksense' !== $screen->id && 'ntbksense_page_ntbksense-settings' !== $screen->id) {
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
            // Menyiapkan data untuk JavaScript
            const ntbksense_data = {
                ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
                nonce: '<?php echo wp_create_nonce('ntbksense_settings_nonce'); ?>'
            };
            // =========================================================================
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

                // Fungsi untuk menampilkan notifikasi
                function showNotification(message, type) {
                    $.notify(message, {
                        className: type,
                        globalPosition: 'top center',
                        autoHideDelay: 5000
                    });
                }
    
                // Inisialisasi tab navigasi
                $(".next-tab, .prev-tab").click(function() {
                    const targetTab = $(this).data("next") || $(this).data("prev");
                    $(`button[data-bs-target="${targetTab}"]`).tab('show');
                    window.scrollTo(0, 0);
                });

                // Inisialisasi Select2 untuk dropdown negara
                $("#akses_lokasi_countries").select2({
                    placeholder: "Pilih Negara",
                    width: "100%"
                });

                // Fungsi untuk menampilkan atau menyembunyikan field berdasarkan mode akses lokasi
                function toggleConditionalFields() {
                    $("#akses_lokasi_mode").val() === 'off' ? $("#akses_lokasi_countries_wrapper").hide() : $("#akses_lokasi_countries_wrapper").show();
                    $("#akses_asn_mode").val() === 'off' ? $("#akses_asn_list_wrapper").hide() : $("#akses_asn_list_wrapper").show();
                }
                $("#akses_lokasi_mode, #akses_asn_mode").on('change', toggleConditionalFields);
                toggleConditionalFields();

                // Fungsi untuk mengunggah logo plugin
                $('#upload_logo_button').on('click', function(e) {
                    e.preventDefault();
                    $('#plugin_logo_file').click();
                });

                $('#plugin_logo_file').on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        $('#plugin_logo').val(file.name);
                    }
                });

                // Fungsi untuk menangani pengiriman form
                $("#settingsForm").submit(function(e) {
                    e.preventDefault();
                    const $submitButton = $(".btn-update-settings");
                    const originalButtonHtml = $submitButton.html();
                    $submitButton.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                    const formData = $(this).serialize();
                    
                    $.ajax({
                        url: ntbksense_data.ajaxUrl,
                        type: "POST",
                        data: formData + '&action=ntbksense_update_settings&security=' + ntbksense_data.nonce,
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                showNotification(response.data.message || "Pengaturan berhasil disimpan.", "success");
                            } else {
                                showNotification(response.data.message || "Terjadi kesalahan.", "error");
                            }
                        },
                        error: function(jqXHR) {
                            const errorMessage = jqXHR.responseJSON?.data?.message || "Koneksi gagal.";
                            showNotification(errorMessage, "error");
                        },
                        complete: function() {
                            $submitButton.prop("disabled", false).html(originalButtonHtml);
                        }
                    });
                });

                // Inisialisasi preview iklan
                $(document).on("input change", "#opacity, #kode_iklan_1, #kode_iklan_2, #kode_iklan_3, #kode_iklan_4, #kode_iklan_5, #posisi, #margin_top, #margin_bottom, #mode_tampilan, #jumlah_iklan", updatePreview);
                updatePreview();
            });
        </script>
    <?php
});

// Mengambil data dari database
global $wpdb;
$options = [];
if (is_object($wpdb)) {
    $table_name = $wpdb->prefix . 'ntbk_ads_settings';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
        $db_result = $wpdb->get_row("SELECT * FROM {$table_name} LIMIT 1", ARRAY_A);
        if (is_array($db_result)) {
            $options = $db_result;
        }
    }
}

// Menyiapkan variabel untuk form
$id_publisher = isset($options['id_publisher']) ? esc_attr($options['id_publisher']) : '';
$opacity = isset($options['opacity']) ? esc_attr($options['opacity']) : 100;
$jumlah_iklan_val = isset($options['jumlah_iklan']) ? $options['jumlah_iklan'] : 5;
$berurutan = isset($options['mode_tampilan']) && $options['mode_tampilan'] === 'Berurutan';
$acak = isset($options['mode_tampilan']) && $options['mode_tampilan'] === 'Acak';
$fixed = isset($options['posisi']) && $options['posisi'] === 'Fixed';
$absolute = isset($options['posisi']) && $options['posisi'] === 'Absolute';
$margintop = isset($options['margin_top']) ? esc_attr($options['margin_top']) : 5;
$marginbottom = isset($options['margin_bottom']) ? esc_attr($options['margin_bottom']) : 5;
$kode_iklan = [];
for ($i = 1; $i <= 5; $i++) {
    $key = 'kode_iklan_' . $i;
    $kode_iklan[] = isset($options[$key]) ? esc_textarea($options[$key]) : '';
}
$refresh = isset($options['refresh_blank']) ? esc_attr($options['refresh_blank']) : 0;
$vinyet = !empty($options['sembunyikan_vinyet']);
$anchor = !empty($options['sembunyikan_anchor']);
$elemen_blank = !empty($options['sembunyikan_elemen_blank']);
$close_ads = !empty($options['tampilkan_close_ads']);
$auto_scroll = !empty($options['auto_scroll']);

// Variabel untuk tab Advance
$off = !isset($options['akses_lokasi_mode']) || $options['akses_lokasi_mode'] === 'off';
$termasuk = isset($options['akses_lokasi_mode']) && $options['akses_lokasi_mode'] === 'termasuk';
$kecualikan = isset($options['akses_lokasi_mode']) && $options['akses_lokasi_mode'] === 'kecualikan';
$selected_countries = isset($options['akses_lokasi_countries']) ? json_decode($options['akses_lokasi_countries'], true) : [];
$countries = ["Indonesia", "Malaysia", "Singapore", "United States", "United Kingdom", "Australia", "Canada", "Germany", "France", "Japan", "South Korea", "India", "Brazil", "Mexico", "Russia", "Netherlands", "Italy", "Spain", "Sweden", "Norway", "Finland", "Denmark", "Poland", "Turkey", "South Africa", "Argentina", "Chile", "Colombia", "Peru", "Venezuela", "Philippines", "Thailand", "Vietnam", "New Zealand", "Taiwan", "Hong Kong", "United Arab Emirates", "Saudi Arabia", "Egypt", "Zimbabwe", "Nigeria", "Kenya", "Ghana", "Uganda", "Tanzania", "Rwanda", "Burundi", "Congo", "Cameroon", "Senegal", "Ivory Coast", "Mali", "Algeria", "Morocco", "Tunisia", "Libya"];

?>