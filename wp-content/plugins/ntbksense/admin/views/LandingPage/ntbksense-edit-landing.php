<?php
// =========================================================================
// FILE: ntbksense/admin/views/LandingPage/ntbksense-edit-landing.php
// =========================================================================

/**
 * Tampilan untuk halaman admin Edit Landing Page.
 * Menggunakan form yang sama dengan halaman 'create', tetapi diisi dengan data yang ada.
 */

// --- START: Logika untuk mengambil data dummy berdasarkan ID ---
// Ambil ID dari URL, jika tidak ada, gunakan 0
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $landing_page_id = intval($_GET['id']);
} else {
    $landing_page_id = 0;
}
// Inisialisasi data LP
$lp_data = null; 

// Generate data dummy yang konsisten dengan halaman daftar untuk simulasi database
$data = [];
$templates = ['Universal (Long screenshot)', 'Video (Long screenshot)'];
$redirect_options = ['isi Manual (Rekomendasi)', 'Opsi Lain'];
$desc_options = ['isi Manual (Rekomendasi)', 'Ambil dari Judul'];
$traffic_options = ['Izinkan Semua Trafik', 'Blokir Trafik Tertentu'];
$video_options = ['On', 'Off'];
$protection_options = ['Pause Debugger', 'Nonaktif'];
$refresh_options = ['Ketika halaman dimuat', 'Setelah Interval Tertentu'];
$title_options = ['isi Manual (Rekomendasi)', 'Gunakan Judul Post'];
$devices = ['Semua Perangkat (kecuali Bot)', 'Desktop', 'Mobile', 'Chrome, Firefox'];

for ($i = 1; $i <= 100; $i++) {
    $data[] = [
        'id'                    => $i,
        'template'              => $templates[array_rand($templates)],
        'url_pengalihan'        => $redirect_options[array_rand($redirect_options)],
        'deskripsi'             => $desc_options[array_rand($desc_options)],
        'kontrol_penujuk'       => $traffic_options[array_rand($traffic_options)],
        'video_melayang'        => $video_options[array_rand($video_options)],
        'proteksi_fitur'        => $protection_options[array_rand($protection_options)],
        'slug'                  => 'my-example-slug-' . $i,
        'judul'                 => $title_options[array_rand($title_options)],
        'auto_refresh'          => $refresh_options[array_rand($refresh_options)],
        'tampilan_perangkat'    => $devices[array_rand($devices)],
        'jumlah_gambar'         => rand(5, 20),
        'cloaking_url'          => 'https://www.google.com/search?q=cloaked' . $i,
        'url_video'             => ['https://example.com/video' . $i . '.mp4', 'https://example.com/video' . $i . '-alt.mp4'],
        'url_gambar'            => ['https://example.com/image' . $i . '.jpg'],
        'param_key'             => 'target',
        'param_value'           => rand(100, 500),
        'jeda_video_min'        => rand(5, 10),
        'jeda_video_max'        => rand(15, 25),
        'waktu_refresh_min'     => rand(5, 10),
        'waktu_refresh_max'     => rand(15, 25),
        'judul_manual'          => 'Manual Title Example ' . $i,
        'daftar_artikel'        => "https://your-domain.com/article-{$i}-1/\nhttps://your-domain.com/article-{$i}-2/",
        'deskripsi_manual'      => 'This is a manual description for item ' . $i,
        'inject_keywords'       => "keyword{$i}a, keyword{$i}b, keyword{$i}c",
    ];
}

// Cari data yang sesuai dengan ID dari URL
if ($landing_page_id > 0) {
    foreach ($data as $item) {
        if ($item['id'] === $landing_page_id) {
            $lp_data = $item;
            break;
        }
    }
}
// --- END: Logika untuk mengambil data dummy ---

