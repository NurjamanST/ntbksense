<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/security_settings.php
// FUNGSI: Menampilkan konten untuk tab "Keamanan".
// =========================================================================
?>
<div class="tab-pane fade" id="keamanan" role="tabpanel">
    <div class="ntb-settings-section">
        <div class="mb-4">
            <label class="form-label">Sembunyikan Form Login</label>
            <div class="d-flex align-items-center">
                <label class="ntb-switch">
                    <input type="checkbox" value="1" id="sembunyikan_form_login" name="sembunyikan_form_login" <?php checked($options['sembunyikan_form_login'] ?? 0, 1); ?>>
                    <span class="ntb-slider"></span>
                </label>
                <label class="form-check-label ms-3" for="sembunyikan_form_login">
                    Lindungi website Anda dengan mengubah URL login dan mencegah akses ke halaman wp-login.php dan direktori wp-admin.
                </label>
            </div>
        </div>
        <div class="mb-4">
            <label for="url_login" class="form-label">URL Login</label>
            <div class="input-group">
                <span class="input-group-text"><?php echo esc_url(home_url('/')); ?></span>
                <input type="text" id="url_login" name="url_login" class="form-control" value="<?php echo esc_attr($options['url_login'] ?? 'custom-login'); ?>">
            </div>
        </div>
        <div class="mb-4">
            <label for="aksi_login" class="form-label">Aksi</label>
            <select id="aksi_login" name="aksi_login" class="form-select">
                <option <?php selected($options['aksi_login'] ?? '403', '403'); ?>>403 Access Forbidden</option>
                <option <?php selected($options['aksi_login'] ?? '', '404'); ?>>404 Not Found</option>
            </select>
        </div>
    </div>
</div>
