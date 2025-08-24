<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/security_settings.php
// FUNGSI: Tab "Keamanan" -- Sembunyikan Form Login
// =========================================================================

$plugin = ntbksense_get_branding();
$enabled = $plugin["hidden_login_form"];
$slug = $plugin["url_login"] ?? "custom-login";
$aksi = $plugin["aksi_login"] ?? "403";
?>

<div class="tab-pane fade" id="keamanan" role="tabpanel">
    <form id="securitySettingsForm" method="post">
        <div class="ntb-settings-section">

            <!-- Toggle -->
            <div class="mb-4">
                <label class="form-label">Sembunyikan Form Login</label>
                <div class="d-flex align-items-center">
                    <label class="ntb-switch">
                        <input type="checkbox" id="hidden_login_form" name="hidden_login_form" value="1" <?php checked($enabled); ?>>
                        <span class="ntb-slider"></span>
                    </label>
                    <label class="form-check-label ms-3" for="hidden_login_form">
                        Lindungi website Anda dengan mengubah URL login dan mencegah akses ke halaman <code>wp-login.php</code> dan direktori <code>wp-admin</code>.
                    </label>
                </div>
            </div>

            <!-- URL Login -->
            <div class="mb-4">
                <label for="url_login" class="form-label">URL Login</label>
                <div class="input-group">
                    <span class="input-group-text"><?php echo esc_url(trailingslashit(home_url("/"))); ?></span>
                    <input type="text" id="url_login" name="url_login" class="form-control" value="<?php echo esc_attr($slug); ?>" placeholder="custom-login">
                </div>
                <p class="description">Hanya slug (tanpa spasi). Contoh: <code>custom-login</code></p>
            </div>

            <!-- Aksi -->
            <div class="mb-4">
                <label for="aksi_login" class="form-label">Aksi</label>
                <select id="aksi_login" name="aksi_login" class="form-select">
                    <option value="403" <?php selected($aksi, "403"); ?>>403 Access Forbidden</option>
                    <option value="404" <?php selected($aksi, "404"); ?>>404 Not Found</option>
                </select>
            </div>

        </div>

        <div class="ntb-form-actions">
            <button type="submit" class="button button-primary btn-save-security">
                Simpan Keamanan
            </button>
        </div>
    </form>
</div>

<script>
jQuery(function($) {
    $('#securitySettingsForm').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('.btn-save-security').prop('disabled', true).text('Menyimpan...');
        const $slug = $('#url_login');

        // Normalize the slug input
        $slug.val(($slug.val() || '').trim().replace(/^\/+|\/+$/g, ''));

        const data = $(this).serializeArray();
        if (!$('#hidden_login_form').is(':checked')) {
            data.push({ name: 'hidden_login_form', value: 0 });
        }

        data.push({ name: 'action', value: 'ntbksense_update_settings' });
        data.push({ name: 'security', value: '<?php echo wp_create_nonce("ntbksense_settings_nonce"); ?>' });

        $.post(ajaxurl, data)
            .done(function(resp) {
                const message = resp?.data?.message || 'Tersimpan. (Permalinks akan di-flush)';
                alert(resp && resp.success ? message : 'Gagal menyimpan.');
                if (resp && resp.success) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
            .fail(function() {
                alert('Koneksi gagal.');
            })
            .always(function() {
                $btn.prop('disabled', false).text('Simpan Keamanan');
            });
    });
});
</script>
