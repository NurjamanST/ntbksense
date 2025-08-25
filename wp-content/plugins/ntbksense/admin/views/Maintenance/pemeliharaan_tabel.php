<div class="ntb-main-content">
    <h2 class="ntb-main-title"><span class="dashicons dashicons-database"></span> Pemeliharaan Tabel</h2>
    <hr>
    <!-- Tombol Aksi Utama -->
    <div class="ntb-actions-bar">
        <a href="#" class="btn btn-danger" id="ntb-log-trafik">
            <span class="dashicons dashicons-trash"></span> Atur Ulang Log Trafik
        </a>
        <a href="#" class="btn btn-warning" id="ntb-article-revision">
            <span class="dashicons dashicons-table-row-delete"></span> Hapus Revisi Artikel
        </a>
        <a href="#" class="btn btn-success" id="ntb-optimize-database">
            <span class="dashicons dashicons-database"></span> Optimalkan Database
        </a>
    </div>

    <!-- Tabel Maintenance (DataTables will populate this) -->
    <table id="ntb-maintenance-table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Nama Tabel</th>
                <th>Type</th>
                <th>Ukuran (MB)</th>
                <th>Index Load (MB)</th>
                <th>Jumlah Baris</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX -->
        </tbody>
    </table>
</div>

