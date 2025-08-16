<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/optimization_settings.php
// FUNGSI: Menampilkan konten untuk tab "Optimasi".
// =========================================================================
?>
<div class="tab-pane fade" id="optimasi" role="tabpanel">
    <div class="ntb-settings-section">
        <div class="row mb-4 align-items-center">
            <div class="col-lg-4">
                <label for="hapus_tag_meta" class="form-label fw-bold mb-0">Hapus Tag Meta WP Header.</label>
            </div>
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <label class="ntb-switch me-3 flex-shrink-0">
                        <input type="checkbox" value="1" id="hapus_tag_meta" name="hapus_tag_meta" <?php checked($options['hapus_tag_meta'] ?? 1, 1); ?>>
                        <span class="ntb-slider"></span>
                    </label>
                    <label class="text-muted mb-0" for="hapus_tag_meta">
                        Hapus tag meta tidak perlu di head agar lebih ringan dan cepat terindeks.
                    </label>
                </div>
            </div>
        </div>
        <div class="row mb-4 align-items-center">
            <div class="col-lg-4">
                <label for="hapus_script_emoji" class="form-label fw-bold mb-0">Hapus Script Emoji.</label>
            </div>
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <label class="ntb-switch me-3 flex-shrink-0">
                        <input type="checkbox" value="1" id="hapus_script_emoji" name="hapus_script_emoji" <?php checked($options['hapus_script_emoji'] ?? 1, 1); ?>>
                        <span class="ntb-slider"></span>
                    </label>
                    <label class="text-muted mb-0" for="hapus_script_emoji">
                        Centang ini jika Anda ingin menghapus skrip emoji dari bagian <code>&lt;head&gt;</code>. Ini dapat meningkatkan kinerja web Anda.
                    </label>
                </div>
            </div>
        </div>
        <div class="row mb-4 align-items-center">
            <div class="col-lg-4">
                <label for="hapus_versi_query" class="form-label fw-bold mb-0">Hapus Versi Query pada JS & CSS.</label>
            </div>
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <label class="ntb-switch me-3 flex-shrink-0">
                        <input type="checkbox" value="1" id="hapus_versi_query" name="hapus_versi_query" <?php checked($options['hapus_versi_query'] ?? 1, 1); ?>>
                        <span class="ntb-slider"></span>
                    </label>
                    <label class="text-muted mb-0" for="hapus_versi_query">
                        Centang ini jika Anda ingin menghapus versi query dari file <code>.js</code> & <code>.css</code>. Ini dapat meningkatkan performa dan cache website Anda.
                    </label>
                </div>
            </div>
        </div>
        <div class="row mb-4 align-items-center">
            <div class="col-lg-4">
                <label for="nonaktifkan_komentar" class="form-label fw-bold mb-0">Nonaktifkan Komentar.</label>
            </div>
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <label class="ntb-switch me-3 flex-shrink-0">
                        <input type="checkbox" value="1" id="nonaktifkan_komentar" name="nonaktifkan_komentar" <?php checked($options['nonaktifkan_komentar'] ?? 1, 1); ?>>
                        <span class="ntb-slider"></span>
                    </label>
                    <label class="text-muted mb-0" for="nonaktifkan_komentar">
                        Centang ini jika Anda ingin menonaktifkan komentar.
                    </label>
                </div>
            </div>
        </div>
        <div class="row mb-4 align-items-center">
            <div class="col-lg-4">
                <label for="judul_situs_otomatis" class="form-label fw-bold mb-0">Judul Situs Otomatis.</label>
            </div>
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <label class="ntb-switch me-3 flex-shrink-0">
                        <input type="checkbox" value="1" id="judul_situs_otomatis" name="judul_situs_otomatis" <?php checked($options['judul_situs_otomatis'] ?? 0, 1); ?>>
                        <span class="ntb-slider"></span>
                    </label>
                    <label class="text-muted mb-0" for="judul_situs_otomatis">
                        Centang ini jika Anda ingin judul WordPress secara otomatis mengikuti nama domain.
                    </label>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="pengalihan_404" class="form-label fw-bold mb-0">Pengalihan 404 Not Found.</label>
            </div>
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <label class="ntb-switch me-3 flex-shrink-0">
                        <input type="checkbox" value="1" id="pengalihan_404" name="pengalihan_404" <?php checked($options['pengalihan_404'] ?? 1, 1); ?>>
                        <span class="ntb-slider"></span>
                    </label>
                    <label class="text-muted mb-0" for="pengalihan_404">
                        Centang ini jika ingin halaman 404 otomatis diarahkan ke halaman utama atau halaman khusus.
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