// Menambahkan aset khusus untuk halaman builder
add_action('admin_footer', function() {
    // Pastikan ID layar cocok dengan halaman edit
    $screen = get_current_screen();
    // **FIX:** Changed screen ID to match WordPress format for hidden admin pages
    if ( 'admin_page_ntbksense-edit-lp' !== $screen->id ) {
        return;
    }
    ?>
    <!-- Bootstrap for grid and form styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery UI for Range Slider -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            // Semua kode JavaScript dari 'create-landing.php' disalin di sini
            // Inisialisasi Range Sliders
            $(".ntb-range-slider").each(function() {
                const $sliderContainer = $(this);
                const $slider = $sliderContainer.find(".slider-track");
                const $minInput = $sliderContainer.find(".range-value-min");
                const $maxInput = $sliderContainer.find(".range-value-max");
                const minVal = parseInt($minInput.val(), 10);
                const maxVal = parseInt($maxInput.val(), 10);
                const dataMin = parseInt($slider.data('min'), 10);
                const dataMax = parseInt($slider.data('max'), 10);

                $slider.slider({
                    range: true,
                    min: dataMin,
                    max: dataMax,
                    values: [minVal, maxVal],
                    slide: function(event, ui) {
                        $minInput.val(ui.values[0]);
                        $maxInput.val(ui.values[1]);
                    }
                });
            });

            // Sinkronisasi input angka dengan slider
            $('.range-value-min, .range-value-max').on('change', function() {
                const $input = $(this);
                const $sliderContainer = $input.closest('.ntb-range-slider');
                const $slider = $sliderContainer.find('.slider-track');
                let minVal = parseInt($sliderContainer.find('.range-value-min').val(), 10);
                let maxVal = parseInt($sliderContainer.find('.range-value-max').val(), 10);
                
                if ($input.hasClass('range-value-min') && minVal > maxVal) {
                    minVal = maxVal;
                    $input.val(minVal);
                }
                if ($input.hasClass('range-value-max') && maxVal < minVal) {
                    maxVal = minVal;
                    $input.val(maxVal);
                }

                $slider.slider("values", [minVal, maxVal]);
            });

            // Logika Tambah/Hapus Input
            function addRepeaterField(e) {
                e.preventDefault();
                const $link = $(this);
                const containerId = $link.data('container');
                const $container = $('#' + containerId);
                const fieldName = $link.data('name');
                const placeholder = $link.data('placeholder');
                const acceptType = $link.data('accept');

                const newField = `
                    <div class="input-group mt-2">
                        <input type="text" name="${fieldName}[]" class="form-control" placeholder="${placeholder}">
                        <input type="file" class="ntb-file-input" style="display: none;" accept="${acceptType}">
                        <button class="btn btn-primary ntb-media-btn" type="button">
                            <span class="dashicons dashicons-cloud-upload"></span>
                        </button>
                        <button class="btn btn-danger ntb-remove-btn" type="button">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                `;
                $container.append(newField);
            }

            function removeRepeaterField(e) {
                e.preventDefault();
                $(this).closest('.input-group').remove();
            }

            $('.ntb-add-repeater').on('click', addRepeaterField);
            $(document).on('click', '.ntb-remove-btn', removeRepeaterField);

            // Logika Toggle Advance
            $('.ntb-advance-toggle input[type="checkbox"]').on('change', function() {
                const $toggle = $(this);
                const $formGroup = $toggle.closest('.ntb-form-group');
                const $simpleView = $formGroup.find('.ntb-simple-view');
                const $advanceView = $formGroup.find('.ntb-advance-view');

                if ($toggle.is(':checked')) {
                    $simpleView.hide();
                    $advanceView.show();
                } else {
                    $simpleView.show();
                    $advanceView.hide();
                }
            }).change(); // Trigger change on load to set initial state

            // Logika Tombol Upload Media
            $(document).on('click', '.ntb-media-btn', function(e) {
                e.preventDefault();
                $(this).siblings('.ntb-file-input').click();
            });

            $(document).on('change', '.ntb-file-input', function() {
                const file = this.files[0];
                if (file) {
                    const $textInput = $(this).siblings('.form-control');
                    $textInput.val(file.name);
                }
            });

            // Logika Tombol Upload Media (BULK)
            $(document).on('click', '.ntb-bulk-upload-btn', function(e) {
                e.preventDefault();
                $(this).siblings('.ntb-bulk-file-input').click();
            });

            $(document).on('change', '.ntb-bulk-file-input', function() {
                const files = this.files;
                if (files.length > 0) {
                    let fileNames = Array.from(files).map(file => file.name);
                    const $textarea = $(this).siblings('textarea');
                    $textarea.val(fileNames.join('\n'));
                }
            });

            // Logika Dapatkan URL Artikel dengan Modal
            $('#ntb-get-articles-btn').on('click', function(e) {
                e.preventDefault();
                var myModal = new bootstrap.Modal(document.getElementById('getArticlesModal'));
                myModal.show();
            });

            $('#submit-get-articles').on('click', function(e) {
                e.preventDefault();
                const $originalButton = $('#ntb-get-articles-btn');
                const originalText = $originalButton.html();
                const $textarea = $('#daftar_artikel');
                const articleCount = $('#article_count').val();

                var myModalEl = document.getElementById('getArticlesModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();

                $originalButton.html('<span class="spinner is-active" style="float: left; margin-right: 5px;"></span> Memuat...').prop('disabled', true);

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ntbksense_get_published_posts',
                        security: $originalButton.data('nonce'),
                        count: articleCount
                    },
                    success: function(response) {
                        if (response.success) {
                            $textarea.val(response.data.join('\n'));
                        } else {
                            alert(response.data.message || 'Gagal mengambil data.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    },
                    complete: function() {
                        $originalButton.html(originalText).prop('disabled', false);
                    }
                });
            });
        });
    </script>
    <?php
});
?>

