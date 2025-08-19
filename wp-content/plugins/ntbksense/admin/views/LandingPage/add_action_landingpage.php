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

            // --- START: Kode Baru untuk Notifikasi dan Salin
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

            // Definisikan selector yang SUPER SPESIFIK untuk checkbox bulk action.
            // Ini hanya menargetkan checkbox di dalam tabel spesifik dan mengabaikan toggle status.
                const bulkActionCheckboxSelector = '#ntb-landing-page-table tbody input[name="lp[]"]:not(.ntb-status-toggle)';

            // --- BAGIAN 1: FUNGSI 'PILIH SEMUA' ---
            // Event ini berjalan saat checkbox di header (#select-all-lp) di-klik.
                $('#select-all-lp').on('click', function() {
                    // Cek statusnya (dicentang atau tidak)
                    const isChecked = $(this).prop('checked');

                    // Gunakan selector yang sudah spesifik. Ini HANYA akan memilih checkbox untuk bulk action
                    $(bulkActionCheckboxSelector).prop('checked', isChecked);
                });


            // --- BAGIAN 2: FUNGSI 'BATAL OTOMATIS' ---
            // Event ini berjalan saat salah satu checkbox item di-klik.
                $(document).on('click', bulkActionCheckboxSelector, function() {
                    // Hitung jumlah total checkbox item (yang spesifik)
                    const totalCheckboxes = $(bulkActionCheckboxSelector).length;
                    // Hitung berapa banyak checkbox item (yang spesifik) yang sedang dicentang
                    const checkedCheckboxes = $(bulkActionCheckboxSelector + ':checked').length;

                    // Jika jumlah yang dicentang sama dengan jumlah total,
                    // maka centang juga checkbox 'Pilih Semua'. Jika tidak, batalkan centangnya.
                    const allAreChecked = totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes;
                    $('#select-all-lp').prop('checked', allAreChecked);
                });


            // [FUNGSI DIPERBARUI] Event listener untuk tombol DUPLIKAT (BULK & SINGLE)
            $(document).on('click', '#ntb-bulk-duplicate-btn, .ntb-duplicate-btn', function(e) {
                e.preventDefault();

                const $button = $(this);
                const idsToDuplicate = [];
                const slugsToCreate = []; // Array baru untuk menampung slug kustom
                let isBulkAction = false;

                // Cek apakah ini aksi massal atau per item
                if ($button.attr('id') === 'ntb-bulk-duplicate-btn') {
                    isBulkAction = true;
                    // Aksi Massal: Kumpulkan ID dari checkbox yang dipilih
                    $('input[name="lp[]"]:checked').each(function() {
                        idsToDuplicate.push($(this).val());
                    });

                    if (idsToDuplicate.length === 0) {
                        alert('Pilih dulu item yang mau diduplikasi.');
                        return;
                    }
                } else {
                    // Aksi per Item: Ambil ID dari atribut data-id
                    idsToDuplicate.push($button.data('id'));
                }

                // --- LOGIKA BARU: Minta slug kustom HANYA untuk aksi tunggal ---
                if (!isBulkAction) {
                    const originalUrl = $button.closest('tr').find('.url-lp-input').val();
                    // Ekstrak slug dari URL lengkap
                    const originalSlug = originalUrl.substring(originalUrl.lastIndexOf('/') + 1);
                    const newSlugSuggestion = originalSlug ? `${originalSlug}-copy` : 'new-copy';

                    const newSlug = prompt('Masukkan slug baru untuk duplikat:', newSlugSuggestion);

                    // Jika pengguna menekan "Cancel" atau mengosongkan input, batalkan proses
                    if (newSlug === null || newSlug.trim() === '') {
                        alert('Proses duplikasi dibatalkan.');
                        return;
                    }
                    slugsToCreate.push(newSlug.trim());
                }
                // --- AKHIR LOGIKA BARU ---

                // Konfirmasi sebelum melanjutkan
                if (!confirm(`Anda yakin ingin menduplikasi ${idsToDuplicate.length} item yang dipilih?`)) {
                    return;
                }

                // Tentukan URL API untuk duplikasi
                const apiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/duplicate.landing.php'); ?>';

                // Tampilkan efek loading pada baris yang akan diduplikasi
                const $rowsToUpdate = idsToDuplicate.map(id => $(`input[value="${id}"], [data-id="${id}"]`).closest('tr'));
                $rowsToUpdate.forEach($row => $row.css('opacity', 0.5));

                // Siapkan payload untuk dikirim ke API
                const payload = {
                    ids: idsToDuplicate
                };
                // Hanya tambahkan properti 'slugs' jika ini bukan aksi massal
                if (!isBulkAction) {
                    payload.slugs = slugsToCreate;
                }

                // Kirim permintaan ke API menggunakan Fetch
                fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload) // Kirim payload yang sudah disiapkan
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Sukses! ' + data.data.message);
                            location.reload(); // Muat ulang halaman untuk menampilkan data terbaru
                        } else {
                            alert('Gagal! ' + data.data.message);
                            $rowsToUpdate.forEach($row => $row.css('opacity', 1)); // Kembalikan opacity jika gagal
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan cek konsol browser untuk detail.');
                        $rowsToUpdate.forEach($row => $row.css('opacity', 1)); // Kembalikan opacity jika error
                    });
            });

            // [FUNGSI BARU] Event listener untuk tombol EKSPOR
            $('#ntb-bulk-export-btn').on('click', function(e) {
                e.preventDefault();

                const idsToExport = [];
                // Kumpulkan semua ID dari checkbox yang dicentang
                $('input[name="lp[]"]:checked').each(function() {
                    idsToExport.push($(this).val());
                });

                // Jika tidak ada yang dipilih, tampilkan peringatan
                if (idsToExport.length === 0) {
                    alert('Pilih dulu item yang mau diekspor.');
                    return;
                }

                // Tentukan URL API untuk ekspor
                const apiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/export.landing.php'); ?>';
                const $button = $(this);
                const originalButtonText = $button.html();

                // Tampilkan status loading
                $button.html('<span class="spinner is-active" style="float: left; margin-top: 4px;"></span> Mengekspor...');
                $button.prop('disabled', true);

                // Kirim permintaan ke API menggunakan Fetch
                fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: idsToExport })
                })
                .then(response => {
                    if (!response.ok) {
                        // Jika ada error, coba baca sebagai JSON untuk pesan error
                        return response.json().then(err => { throw new Error(err.data.message || 'Gagal mengekspor data.') });
                    }
                    // Dapatkan nama file dari header Content-Disposition
                    const disposition = response.headers.get('content-disposition');
                    let filename = 'LandingPage-ntbksense-export.json'; // Nama default
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        const matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }
                    return response.blob().then(blob => ({ blob, filename }));
                })
                .then(({ blob, filename }) => {
                    // Buat URL sementara untuk file blob
                    const url = window.URL.createObjectURL(blob);
                    // Buat link sementara untuk memicu unduhan
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    // Hapus URL dan link sementara
                    window.URL.revokeObjectURL(url);
                    a.remove();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                })
                .finally(() => {
                    // Kembalikan tombol ke keadaan semula
                    $button.html(originalButtonText);
                    $button.prop('disabled', false);
                });
            });
            // [FUNGSI BARU] Event listener untuk tombol IMPOR
            $('#ntb-bulk-import-btn').on('click', function(e) {
                e.preventDefault();
                // Memicu klik pada input file yang tersembunyi
                $('#ntb-import-file-input').click();
            });

            $('#ntb-import-file-input').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    return; // Batalkan jika tidak ada file yang dipilih
                }

                // Validasi tipe file
                if (file.type !== 'application/json') {
                    alert('Gagal! Harap pilih file dengan format .json');
                    $(this).val(''); // Reset input file
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const content = event.target.result;
                    try {
                        // Coba parse JSON untuk validasi awal
                        JSON.parse(content);
                        
                        // Konfirmasi sebelum mengirim
                        if (!confirm('Anda yakin ingin mengimpor data dari file ini?.')) {
                            $('#ntb-import-file-input').val(''); // Reset input file
                            return;
                        }

                        // Kirim data ke API
                        sendImportRequest(content);

                    } catch (error) {
                        alert('Gagal membaca file. Pastikan file JSON valid.');
                        console.error('JSON Parse Error:', error);
                    } finally {
                        // Reset input file setelah selesai
                        $('#ntb-import-file-input').val('');
                    }
                };
                
                reader.readAsText(file);
            });

            function sendImportRequest(jsonData) {
                const apiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/import.landing.php'); ?>';
                const $button = $('#ntb-bulk-import-btn');
                const originalButtonText = $button.html();

                // Tampilkan status loading
                $button.html('<span class="spinner is-active" style="float: left; margin-top: 4px;"></span> Mengimpor...');
                $button.prop('disabled', true);

                fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: jsonData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Sukses! ' + data.data.message);
                        location.reload(); // Muat ulang halaman
                    } else {
                        alert('Gagal! ' + data.data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengimpor. Cek konsol untuk detail.');
                })
                .finally(() => {
                    // Kembalikan tombol ke keadaan semula
                    $button.html(originalButtonText);
                    $button.prop('disabled', false);
                });
            }
        });
    </script>
    
    <script>
        // Kode ini untuk menambahkan event pada tombol "Hapus" di dalam tabel
        jQuery(document).ready(function($) {
            // Ganti event dari 'change' ke 'click', dan targetnya ke label pembungkus
            $(document).on('click', '.ntb-switch', function(e) {
                // Mencegah aksi default dari label (yang bisa mentrigger event 'change' 2x)
                e.preventDefault();

                // 'this' sekarang adalah <label class="ntb-switch"> yang di-klik
                const label = $(this);
                // Cari checkbox di dalam label tersebut
                const checkbox = label.find('.ntb-status-toggle');
                const rowId = checkbox.data('id');

                // Jika tidak ada ID, jangan lakukan apa-apa
                if (!rowId) {
                    return;
                }

                // 1. PHP HANYA menyediakan URL dasar, tanpa ID.
                const baseUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/statusu.landing.php'); ?>';
                // 2. JavaScript menambahkan ID saat tombol di-klik.
                const apiUrl = `${baseUrl}?id=${rowId}`;

                console.log('Mencoba memanggil URL (via click):', apiUrl);

                label.closest('td').css('opacity', 0.5);

                $.ajax({
                    url: apiUrl,
                    type: 'PUT',
                    success: function(response) {
                        console.log('Respon sukses:', response.message);
                        // UPDATE PENTING:
                        // Update status visual checkbox berdasarkan respon dari server
                        const isChecked = response.status_baru == 1;
                        checkbox.prop('checked', isChecked);
                    },
                    error: function(jqXHR) {
                        // Error handling tetap sama, tapi kita tidak perlu membalikkan status
                        // karena kita tidak mengubahnya di awal.
                        try {
                            const errorResponse = JSON.parse(jqXHR.responseText);
                            console.error('Gagal update:', errorResponse.message);
                            alert('Error: ' + errorResponse.message);
                        } catch (e) {
                            console.error('Gagal mem-parsing respon error:', jqXHR.responseText);
                            alert('Terjadi error yang tidak diketahui.');
                        }
                    },
                    complete: function() {
                        label.closest('td').css('opacity', 1);
                    }
                });
            });

            // Event listener untuk tombol hapus di tabel
            // Pastikan tombol hapus lo punya class 'ntb-delete-btn' dan atribut 'data-id'
            $(document).on('click', '.ntb-delete-btn', function(e) {
                e.preventDefault();

                // Konfirmasi sebelum menghapus (PENTING!)
                if (!confirm('Apakah lo yakin mau menghapus template ini? Aksi ini tidak bisa dibatalkan.')) {
                    return;
                }

                const $button = $(this);
                const landingPageId = $button.data('id');
                const $tableRow = $button.closest('tr'); // Cari baris tabel terdekat

                // Ganti URL API ke endpoint untuk DELETE
                const apiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/delete.landing.php'); ?>';

                // Tampilkan status loading (opsional)
                $tableRow.css('opacity', 0.5);

                fetch(apiUrl, {
                        method: 'POST', // Bisa juga 'DELETE', tapi POST lebih umum didukung
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id: landingPageId
                        }) // Kirim ID dalam format JSON
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Sukses! ' + data.data.message);
                            // Hapus baris dari tabel secara visual
                            $tableRow.fadeOut(400, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('Gagal! ' + data.data.message);
                            $tableRow.css('opacity', 1); // Kembalikan opacity jika gagal
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Cek console untuk detail.');
                        $tableRow.css('opacity', 1); // Kembalikan opacity jika error
                    });
            });

            // [FUNGSI BARU] Event listener untuk tombol HAPUS MASAL (BULK DELETE)
            $('#ntb-bulk-delete-btn').on('click', function(e) {
                e.preventDefault();

                // 1. Kumpulkan semua ID dari checkbox yang dicentang
                const idsToDelete = [];
                // Pastikan checkbox lo punya name="lp[]" dan value berisi ID
                $('input[name="lp[]"]:checked').each(function() {
                    idsToDelete.push($(this).val());
                });

                // 2. Jika tidak ada yang dipilih, kasih peringatan
                if (idsToDelete.length === 0) {
                    alert('Pilih dulu item yang mau dihapus.');
                    return;
                }

                // 3. Konfirmasi sebelum menghapus
                if (!confirm(`Lo yakin mau menghapus ${idsToDelete.length} item yang dipilih?`)) {
                    return;
                }

                // 4. Kirim data ke API endpoint baru untuk bulk delete
                const apiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/landing_page/bulk-delete.landing.php'); ?>';

                // Tampilkan status loading (opsional)
                $('input[name="lp[]"]:checked').closest('tr').css('opacity', 0.5);

                fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ids: idsToDelete
                        }) // Kirim array of IDs
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Sukses! ' + data.data.message);
                            // Hapus semua baris yang dipilih dari tabel
                            $('input[name="lp[]"]:checked').closest('tr').fadeOut(400, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('Gagal! ' + data.data.message);
                            $('input[name="lp[]"]:checked').closest('tr').css('opacity', 1);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Cek console untuk detail.');
                        $('input[name="lp[]"]:checked').closest('tr').css('opacity', 1);
                    });
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