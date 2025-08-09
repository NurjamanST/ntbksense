<?php
// =========================================================================
// FILE: ntbksense/admin/views/LandingPage/ntbksense-landing-page.php
// =========================================================================

/**
 * Tampilan untuk halaman admin Landing Page.
 *
 * Menggunakan tabel HTML standar yang disempurnakan dengan DataTables.js.
 *
 * @package    Ntbksense
 * @subpackage Ntbksense/admin/views
 * @author     Nama Anda <email@example.com>
 */

// Menambahkan aset dan skrip DataTables ke footer halaman admin.
// Ini adalah cara yang andal untuk memastikan jQuery dan DOM siap.
add_action('admin_footer', function () {
    // Periksa apakah kita berada di halaman yang benar sebelum memuat aset
    $screen = get_current_screen();
    if ('ntbksense_page_ntbksense-landing-page' !== $screen->id) {
        return;
    }
?>
    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Inisialisasi jQuery -->
    <script>
        jQuery(document).ready(function($) {
            // Inisialisasi DataTables (kode ini tetap sama)
            $('#ntb-landing-page-table').DataTable({
                "lengthMenu": [5, 10, 25, 50, 100],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                    "zeroRecords": "<span class='text-danger fs-1'>Tidak ada data yang ditemukan..</span><br><span style='font-size:100px;'>🤨</span>",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(Difilter dari _MAX_ total entri)",
                    "search": "",
                    "searchPlaceholder": "Cari...",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    },
                },
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
            });

            // --- START: Kode Baru untuk Notifikasi dan Salin ---

            // Fungsi untuk menampilkan notifikasi
            function showCopyNotification(message) {
                const notification = $('#ntb-copy-notification');
                notification.html(message);
                notification.addClass('show');
                setTimeout(function() {
                    notification.removeClass('show');
                }, 3000);
            }

            // Event handler gabungan untuk semua tombol salin
            $(document).on('click', '.copy-btn, .ntb-param-copy-btn', function() {
                const $button = $(this);
                const $inputGroup = $button.closest('.url-lp-input-group, .ntb-param-group');
                const inputElement = $inputGroup.find('input[type="text"]')[0];
                let valueToCopy = inputElement.value;
                let message;

                // Logika untuk menentukan nilai dan pesan yang akan disalin
                if ($button.hasClass('ntb-param-copy-btn')) {
                    message = `Parameter berhasil disalin!<br><strong style="font-size: 12px;">${valueToCopy}</strong>`;
                } else if ($button.hasClass('copydanger-btn')) {
                    valueToCopy = $(inputElement).data('ads-url'); // Ambil URL Ads dari data attribute
                    message = `URL Adsense berhasil disalin!<br><strong style="font-size: 12px;">${valueToCopy}</strong>`;
                } else {
                    message = `URL Public berhasil disalin!<br><strong style="font-size: 12px;">${valueToCopy}</strong>`;
                }

                // Buat elemen textarea sementara untuk menyalin teks
                const tempTextArea = document.createElement('textarea');
                tempTextArea.value = valueToCopy;
                document.body.appendChild(tempTextArea);
                tempTextArea.select();
                tempTextArea.setSelectionRange(0, 99999); // Untuk mobile

                try {
                    if (document.execCommand("copy")) {
                        showCopyNotification(message);
                    } else {
                        alert('Gagal menyalin. Silakan salin secara manual.');
                    }
                } catch (err) {
                    console.error('Error saat menyalin:', err);
                    alert('Gagal menyalin. Silakan salin secara manual.');
                } finally {
                    document.body.removeChild(tempTextArea); // Selalu hapus elemen sementara
                }
            });

            // --- END: Kode Baru ---

            // Fungsionalitas untuk checkbox "Pilih Semua" (kode ini tetap sama)
            $('#select-all-lp').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('#ntb-landing-page-table tbody input[type="checkbox"]').prop('checked', isChecked);
            });
        });
    </script>
<?php
});

// START: Generate 100 dummy data entries
$data = [];
$templates = ['Sales', 'Video', 'Default', 'Lead Gen'];
$parameter = ['Parameter 1', 'Parameter 2', 'Parameter 3', 'Parameter 4', 'Parameter 5'];
$parameter2 = ['Value 1', 'Value 2', 'Value 3', 'Value 4', 'Value 5'];
$statuses = ['Aktif', 'Tidak Aktif'];
$devices = ['Desktop', 'Mobile', 'Desktop, Mobile', 'Chrome, Firefox', 'Safari', 'Edge'];
$gambar = ['logotasik.png'];

