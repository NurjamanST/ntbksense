<?php
// =========================================================================
// FILE: ntbksense/admin/views/AutoPost/ntbksense-auto-post.php
// FUNGSI: Menampilkan halaman Auto Post global.
// =========================================================================

// Memuat logika, aset, dan persiapan variabel
include "add_action_autopost.php";
?>

<div class="wrap" id="ntbksense-autopost-wrapper">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Auto Post</span>
    </div>
    
    <!-- Main Content -->
    <div class="ntb-main-content">
        <form action="" method="post">
            <div class="row">
                <!-- Kolom Kiri: Form -->
                <div class="col-lg-7">
                    <div class="ntb-card">
                        <div class="ntb-card-header">
                            <h5 class="mb-0"><i class="fas fa-cogs"></i> Form</h5>
                        </div>
                        <div class="ntb-card-body">
                            <!-- Kunci API -->
                            <div class="mb-3">
                                <label class="form-label">Kunci API</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="api_mode" id="api_rotasi" value="rotasi" checked>
                                    <label class="form-check-label" for="api_rotasi">Rotasi</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="api_mode" id="api_acak" value="acak">
                                    <label class="form-check-label" for="api_acak">Acak</label>
                                </div>
                                <div id="api-keys-container">
                                    <div class="input-group mt-2">
                                        <input type="text" class="form-control" name="kunci_api[]">
                                        <button class="btn btn-success" type="button" id="add-api-key">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <a href="#">Tambahkan Kunci API</a> &rarr; <a href="#">Gemini</a> / <a href="#">Open AI</a>
                                </small>
                            </div>
                            <hr>
                            <!-- Pengaturan Artikel -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="bahasa" class="form-label">Bahasa</label>
                                    <select id="bahasa" name="bahasa" class="form-select">
                                        <option>English</option>
                                        <option selected>Indonesian</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="gaya_bahasa" class="form-label">Gaya Bahasa</label>
                                    <select id="gaya_bahasa" name="gaya_bahasa" class="form-select">
                                        <option>Bahasa Santai</option>
                                        <option selected>Bahasa Santai Resmi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="kedalaman_artikel" class="form-label">Kedalaman Artikel</label>
                                    <select id="kedalaman_artikel" name="kedalaman_artikel" class="form-select">
                                        <option>Standard Expedition (12|Auto)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="status_artikel" class="form-label">Status Artikel</label>
                                    <select id="status_artikel" name="status_artikel" class="form-select">
                                        <option>Publish</option>
                                        <option>Draft</option>
                                    </select>
                                </div>
                            </div>
                             <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="penjadwalan" class="form-label">Penjadwalan</label>
                                    <select id="penjadwalan" name="penjadwalan" class="form-select">
                                        <option>1 Jam</option>
                                        <option>2 Jam</option>
                                        <option selected>3 Jam</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                                    <select id="nama_pengguna" name="nama_pengguna" class="form-select">
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo esc_attr($user->ID); ?>"><?php echo esc_html($user->display_name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select id="kategori" name="kategori" class="form-select">
                                    <option>Auto</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="checkbox" id="tag_otomatis">
                                    <label class="form-check-label" for="tag_otomatis">Tag otomatis</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="fitur_gambar">
                                    <label class="form-check-label" for="fitur_gambar">Fitur Gambar</label>
                                </div>
                            </div>
                            <hr>
                            <!-- Bypass AI -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label">Bypass AI</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bypass_ai" id="bypass_original" value="original" checked>
                                        <label class="form-check-label" for="bypass_original">Original</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bypass_ai" id="bypass_zerogpt" value="zerogpt">
                                        <label class="form-check-label" for="bypass_zerogpt">zerogpt</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bypass_ai" id="bypass_sinonim" value="sinonim">
                                        <label class="form-check-label" for="bypass_sinonim">Sinonim</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Multi-thread</label>
                                    <select class="form-select">
                                        <option>1 thread</option>
                                        <option>2 thread</option>
                                    </select>
                                </div>
                            </div>
                             <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label">Upaya Percobaan</label>
                                    <select class="form-select">
                                        <option>2x Upaya Percobaan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Delay</label>
                                    <select class="form-select">
                                        <option>0 second</option>
                                        <option>1 second</option>
                                    </select>
                                </div>
                            </div>
                             <hr>
                            <!-- Kata Kunci -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="kata_kunci" class="form-label">Kata Kunci <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-danger btn-sm">Hasilkan Kata Kunci</button>
                                </div>
                                <textarea id="kata_kunci" name="kata_kunci" class="form-control" rows="8"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit</button>
                        </div>
                    </div>
                </div>
                <!-- Kolom Kanan: Logs -->
                <div class="col-lg-5">
                    <div class="ntb-card">
                        <div class="ntb-card-header">
                            <h5 class="mb-0"><i class="fas fa-history"></i> Logs</h5>
                        </div>
                        <div class="ntb-card-body ntb-logs-body">
                            <!-- Log content will appear here -->
                            <p class="text-muted text-center mt-5">Belum ada log yang tersedia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
// Memuat stylesheet
include "stylesheets.php";
?>