<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR."admin/views/Layout/navbar.php"; ?>

    <!-- START: Breadcrumb Kustom -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span> <span>NTBKSense</span> &gt; 
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-landing-page')); ?>">Landing Page</a> &gt; <span>Edit LP #<?php echo esc_html($landing_page_id); ?></span>
    </div>
    <!-- END: Breadcrumb Kustom -->

    <div class="ntb-main-content">
        <h1 class="wp-heading-inline">Edit Landing Page</h1>
        <p>
            Halaman ini digunakan untuk mengubah konfigurasi Landing Page yang sudah ada.
            Pastikan untuk mengisi semua bidang yang diperlukan sebelum menyimpan.<br>
            Note : <span class="text-danger">*</span> menandakan bidang yang wajib diisi.
        </p>
        <hr class="wp-header-end">
    
        <?php if (!$lp_data): ?>
            <div class="notice notice-error">
                <p><?php _e('Data Landing Page dengan ID yang diminta tidak ditemukan.', 'ntbksense'); ?></p>
            </div>
        <?php else: ?>
            <form method="post" action="">
                <!-- Aksi Keamanan WordPress untuk Update -->
                <?php wp_nonce_field('ntb_update_lp_action', 'ntb_update_lp_nonce'); ?>
                <!-- Hidden field untuk ID -->
                <input type="hidden" name="lp_id" value="<?php echo esc_attr($landing_page_id); ?>">
    
                <div class="ntb-form-container">
                    <div class="row">
                        <!-- Kiri -->
                        <div class="col-md-6">
                            <!-- Atas -->
                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">
                                    <div class="ntb-form-group">
                                        <label for="template">Template <span class="text-danger">*</span></label>
                                        <select id="template" name="ntb_template" class="form-select">
                                            <option <?php selected($lp_data['template'], 'Universal (Long screenshot)'); ?>>Universal (Long screenshot)</option>
                                            <option <?php selected($lp_data['template'], 'Video (Long screenshot)'); ?>>Video (Long screenshot)</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="url_pengalihan">URL Pengalihan <span class="text-danger">*</span></label>
                                        <select id="url_pengalihan" name="ntb_url_pengalihan" class="form-select">
                                            <option <?php selected($lp_data['url_pengalihan'], 'isi Manual (Rekomendasi)'); ?>>isi Manual (Rekomendasi)</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                                        <select id="deskripsi" name="ntb_deskripsi" class="form-select">
                                            <option <?php selected($lp_data['deskripsi'], 'isi Manual (Rekomendasi)'); ?>>isi Manual (Rekomendasi)</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="kontrol_penujuk">Kontrol Penujuk <span class="text-danger">*</span></label>
                                        <select id="kontrol_penujuk" name="ntb_kontrol_penujuk" class="form-select">
                                            <option <?php selected($lp_data['kontrol_penujuk'], 'Izinkan Semua Trafik'); ?>>Izinkan Semua Trafik</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="video_melayang">Video Melayang <span class="text-danger">*</span></label>
                                        <select id="video_melayang" name="ntb_video_melayang" class="form-select">
                                            <option <?php selected($lp_data['video_melayang'], 'On'); ?>>On</option>
                                            <option <?php selected($lp_data['video_melayang'], 'Off'); ?>>Off</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="proteksi_fitur">Proteksi fitur inspeksi elemen</label>
                                        <select id="proteksi_fitur" name="ntb_proteksi_fitur" class="form-select">
                                            <option <?php selected($lp_data['proteksi_fitur'], 'Pause Debugger'); ?>>Pause Debugger</option>
                                        </select>
                                    </div>
                                </div>
    
                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <div class="ntb-form-group">
                                        <label for="slug">Slug <span class="text-danger">*</span></label>
                                        <input type="text" id="slug" name="ntb_slug" class="form-control" value="<?php echo esc_attr($lp_data['slug']); ?>">
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="judul">Judul <span class="text-danger">*</span></label>
                                        <select id="judul" name="ntb_judul" class="form-select">
                                            <option <?php selected($lp_data['judul'], 'isi Manual (Rekomendasi)'); ?>>isi Manual (Rekomendasi)</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="auto_refresh">Auto Refresh <span class="text-danger">*</span></label>
                                        <select id="auto_refresh" name="ntb_auto_refresh" class="form-select">
                                            <option <?php selected($lp_data['auto_refresh'], 'Ketika halaman dimuat'); ?>>Ketika halaman dimuat</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="tampilan_perangkat">Tampilan Perangkat <span class="text-danger">*</span></label>
                                        <select id="tampilan_perangkat" name="ntb_tampilan_perangkat" class="form-select">
                                            <option <?php selected($lp_data['tampilan_perangkat'], 'Semua Perangkat (kecuali Bot)'); ?>>Semua Perangkat (kecuali Bot)</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="jumlah_gambar">Jumlah Gambar Ditampilkan <span class="text-danger">*</span></label>
                                        <input type="number" id="jumlah_gambar" name="ntb_jumlah_gambar" class="form-control" value="<?php echo esc_attr($lp_data['jumlah_gambar']); ?>">
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="cloaking_url">Cloaking URL</label>
                                        <input type="text" id="cloaking_url" name="ntb_cloaking_url" class="form-control" value="<?php echo esc_attr($lp_data['cloaking_url']); ?>">
                                    </div>
                                </div>
                            </div>
                            <!-- Bawah -->
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- URL Video -->
                                    <div class="ntb-form-group">
                                        <div class="ntb-simple-view">
                                            <label>URL Video</label>
                                            <div id="ntb-video-urls-container">
                                                <?php foreach($lp_data['url_video'] as $index => $url): ?>
                                                <div class="input-group <?php if ($index > 0) { echo 'mt-2'; } ?>">
                                                    <input type="text" name="ntb_url_video[]" class="form-control" placeholder="https://example.com/video.mp4" value="<?php echo esc_attr($url); ?>">
                                                    <input type="file" class="ntb-file-input" style="display: none;" accept="video/*">
                                                    <button class="btn btn-primary ntb-media-btn" type="button"><span class="dashicons dashicons-cloud-upload"></span></button>
                                                    <?php if($index > 0): ?>
                                                    <button class="btn btn-danger ntb-remove-btn" type="button"><span class="dashicons dashicons-trash"></span></button>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="ntb-advance-view" style="display: none;">
                                            <button class="btn btn-primary w-100 mb-2 ntb-bulk-upload-btn" type="button"><span class="dashicons dashicons-cloud-upload"></span> Upload Video</button>
                                            <input type="file" class="ntb-bulk-file-input" style="display: none;" accept="video/*" multiple>
                                            <textarea name="ntb_url_video_bulk" class="form-control" rows="4" placeholder="Contoh:&#10;https://example.com/video.mp4&#10;https://example.com/video2.mp4"><?php echo esc_textarea(implode("\n", $lp_data['url_video'])); ?></textarea>
                                        </div>
                                        <div class="ntb-field-footer">
                                            <a href="#" class="ntb-add-repeater" data-container="ntb-video-urls-container" data-name="ntb_url_video" data-placeholder="https://example.com/video.mp4" data-accept="video/*">+ Tambahkan video</a>
                                            <div class="ntb-advance-toggle">
                                                <span>Advance</span>
                                                <label class="ntb-switch small-switch">
                                                    <input type="checkbox" name="ntb_video_advance">
                                                    <span class="ntb-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- URL Gambar -->
                                    <div class="ntb-form-group">
                                        <div class="ntb-simple-view">
                                            <label>URL Gambar (Long screenshot) <a href="#" class="sample-link">Sample</a></label>
                                            <div id="ntb-image-urls-container">
                                                <?php foreach($lp_data['url_gambar'] as $index => $url): ?>
                                                <div class="input-group <?php if ($index > 0) { echo 'mt-2'; } ?>">
                                                    <input type="text" name="ntb_url_gambar[]" class="form-control" placeholder="https://example.com/image.jpg" value="<?php echo esc_attr($url); ?>">
                                                    <input type="file" class="ntb-file-input" style="display: none;" accept="image/*">
                                                    <button class="btn btn-primary ntb-media-btn" type="button"><span class="dashicons dashicons-cloud-upload"></span></button>
                                                    <?php if($index > 0): ?>
                                                    <button class="btn btn-danger ntb-remove-btn" type="button"><span class="dashicons dashicons-trash"></span></button>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="ntb-advance-view" style="display: none;">
                                            <button class="btn btn-primary w-100 mb-2 ntb-bulk-upload-btn" type="button"><span class="dashicons dashicons-cloud-upload"></span> Upload Image</button>
                                            <input type="file" class="ntb-bulk-file-input" style="display: none;" accept="image/*" multiple>
                                            <textarea name="ntb_url_gambar_bulk" class="form-control" rows="4" placeholder="Contoh:&#10;https://example.com/image1.jpg&#10;https://example.com/image2.jpg"><?php echo esc_textarea(implode("\n", $lp_data['url_gambar'])); ?></textarea>
                                        </div>
                                        <div class="ntb-field-footer">
                                            <a href="#" class="ntb-add-repeater" data-container="ntb-image-urls-container" data-name="ntb_url_gambar" data-placeholder="https://example.com/image.jpg" data-accept="image/*">+ Tambahkan Gambar</a>
                                            <div class="ntb-advance-toggle">
                                                <span>Advance</span>
                                                <label class="ntb-switch small-switch">
                                                    <input type="checkbox" name="ntb_gambar_advance">
                                                    <span class="ntb-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="ntb-form-group">
                                        <label for="parameter">Parameter</label>
                                        <div class="input-group">
                                            <input type="text" name="ntb_param_key" class="form-control" value="<?php echo esc_attr($lp_data['param_key']); ?>">
                                            <span class="input-group-text">=</span>
                                            <input type="text" name="ntb_param_value" class="form-control" value="<?php echo esc_attr($lp_data['param_value']); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="ntb-form-group col-md-6">
                                            <label>Rentang Waktu Jeda Video (0-30 Detik)</label>
                                            <div class="ntb-range-slider">
                                                <div class="slider-track" data-min="0" data-max="30"></div>
                                                <div class="slider-inputs">
                                                    <input type="number" name="ntb_jeda_video_min" class="form-control range-value-min" value="<?php echo esc_attr($lp_data['jeda_video_min']); ?>">
                                                    <span class="range-separator"><-></span>
                                                    <input type="number" name="ntb_jeda_video_max" class="form-control range-value-max" value="<?php echo esc_attr($lp_data['jeda_video_max']); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ntb-form-group col-md-6">
                                            <label>Rentang Waktu Refresh Otomatis (0-30 Detik)</label>
                                            <div class="ntb-range-slider">
                                                <div class="slider-track" data-min="0" data-max="30"></div>
                                                <div class="slider-inputs">
                                                    <input type="number" name="ntb_waktu_refresh_min" class="form-control range-value-min" value="<?php echo esc_attr($lp_data['waktu_refresh_min']); ?>">
                                                    <span class="range-separator"><-></span>
                                                    <input type="number" name="ntb_waktu_refresh_max" class="form-control range-value-max" value="<?php echo esc_attr($lp_data['waktu_refresh_max']); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="judul_manual">Judul Manual</label>
                                        <input type="text" id="judul_manual" name="ntb_judul_manual" class="form-control" value="<?php echo esc_attr($lp_data['judul_manual']); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="ntb-form-group">
                                        <div class="ntb-field-header">
                                            <label for="daftar_artikel">Daftar URL Artikel <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-2">
                                                <button id="ntb-get-articles-btn" class="btn btn-danger btn-sm ntb-header-btn" type="button" data-nonce="<?php echo wp_create_nonce('ntb_get_articles_nonce'); ?>">
                                                    <span class="dashicons dashicons-admin-links"></span> Dapatkan URL Artikel
                                                </button>
                                                <button class="btn btn-success btn-sm ntb-header-btn" type="button">
                                                    <span class="dashicons dashicons-search"></span> Dapatkan Link SEO
                                                </button>
                                            </div>
                                        </div>
                                        <textarea id="daftar_artikel" name="ntb_daftar_artikel" class="form-control" rows="5"><?php echo esc_textarea($lp_data['daftar_artikel']); ?></textarea>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="deskripsi_manual">Deskripsi Manual</label>
                                        <textarea id="deskripsi_manual" name="ntb_deskripsi_manual" class="form-control" rows="5"><?php echo esc_textarea($lp_data['deskripsi_manual']); ?></textarea>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="inject_keywords">Inject Keywords</label>
                                        <textarea id="inject_keywords" name="ntb_inject_keywords" class="form-control" rows="5" placeholder="Pisahkan dengan koma | keyword1,keyword"><?php echo esc_textarea($lp_data['inject_keywords']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="ntb-form-footer">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-landing-page')); ?>" class="button">Batal</a>
                    <button type="submit" name="ntb_update_lp" class="button button-primary">
                        <span class="dashicons dashicons-saved"></span> Update
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- START: Modal untuk Dapatkan Artikel -->
    <div class="modal fade" id="getArticlesModal" tabindex="-1" aria-labelledby="getArticlesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="getArticlesModalLabel">Atur Jumlah URL Artikel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="get-articles-form">
                <div class="mb-3">
                    <label for="article_count" class="form-label">Jumlah URL yang akan diambil</label>
                    <input type="number" class="form-control" id="article_count" name="article_count" value="10" min="1">
                    <small class="form-text text-muted">Masukkan -1 untuk mengambil semua artikel yang diterbitkan.</small>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="submit-get-articles">Dapatkan URL</button>
            </div>
            </div>
        </div>
    </div>
    <!-- END: Modal -->
</div>

<style>
    .ntb-main-content {
        background: #fff;
        border: 1px solid #c3c4c7;
        border-radius: 10px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 20px;
        margin: 20px;
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
    .ntb-breadcrumb { padding: 15px 20px; color: #50575e; font-size: 14px; border-bottom: 1px solid #e0e0e0; }
    .ntb-breadcrumb a { text-decoration: none; color: #0073aa; }
    .ntb-breadcrumb a:hover { text-decoration: underline; }
    .ntb-breadcrumb span { color: #1d2327; font-weight: 600; }

    #ntb-lp-builder { margin: 0px 0px 0px -15px; }
    .ntb-form-container { background: #fff; border-radius: 20px; padding: 0px; margin-top: 10px; }
    .ntb-form-group { margin-bottom: 1rem; }
    .ntb-form-group label { margin-bottom: 0.5rem; display: block;font-size: 12px;font-weight: 600; }
    .ntb-form-group a { text-decoration: none; font-size: 12px; }
    
    .ntb-form-footer { margin-top: 20px; padding: 15px 20px; background: #fff; border-top: 1px solid #c3c4c7; text-align: right;}
    .ntb-form-footer .button { font-size: 14px; height: auto; padding: 2px 16px; }
    .ntb-form-footer .button .dashicons { vertical-align: middle; margin-top: -2px; }

    /* New styles for media inputs */
    .ntb-media-btn, .ntb-remove-btn {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .ntb-field-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
    }
    .ntb-advance-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ntb-advance-toggle label{
        margin-top: 10px;
        font-size: 12px;
        color: #50575e;
    }
    .ntb-advance-toggle span {
        font-size: 12px;
        color: #50575e;
    }
    .sample-link {
        font-size: 12px;
        font-weight: normal;
        margin-left: 5px;
    }

    /* General Toggle Switch Styling */
    .ntb-switch {
        position: relative;
        display: inline-block;
    }
    .ntb-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .ntb-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 20px;
    }
    .ntb-slider:before {
        position: absolute;
        content: "";
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .ntb-slider { background-color: #007bff; }
    input:focus + .ntb-slider { box-shadow: 0 0 1px #007bff; }
    
    /* Smaller toggle switch */
    .ntb-switch.small-switch {
        width: 34px;
        height: 20px;
    }
    .ntb-switch.small-switch .ntb-slider:before {
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
    }
    .ntb-switch.small-switch input:checked + .ntb-slider:before {
        transform: translateX(14px);
    }

    /* Range Slider Styling */
    .ntb-range-slider {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .ntb-range-slider .slider-track { margin: 10px 0; }
    .ntb-range-slider .ui-slider {
        background: #e9e9e9;
        border: none;
        height: 6px;
    }
    .ntb-range-slider .ui-slider-range { background: #007bff; }
    .ntb-range-slider .ui-slider-handle {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #007bff;
        cursor: pointer;
        top: -6px;
    }
    .ntb-range-slider .slider-inputs {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }
    .ntb-range-slider .range-value-min,
    .ntb-range-slider .range-value-max {
        width: 60px;
        text-align: center;
    }
    .ntb-range-slider .range-separator { font-weight: bold; }

    /* Field Header Styling */
    .ntb-field-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .ntb-field-header label { margin-bottom: 0; }
    .ntb-header-btn .dashicons {
        margin-right: 5px;
        font-size: 16px;
        vertical-align: text-top;
    }
</style>