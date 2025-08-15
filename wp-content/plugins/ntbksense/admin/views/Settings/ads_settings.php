<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/ads_settings.php
// FUNGSI: Menampilkan konten untuk tab "Pengaturan Iklan".
// =========================================================================
?>
<div class="tab-pane fade show active" id="pengaturan-iklan" role="tabpanel">
    <div class="ntb-settings-section">
        <div class="mb-3">
            <label for="id_publisher" class="form-label">ID Publisher</label>
            <input type="text" id="id_publisher" name="id_publisher" class="form-control" value="<?= $id_publisher ?>">
        </div>
    </div>
    <div class="ntb-settings-section">
        <h5 class="section-title">Iklan Display</h5>
        <div class="row">
            <div class="col-md-6"><label for="opacity" class="form-label">Opacity (%)</label><input type="number" id="opacity" name="opacity" class="form-control" value="<?= $opacity ?>"></div>
            <div class="col-md-6"><label for="jumlah_iklan" class="form-label">Jumlah Iklan</label>
                <select id="jumlah_iklan" name="jumlah_iklan" class="form-select">
                    <?php 
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<option value="' . $i . '" ' . selected($jumlah_iklan_val, $i, false) . '>' . $i . '</option>';
                        } 
                    ?>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3"><label for="mode_tampilan" class="form-label">Mode Tampilan</label><select id="mode_tampilan" name="mode_tampilan" class="form-select">
                    <option value="Berurutan" <?php selected($berurutan, true); ?>>Berurutan</option>
                    <option value="Acak" <?php selected($acak, true); ?>>Acak</option>
                </select></div>
            <div class="col-md-3"><label for="posisi" class="form-label">Posisi</label><select id="posisi" name="posisi" class="form-select">
                    <option value="Fixed" <?php selected($fixed, true); ?>>Fixed</option>
                    <option value="Absolute" <?php selected($absolute, true); ?>>Absolute</option>
                </select></div>
            <div class="col-md-3"><label for="margin_top" class="form-label">Margin Top</label>
                <div class="input-group"><input type="number" id="margin_top" name="margin_top" class="form-control" value="<?= $margintop; ?>"><span class="input-group-text">px</span></div>
            </div>
            <div class="col-md-3"><label for="margin_bottom" class="form-label">Margin Bottom</label>
                <div class="input-group"><input type="number" id="margin_bottom" name="margin_bottom" class="form-control" value="<?= $marginbottom; ?>"><span class="input-group-text">px</span></div>
            </div>
        </div>
    </div>
    <div class="ntb-settings-section">
        <h5 class="section-title">Kode Iklan Display</h5>
        <div class="row">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="col-md-6 mb-3">
                    <label for="kode_iklan_<?= $i; ?>" class="form-label">Kode Iklan <?= $i; ?></label>
                    <textarea id="kode_iklan_<?= $i; ?>" name="kode_iklan_<?= $i; ?>" class="form-control" rows="4"><?= $kode_iklan[$i-1]; ?></textarea>
                </div>
            <?php endfor; ?>
            <div class="col-md-6 mb-3">
                <label for="refresh_blank" class="form-label">Refresh jika iklan blank</label>
                <div class="input-group"><input type="number" id="refresh_blank" name="refresh_blank" class="form-control" value="<?= $refresh?>"><span class="input-group-text">Detik</span></div>
            </div>
        </div>
    </div>
    <div class="ntb-settings-section">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="sembunyikan_vinyet" name="sembunyikan_vinyet" <?php checked($vinyet); ?>>
            <label class="form-check-label" for="sembunyikan_vinyet">Sembunyikan iklan vinyet.</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="sembunyikan_anchor" name="sembunyikan_anchor" <?php checked($anchor); ?>>
            <label class="form-check-label" for="sembunyikan_anchor">Sembunyikan iklan anchor.</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="sembunyikan_elemen_blank" name="sembunyikan_elemen_blank" <?php checked($elemen_blank); ?>>
            <label class="form-check-label" for="sembunyikan_elemen_blank">Sembunyikan elemen iklan (jika blank).</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="tampilkan_close_ads" name="tampilkan_close_ads" <?php checked($close_ads); ?>>
            <label class="form-check-label" for="tampilkan_close_ads">Tampilkan Tombol Close ADS.</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="auto_scroll" name="auto_scroll" <?php checked($auto_scroll); ?>>
            <label class="form-check-label" for="auto_scroll">Auto Scroll.</label>
        </div>
    </div>
</div>