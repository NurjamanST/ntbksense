<?php
// File: wp-content/plugins/ntbksense/admin/views/Beranda/ntbksense-beranda.php

include "add_action_beranda.php";
?>

<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- START: Breadcrumb Kustom -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <span>NTBKSense</span> &gt; <span>Beranda</span>
    </div>
    <!-- END: Breadcrumb Kustom -->

    <div class="ntb-main-content">
        <h2 class="ntb-main-title"><span class="dashicons dashicons-dashboard"></span> Dasbor</h2>
        <p>Selamat datang di dasbor NTBKSense. Berikut adalah ringkasan aktivitas Anda.</p>
        <hr>

        <!-- START: Kartu Statistik -->
        <div class="ntb-stats-cards">
            <!-- Card Total LP -->
            <div class="ntb-card">
                <div class="ntb-card-icon"><span class="dashicons dashicons-admin-page"></span></div>
                <div class="ntb-card-content">
                    <h3 class="ntb-card-title">Total Landing Page</h3>
                    <p class="ntb-card-value" id="total-lp-value">...</p>
                </div>
            </div>
            <!-- Card LP Aktif -->
            <div class="ntb-card">
                <div class="ntb-card-icon ntb-icon-active"><span class="dashicons dashicons-yes-alt"></span></div>
                <div class="ntb-card-content">
                    <h3 class="ntb-card-title">LP Aktif</h3>
                    <p class="ntb-card-value" id="active-lp-value">...</p>
                </div>
            </div>
            <!-- Card Auto Post -->
            <div class="ntb-card ntb-card-disabled">
                <div class="ntb-card-icon"><span class="dashicons dashicons-admin-post"></span></div>
                <div class="ntb-card-content">
                    <h3 class="ntb-card-title">Total Auto Post</h3>
                    <p class="ntb-card-value" id="total-autopost-value">0</p>
                </div>
            </div>
            <!-- Card Laporan -->
            <div class="ntb-card ntb-card-disabled">
                <div class="ntb-card-icon"><span class="dashicons dashicons-chart-bar"></span></div>
                <div class="ntb-card-content">
                    <h3 class="ntb-card-title">Total Laporan</h3>
                    <p class="ntb-card-value" id="total-laporan-value">0</p>
                </div>
            </div>
        </div>
        <!-- END: Kartu Statistik -->

        <!-- START: Aksi Cepat -->
        <div class="ntb-quick-actions ">
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-create-landing')); ?>" class="button ntb-btn-primary">
                <span class="dashicons dashicons-plus"></span> Buat LP Baru</a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-laporan')); ?>" class="button">
                <span class="dashicons dashicons-visibility"></span> Lihat Laporan</a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-settings')); ?>" class="button">
                <span class="dashicons dashicons-admin-settings"></span> Pengaturan</a>
        </div>
        <!-- END: Aksi Cepat -->

        <!-- START: Aktivitas Terbaru -->
        <div class="ntb-recent-activity">
            <h3><span class="dashicons dashicons-clock"></span> Aktivitas Landing Page Terbaru</h3>
            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="recent-lps-tbody">
                    <tr><td colspan="4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
        <!-- END: Aktivitas Terbaru -->

        <!-- START: Info Box / Tabs -->
        <div class="ntb-info-box">
            <nav class="ntb-info-nav">
                <a href="#" class="ntb-tab-item active" data-tab="tentang"><span class="dashicons dashicons-info"></span> Tentang</a>
                <a href="#" class="ntb-tab-item" data-tab="tutorial"><span class="dashicons dashicons-video-alt3"></span> Tutorial</a>
                <a href="#" class="ntb-tab-item" data-tab="faq"><span class="dashicons dashicons-editor-help"></span> Pertanyaan Umum</a>
            </nav>

            <div class="ntb-tab-content">
                <!-- Tab Tentang -->
                <div id="tentang" class="ntb-tab-pane active">
                    <h3>NTBKSense - Cloaking Iklan Terbaik, Penyaringan Lalu Lintas Cerdas & Template Kustom</h3>
                    <h4><span class="dashicons dashicons-shield-alt"></span> Optimalkan Monetisasi Iklan & Minimalkan Risiko Banned!</h4>
                    <p>NTBKSense adalah plugin WordPress canggih yang membantu Anda mengontrol siapa yang dapat melihat iklan Adsense dengan sistem penyaringan trafik tingkat lanjut. Dengan teknologi <strong>stealth cloaking</strong>, plugin ini memastikan hanya pengunjung asli yang melihat iklan, sementara bot atau pengunjung yang tidak diinginkan dialihkan ke halaman lain. Dibangun dengan algoritma pintar untuk mengurangi deteksi sistem Google, risiko banned menjadi sangat minim.</p>

                    <h4><span class="dashicons dashicons-star-filled"></span> Kenapa Harus NTBKSense?</h4>
                    <ul>
                        <li><span class="dashicons dashicons-yes"></span> <strong>Keamanan Maksimal, Risiko Banned Minim</strong> - Teknologi Cloaking canggih membuat sistem Google sulit mendeteksi teknik cloaking.</li>
                        <li><span class="dashicons dashicons-yes"></span> <strong>Meningkatkan CTR & CPC</strong> - Pastikan hanya pengunjung berkualitas tinggi yang melihat iklan.</li>
                        <li><span class="dashicons dashicons-yes"></span> <strong>Deteksi & Filter Trafik</strong> - Saring trafik dengan Geolokasi, IP, ASN, VPN & Proxy Detector.</li>
                        <li><span class="dashicons dashicons-yes"></span> <strong>Pilihan Template Beragam</strong> - Gunakan berbagai template siap pakai yang dapat disesuaikan.</li>
                        <li><span class="dashicons dashicons-yes"></span> <strong>100% Otomatis & Mudah Digunakan</strong> - Instal, atur filter, dan plugin akan bekerja sendiri.</li>
                    </ul>

                    <h4><span class="dashicons dashicons-admin-plugins"></span> Fitur Premium NTBKSense</h4>
                    <ul>
                        <li><span class="dashicons dashicons-controls-play"></span> Kontrol Transparansi Iklan - Atur visibilitas dari 0% hingga 100%.</li>
                        <li><span class="dashicons dashicons-smartphone"></span> Deteksi Perangkat - Pilih tampilan iklan untuk Mobile, Desktop, atau Tablet.</li>
                        <li><span class="dashicons dashicons-admin-site-alt3"></span> Filter Negara & Geolokasi - Batasi tampilan iklan hanya untuk negara tertentu.</li>
                        <li><span class="dashicons dashicons-filter"></span> Filter IP & ASN - Blokir atau izinkan trafik berdasarkan IP atau ASN.</li>
                        <li><span class="dashicons dashicons-shield"></span> Deteksi & Blokir Bot - Saring bot dari Google, Bing, Facebook, dll.</li>
                        <li><span class="dashicons dashicons-randomize"></span> Pengalihan Otomatis - Pengunjung asli ke halaman utama, bot ke halaman dummy.</li>
                        <li><span class="dashicons dashicons-unlock"></span> Bypass VPN & Proxy Detector - Blokir akses dari VPN atau Proxy.</li>
                        <li><span class="dashicons dashicons-chart-line"></span> Log & Statistik Trafik Real-time - Pantau semua aktivitas dengan mudah.</li>
                    </ul>
                </div>
                <!-- Tab Tutorial -->
                <div id="tutorial" class="ntb-tab-pane">
                    <h3>Video Tutorial</h3>
                    <p>Konten tutorial akan segera tersedia di sini. Anda akan menemukan panduan langkah demi langkah tentang cara memaksimalkan penggunaan plugin NTBKSense.</p>
                </div>
                <!-- Tab FAQ -->
                <div id="faq" class="ntb-tab-pane">
                    <h3>Pertanyaan Umum (FAQ)</h3>
                    <p>Daftar pertanyaan yang sering diajukan akan ditambahkan di sini untuk membantu Anda menyelesaikan masalah umum dengan cepat.</p>
                </div>
            </div>
        </div>
        <!-- END: Info Box / Tabs -->
    </div>
</div>

<?php 
    // Memuat file CSS kustom untuk halaman ini
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>
