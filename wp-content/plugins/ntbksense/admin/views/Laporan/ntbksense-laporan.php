<?php
// =========================================================================
// FILE: ntbksense/admin/views/Laporan/ntbksense-laporan.php
// FUNGSI: Menampilkan halaman informasi Laporan.
// =========================================================================

// Memuat logika, aset, dan persiapan variabel
include "add_action_laporan.php";
?>

<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Laporan</span>
    </div>
    
    <!-- Main Content -->
    <div class="ntb-main-content">
        <!-- Stat Cards -->
        <div class="row mb-2">
            <?php foreach ($stat_cards as $card): ?>
                <div class="col-2">
                    <div class="ntb-stat-card">
                        <div class="stat-icon <?php echo esc_attr($card['color_class']); ?>">
                            <i class="<?php echo esc_attr($card['icon']); ?>"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo esc_html($card['value']); ?></div>
                            <div class="stat-label"><?php echo esc_html($card['label']); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Filter Section -->
        <div class="ntb-filter-box mb-2">
             <h5 class="ntb-box-title"><i class="fas fa-filter"></i> Filter</h5>
             <div class="row align-items-center">
                 <div class="col-md-3">
                     <select class="form-select">
                         <option selected>All Status</option>
                         <option>Berhasil</option>
                         <option>Gagal</option>
                     </select>
                 </div>
                 <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" id="daterange" class="form-control" value="Today">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                 </div>
                 <div class="col-md-2">
                     <button class="btn btn-primary w-100">Apply Filters</button>
                 </div>
             </div>
        </div>

        <!-- Data Table Section -->
        <div class="ntb-data-box">
            <h5 class="ntb-box-title"><i class="fas fa-database"></i> Data</h5>
            <table id="laporan-table" class="display compact" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Slug</th>
                        <th>Tanggal & Waktu</th>
                        <th>Status</th>
                        <th>Alamat IP</th>
                        <th>Negara</th>
                        <th>ISP</th>
                        <th>ASN</th>
                        <th>Perangkat</th>
                        <th>Nama OS</th>
                        <th>Merek</th>
                        <th>Model</th>
                        <th>Browser</th>
                        <th>Permintaan URI</th>
                        <th>Tujuan</th>
                        <th>Perujuk</th>
                        <th>Tayang Iklan</th>
                        <th>Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($report_data)): ?>
                        <?php foreach($report_data as $row): ?>
                            <tr>
                                <td><?php echo esc_html($row['id']); ?></td>
                                <td><?php echo esc_html($row['slug']); ?></td>
                                <td><?php echo esc_html($row['datetime']); ?></td>
                                <td><?php echo $row['status']; // Raw HTML for badge ?></td>
                                <td><?php echo esc_html($row['ip']); ?></td>
                                <td><?php echo esc_html($row['country']); ?></td>
                                <td><?php echo esc_html($row['isp']); ?></td>
                                <td><?php echo esc_html($row['asn']); ?></td>
                                <td><?php echo esc_html($row['device']); ?></td>
                                <td><?php echo esc_html($row['os']); ?></td>
                                <td><?php echo esc_html($row['brand']); ?></td>
                                <td><?php echo esc_html($row['model']); ?></td>
                                <td><?php echo esc_html($row['browser']); ?></td>
                                <td><?php echo esc_html($row['uri']); ?></td>
                                <td><?php echo esc_html($row['destination']); ?></td>
                                <td><?php echo esc_html($row['referrer']); ?></td>
                                <td><?php echo esc_html($row['ad_views']); ?></td>
                                <td><?php echo esc_html($row['duration']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// Memuat stylesheet global
include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>
