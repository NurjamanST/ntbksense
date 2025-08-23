<div class="ntb-main-content">
    <h2 class="ntb-main-title"><span class="dashicons dashicons-database"></span> Pemeriharaan Tabel</h2>
    <hr>
    <!-- Tombol Aksi Utama -->
    <div class="ntb-actions-bar">
        <a href="#" class="button ntb-btn-danger" id="ntb-log-trafik"><span class="dashicons dashicons-trash"></span>Atur Ulang Log Trafik</a>
        <a href="#" class="button ntb-btn-warning" id="ntb-article-revision"><span class="dashicons dashicons-table-row-delete"></span>Hapus Revisi Artikel</a>
        <a href="#" class="button ntb-btn-success" id="ntb-optimize-database"><span class="dashicons dashicons-database"></span>Optimalkan Database</a>
    </div>

    <!-- Tabel HTML Standar untuk DataTables -->
    <table id="ntb-landing-page-table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Nama Tabel</th>
                <th>Type</th>
                <th>Ukuran(MB)</th>
                <th>Index Load (MB)</th>
                <th>Jumlah Baris</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Kodingan cURL Disini...
            for ($i = 0; $i < 10; $i++) {
            ?>
                <tr>
                    <td>wp_users</td>
                    <td>InnoDB</td>
                    <td>24 MB</td>
                    <td>0.09 MB</td>
                    <td>2001</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>