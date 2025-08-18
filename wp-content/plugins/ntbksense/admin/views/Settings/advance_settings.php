<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/advance_settings.php
// FUNGSI: Menampilkan konten untuk tab "Advance".
// =========================================================================
?>
<div class="tab-pane fade" id="advance" role="tabpanel">
    <form id="advancesettingsForm" method="post">
        <!-- Form List -->
        <div class="">
            <!-- Jenis Pengalihan -->
            <div class="ntb-settings-section">
                <div class="mb-3">
                    <label for="jenis_pengalihan" class="form-label">Jenis Pengalihan</label>
                    <select id="jenis_pengalihan" name="jenis_pengalihan" class="form-select">
                        <option>Mode 302 Temporary - (PHP)</option>
                        <option>Mode Javascript</option>
                    </select>
                    <small class="form-text text-muted">
                        <b>Mode 302 (Temporary - PHP) :</b> Pengalihan menggunakan header PHP, lebih cepat dan direkomendasikan untuk perubahan URL sementara.<br>
                        <b>Mode Javascript :</b> Redirect berbasis skrip yang tetap memungkinkan Google Analytics melacak data kunjungan.
                    </small>
                </div>
            </div>
            <!-- Akses Lokasi -->
            <div class="ntb-settings-section">
                <div class="mb-3">
                    <label for="akses_lokasi_mode" class="form-label">Akses Lokasi</label>
                    <select id="akses_lokasi_mode" name="akses_lokasi_mode" class="form-select mb-2">
                        <option value="off" <?php selected($off, true); ?>>Off</option>
                        <option value="termasuk" <?php selected($termasuk, true); ?>>Termasuk</option>
                        <option value="kecualikan" <?php selected($kecualikan, true); ?>>Kecualikan</option>
                    </select>
                    <div id="akses_lokasi_countries_wrapper">
                        <select id="akses_lokasi_countries" name="akses_lokasi_countries[]" class="form-select" multiple="multiple">
                            <?php include "countries.php"; ?>
                        </select>
                    </div>
                    <small class="form-text text-muted mt-2">
                        <ul class="list-unstyled mb-0">
                            <li><span class="text-primary">&bull;</span> <strong>**Termasuk**</strong> &rarr; Hanya berlaku untuk negara yang dipilih.</li>
                            <li><span class="text-primary">&bull;</span> <strong>**Kecualikan**</strong> &rarr; Berlaku untuk *semua negara kecuali* negara yang dipilih.</li>
                        </ul>
                    </small>
                </div>
            </div>
            <!-- Blokir IP Address-->
            <div class="ntb-settings-section">
                <div class="mb-3">
                    <label for="blokir_ip_address" class="form-label">Blokir IP address</label>
                    <textarea id="blokir_ip_address" name="blokir_ip_address" class="form-control" rows="4" placeholder="Masukkan satu IP per baris untuk memblokir akses dari IP tertentu, Contoh:&#10;192.168.1.1&#10;203.0.113.42"><?= esc_textarea($options['blokir_ip_address'] ?? ''); ?></textarea>
                    <small class="form-text text-muted">Masukkan satu IP per baris untuk memblokir akses dari IP tertentu.<br>
                    Contoh:<br>
                    192.168.1.1<br>
                    203.0.113.42<br>
                    Anda juga bisa memblokir rentang IP dengan format CIDR. Contoh: <code>192.0.2.0/24</code></small>
                </div>
            </div>
            <!-- Akses ASN Section -->
            <div class="ntb-settings-section">
                <div class="mb-3">
                    <label for="akses_asn_mode" class="form-label">Akses ASN</label>
                    <select id="akses_asn_mode" name="akses_asn_mode" class="form-select">
                        <option value="off" <?php selected($options['akses_asn_mode'] ?? 'off', 'off'); ?>>Off</option>
                        <option value="termasuk" <?php selected($options['akses_asn_mode'] ?? '', 'termasuk'); ?>>Termasuk</option>
                        <option value="kecualikan" <?php selected($options['akses_asn_mode'] ?? '', 'kecualikan'); ?>>Kecualikan</option>
                    </select>
                </div>
                <div class="mb-3" id="akses_asn_list_wrapper">
                    <textarea id="akses_asn_list" name="akses_asn_list" class="form-control" rows="4" placeholder="Masukkan satu ASN per baris, Contoh:&#10;15169&#10;45566"><?php echo esc_textarea($options['akses_asn_list'] ?? ''); ?></textarea>
                </div>
                <small class="form-text text-muted">
                    <strong>Apa itu ASN?</strong><br>
                    ASN (Autonomous System Number) adalah nomor unik yang digunakan untuk mengidentifikasi jaringan internet milik perusahaan, penyedia layanan internet (ISP), atau organisasi tertentu. Contoh ASN:<br>
                    - 15169 &rarr; Google<br>
                    - 45566 &rarr; Indosat<br>
                    - 32934 &rarr; Facebook<br>
                    <strong>Cara penggunaan:</strong><br>
                    - Pilih mode Kecualikan untuk memblokir semua ASN kecuali yang terdaftar di daftar ini.<br>
                    - Pilih mode Termasuk untuk hanya mengizinkan ASN yang terdaftar di daftar ini.<br>
                    - Masukkan satu ASN per baris.<br>
                    <strong>Bagaimana cara mengetahui ASN?.</strong><br>
                    Anda bisa mencari ASN suatu IP menggunakan layanan seperti: <a href="https://ipinfo.io" target="_blank">ipinfo.io</a> or <a href="https://bgpview.io" target="_blank">bgpview.io</a>.
                </small>
            </div>
            <!-- Detektor Perangkat -->
            <div class="ntb-settings-section">
                <div class="mb-3">
                    <label for="detektor_perangkat" class="form-label">Detektor Perangkat</label>
                    <select id="detektor_perangkat" name="detektor_perangkat" class="form-select">
                        <option value="ya" <?php selected($options['detektor_perangkat'] ?? 'ya', 'ya'); ?>>Ya (Default)</option>
                        <option value="tidak" <?php selected($options['detektor_perangkat'] ?? '', 'tidak'); ?>>Tidak</option>
                    </select>
                    <small class="form-text text-muted">Jika diaktifkan, plugin akan menggunakan Detektor Perangkat untuk mendeteksi jenis perangkat pengunjung (mobile, tablet, desktop, bot, dll.).</small>
                </div>
                <div class="mt-3">
                    <p class="mb-1"><strong>Keuntungan:</strong></p>
                    <ul class="ntb-feature-list">
                        <li><span class="dashicons dashicons-yes-alt text-success"></span> Akurasi lebih tinggi dibanding metode user-agent standar.</li>
                        <li><span class="dashicons dashicons-yes-alt text-success"></span> Dapat membedakan bot dan perangkat asli dengan akurat.</li>
                        <li><span class="dashicons dashicons-yes-alt text-success"></span> Memungkinkan pengalihan (redirect) yang lebih tepat berdasarkan jenis perangkat.</li>
                        <li><span class="dashicons dashicons-yes-alt text-success"></span> Meningkatkan pengalaman pengguna dengan konten yang disesuaikan.</li>
                    </ul>
                    <p class="mb-1"><strong>Kekurangan:</strong></p>
                    <ul class="ntb-feature-list">
                        <li><span class="dashicons dashicons-warning text-warning"></span> Membutuhkan lebih banyak sumber daya server, terutama pada situs dengan trafik tinggi.</li>
                        <li><span class="dashicons dashicons-warning text-warning"></span> Bisa meningkatkan waktu loading jika tidak dikonfigurasi dengan baik.</li>
                        <li><span class="dashicons dashicons-warning text-warning"></span> Menggunakan caching dapat mengurangi beban, tetapi perlu dikonfigurasi dengan benar.</li>
                    </ul>
                    <p class="mb-1"><strong>Catatan:</strong></p>
                        <ul class="ntb-feature-list">
                        <li>- Jika diatur ke <strong>Tidak</strong>, sistem hanya akan mengandalkan metode deteksi bawaan yang lebih ringan tetapi kurang akurat.</li>
                        <li>- Jika mengalami masalah performa, pertimbangkan untuk menggunakan caching atau metode deteksi yang lebih sederhana.</li>
                    </ul>
                </div>
            </div>
            <!-- Simpan Log ke Database -->
            <div class="ntb-settings-section">
                <div class="mb-3">
                    <label for="simpan_log" class="form-label">Simpan Log ke Database</label>
                    <select id="simpan_log" name="simpan_log" class="form-select">
                        <option <?php selected($options['simpan_log'] ?? 'Aktifkan', 'Aktifkan'); ?>>Aktifkan</option>
                        <option <?php selected($options['simpan_log'] ?? '', 'Nonaktifkan'); ?>>Nonaktifkan</option>
                    </select>
                    <small class="form-text text-muted">Jika diaktifkan, setiap redirect akan dicatat di database.<br><strong>Keuntungan:</strong> Memberikan data analitik yang berharga soal traffic.<br><strong>Kekurangan:</strong> Database bisa cepat penuh jika traffic tinggi.</small>
                </div>
            </div>
            <!-- Simpan Durasi Kunjungan -->
            <div class="ntb-settings-section">
                <h5 class="section-title">Simpan Durasi Kunjungan</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label for="simpan_durasi" class="form-label">Simpan Durasi Kunjungan</label>
                        <select id="simpan_durasi" name="simpan_durasi" class="form-select">
                            <option <?php selected($options['simpan_durasi'] ?? 'Tidak (Default)', 'Tidak (Default)'); ?>>Tidak (Default)</option>
                            <option <?php selected($options['simpan_durasi'] ?? '', 'Ya'); ?>>Ya</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="interval_durasi" class="form-label">Interval</label>
                            <div class="input-group">
                            <input type="number" id="interval_durasi" name="interval_durasi" class="form-control" value="<?php echo esc_attr($options['interval_durasi'] ?? 5); ?>">
                            <span class="input-group-text">Detik</span>
                        </div>
                    </div>
                </div>
                <small class="form-text text-muted mt-2 d-block">Jika diaktifkan, setiap traffic akan dicatat durasi kunjungannya.</small>
            </div>
        </div>
        <!-- END: Form List -->
        
        <!-- Tombol Aksi Form -->
        <div class="ntb-form-actions">
            <a href="#" class="button button-secondary prev-tab" data-prev="#pengaturan-iklan">&larr; Kembali</a>
            <button type="submit" class="button button-primary btn-update-settings">Simpan Advance Pengaturan</button>
            <a href="#" class="button button-secondary next-tab" data-next="#header-footer">Selanjutnya &rarr;</a>
        </div>
    </form>
</div>