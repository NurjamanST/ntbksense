<?php
// =========================================================================
// FILE: ntbksense/admin/views/AutoPost/ntbksense-auto-post.php
// FUNGSI: Menampilkan halaman Auto Post global.
// =========================================================================

// Memuat logika, aset, dan persiapan variabel
include "add_action_autopost.php";
?>

<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Auto Post</span>
    </div>

    <!-- Main Content -->
    <div class="ntb-main-content">
        <form action="" method="post" id="autoPostForm">
            <div class="row">
                <!-- Kolom Kiri: Form -->
                <div class="col-lg-7">
                    <div class="ntb-card">
                        <div class="ntb-card-header">
                            <h5 class="mb-0"><i class="fas fa-cogs"></i> Form</h5>
                        </div>
                        <div class="ntb-card-body">
                            <!-- Kunci API Section -->
                            <div class="mb-3">
                                <label class="form-label">Kunci API</label>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2">Rotasi</span>
                                    <label class="ntb-switch">
                                        <input type="checkbox" name="api_mode" value="acak">
                                        <span class="ntb-slider"></span>
                                    </label>
                                    <span class="ms-2">Acak</span>
                                </div>
                                <div id="api-keys-container">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="kunci_api[]" placeholder="Kunci API">
                                        <button class="btn btn-success" type="button">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <a href="#" id="add-api-key-link" target="_blank" class="text-decoration-none me-3"><i class="fas fa-plus-circle"></i> Tambah Kunci API</a>
                                    <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-decoration-none me-3"><i class="fas fa-bolt"></i> Gemini</a>
                                    <a href="https://platform.openai.com/api-keys" target="_blank" class="text-decoration-none"><i class="fas fa-brain"></i> Open AI</a>
                                </div>
                            </div>
                            <hr>
                            <!-- Pengaturan Artikel -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="bahasa" class="form-label">Bahasa <span class="text-danger">*</span></label>
                                    <select id="bahasa" name="bahasa" class="form-select">
                                        <option selected>English</option>
                                        <option>Bahasa Indonesia</option>
                                        <option>Deutsch (German)</option>
                                        <option>Italiano (Italian)</option>
                                        <option>Nederlands (Dutch)</option>
                                        <option>Norsk (Norwegian)</option>
                                        <option>Svenska (Swedish)</option>
                                        <option>Español (Spanish)</option>
                                        <option>Français (French)</option>
                                        <option>Bahasa Melayu (Malay)</option>
                                        <option>Bahasa Melayu (Malay)[alt]</option>
                                        <option>Português (Portuguese)</option>
                                        <option>Русский (Russian)</option>
                                        <option>日本語 (Japanese)</option>
                                        <option>한국어 (Korean)</option>
                                        <option>简体中文 (Simplified Chinese)</option>
                                        <option>繁體中文 (Traditional Chinese)</option>
                                        <option>العربية (Arabic)</option>
                                        <option>हिन्दी (Hindi)</option>
                                        <option>বাংলা (Bengali)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="gaya_bahasa" class="form-label">Gaya Bahasa <span class="text-danger">*</span></label>
                                    <select id="gaya_bahasa" name="gaya_bahasa" class="form-select">
                                        <option selected>Bahasa Santai Resmi</option>
                                        <option>Bahasa Santai tapi Bahasa Baku</option>
                                        <option>Formal dan Ramah</option>
                                        <option>Kreatif dan Humoris</option>
                                        <option>Cerita dengan Fakta Ilmiah</option>
                                        <option>Bahasa Santai Formal</option>
                                        <option>Persuasif Kreatif</option>
                                        <option>Instruksional Ramah</option>
                                        <option>Sederhana tapi Menyentuh</option>
                                        <option>Santai tapi Instruktif</option>
                                        <option>Resmi tapi Lucu</option>
                                        <option>Informal tapi Serius</option>
                                        <option>Penelitian</option>
                                        <option>Interaktif yang Menghibur</option>
                                        <option>Kultwit yang Kreatif</option>
                                        <option>Bercerita Asyik dan Menyenangkan</option>
                                        <option>Bahasa Santai, Gaul Anak Jaksel</option>
                                        <option>Slang Anak Bandung</option>
                                        <option>Bahasa Gaul Anak Medan</option>
                                        <option>Gaya Kekinian Anak Jogja</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="kedalaman_artikel" class="form-label">Kedalaman Artikel <span class="text-danger">*</span></label>
                                    <select id="kedalaman_artikel" name="kedalaman_artikel" class="form-select">
                                        <option>Standard Expedition (L2)(Auto)</option>
                                        <option>Essential Snapshot (L1)</option>
                                        <option>Standard Expedition (T3)</option>
                                        <option>Standard Expedition (T4)</option>
                                        <option>Standard Expedition (T5)</option>
                                        <option>In-Depth Journey (L3)</option>
                                        <option selected>Oasis of Knowledge (L4)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="status_artikel" class="form-label">Status Artikel</label>
                                    <select id="status_artikel" name="status_artikel" class="form-select">
                                        <option>Publish</option>
                                        <option>Draft</option>
                                        <option>Penjadwalan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="penjadwalan" class="form-label">Penjadwalan</label>
                                    <select id="penjadwalan" name="penjadwalan" class="form-select" disabled>
                                        <option selected>--</option>
                                        <option>1 Jam</option>
                                        <option>2 Jam</option>
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
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select id="kategori" name="kategori" class="form-select">
                                        <option>Auto</option>
                                        <option>Technology</option>
                                        <option selected>WordPress</option>
                                        <option>WordPress category</option>
                                        <option>WordPress category: AI Plugins</option>
                                        <option>WordPress Category: Data Protection</option>
                                        <option>WordPress Plugins</option>
                                        <option>WordPress Plugins for Best ERP Software</option>
                                        <option>WordPress Plugins for Email Marketing Automation</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <label class="ntb-switch me-2">
                                                <input type="checkbox" id="tag_otomatis">
                                                <span class="ntb-slider"></span>
                                            </label>
                                            <label class="form-check-label" for="tag_otomatis">Tag otomatis</label>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <label class="ntb-switch me-2">
                                                <input type="checkbox" id="fitur_gambar">
                                                <span class="ntb-slider"></span>
                                            </label>
                                            <label class="form-check-label" for="fitur_gambar">Fitur Gambar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- Bypass AI & Advanced Options -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="ntb-settings-box">
                                        <label class="form-label">Bypass AI</label>
                                        <div class="d-flex">
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="radio" name="bypass_ai" id="bypass_original" value="original" checked>
                                                <label class="form-check-label" for="bypass_original">Original</label>
                                            </div>
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="radio" name="bypass_ai" id="bypass_zerogpt" value="zerogpt">
                                                <label class="form-check-label" for="bypass_zerogpt">Zerogpt</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="bypass_ai" id="bypass_sinonim" value="sinonim">
                                                <label class="form-check-label" for="bypass_sinonim">Sinonim</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="ntb-settings-box">
                                        <label for="multi_thread" class="form-label" id="multi_thread_label">Multi-thread (1 thread)</label>
                                        <input type="range" class="form-range" step="1" id="multi_thread" value="1">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="ntb-settings-box">
                                        <label for="upaya_percobaan" class="form-label" id="upaya_percobaan_label">Upaya Percobaan (3x Upaya Percobaan)</label>
                                        <input type="range" class="form-range" step="1" id="upaya_percobaan" value="3">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="ntb-settings-box">
                                        <label for="delay" class="form-label" id="delay_label">Delay (0 second)</label>
                                        <input type="range" class="form-range" step="1" id="delay" value="0">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- Kata Kunci Section -->
                            <div class="ntb-settings-box">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="kata_kunci" class="form-label mb-0">Kata Kunci <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#generateKeywordsModal">
                                        <i class="fas fa-magic"></i> Hasilkan Kata Kunci
                                    </button>
                                </div>

                                <!-- [PERBAIKAN] Gunakan textarea biasa yang terlihat -->
                                <textarea id="kata_kunci" name="kata_kunci" class="form-control" style="width:100%; height:200px;"></textarea>

                                <small class="form-text text-muted mt-2 d-block">Setiap baris akan dianggap sebagai satu kata kunci untuk pembuatan artikel.</small>
                            </div>
                            <div class="my-2">
                                <button type="submit" class="btn btn-primary" id="btn-post"><i class="fas fa-paper-plane"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Kolom Kanan: Logs -->
                <div class="col-lg-5">
                    <div class="ntb-card">
                        <div class="ntb-card-header">
                            <h5 class="mb-0"><i class="fas fa-history"></i> Logs</h5>
                        </div>
                        <div class="ntb-card-body ntb-logs-body" id="log-output">
                            <!-- Log content will appear here -->
                            <!-- <p class="text-muted text-center mt-5">Belum ada log yang tersedia.</p> -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal for Generating Keywords -->
    <div class="modal fade" id="generateKeywordsModal" tabindex="-1" aria-labelledby="generateKeywordsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateKeywordsModalLabel">Generate Keywords</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="api_key_selector" class="form-label">Gunakan Kunci API:</label>
                        <select class="form-select" id="api_key_selector" name="api_key">
                            <!-- Pilihan API key akan diisi oleh JavaScript -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="total_keyword" class="form-label">Total Keyword:</label>
                        <input type="number" class="form-control" id="total_keyword" value="20" name="total_keyword">
                    </div>

                    <div class="mb-3">
                        <label for="base_keyword" class="form-label">Keyword:</label>
                        <input type="text" class="form-control" id="base_keyword" placeholder="Enter Keyword" name="base_keyword">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" id="generate-keywords-submit"><i class="fas fa-check"></i> Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>