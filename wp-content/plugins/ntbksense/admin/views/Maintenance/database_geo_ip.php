<div class="ntb-main-content">
    <h2 class="ntb-main-title"><span class="dashicons dashicons-database"></span> Database GEO IP</h2>
    <hr>
    <!-- Tabel HTML Standar untuk DataTables -->
    <table id="ntb-landing-page-table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Nama Database</th>
                <th>Status</th>
                <th>Fungsi</th>
                <th>Ukuran</th>
                <th>Terakhir Diperbaharui</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < 2; $i++) {
            ?>
                <tr>
                    <td>country.mmdb</td>
                    <td>
                    <span class="text-success fw-bold d-flex align-items-center">
                        <span class="dashicons dashicons-yes-alt"></span> Tersedia
                    </span>    
                    </td>
                    <td>Country DB</td>
                    <td>24.09 MB</td>
                    <td>1 Day ago</td>
                    <td>
                        <button class="btn btn-primary">Diperbaharui</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
