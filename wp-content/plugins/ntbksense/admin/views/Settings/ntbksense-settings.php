<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/ntbksense-settings.php
// =========================================================================

/**
 * Tampilan untuk halaman admin Settings.
 * Menampilkan form pengaturan dengan tab dan pratinjau.
 */

// Menambahkan aset khusus untuk halaman ini
add_action('admin_footer', function() {
    $screen = get_current_screen();
    // Pastikan ID layar cocok dengan halaman settings
    if ( 'ntbksense_page_ntbksense-settings' !== $screen->id ) {
        return;
    }
?>
    <!-- Bootstrap 5 for layout and components -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Notify.js for notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>

    <!-- Select2 for advanced select boxes -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        // Membuat objek JavaScript untuk menampung data dari PHP (seperti ajaxurl dan nonce)
        const ntbksense_data = {
            ajaxUrl: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            nonce: '<?php echo wp_create_nonce( 'ntbksense_settings_nonce' ); ?>'
        };

        jQuery(document).ready(function(a){
            // Fungsi ini belum memiliki elemen target di HTML, akan diimplementasikan nanti
            function e(){
                // let e=a("#mode_display_ads").val();
                // "stacked"===e?a(".hidden_mode_display_ads").css("display","flex"):a(".hidden_mode_display_ads").css("display","none"),console.log(e)
            }
            
            // Fungsi untuk memperbarui pratinjau iklan di iframe
            function t(){
                let e=parseInt(a("#opacity").val()),
                    t=a("#kode_iklan_1").val().trim(),
                    s=a("#kode_iklan_2").val().trim(),
                    i=a("#kode_iklan_3").val().trim(),
                    d=a("#kode_iklan_4").val().trim(),
                    o=a("#kode_iklan_5").val().trim(),
                    l=a("#posisi").val().trim(),
                    n=parseInt(a("#margin_top").val().trim()),
                    c=parseInt(a("#margin_bottom").val().trim()),
                    r=a("#mode_tampilan").val().trim(),
                    g=a("#jumlah_iklan").val().trim();
                
                // Membuat URL untuk iframe pratinjau
                // CATATAN: Aksi 'ntbksense-landing-preview' perlu dibuat di backend
                let $=`${ntbksense_data.ajaxUrl}?${new URLSearchParams({
                    action:"ntbksense-landing-preview",
                    security:ntbksense_data.nonce,
                    opacity:e,
                    margin_top_ads:n,
                    margin_bottom_ads:c,
                    mode:r,
                    position:l,
                    ads1:""!==t?1:0,
                    ads2:""!==s?1:0,
                    ads3:""!==i?1:0,
                    ads4:""!==d?1:0,
                    ads5:""!==o?1:0,
                    number_display_code:g
                }).toString()}`;
                
                console.log("Updating preview with URL: ", $);
                a("#ad-preview-iframe").attr("src",$)
            }

            // Fungsi untuk menampilkan notifikasi
            function s(e,t,s="top center"){
                a.notify(e,{className:t,globalPosition:s,autoHide:!0,autoHideDelay:5e3,showAnimation:"fadeIn",hideAnimation:"fadeOut"})
            }

            // Navigasi tab
            a(".next-tab").click(function(){let e=a(this).data("next");a(`button[data-bs-target="${e}"]`).trigger("click"),a("html, body").animate({scrollTop:0},100)});
            a(".prev-tab").click(function(){let e=a(this).data("prev");a(`button[data-bs-target="${e}"]`).trigger("click"),a("html, body").animate({scrollTop:0},100)});
            
            // Inisialisasi Select2 jika ada
            // a("#allowed_countries").select2({allowClear:!0,width:"100%",closeOnSelect:!1});
            
            // Handler untuk submit form via AJAX
            a("#settingsForm").submit(async function(e){
                e.preventDefault();
                
                // Mengumpulkan data checkbox secara manual
                let t={
                    action:"ntbksense_update_settings", // Ganti dengan action yang sesuai
                    security:ntbksense_data.nonce,
                    sembunyikan_vinyet:a("#sembunyikan_vinyet").is(":checked")?1:0,
                    sembunyikan_anchor:a("#sembunyikan_anchor").is(":checked")?1:0,
                    sembunyikan_elemen_blank:a("#sembunyikan_elemen_blank").is(":checked")?1:0,
                    tampilkan_close_ads:a("#tampilkan_close_ads").is(":checked")?1:0,
                    auto_scroll:a("#auto_scroll").is(":checked")?1:0
                };

                // Menggabungkan data form yang diserialisasi dengan data checkbox
                let i=a(this).serialize();
                i+="&"+a.param(t);
                
                let d=a(".btn-update-settings"),o=d.html();
                d.prop("disabled",!0).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                
                try{
                    // CATATAN: Aksi 'ntbksense_update_settings' perlu dibuat di backend
                    let l=await a.ajax({url:ntbksense_data.ajaxUrl,type:"POST",data:i,dataType:"json"});
                    if(l.success){
                        s(l.data?.message || "Pengaturan berhasil disimpan.","success");
                    } else {
                        let n=l.data?.message||"An unknown error occurred";
                        s(n,"error")
                    }
                } catch(c) {
                    let r="Connection failed or server not responding";
                    c.responseJSON&&(r=c.responseJSON.data?.message||"Internal server error"),s(r,"error")
                } finally {
                    d.prop("disabled",!1).html(o)
                }
            });
            
            // Event listener untuk memperbarui pratinjau secara live
            a(document).on("input change","#opacity, #kode_iklan_1, #kode_iklan_2, #kode_iklan_3,#kode_iklan_4,#kode_iklan_5,#posisi,#margin_top,#margin_bottom,#mode_tampilan,#jumlah_iklan",function(){t()});
            
            // Panggil fungsi saat halaman dimuat untuk inisialisasi
            e();
            t();
        });
    </script>
<?php
});

