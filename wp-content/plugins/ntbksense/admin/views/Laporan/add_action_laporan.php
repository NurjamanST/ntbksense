<?php
// =========================================================================
// FILE: ntbksense/admin/views/Laporan/add_action_laporan.php
// FUNGSI: Menangani logika backend, aset, dan persiapan variabel untuk halaman Laporan.
// =========================================================================

// Menambahkan aset khusus untuk halaman ini
add_action('admin_footer', function() {
    $screen = get_current_screen();
    if ( 'ntbksense_page_ntbksense-laporan' !== $screen->id ) {
        return;
    }
?>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Date Range Picker CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            // Inisialisasi DataTables
            $('#laporan-table').DataTable({
                "scrollX": true, // Aktifkan scroll horizontal
                "language": {
                    "search": "",
                    "searchPlaceholder": "Cari data..."
                }
            });

            // Inisialisasi Date Range Picker
            $('#daterange').daterangepicker({
                ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end, label) {
                $('#daterange').val(label);
            });
        });
    </script>
<?php
});

// Data dummy untuk stat cards
$stat_cards = [
    ['value' => '1,250', 'label' => 'Total', 'icon' => 'fas fa-chart-pie', 'color_class' => 'text-primary'],
    ['value' => '1,180', 'label' => 'Berhasil', 'icon' => 'fas fa-check-circle', 'color_class' => 'text-success'],
    ['value' => '70', 'label' => 'Gagal', 'icon' => 'fas fa-times-circle', 'color_class' => 'text-danger'],
    ['value' => '94.4%', 'label' => '% Trafik valid', 'icon' => 'fas fa-shield-alt', 'color_class' => 'text-info'],
    ['value' => '2,360', 'label' => 'Tayangan Iklan', 'icon' => 'fas fa-eye', 'color_class' => 'text-warning'],
    ['value' => '1m 32s', 'label' => 'Durasi Kunjungan (AVG)', 'icon' => 'fas fa-clock', 'color_class' => 'text-secondary']
];

// Data dummy untuk tabel laporan
$report_data = [
    [
        'id' => 1, 'slug' => 'sample-slug-1', 'datetime' => '2025-08-18 14:20:15', 'status' => '<span class="badge bg-success">Berhasil</span>', 'ip' => '103.45.21.88', 'country' => 'Indonesia', 'isp' => 'Telkomsel', 'asn' => '7713', 'device' => 'Mobile', 'os' => 'Android', 'brand' => 'Samsung', 'model' => 'Galaxy S23', 'browser' => 'Chrome', 'uri' => '/page-1', 'destination' => '/target-1', 'referrer' => 'google.com', 'ad_views' => 2, 'duration' => '2m 15s'
    ],
    [
        'id' => 2, 'slug' => 'sample-slug-2', 'datetime' => '2025-08-18 14:22:30', 'status' => '<span class="badge bg-danger">Gagal</span>', 'ip' => '201.33.11.45', 'country' => 'Brazil', 'isp' => 'Claro S.A.', 'asn' => '28573', 'device' => 'Desktop', 'os' => 'Windows', 'brand' => '', 'model' => '', 'browser' => 'Firefox', 'uri' => '/page-2', 'destination' => 'BLOCKED', 'referrer' => 'facebook.com', 'ad_views' => 0, 'duration' => '0m 5s'
    ],
];
?>
