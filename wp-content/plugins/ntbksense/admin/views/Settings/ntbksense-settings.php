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

$countries = ["Indonesia", "Malaysia", "Singapore", "United States", "United Kingdom", "Australia"];
?>

<div class="wrap" id="ntbksense-settings-wrapper">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Settings</span>
    </div>

    <div class="ntb-main-content">
        <form id="settingsForm" method="post">
            <div class="row">
                <!-- Kolom Kiri: Pengaturan -->
                <div class="col-lg-8">
                    <ul class="nav nav-tabs ntb-nav-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="pengaturan-iklan-tab" data-bs-toggle="tab" data-bs-target="#pengaturan-iklan" type="button" role="tab">Pengaturan Iklan</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="advance-tab" data-bs-toggle="tab" data-bs-target="#advance" type="button" role="tab">Advance</button></li>
                    </ul>

                    <div class="tab-content ntb-tab-content" id="settingsTabsContent">
                        <!-- Tab: Pengaturan Iklan -->
                        <div class="tab-pane fade show active" id="pengaturan-iklan" role="tabpanel">
                            <div class="ntb-settings-section">
                                <div class="mb-3">
                                    <label for="id_publisher" class="form-label">ID Publisher</label>
                                    <input type="text" id="id_publisher" name="id_publisher" class="form-control" value="<?php echo esc_attr(isset($options['id_publisher']) ? $options['id_publisher'] : ''); ?>">
                                </div>
                            </div>
                            <div class="ntb-settings-section">
                                <h5 class="section-title">Iklan Display</h5>
                                <div class="row">
                                    <div class="col-md-6"><label for="opacity" class="form-label">Opacity (%)</label><input type="number" id="opacity" name="opacity" class="form-control" value="<?php echo esc_attr(isset($options['opacity']) ? $options['opacity'] : '100'); ?>"></div>
                                    <div class="col-md-6"><label for="jumlah_iklan" class="form-label">Jumlah Iklan</label><select id="jumlah_iklan" name="jumlah_iklan" class="form-select"><?php $jumlah_iklan_val = isset($options['jumlah_iklan']) ? $options['jumlah_iklan'] : 5;
                                                                                                                                                                                                for ($i = 1; $i <= 5; $i++) {
                                                                                                                                                                                                    echo '<option value="' . $i . '" ' . selected($jumlah_iklan_val, $i, false) . '>' . $i . '</option>';
                                                                                                                                                                                                } ?></select></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3"><label for="mode_tampilan" class="form-label">Mode Tampilan</label><select id="mode_tampilan" name="mode_tampilan" class="form-select">
                                            <option <?php selected(isset($options['mode_tampilan']) ? $options['mode_tampilan'] : 'Berurutan', 'Berurutan'); ?>>Berurutan</option>
                                            <option <?php selected(isset($options['mode_tampilan']) ? $options['mode_tampilan'] : '', 'Acak'); ?>>Acak</option>
                                        </select></div>
                                    <div class="col-md-3"><label for="posisi" class="form-label">Posisi</label><select id="posisi" name="posisi" class="form-select">
                                            <option <?php selected(isset($options['posisi']) ? $options['posisi'] : 'Fixed', 'Fixed'); ?>>Fixed</option>
                                            <option <?php selected(isset($options['posisi']) ? $options['posisi'] : '', 'Absolute'); ?>>Absolute</option>
                                        </select></div>
                                    <div class="col-md-3"><label for="margin_top" class="form-label">Margin Top</label>
                                        <div class="input-group"><input type="number" id="margin_top" name="margin_top" class="form-control" value="<?php echo esc_attr(isset($options['margin_top']) ? $options['margin_top'] : '5'); ?>"><span class="input-group-text">px</span></div>
                                    </div>
                                    <div class="col-md-3"><label for="margin_bottom" class="form-label">Margin Bottom</label>
                                        <div class="input-group"><input type="number" id="margin_bottom" name="margin_bottom" class="form-control" value="<?php echo esc_attr(isset($options['margin_bottom']) ? $options['margin_bottom'] : '5'); ?>"><span class="input-group-text">px</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="ntb-settings-section">
                                <h5 class="section-title">Kode Iklan Display</h5>
                                <div class="row">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <div class="col-md-6 mb-3">
                                            <label for="kode_iklan_<?php echo $i; ?>" class="form-label">Kode Iklan <?php echo $i; ?></label>
                                            <textarea id="kode_iklan_<?php echo $i; ?>" name="kode_iklan_<?php echo $i; ?>" class="form-control" rows="4"><?php echo esc_textarea(isset($options["kode_iklan_{$i}"]) ? $options["kode_iklan_{$i}"] : ''); ?></textarea>
                                        </div>
                                    <?php endfor; ?>
                                    <div class="col-md-6 mb-3">
                                        <label for="refresh_blank" class="form-label">Refresh jika iklan blank</label>
                                        <div class="input-group"><input type="number" id="refresh_blank" name="refresh_blank" class="form-control" value="<?php echo esc_attr(isset($options['refresh_blank']) ? $options['refresh_blank'] : '0'); ?>"><span class="input-group-text">Detik</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="ntb-settings-section">
                                <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="sembunyikan_vinyet" name="sembunyikan_vinyet" <?php checked(isset($options['sembunyikan_vinyet']) ? $options['sembunyikan_vinyet'] : 0, 1); ?>><label class="form-check-label" for="sembunyikan_vinyet">Sembunyikan iklan vinyet.</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="sembunyikan_anchor" name="sembunyikan_anchor" <?php checked(isset($options['sembunyikan_anchor']) ? $options['sembunyikan_anchor'] : 0, 1); ?>><label class="form-check-label" for="sembunyikan_anchor">Sembunyikan iklan anchor.</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="sembunyikan_elemen_blank" name="sembunyikan_elemen_blank" <?php checked(isset($options['sembunyikan_elemen_blank']) ? $options['sembunyikan_elemen_blank'] : 0, 1); ?>><label class="form-check-label" for="sembunyikan_elemen_blank">Sembunyikan elemen iklan (jika blank).</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="tampilkan_close_ads" name="tampilkan_close_ads" <?php checked(isset($options['tampilkan_close_ads']) ? $options['tampilkan_close_ads'] : 0, 1); ?>><label class="form-check-label" for="tampilkan_close_ads">Tampilkan Tombol Close ADS.</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="auto_scroll" name="auto_scroll" <?php checked(isset($options['auto_scroll']) ? $options['auto_scroll'] : 0, 1); ?>><label class="form-check-label" for="auto_scroll">Auto Scroll.</label></div>
                            </div>
                        </div>

                        <!-- Tab: Advance -->
                        <div class="tab-pane fade" id="advance" role="tabpanel">
                            <div class="ntb-settings-section">
                                <div class="mb-3">
                                    <label for="akses_lokasi_mode" class="form-label">Akses Lokasi</label>
                                    <select id="akses_lokasi_mode" name="akses_lokasi_mode" class="form-select mb-2">
                                        <option value="off" <?php selected(isset($options['akses_lokasi_mode']) ? $options['akses_lokasi_mode'] : 'off', 'off'); ?>>Off</option>
                                        <option value="termasuk" <?php selected(isset($options['akses_lokasi_mode']) ? $options['akses_lokasi_mode'] : '', 'termasuk'); ?>>Termasuk</option>
                                        <option value="kecualikan" <?php selected(isset($options['akses_lokasi_mode']) ? $options['akses_lokasi_mode'] : '', 'kecualikan'); ?>>Kecualikan</option>
                                    </select>
                                    <div id="akses_lokasi_countries_wrapper">
                                        <select id="akses_lokasi_countries" name="akses_lokasi_countries[]" class="form-select" multiple="multiple">
                                            <?php
                                            $selected_countries = json_decode(isset($options['akses_lokasi_countries']) ? $options['akses_lokasi_countries'] : '[]', true);
                                            foreach ($countries as $country) {
                                                echo '<option value="' . esc_attr($country) . '" ' . (is_array($selected_countries) && in_array($country, $selected_countries) ? 'selected' : '') . '>' . esc_html($country) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <small class="form-text text-muted mt-2">
                                        <ul class="list-unstyled mb-0">
                                            <li><span class="text-primary">&bull;</span> <strong>**Termasuk**</strong> &rarr; Hanya berlaku untuk negara yang dipilih.</li>
                                            <li><span class="text-primary">&bull;</span> <strong>**Kecualikan**</strong> &rarr; Berlaku untuk *semua negara kecuali* negara yang dipilih.</li>
                                        </ul>
                                    </small>
                                </div>
                            </div>
                            <div class="ntb-settings-section">
                                <div class="mb-3">
                                    <label for="blokir_ip_address" class="form-label">Blokir IP address</label>
                                    <textarea id="blokir_ip_address" name="blokir_ip_address" class="form-control" rows="4"><?php echo esc_textarea($options['blokir_ip_address'] ?? "192.168.1.1\n192.168.1.12"); ?></textarea>
                                    <small class="form-text text-muted">Masukkan satu IP per baris untuk memblokir akses dari IP tertentu.<br>
                                    Contoh:<br>
                                    192.168.1.1<br>
                                    203.0.113.42<br>
                                    Anda juga bisa memblokir rentang IP dengan format CIDR. Contoh: <code>192.0.2.0/24</code></small>
                                </div>
                            </div>
                            <!-- **MODIFIED:** Akses ASN Section -->
                            <div class="ntb-settings-section">
                                <div class="mb-3">
                                    <label for="akses_asn_mode" class="form-label">Akses ASN</label>
                                    <select id="akses_asn_mode" name="akses_asn_mode" class="form-select">
                                        <option value="off" <?php selected($options['akses_asn_mode'] ?? 'off', 'off'); ?>>Off</option>
                                        <option value="termasuk" <?php selected($options['akses_asn_mode'] ?? '', 'termasuk'); ?>>Termasuk</option>
                                        <option value="kecualikan" <?php selected($options['akses_asn_mode'] ?? '', 'kecualikan'); ?>>Kecualikan</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="akses_asn_list_wrapper">
                                    <textarea id="akses_asn_list" name="akses_asn_list" class="form-control" rows="4" placeholder="Masukkan satu ASN per baris, Contoh:&#10;15169&#10;45566"><?php echo esc_textarea($options['akses_asn_list'] ?? ''); ?></textarea>
                                </div>
                                <small class="form-text text-muted">
                                    <strong>Apa itu ASN?</strong><br>
                                    ASN (Autonomous System Number) adalah nomor unik yang digunakan untuk mengidentifikasi jaringan internet milik perusahaan, penyedia layanan internet (ISP), atau organisasi tertentu. Contoh ASN:<br>
                                    - 15169 &rarr; Google<br>
                                    - 45566 &rarr; Indosat<br>
                                    - 32934 &rarr; Facebook<br>
                                    <strong>Cara penggunaan:</strong><br>
                                    - Pilih mode Kecualikan untuk memblokir semua ASN kecuali yang terdaftar di daftar ini.<br>
                                    - Pilih mode Termasuk untuk hanya mengizinkan ASN yang terdaftar di daftar ini.<br>
                                    - Masukkan satu ASN per baris.<br>
                                    <strong>Bagaimana cara mengetahui ASN?.</strong><br>
                                    Anda bisa mencari ASN suatu IP menggunakan layanan seperti: <a href="https://ipinfo.io" target="_blank">ipinfo.io</a> or <a href="https://bgpview.io" target="_blank">bgpview.io</a>.
                                </small>
                            </div>
                            <div class="ntb-settings-section">
                                <h5 class="section-title">Detektor Perangkat</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="detektor_perangkat_tinggi" <?php checked($options['detektor_perangkat_tinggi'] ?? 0, 1); ?>>
                                    <label class="form-check-label" for="detektor_perangkat_tinggi"><strong>Akurasi lebih tinggi</strong> (dibanding metode user-agent standar).</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="detektor_perangkat_sedang" <?php checked($options['detektor_perangkat_sedang'] ?? 1, 1); ?>>
                                    <label class="form-check-label" for="detektor_perangkat_sedang"><strong>Memungkinkan penargetan (redirect)</strong> yang lebih tepat berdasarkan jenis perangkat.</label>
                                </div>
                                 <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="detektor_perangkat_rendah" <?php checked($options['detektor_perangkat_rendah'] ?? 1, 1); ?>>
                                    <label class="form-check-label" for="detektor_perangkat_rendah"><strong>Mendukung lebih banyak</strong> jenis perangkat.</label>
                                </div>
                                <p class="mt-3"><strong>Kekurangan:</strong><br>Membutuhkan lebih banyak sumber daya server, terutama pada situs dengan traffic tinggi.<br>Bisa meningkatkan waktu loading jika tidak dikonfigurasi dengan baik.<br>Menggunakan caching dapat mengurangi beban, tetapi perlu dikonfigurasi dengan benar.</p>
                                <p><strong>Catatan:</strong><br>Jika opsi ini <strong>TIDAK</strong> dalam bentuk isian, akan mengembalikan metode deteksi lawas yang lebih ringan tetapi kurang akurat.<br>Jika Anda memiliki traffic terbatas, pertimbangkan untuk menggunakan caching atau metode deteksi yang lebih sederhana.</p>
                            </div>
                             <div class="ntb-settings-section">
                                <div class="mb-3">
                                    <label for="simpan_log" class="form-label">Simpan Log ke Database</label>
                                    <select id="simpan_log" name="simpan_log" class="form-select">
                                        <option <?php selected($options['simpan_log'] ?? 'Aktifkan', 'Aktifkan'); ?>>Aktifkan</option>
                                        <option <?php selected($options['simpan_log'] ?? '', 'Nonaktifkan'); ?>>Nonaktifkan</option>
                                    </select>
                                    <small class="form-text text-muted">Jika diaktifkan, setiap redirect akan dicatat di database.<br><strong>Keuntungan:</strong> Memberikan data analitik yang berharga soal traffic.<br><strong>Kekurangan:</strong> Database bisa cepat penuh jika traffic tinggi.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Pratinjau -->
                <div class="col-lg-4">
                    <div class="ntb-preview-container">
                        <div class="ntb-mobile-preview">
                            <div class="ntb-mobile-header"><span class="time">11:39 AM</span>
                                <div class="notch"></div>
                                <div class="status-icons">...</div>
                            </div>
                            <div class="ntb-mobile-screen"><iframe id="ad-preview-iframe" src="about:blank" style="width: 100%; height: 100%; border: none;"></iframe></div>
                            <div class="ntb-mobile-footer">
                                <div class="ntb-address-bar">...</div>
                                <div class="ntb-nav-buttons">...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Form -->
            <div class="ntb-form-actions">
                <button type="submit" class="button button-primary btn-update-settings">Simpan Pengaturan</button>
                <a href="#" class="button button-secondary next-tab" data-next="#advance">Selanjutnya &rarr;</a>
            </div>
        </form>
    </div>
</div>

<!-- <style>
    /* ... (Semua kode CSS lo yang sudah ada) ... */
</style> -->


<style>
    /* General Layout & Main Content Box */
    #ntbksense-settings-wrapper {
        background-color: #f0f0f1;
        padding: 0;
        margin: 0px 0px 0px -20px;
    }

    .ntb-main-content {
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        padding: 20px;
        margin: 20px;
    }

    /* Navbar & Breadcrumb */
    .ntb-navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #ffffff;
        border-bottom: 1px solid #c3c4c7;
    }

    .ntb-navbar-title {
        font-size: 16px;
        font-weight: 600;
    }

    .ntb-breadcrumb {
        padding: 15px 20px;
        color: #50575e;
        font-size: 14px;
        background-color: #fff;
        border-bottom: 1px solid #e0e0e0;
    }

    .ntb-breadcrumb a {
        text-decoration: none;
        color: #0073aa;
    }

    .ntb-breadcrumb span {
        font-weight: 600;
    }

    /* Tabs Styling */
    .ntb-nav-tabs {
        border-bottom: 1px solid #ddd;
    }

    .ntb-nav-tabs .nav-link {
        border: 1px solid transparent;
        border-bottom: none;
        color: #555;
        font-size: 13px;
        padding: 8px 15px;
    }

    .ntb-nav-tabs .nav-link .dashicons {
        font-size: 16px;
        vertical-align: text-top;
        margin-right: 5px;
    }

    .ntb-nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #ddd #ddd #fff;
        color: #0073aa;
        font-weight: 600;
    }

    .ntb-tab-content {
        background: #fff;
        padding: 20px;
        border: 1px solid #ddd;
        border-top: none;
    }

    /* Form Sections & Fields */
    .ntb-settings-section {
        padding: 20px;
        border: 1px solid #e5e5e5;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .ntb-settings-section:last-child {
        margin-bottom: 0;
    }

    .ntb-settings-section .section-title {
        font-size: 14px;
        font-weight: 600;
        margin-top: 0;
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: 600;
        font-size: 13px;
    }

    .form-control,
    .form-select {
        font-size: 13px;
    }

    /* Mobile Preview */
    .ntb-preview-container {
        position: sticky;
        top: 50px;
    }

    .ntb-mobile-preview {
        width: 300px;
        /* Lebar iPhone 13 Mini */
        height: 615px;
        /* Tinggi iPhone 13 Mini */
        background: #fff;
        border: 8px solid #111;
        border-radius: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        margin: 0 auto;
        display: flex;
        flex-direction: column;
    }

    .ntb-mobile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 20px;
        color: #111;
        position: relative;
        background-color: #fff;
        border-top-left-radius: 32px;
        border-top-right-radius: 32px;
    }

    .ntb-mobile-header .time {
        font-weight: 600;
        font-size: 14px;
    }

    .ntb-mobile-header .notch {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 120px;
        height: 25px;
        background: #111;
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
    }

    .ntb-mobile-header .status-icons {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
    }

    .ntb-mobile-screen {
        background: #000;
        flex-grow: 1;
    }

    .ntb-mobile-footer {
        background-color: #f0f0f1;
        padding: 8px 12px 15px 12px;
        border-bottom-left-radius: 32px;
        border-bottom-right-radius: 32px;
    }

    .ntb-address-bar {
        background-color: #e1e1e1;
        border-radius: 8px;
        padding: 5px 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
    }

    .ntb-address-bar .url-text {
        color: #333;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .ntb-address-bar .url-text .dashicons {
        font-size: 14px;
    }

    .ntb-nav-buttons {
        display: flex;
        justify-content: space-around;
        padding-top: 15px;
        color: #007aff;
        font-size: 22px;
    }

    .ntb-nav-buttons .dashicons {
        font-size: 24px;
        height: auto;
        width: auto;
    }

    .ntb-nav-buttons .bi-box-arrow-up {
        font-size: 20px;
    }


    /* Form Actions */
    .ntb-form-actions {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }
</style>