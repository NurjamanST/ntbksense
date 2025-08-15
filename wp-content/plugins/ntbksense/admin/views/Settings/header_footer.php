<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/header_footer.php
// FUNGSI: Menampilkan konten untuk tab "Header & Footer".
// =========================================================================
?>
<div class="tab-pane fade" id="header-footer" role="tabpanel">
    <div class="ntb-settings-section">
        <h5 class="section-title">Header</h5>
        <div class="mb-3">
            <label for="header_code" class="form-label">Kode Header</label>
            <textarea id="header_code" name="header_code" class="form-control" rows="8"><?php echo esc_textarea($options['header_code'] ?? ''); ?></textarea>
            <small class="form-text text-muted">Masukkan kode HTML/JS yang ingin ditambahkan di bagian <code>&lt;head&gt;</code> situs Anda.</small>
        </div>
    </div>
    <div class="ntb-settings-section">
        <h5 class="section-title">Footer</h5>
        <div class="mb-3">
            <label for="footer_code" class="form-label">Kode Footer</label>
            <textarea id="footer_code" name="footer_code" class="form-control" rows="8"><?php echo esc_textarea($options['footer_code'] ?? ''); ?></textarea>
            <small class="form-text text-muted">Masukkan kode HTML/JS yang ingin ditambahkan sebelum tag penutup <code>&lt;/body&gt;</code>.</small>
        </div>
    </div>
</div>