for ($i = 1; $i <= 3; $i++) {
    $random_timestamp = time() - rand(0, 365 * 24 * 60 * 60); // Random date in the last year
    $data[] = [
        'id'        => $i,
        'template'  => $templates[array_rand($templates)],
        'title'     => 'Whatch Now #' . $i,
        'url_public'    => 'https://developer.wordpress.org/plugins/',
        'url_ads'    => 'https://developer.wordpress.org/plugins/',
        'text_mode_key'    => 'n^t*b@k%sense',
        'parameter' => $parameter[array_rand($parameter)] . "=" . $parameter2[array_rand($parameter2)],
        'status'    => $statuses[array_rand($statuses)],
        'device'    => $devices[array_rand($devices)],
        'date'      => date('Y-m-d H:i:s', $random_timestamp),
        'gambar'    => $gambar[array_rand($gambar)],
    ];
}
// END: Generate 100 dummy data entries

?>
<div class="wrap" id="ntbksense-wrapper">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- START: Breadcrumb Kustom -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Landing Page</span>
    </div>
    <!-- END: Breadcrumb Kustom -->

    <div class="ntb-main-content">
        <h2 class="ntb-main-title"><span class="dashicons dashicons-editor-ul"></span> Landing Page</h2>
        <hr>
        <!-- Tombol Aksi Utama -->
        <div class="ntb-actions-bar">
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-create-landing')); ?>" class="button ntb-btn-primary">
                <span class="dashicons dashicons-plus-alt"></span>LP Baru
            </a>
            <a href="#" class="button ntb-btn-danger"><span class="dashicons dashicons-trash"></span>Hapus</a>
            <a href="#" class="button ntb-btn-success"><span class="dashicons dashicons-upload"></span>Ekspor</a>
            <a href="#" class="button"><span class="dashicons dashicons-download"></span>Impor</a>
            <a href="#" class="button ntb-btn-warning"><span class="dashicons dashicons-admin-page"></span>Duplikat</a>
        </div>

        <!-- Tabel HTML Standar untuk DataTables -->
        <table id="ntb-landing-page-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-lp" />&nbsp;&nbsp;&nbsp;</th>
                    <th>Templat</th>
                    <th>Judul Templat</th>
                    <th>URL LP</th>
                    <th>Parameter</th>
                    <th>Status</th>
                    <th>Tampilan Perangkat</th>
                    <th>Tanggal Dibuat (WIB)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Kodingan cURL asli lo, TIDAK DIUBAH
                // $ApiUrl = 'http://ntbksenseapi.test/api/landing_page/read.landing.php';
                $ApiUrl = esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/read.landing.php');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $ApiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                $data = json_decode($response, true);
                // var_dump($data); // Debugging line to check the structure of $data


                if (isset($data) && is_array($data)) {
                    foreach ($data['data'] as $row) {
                        // echo $row['id'];

                ?>
                        <tr>
                            <td><input type="checkbox" name="lp[]" value="<?= $row['id'] ?? '' ?>" /></td>
                            <td>
                                <div class="d-flex w-100 justify-content-start align-items-center">
                                    <div class="d-flex flex-column">
                                        <span><?= $row['template_name'] ?? 'Tanpa Nama Template' ?></span>
                                        <div class="row-actions">
                                            <a style="text-decoration:none;" href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-edit-lp&id=' . ($row['id'] ?? ''))); ?>" class="text-primary">
                                                <span class="dashicons dashicons-edit"></span>
                                            </a>
                                            <a style="text-decoration:none;" href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-duplicate-lp&id=' . ($row['id'] ?? ''))); ?>" class="text-secondary">
                                                <span class="dashicons dashicons-admin-page"></span>
                                            </a>
                                            <a style="text-decoration:none;" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=ntbksense-landing-page&action=delete&id=' . ($row['id'] ?? '')), 'ntb_delete_lp_nonce')); ?>" class="text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus landing page ini?');">
                                                <span class="dashicons dashicons-trash"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p><?php echo htmlspecialchars($row['title'] ?? 'Tanpa Judul'); ?></p>
                            </td>
                            <?php

                            $baseUrl = home_url(); // Gunakan fungsi WordPress untuk base URL
                            $adsUrl = "{$baseUrl}/redirect_ads.php?slug=" . urlencode($row['slug']);
                            $cekURL = "{$baseUrl}/redirect_ads.php?slug=" . urlencode($row['slug']) . "&mode=ads";


                            ?>
                            <td>
                                <div class="url-lp-input-group">
                                    <input type="text" value="<?php echo $adsUrl ?>" data-ads-url="<?php echo $cekURL ?>" disabled class="url-lp-input">
                                    <button class="url-lp-action-btn copy-btn" title="Salin URL Public">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy">
                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2v2" />
                                        </svg>
                                    </button>
                                    <button class="url-lp-action-btn copy-btn copydanger-btn" title="Salin URL Adsense">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy">
                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2v2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="ntb-param-group">
                                    <input type="text" value="<?= $row['parameter_key'] ?> = <?= $row['parameter_value'] ?>" disabled class="ntb-param-input">
                                    <button class="ntb-param-copy-btn" title="Salin Parameter">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy">
                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2v2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <label class="ntb-switch">
                                    <input type="checkbox" class="ntb-status-toggle" data-id="<?php echo $row['id'] ?? ''; ?>" <?php checked(($row['status'] ?? 'Tidak Aktif'), 'Aktif'); ?>>
                                    <span class="ntb-slider"></span>
                                </label>
                            </td>
                            <td><small><?php echo htmlspecialchars($row['device_view'] ?? '-'); ?></small></td>
                            <td><small><?php echo htmlspecialchars($row['created_at'] ?? '-'); ?></small></td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="8" class="dataTables_empty">Tidak ada data yang tersedia</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- START: Elemen Notifikasi (Tambahkan ini) -->
    <div id="ntb-copy-notification"></div>
    <!-- END: Elemen Notifikasi -->
</div>

<style>
    /* General Layout & Main Content Box */
    #ntbksense-landing-page-wrapper {
        background-color: #ffffffff;
        padding: 0;
        margin: 0px 0px 0px -15px;
        /* Override default .wrap margin */
    }

    .ntb-main-content {
        background: #fff;
        border: 1px solid #c3c4c7;
        border-radius: 10px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        padding: 20px;
        margin: 20px;
    }

    .ntb-main-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 8px;
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

    .ntb-navbar-title {
        font-size: 16px;
        font-weight: 600;
        color: #1d2327;
    }

    .ntb-navbar-version {
        font-size: 12px;
        color: #646970;
        margin-left: 8px;
        background-color: #f0f0f1;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .ntb-navbar-right .ntb-navbar-icon {
        color: #50575e;
        text-decoration: none;
        margin-left: 15px;
    }

    .ntb-navbar-right .ntb-navbar-icon .dashicons {
        font-size: 20px;
        vertical-align: middle;
    }

    /* Breadcrumb Styling */
    .ntb-breadcrumb {
        padding: 15px 20px;
        color: #50575e;
        font-size: 14px;
        border-bottom: 1px solid #e0e0e0;
    }

    .ntb-breadcrumb a {
        text-decoration: none;
        color: #0073aa;
    }

    .ntb-breadcrumb a:hover {
        text-decoration: underline;
    }

    .ntb-breadcrumb span {
        color: #1d2327;
        font-weight: 600;
    }

    /* Action Buttons Bar */
    .ntb-actions-bar {
        margin-bottom: 20px;
    }

    .ntb-actions-bar .button {
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .ntb-actions-bar .button .dashicons {
        font-size: 18px;
        line-height: 1;
    }

    .ntb-btn-primary {
        background: #007bff !important;
        border-color: #007bff !important;
        color: white !important;
    }

    .ntb-btn-danger {
        background: #dc3545 !important;
        border-color: #dc3545 !important;
        color: white !important;
    }

    .ntb-btn-success {
        background: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
    }

    .ntb-btn-warning {
        background: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #212529 !important;
    }

    /* DataTables Styling to match WordPress UI */
    .dataTables_wrapper {
        margin-top: 15px;
    }

    .dataTables_length,
    .dataTables_filter {
        margin-bottom: 15px;
    }

    .dataTables_filter label {
        font-weight: 400;
        color: #3c434a;
    }

    /* START: Styles for sizing up the length menu */
    .dataTables_length {
        position: relative;
        /* Add this to allow z-index to work */
        z-index: 10;
        /* Add this to bring the dropdown to the front */
    }

    .dataTables_length label {
        font-size: 14px;
        /* Increase font size of the label */
        font-weight: 400;
        color: #3c434a;
        display: inline-flex;
        align-items: center;
    }

    .dataTables_length select {
        padding: 8px 12px;
        font-size: 14px;
        height: auto;
        /* Atur tinggi sesuai keinginan, misalnya 50px */
        width: 5vw;
        /* Atur lebar sesuai keinginan, misalnya 100px */
        margin: 0 10px;
        border-radius: 4px;
        border-color: #8c8f94;
    }

    /* END: Styles for sizing up the length menu */

    .dataTables_filter input[type="search",
    placeholder="searchPlaceholder"] {
        border-radius: 3px;
        border-color: #8c8f94;
    }

    .dataTables_paginate .paginate_button {
        padding: .25em .65em;
        margin-left: 2px;
        border: 1px solid #c3c4c7;
        border-radius: 3px;
        background: #f6f7f7;
        color: #0071a1 !important;
        text-decoration: none;
    }

    .dataTables_paginate .paginate_button.current,
    .dataTables_paginate .paginate_button.current:hover {
        background: #f0f0f1;
        border-color: #c3c4c7;
        color: #3c434a !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: #f0f0f1;
        border-color: #0071a1;
        color: #0071a1 !important;
    }

    .dataTables_paginate .paginate_button.disabled,
    .dataTables_paginate .paginate_button.disabled:hover {
        color: #a0a5aa !important;
        background: #f6f7f7;
        border-color: #e2e4e7;
    }

    /* Table Styling */
    #ntb-landing-page-table {
        border-collapse: collapse !important;
        border: 1px solid #c3c4c7;
    }

    #ntb-landing-page-table th,
    #ntb-landing-page-table td {
        padding: 8px 10px;
        border-bottom: 1px solid #e2e4e7;
    }

    #ntb-landing-page-table thead th {
        background-color: #f6f7f7;
        font-weight: 600;
        white-space: nowrap;
    }

    .dataTables_empty {
        padding: 50px !important;
        font-size: 10px;
        color: #d63638;
    }

    .row-actions {
        color: #b3b3b3;
        font-size: 10px;
        padding: 2px 0 0;
        visibility: visible;
    }

    tr:hover .row-actions {
        visibility: visible;
    }

    /* URL LP Input Group */
    .url-lp-input-group {
        display: flex;
        align-items: center;
        gap: 2px;
        /* Jarak lebih rapat */
        background-color: #f6f7f7;
        border: 1px solid #e2e4e7;
        border-radius: 6px;
        padding: 2px;
        /* Padding lebih kecil */
        height: 32px;
        /* Tinggi konsisten */
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .05);
    }

    .url-lp-input {
        flex-grow: 1;
        border: none;
        background-color: transparent;
        padding: 4px 8px;
        font-size: 13px;
        color: #3c434a;
        outline: none;
        cursor: text;
    }

    .url-lp-input:focus {
        outline: none;
    }

    .url-lp-action-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        /* Ukuran tombol ikon */
        height: 28px;
        border-radius: 4px;
        transition: background-color 0.2s, color 0.2s;
    }

    .url-lp-action-btn svg {
        width: 16px;
        height: 16px;
        color: #646970;
    }

    .url-lp-action-btn:hover {
        background-color: #e0e0e0;
        color: #007bff;
    }

    .url-lp-action-btn.copydanger-btn svg {
        color: #dc3545;
    }

    .url-lp-action-btn.copydanger-btn:hover {
        background-color: #f8d7da;
        color: #dc3545;
    }

    /* Parameter Input Group */
    .ntb-param-group {
        display: flex;
        align-items: center;
        gap: 2px;
        background-color: #f6f7f7;
        border: 1px solid #cdd0d4;
        /* Sedikit lebih gelap agar cocok dengan gambar */
        border-radius: 6px;
        padding: 2px;
        height: 32px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .05);
    }

    .ntb-param-input {
        flex-grow: 1;
        border: none;
        background-color: transparent;
        padding: 4px 8px;
        font-size: 13px;
        color: #3c434a;
        outline: none;
        cursor: text;
    }

    .ntb-param-copy-btn {
        background: #ffffff;
        /* Latar belakang putih seperti di gambar */
        border: 1px solid #cdd0d4;
        /* Border tipis di sekitar tombol */
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .ntb-param-copy-btn svg {
        width: 16px;
        height: 16px;
        color: #50575e;
        /* Warna ikon sedikit lebih gelap */
    }

    .ntb-param-copy-btn:hover {
        background-color: #f0f0f1;
    }

    /* Copy Notification Styling */
    #ntb-copy-notification {
        visibility: hidden;
        /* Sembunyi secara default */
        min-width: 250px;
        background-color: #32373c;
        /* Warna admin WordPress */
        color: #fff;
        text-align: center;
        border-radius: 5px;
        padding: 16px;
        position: fixed;
        z-index: 9999;
        left: 50%;
        bottom: 30px;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.5s, visibility 0.5s;
    }

    /* Kelas untuk menampilkan notifikasi dengan transisi fade-in/out */
    #ntb-copy-notification.show {
        visibility: visible;
        opacity: 1;
    }

    /* Toggle Switch Styling */
    .ntb-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        /* Lebar switch */
        height: 24px;
        /* Tinggi switch */
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
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 24px;
        /* Membuat ujungnya bulat */
    }

    .ntb-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        /* Ukuran lingkaran dalam */
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
        /* Membuatnya bulat sempurna */
    }

    input:checked+.ntb-slider {
        background-color: #007bff;
        /* Warna biru saat aktif */
    }

    input:focus+.ntb-slider {
        box-shadow: 0 0 1px #007bff;
    }

    input:checked+.ntb-slider:before {
        -webkit-transform: translateX(20px);
        -ms-transform: translateX(20px);
        transform: translateX(20px);
        /* Jarak pergeseran lingkaran */
    }
</style>