// Mengambil opsi yang tersimpan untuk ditampilkan di form
$options = get_option('ntbksense_settings');


// **NEW:** Menambahkan data dummy untuk kode iklan dan checkbox jika kosong
$dummy_ad_code = '
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2453446323664281" crossorigin="anonymous"></script>
    <!-- ks41 -->
    <ins class="adsbygoogle"
        style="display:block"
        data-ad-client="ca-pub-2453446323664281"
        data-ad-slot="4556563739"
        data-ad-format="auto"
        data-full-width-responsive="true">
    </ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
';

if (empty($options['kode_iklan_1'])) {
    $options['kode_iklan_1'] = str_replace('DUMMY_SLOT', '1111111111', $dummy_ad_code);
}
if (empty($options['kode_iklan_2'])) {
    $options['kode_iklan_2'] = str_replace('DUMMY_SLOT', '2222222222', $dummy_ad_code);
}
if (empty($options['kode_iklan_3'])) {
    $options['kode_iklan_3'] = str_replace('DUMMY_SLOT', '3333333333', $dummy_ad_code);
}
if (empty($options['kode_iklan_4'])) {
    $options['kode_iklan_4'] = str_replace('DUMMY_SLOT', '4444444444', $dummy_ad_code);
}
if (empty($options['kode_iklan_5'])) {
    $options['kode_iklan_5'] = str_replace('DUMMY_SLOT', '5555555555', $dummy_ad_code);
}
if (!isset($options['sembunyikan_elemen_blank'])) {
    $options['sembunyikan_elemen_blank'] = 1;
}
if (!isset($options['tampilkan_close_ads'])) {
    $options['tampilkan_close_ads'] = 1;
}

