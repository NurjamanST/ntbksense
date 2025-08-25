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
add_action('admin_footer', function () {
    // Pastikan ID layar cocok dengan halaman edit
    $screen = get_current_screen();
    // **FIX:** Changed screen ID to match WordPress format for hidden admin pages
    if ('admin_page_ntbksense-edit-lp' !== $screen->id) {
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
    <script>
        jQuery(document).ready(function($) {

            // ===================================================================
            // KODE UNTUK INTERAKSI UI (Slider, Repeater, Toggle, Modal, dll)
            // ===================================================================

            // Inisialisasi Range Sliders
            $(".ntb-range-slider").each(function() {
                // ... (kode slider lo yang sudah ada)
            });
            $('.range-value-min, .range-value-max').on('change', function() {
                // ... (kode sinkronisasi slider lo yang sudah ada)
            });

            // Logika Tambah/Hapus Input Repeater
            function addRepeaterField(e) {
                // ... (kode repeater lo yang sudah ada)
            }

            function removeRepeaterField(e) {
                // ... (kode repeater lo yang sudah ada)
            }
            $('.ntb-add-repeater').on('click', addRepeaterField);
            $(document).on('click', '.ntb-remove-btn', removeRepeaterField);

            // Logika Toggle Advance
            $('.ntb-advance-toggle input[type="checkbox"]').on('change', function() {
                // ... (kode toggle lo yang sudah ada)
            });

            // Logika Tombol Upload Media
            $(document).on('click', '.ntb-media-btn, .ntb-bulk-upload-btn', function(e) {
                // ... (kode upload media lo yang sudah ada)
            });
            $(document).on('change', '.ntb-file-input, .ntb-bulk-file-input', function() {
                // ... (kode upload media lo yang sudah ada)
            });

            // Logika Dapatkan URL Artikel dengan Modal
            $('#ntb-get-articles-btn').on('click', function(e) {
                // ... (kode modal lo yang sudah ada)
            });
            $('#submit-get-articles').on('click', function(e) {
                // ... (kode AJAX untuk get articles lo yang sudah ada)
            });
            // --- CHECKPOINT 2 ---
            // const $form = $('#ntb-update-landing');
            // console.log('Checkpoint 2: Mencari form #ntb-update-landing. Ditemukan:', $form.length);
            // Jika hasilnya "Ditemukan: 0", berarti ID form di HTML salah atau tidak ada.

            // --- LOGIKA SUBMIT FORM KE API ---
            // Target form update lo. Pastikan ID form di HTML adalah 'ntb-update-form'
            $('#ntb-update-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit cara lama

                const $form = $(this);
                const $submitButton = $form.find('button[type="submit"]');
                const originalButtonText = $submitButton.html();

                // Tampilkan status loading di tombol
                $submitButton.html('<span class="spinner is-active" style="float: left; margin-top: 4px;"></span> Menyimpan...');
                $submitButton.prop('disabled', true);

                // Kumpulkan semua data dari form
                const formData = {
                    id: $('#id').val(),
                    slug: $('#slug').val(),
                    status: '1', // Atau ambil dari input jika ada
                    title: $('#judul_manual').val(),
                    title_option: $('#judul option:selected').val(),
                    template_name: $('#template option:selected').text().trim(),
                    template_file: $('#template').val(),
                    description_option: $('#deskripsi option:selected').val(),
                    description: $('textarea[name="ntb_deskripsi_manual"]').val() || '',
                    inject_keywords: $('textarea[name="ntb_inject_keywords"]').val(),
                    post_urls: $('textarea[name="ntb_daftar_artikel"]').val(),
                    video_urls: JSON.stringify($('input[name="ntb_url_video[]"]').map((_, el) => $(el).val()).get().filter(url => url)),
                    universal_image_urls: JSON.stringify($('input[name="ntb_url_gambar[]"]').map((_, el) => $(el).val()).get().filter(url => url)),
                    landing_image_urls: '[]', // Ganti jika ada inputnya
                    number_images_displayed: parseInt($('input[name="ntb_jumlah_gambar"]').val(), 10) || 0,
                    redirect_post_option: $('#url_pengalihan option:selected').val(),
                    timer_auto_refresh: `${$('input[name="ntb_waktu_refresh_min"]').val()}-${$('input[name="ntb_waktu_refresh_max"]').val()}`,
                    auto_refresh_option: $('#auto_refresh option:selected').val(),
                    protect_elementor: $('#proteksi_fitur option:selected').val(),
                    referrer_option: $('#kontrol_penujuk option:selected').val(),
                    device_view: $('#tampilan_perangkat option:selected').val(),
                    videos_floating_option: $('#video_melayang option:selected').val(),
                    timer_auto_pause_video: `${$('input[name="ntb_jeda_video_min"]').val()}-${$('input[name="ntb_jeda_video_max"]').val()}`,
                    parameter_key: $('input[name="ntb_param_key"]').val(),
                    parameter_value: $('input[name="ntb_param_value"]').val(),
                    cloaking_url: $('input[name="ntb_cloaking_url"]').val(),
                    custom_html: '',
                    custom_template_builder: '[]',
                    random_template_method: 'random',
                    random_template_file: '[]',
                    random_template_file_afs: '[]'
                };

                // Ganti URL API ke endpoint untuk UPDATE
                const apiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/update.landing.php'); ?>';

                fetch(apiUrl, {
                        method: 'POST', // Bisa juga 'PUT'
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json()) // Langsung ubah ke JSON
                    .then(data => {
                        // 'data' adalah objek JSON dari wp_send_json_success/error
                        if (data.success) {
                            alert('Sukses! ' + data.data.message);
                            window.location.href = '<?php echo esc_url(admin_url('admin.php?page=ntbksense-landing-page')); ?>';
                        } else {
                            // Tampilkan pesan error dari server
                            alert('Gagal! ' + data.data.message);
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan. Cek console untuk detail.');
                    })
                    .finally(() => {
                        // Kembalikan tombol ke keadaan semula
                        $submitButton.html(originalButtonText);
                        $submitButton.prop('disabled', false);
                    });
            });
        });
    </script>
<?php
});
?>

<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

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
        <hr>
        <?php

        $apiUrl = esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/read.landing.php');
        $ch = curl_init($apiUrl . '?id=' . $landing_page_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
// var_dump($data);
        
        $mRefresh = $data['timer_auto_refresh'];
        $mValue = $data['timer_auto_pause_video'];

        if (isset($mRefresh) and $mRefresh != "0") {
            list($min_refresh, $max_refresh) = explode('-', $mRefresh);
        } else {
            $min_refresh = 0;
            $max_refresh = 0;
        }

        if (isset($mValue) and $mValue != '0') {
            list($min_value, $max_value) = explode('-', $mValue);
        } else {
            $min_value = 0;
            $max_value = 0;
        }

        // var_dump($min_refresh, $max_refresh);
        // var_dump($min_value, $max_value);
        ?>

        <?php if (!$lp_data): ?>
            <div class="notice notice-error">
                <p><?php _e('Data Landing Page dengan ID yang diminta tidak ditemukan.', 'ntbksense'); ?></p>
            </div>
        <?php else: ?>
            <form method="post" action="" id="ntb-update-form">
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
                                        <input type="hidden" id="id" value="<?php echo esc_attr($data['id']); ?>" disabled>
                                        <label for="template">Template <span class="text-danger">*</span></label>
                                        <select id="template" name="ntb_template" class="form-select">
                                            <option value="<?= $data['template_name'] ?>"><?= $data['template_name'] ?></option>
                                            <option>Video (Long screenshot)</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="url_pengalihan">URL Pengalihan <span class="text-danger">*</span></label>
                                        <select id="url_pengalihan" name="ntb_url_pengalihan" class="form-select">
                                            <?php
                                            // Ambil nilai yang tersimpan di database.
                                            // Kasih nilai default 'post_urls' kalau datanya belum ada.
                                            $saved_value = $data['redirect_post_option'] ?? 'custom';
                                            ?>
                                            <option value="custom';" <?php selected($saved_value, 'custom'); ?>>Gunakan URL dari Daftar Artikel (Acak)</option>
                                            <option value="cloaking_url" <?php selected($saved_value, 'cloaking_url'); ?>>Gunakan URL Cloaking Utama</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                                        <select id="deskripsi" name="ntb_deskripsi" class="form-select">
                                            <?php
                                            // Ambil nilai yang tersimpan di database.
                                            // Kasih nilai default 'custom' kalau datanya belum ada.
                                            $saved_value = $data['description_option'] ?? 'custom';
                                            ?>
                                            <option value="custom" <?php selected($saved_value, 'custom'); ?>>Isi Manual (Rekomendasi)</option>
                                            <option value="auto" <?php selected($saved_value, 'auto'); ?>>Deskripsi Otomatis</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="kontrol_penujuk">Kontrol Penunjuk <span class="text-danger">*</span></label>
                                        <select id="kontrol_penujuk" name="ntb_kontrol_penujuk" class="form-select">
                                            <?php
                                            // Ambil nilai yang tersimpan di database.
                                            // Kasih nilai default 'on' kalau datanya belum ada.
                                            $saved_value = $data['referrer_option'] ?? 'on';
                                            ?>
                                            <option value="on" <?php selected($saved_value, 'on'); ?>>Izinkan Semua Trafik</option>
                                            <option value="off" <?php selected($saved_value, 'off'); ?>>Blokir Trafik Tanpa Penunjuk</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="video_melayang">Video Melayang <span class="text-danger">*</span></label>
                                        <select id="video_melayang" name="ntb_video_melayang" class="form-select">
                                            <?php
                                            // Ambil nilai yang tersimpan di database.
                                            // Kasih nilai default 'off' kalau datanya belum ada.
                                            $saved_value = $data['videos_floating_option'] ?? 'off';
                                            ?>
                                            <option value="on" <?php selected($saved_value, 'on'); ?>>On</option>
                                            <option value="off" <?php selected($saved_value, 'off'); ?>>Off</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="proteksi_fitur">
                                            Proteksi fitur inspeksi elemen <small class="text-danger">{Margin if error 1.2%}</small>
                                        </label>
                                        <select id="proteksi_fitur" name="ntb_proteksi_fitur" class="form-select">
                                            <?php
                                            // Ambil nilai yang tersimpan di database.
                                            // Kasih nilai default 'off' kalau datanya belum ada.
                                            $saved_value = $data['protect_elementor'] ?? 'off';
                                            ?>
                                            <option value="on" <?php selected($saved_value, 'on'); ?>>Aktifkan (Pause Debugger)</option>
                                            <option value="off" <?php selected($saved_value, 'off'); ?>>Nonaktifkan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <div class="ntb-form-group">
                                        <label for="slug">Slug <span class="text-danger">*</span></label>
                                        <input type="text" id="" name="" class="form-control" value="<?php echo esc_attr($data['slug']); ?>" disabled>
                                        <input type="hidden" id="slug" name="ntb_slug" class="form-control" value="<?php echo esc_attr($data['slug']); ?>">
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="judul">Judul <span class="text-danger">*</span></label>
                                        <select id="judul" name="ntb_judul" class="form-select">
                                            <?php
                                            // Ambil nilai yang tersimpan di database.
                                            // Kasih nilai default 'manual' kalau datanya belum ada.
                                            $saved_value = $data['title_option'] ?? 'manual';
                                            ?>
                                            <option value="manual" <?php selected($saved_value, 'manual'); ?>>Isi Manual (rekomendasi)</option>
                                            <option value="auto" <?php selected($saved_value, 'auto'); ?>>Judul Otomatis</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="auto_refresh">Auto Refresh <span class="text-danger">*</span></label>
                                        <select id="auto_refresh" name="ntb_auto_refresh" class="form-select">
                                            <?php
                                            // Ambil nilai yang tersimpan di database.
                                            // Kasih nilai default 'off' kalau datanya belum ada.
                                            $saved_value = $data['auto_refresh_option'] ?? 'off';
                                            ?>
                                            <option value="on_load" <?php selected($saved_value, 'on_load'); ?>>Ketika halaman dimuat</option>
                                            <option value="off" <?php selected($saved_value, 'off'); ?>>Jangan Refresh</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="tampilan_perangkat">Tampilan Perangkat <span class="text-danger">*</span></label>
                                        <select id="tampilan_perangkat" name="ntb_tampilan_perangkat" class="form-select">
                                            <?php
                                            // Ambil nilai yang tersimpan di database.
                                            // Kasih nilai default 'semua' kalau datanya belum ada.
                                            $saved_value = $data['device_view'] ?? 'semua';
                                            ?>
                                            <option value="semua" <?php selected($saved_value, 'semua'); ?>>Semua Perangkat (kecuali Bot)</option>
                                            <option value="fb_browser" <?php selected($saved_value, 'fb_browser'); ?>>Hanya Browser FB/IG</option>
                                            <option value="ponsel" <?php selected($saved_value, 'ponsel'); ?>>Hanya Ponsel</option>
                                            <option value="desktop" <?php selected($saved_value, 'desktop'); ?>>Hanya Desktop</option>
                                        </select>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="jumlah_gambar">Jumlah Gambar Ditampilkan <span class="text-danger">*</span></label>
                                        <input type="number" id="jumlah_gambar" name="ntb_jumlah_gambar" class="form-control" value="<?php echo esc_attr($data['number_images_displayed']); ?>">
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="cloaking_url">Cloaking URL</label>
                                        <input type="text" id="cloaking_url" name="ntb_cloaking_url" class="form-control" value="<?php echo esc_attr($data['cloaking_url']); ?>">
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
                                                <?php
                                                // 1. Ambil data JSON dari database.
                                                $video_urls_json = $data['video_urls'] ?? '[]';

                                                // 2. Ubah JSON jadi array PHP.
                                                $video_urls = json_decode($video_urls_json, true);

                                                // 3. Jika datanya kosong atau bukan array, buat satu input default.
                                                if (empty($video_urls) || !is_array($video_urls)) {
                                                    $video_urls = ['']; // Default dengan satu input kosong
                                                }
                                                ?>

                                                <?php foreach ($video_urls as $index => $url): ?>
                                                    <div class="input-group <?php if ($index > 0) echo 'mt-2'; ?>">
                                                        <input type="text" name="ntb_url_video[]" class="form-control" placeholder="https://example.com/video.mp4" value="<?php echo esc_attr($url); ?>">
                                                        <input type="file" class="ntb-file-input" style="display: none;" accept="video/*">
                                                        <button class="btn btn-primary ntb-media-btn" type="button"><span class="dashicons dashicons-cloud-upload"></span></button>

                                                        <?php if ($index > 0): ?>
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
                                                <?php
                                                // 1. Ambil data JSON dari database.
                                                $image_urls_json = $data['universal_image_urls'] ?? '[]';

                                                // 2. Ubah JSON jadi array PHP.
                                                $image_urls = json_decode($image_urls_json, true);

                                                // 3. Jika datanya kosong atau bukan array, buat satu input default.
                                                if (empty($image_urls) || !is_array($image_urls)) {
                                                    $image_urls = ['']; // Default dengan satu input kosong
                                                }
                                                ?>

                                                <?php foreach ($image_urls as $index => $url): ?>
                                                    <div class="input-group <?php if ($index > 0) echo 'mt-2'; ?>">
                                                        <input type="text" name="ntb_url_gambar[]" class="form-control" placeholder="https://example.com/image.jpg" value="<?php echo esc_attr($url); ?>">
                                                        <input type="file" class="ntb-file-input" style="display: none;" accept="image/*">
                                                        <button class="btn btn-primary ntb-media-btn" type="button"><span class="dashicons dashicons-cloud-upload"></span></button>

                                                        <?php if ($index > 0): ?>
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
                                            <input type="text" name="ntb_param_key" class="form-control" value="<?php echo esc_attr($data['parameter_key']); ?>">
                                            <span class="input-group-text">=</span>
                                            <input type="text" name="ntb_param_value" class="form-control" value="<?php echo esc_attr($data['parameter_value']); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="ntb-form-group col-md-6">
                                            <label>Rentang Waktu Jeda Video (0-30 Detik)</label>
                                            <div class="ntb-range-slider">
                                                <div class="slider-track" data-min="0" data-max="30"></div>
                                                <div class="slider-inputs">
                                                    <?php //include "ntb_jeda_video.php"; ?>
                                                    <input type="number" name="ntb_jeda_video_min" class="form-control range-value-min" value="<?php echo esc_attr($min_value); ?>">
                                                    <span class="range-separator"><-></span>
                                                    <input type="number" name="ntb_jeda_video_max" class="form-control range-value-max" value="<?php echo esc_attr($max_value); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ntb-form-group col-md-6">
                                            <label>Rentang Waktu Refresh Otomatis (0-30 Detik)</label>
                                            <div class="ntb-range-slider">
                                                <div class="slider-track" data-min="0" data-max="30"></div>
                                                <div class="slider-inputs">
                                                    <?php //include "ntb_waktu_refresh.php"; ?>
                                                    <input type="number" name="ntb_waktu_refresh_min" class="form-control range-value-min" value="<?php echo esc_attr($min_refresh); ?>">
                                                    <span class="range-separator"><-></span>
                                                    <input type="number" name="ntb_waktu_refresh_max" class="form-control range-value-max" value="<?php echo esc_attr($max_refresh); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="judul_manual">Judul Manual</label>
                                        <input type="text" id="judul_manual" name="ntb_judul_manual" class="form-control" value="<?php echo esc_attr($data['title']); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="ntb-form-group">
                                        <div class="ntb-field-header">
                                            <label for="daftar_artikel">Daftar URL Artikel <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-2">
                                                <button id="ntb-get-articles-btn" class="btn btn-warning btn-sm ntb-header-btn" type="button" data-nonce="<?php echo wp_create_nonce('ntb_get_articles_nonce'); ?>">
                                                    <span class="dashicons dashicons-admin-links"></span> Dapatkan URL Artikel
                                                </button>
                                                <button class="btn btn-primary btn-sm ntb-header-btn" type="button">
                                                    <span class="dashicons dashicons-search"></span> Dapatkan Link SEO
                                                </button>
                                            </div>
                                        </div>
                                        <textarea id="daftar_artikel" name="ntb_daftar_artikel" class="form-control" rows="5"><?php echo esc_textarea($data['post_urls'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="deskripsi_manual">Deskripsi Manual</label>
                                        <textarea id="deskripsi_manual" name="ntb_deskripsi_manual" class="form-control" rows="5"><?php echo esc_textarea($data['description']); ?></textarea>
                                    </div>
                                    <div class="ntb-form-group">
                                        <label for="inject_keywords">Inject Keywords</label>
                                        <textarea id="inject_keywords" name="ntb_inject_keywords" class="form-control" rows="5" placeholder="Pisahkan dengan koma | keyword1,keyword"><?php echo esc_textarea($data['inject_keywords']); ?></textarea>
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

    <?php include "getArticlesModalLabel.php"; ?>
</div>

<?php 
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>