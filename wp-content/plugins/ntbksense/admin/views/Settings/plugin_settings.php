<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/plugin_settings.php
// FUNGSI: Tab "Plugin" -- Branding Dinamis (form terpisah).
// =========================================================================

$plugin = ntbksense_get_branding();
$plugin_name  = $plugin['plugin_name'] ?? 'NTBKSense';
$plugin_logo  = $plugin['plugin_logo'] ?? '';
$plugin_lang  = $plugin['locale'] ?? '';

if (function_exists('wp_enqueue_media')) {
    wp_enqueue_media();
}
?>

<div class="tab-pane fade" id="plugin" role="tabpanel">
    <form id="pluginSettingsForm" method="post">
        <div class="ntb-settings-section">

            <!-- Nama Plugin -->
            <div class="row my-2">
                <div class="col-md-2">
                    <label for="plugin_name" class="form-label">Nama Plugin</label>
                </div>
                <div class="col-md-10">
                    <input type="text" id="plugin_name" name="plugin_name" class="form-control"
                           value="<?php echo esc_attr($plugin_name); ?>">
                </div>
            </div>

            <!-- Logo Plugin -->
            <div class="row my-2">
                <div class="col-md-2">
                    <label for="plugin_logo" class="form-label">Logo Plugin</label>
                </div>
                <div class="col-md-10">
                    <div class="input-group" style="gap:8px;align-items:center;">
                        <input type="text" id="plugin_logo" name="plugin_logo" class="form-control"
                               value="<?php echo esc_attr($plugin_logo); ?>" placeholder="https://example.com/logo.png">
                        <button type="button" class="button" id="upload_logo_button">Pilih dari Media</button>
                    </div>
                    <div style="margin-top:8px;">
                        <img id="plugin_logo_preview" src="<?php echo esc_url($plugin_logo); ?>"
                             style="max-height:48px;<?php echo $plugin_logo ? '' : 'display:none;'; ?>">
                        <button type="button" class="button-link-delete" id="remove_logo_button"
                                style="<?php echo $plugin_logo ? '' : 'display:none;'; ?>">Hapus Logo</button>
                    </div>
                </div>
            </div>

            <!-- Language Selection (commented out for now)
            <div class="row my-2">
                <div class="col-md-2">
                    <label for="plugin_language" class="form-label">Bahasa</label>
                </div>
                <div class="col-md-10">
                    <select id="plugin_language" name="plugin_language" class="form-select">
                        <option value="" <?php selected($plugin_lang, ''); ?>>Ikuti bahasa situs</option>
                        <?php
                        $languages = [
                            'id_ID' => 'Bahasa Indonesia',
                            'en_US' => 'English',
                            'es_ES' => 'Spanish',
                            'fr_FR' => 'French',
                            'de_DE' => 'German',
                            'pt_PT' => 'Portuguese',
                            'ru_RU' => 'Russian',
                            'zh_CN' => 'Chinese',
                            'ja_JP' => 'Japanese',
                            'ko_KR' => 'Korean',
                            'ar_AR' => 'Arabic',
                            'hi_IN' => 'Hindi',
                            'bn_BD' => 'Bengali',
                        ];
                        foreach ($languages as $code => $name) {
                            echo '<option value="' . esc_attr($code) . '" ' . selected($plugin_lang, $code, false) . '>' . esc_html($name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            -->

        </div>

        <div class="ntb-form-actions">
            <button type="submit" class="button button-primary btn-save-plugin">
                Simpan Pengaturan Plugin
            </button>
        </div>
    </form>
</div>

<script>
jQuery(function($) {
    let frame;
    
    $('#upload_logo_button').on('click', function(e) {
        e.preventDefault();
        if (frame) {
            frame.open();
            return;
        }
        
        frame = wp.media({
            title: 'Pilih Logo',
            button: { text: 'Gunakan Logo' },
            multiple: false
        });
        
        frame.on('select', function() {
            const att = frame.state().get('selection').first().toJSON();
            $('#plugin_logo').val(att.url);
            $('#plugin_logo_preview').attr('src', att.url).show();
            $('#remove_logo_button').show();
        });
        
        frame.open();
    });
    
    $('#remove_logo_button').on('click', function() {
        $('#plugin_logo').val('');
        $('#plugin_logo_preview').hide().attr('src', '');
        $(this).hide();
    });

    $('#pluginSettingsForm').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('.btn-save-plugin').prop('disabled', true).text('Menyimpan...');
        
        $.post(ajaxurl, $(this).serialize() + '&action=ntbksense_update_settings&security=<?php echo wp_create_nonce("ntbksense_settings_nonce"); ?>')
            .done(function(resp) {
                if (resp.success) {
                    alert(resp.data.message || 'Pengaturan disimpan.');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    alert(resp.data.message || 'Gagal menyimpan.');
                }
            })
            .fail(() => alert('Terjadi kesalahan koneksi.'))
            .always(() => $btn.prop('disabled', false).text('Simpan Pengaturan Plugin'));
    });
});
</script>