?>
<div class="wrap" id="ntbksense-settings-wrapper">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR."admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Settings</span>
    </div>

    <div class="ntb-main-content">
        <!-- **MODIFIED:** Form tag diubah untuk AJAX -->
        <form id="settingsForm" method="post">
            <div class="row">
                <!-- Kolom Kiri: Pengaturan -->
                <div class="col-lg-8">
                    <!-- Navigasi Tab -->
                    <ul class="nav nav-tabs ntb-nav-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pengaturan-iklan-tab" data-bs-toggle="tab" data-bs-target="#pengaturan-iklan" type="button" role="tab" aria-controls="pengaturan-iklan" aria-selected="true"><span class="dashicons dashicons-megaphone"></span> Pengaturan Iklan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="advance-tab" data-bs-toggle="tab" data-bs-target="#advance" type="button" role="tab" aria-controls="advance" aria-selected="false"><span class="dashicons dashicons-admin-settings"></span> Advance</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="header-footer-tab" data-bs-toggle="tab" data-bs-target="#header-footer" type="button" role="tab" aria-controls="header-footer" aria-selected="false"><span class="dashicons dashicons-editor-code"></span> Header & Footer</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="plugin-tab" data-bs-toggle="tab" data-bs-target="#plugin" type="button" role="tab" aria-controls="plugin" aria-selected="false"><span class="dashicons dashicons-admin-plugins"></span> Plugin</button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link" id="keamanan-tab" data-bs-toggle="tab" data-bs-target="#keamanan" type="button" role="tab" aria-controls="keamanan" aria-selected="false"><span class="dashicons dashicons-lock"></span> Keamanan</button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link" id="optimasi-tab" data-bs-toggle="tab" data-bs-target="#optimasi" type="button" role="tab" aria-controls="optimasi" aria-selected="false"><span class="dashicons dashicons-performance"></span> Optimasi</button>
                        </li>
                    </ul>

                    <!-- Konten Tab -->
                    <div class="tab-content ntb-tab-content" id="settingsTabsContent">
                        <!-- Tab: Pengaturan Iklan -->
                        <div class="tab-pane fade show active" id="pengaturan-iklan" role="tabpanel" aria-labelledby="pengaturan-iklan-tab">
                            <div class="ntb-settings-section">
                                <div class="mb-3">
                                    <label for="id_publisher" class="form-label">ID Publisher</label>
                                    <input type="text" id="id_publisher" name="id_publisher" class="form-control" value="<?php echo esc_attr($options['id_publisher'] ?? 'Bllxxx'); ?>">
                                    <small class="form-text text-muted">Kode publisher Adsense, contoh: Bllxxx.</small>
                                </div>
                            </div>
                            <div class="ntb-settings-section">
                                <h5 class="section-title">Iklan Display</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="opacity" class="form-label">Opacity (%)</label>
                                        <input type="number" id="opacity" name="opacity" class="form-control" value="<?php echo esc_attr($options['opacity'] ?? '80'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="jumlah_iklan" class="form-label">Jumlah Iklan Ditampilkan Di LP</label>
                                        <select id="jumlah_iklan" name="jumlah_iklan" class="form-select">
                                            <option value="1" <?php selected($options['jumlah_iklan'] ?? '1', '1'); ?>>1</option>
                                            <option value="2" <?php selected($options['jumlah_iklan'] ?? '2', '2'); ?>>2</option>
                                            <option value="3" <?php selected($options['jumlah_iklan'] ?? '3', '3'); ?>>3</option>
                                            <option value="4" <?php selected($options['jumlah_iklan'] ?? '4', '4'); ?>>4</option>
                                            <option value="5" <?php selected($options['jumlah_iklan'] ?? '5', '5'); ?>>5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="mode_tampilan" class="form-label">Mode Tampilan Iklan</label>
                                        <select id="mode_tampilan" name="mode_tampilan" class="form-select">
                                            <option <?php selected($options['mode_tampilan'] ?? 'Berurutan', 'Berurutan'); ?>>Berurutan</option>
                                            <option <?php selected($options['mode_tampilan'] ?? '', 'Acak'); ?>>Acak</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="posisi" class="form-label">Posisi</label>
                                        <select id="posisi" name="posisi" class="form-select">
                                            <option <?php selected($options['posisi'] ?? 'Fixed', 'Fixed'); ?>>Fixed</option>
                                            <option <?php selected($options['posisi'] ?? '', 'Absolute'); ?>>Absolute</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="margin_top" class="form-label">Margin Top</label>
                                        <div class="input-group">
                                            <input type="number" id="margin_top" name="margin_top" class="form-control" value="<?php echo esc_attr($options['margin_top'] ?? '5'); ?>">
                                            <span class="input-group-text">Pixel</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="margin_bottom" class="form-label">Margin Bottom</label>
                                        <div class="input-group">
                                            <input type="number" id="margin_bottom" name="margin_bottom" class="form-control" value="<?php echo esc_attr($options['margin_bottom'] ?? '5'); ?>">
                                            <span class="input-group-text">Pixel</span>
                                        </div>
                                    </div>
                                </div>
                                <small class="form-text text-muted mt-2 d-block">Pratinjau ini tidak sepenuhnya akurat.</small>
                            </div>
                            <div class="ntb-settings-section">
                                <h5 class="section-title">Kode Iklan Display</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="kode_iklan_1" class="form-label">Kode Iklan 1</label>
                                        <textarea id="kode_iklan_1" name="kode_iklan_1" class="form-control" rows="4"><?php echo esc_textarea($options['kode_iklan_1'] ?? ''); ?></textarea>
                                        <small class="form-text text-muted">Selain di LP, iklan ini akan tampil sebelum judul.</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="kode_iklan_2" class="form-label">Kode Iklan 2</label>
                                        <textarea id="kode_iklan_2" name="kode_iklan_2" class="form-control" rows="4"><?php echo esc_textarea($options['kode_iklan_2'] ?? ''); ?></textarea>
                                        <small class="form-text text-muted">Selain di LP, iklan ini akan tampil di sebelum konten.</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="kode_iklan_3" class="form-label">Kode Iklan 3</label>
                                        <textarea id="kode_iklan_3" name="kode_iklan_3" class="form-control" rows="4"><?php echo esc_textarea($options['kode_iklan_3'] ?? ''); ?></textarea>
                                        <small class="form-text text-muted">Selain di LP, iklan ini akan tampil setelah konten.</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="kode_iklan_4" class="form-label">Kode Iklan 4</label>
                                        <textarea id="kode_iklan_4" name="kode_iklan_4" class="form-control" rows="4"><?php echo esc_textarea($options['kode_iklan_4'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="kode_iklan_5" class="form-label">Kode Iklan 5</label>
                                        <textarea id="kode_iklan_5" name="kode_iklan_5" class="form-control" rows="4"><?php echo esc_textarea($options['kode_iklan_5'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="refresh_blank" class="form-label">Refresh halaman jika iklan blank</label>
                                        <div class="input-group">
                                            <input type="number" id="refresh_blank" name="refresh_blank" class="form-control" value="<?php echo esc_attr($options['refresh_blank'] ?? '0'); ?>">
                                            <span class="input-group-text">Detik</span>
                                        </div>
                                        <small class="form-text text-muted">Halaman akan direfresh jika jumlah tayang iklan 0. Isi 0 (nol) untuk menonaktifkan fitur ini.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="ntb-settings-section">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="sembunyikan_vinyet" <?php checked($options['sembunyikan_vinyet'] ?? 0, 1); ?>>
                                    <label class="form-check-label" for="sembunyikan_vinyet">Sembunyikan iklan vinyet.</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="sembunyikan_anchor" <?php checked($options['sembunyikan_anchor'] ?? 0, 1); ?>>
                                    <label class="form-check-label" for="sembunyikan_anchor">Sembunyikan iklan anchor.</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="sembunyikan_elemen_blank" <?php checked($options['sembunyikan_elemen_blank'] ?? 0, 1); ?>>
                                    <label class="form-check-label" for="sembunyikan_elemen_blank">Sembunyikan elemen iklan (jika blank).</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="tampilkan_close_ads" <?php checked($options['tampilkan_close_ads'] ?? 0, 1); ?>>
                                    <label class="form-check-label" for="tampilkan_close_ads">Tampilkan Tombol Close ADS.</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="auto_scroll" <?php checked($options['auto_scroll'] ?? 0, 1); ?>>
                                    <label class="form-check-label" for="auto_scroll">Auto Scroll.</label>
                                </div>
                            </div>
                        </div>
                        <!-- Placeholder untuk tab lain -->
                        <div class="tab-pane fade" id="advance" role="tabpanel" aria-labelledby="advance-tab"><p>Pengaturan Advance akan muncul di sini.</p></div>
                        <div class="tab-pane fade" id="header-footer" role="tabpanel" aria-labelledby="header-footer-tab"><p>Pengaturan Header & Footer akan muncul di sini.</p></div>
                        <div class="tab-pane fade" id="plugin" role="tabpanel" aria-labelledby="plugin-tab"><p>Pengaturan Plugin akan muncul di sini.</p></div>
                        <div class="tab-pane fade" id="keamanan" role="tabpanel" aria-labelledby="keamanan-tab"><p>Pengaturan Keamanan akan muncul di sini.</p></div>
                        <div class="tab-pane fade" id="optimasi" role="tabpanel" aria-labelledby="optimasi-tab"><p>Pengaturan Optimasi akan muncul di sini.</p></div>
                    </div>
                </div>

                <!-- Kolom Kanan: Pratinjau -->
                <div class="col-lg-4">
                    <div class="ntb-preview-container">
                        <div class="ntb-mobile-preview">
                            <div class="ntb-mobile-header">
                                <span class="time">11:39 AM</span>
                                <div class="notch"></div>
                                <div class="status-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reception-4" viewBox="0 0 16 16"><path d="M0 11.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2zm4-3a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-5zm4-3a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-8zm4-3a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-11z"/></svg>
                                    <span>5G</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-battery-full" viewBox="0 0 16 16"><path d="M2 6h10v4H2V6z"/><path d="M2 4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H2zm13 1a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-1v-4h1z"/></svg>
                                </div>
                            </div>
                            <div class="ntb-mobile-screen">
                                <!-- **MODIFIED:** Iframe untuk pratinjau live -->
                                <iframe id="ad-preview-iframe" src="about:blank" style="width: 100%; height: 100%; border: none;"></iframe>
                            </div>
                            <div class="ntb-mobile-footer">
                                <div class="ntb-address-bar">
                                    <span>Aa</span>
                                    <div class="url-text"><span class="dashicons dashicons-lock"></span> www.example.com</div>
                                    <span class="dashicons dashicons-image-rotate"></span>
                                </div>
                                <div class="ntb-nav-buttons">
                                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1h-2z"/><path fill-rule="evenodd" d="M7.646.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 1.707V10.5a.5.5 0 0 1-1 0V1.707L5.354 3.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>
                                    <span class="dashicons dashicons-book-alt"></span>
                                    <span class="dashicons dashicons-images-alt"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Form -->
            <div class="ntb-form-actions">
                <!-- **MODIFIED:** Tombol diubah untuk AJAX -->
                <button type="submit" class="button button-primary btn-update-settings">Simpan Pengaturan</button>
                <a href="#" class="button button-secondary next-tab" data-next="#advance">Selanjutnya &rarr;</a>
            </div>
        </form>
    </div>
</div>

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
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 20px;
        margin: 20px;
    }
    /* Navbar & Breadcrumb */
    .ntb-navbar { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: #ffffff; border-bottom: 1px solid #c3c4c7; }
    .ntb-navbar-title { font-size: 16px; font-weight: 600; }
    .ntb-breadcrumb { padding: 15px 20px; color: #50575e; font-size: 14px; background-color: #fff; border-bottom: 1px solid #e0e0e0; }
    .ntb-breadcrumb a { text-decoration: none; color: #0073aa; }
    .ntb-breadcrumb span { font-weight: 600; }

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
    .form-control, .form-select {
        font-size: 13px;
    }

    /* Mobile Preview */
    .ntb-preview-container {
        position: sticky;
        top: 50px;
    }
    .ntb-mobile-preview {
        width: 300px; /* Lebar iPhone 13 Mini */
        height: 615px; /* Tinggi iPhone 13 Mini */
        background: #fff;
        border: 8px solid #111;
        border-radius: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
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