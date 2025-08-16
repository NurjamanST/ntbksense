<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/plugin_settings.php
// FUNGSI: Menampilkan konten untuk tab "Plugin".
// =========================================================================
?>
<div class="tab-pane fade" id="plugin" role="tabpanel">
    <div class="ntb-settings-section">
        <div class="row my-2">
            <div class="col-md-2">
                <label for="plugin_name" class="form-label">Nama Plugin</label>
            </div>
            <div class="col-md-10">
                <input type="text" id="plugin_name" name="plugin_name" class="form-control" value="<?php echo esc_attr($options['plugin_name'] ?? 'NTBKSense'); ?>">
            </div>
        </div>

        <div class="row my-2">
            <div class="col-md-2">
                <label for="plugin_language" class="form-label">Bahasa Plugin</label>
            </div>
            <div class="col-md-10">
                <select id="plugin_language" name="plugin_language" class="form-select">
                    <option <?php selected($options['plugin_language'] ?? 'Indonesian', 'Indonesian'); ?>>Indonesian</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'English'); ?>>English</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'Spanish'); ?>>Spanish</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'French'); ?>>French</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'German'); ?>>German</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'Portuguese'); ?>>Portuguese</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'Russian'); ?>>Russian</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'Chinese'); ?>>Chinese</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'Japanese'); ?>>Japanese</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'Korean'); ?>>Korean</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'Arabic'); ?>>Arabic</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'Hindi'); ?>>Hindi</option>
                    <option <?php selected($options['plugin_language'] ?? '', 'Bengali'); ?>>Bengali</option>
                </select>
            </div>
        </div>

        <div class="row my-2">
            <div class="col-md-2">
                <label for="plugin_logo" class="form-label">Logo Plugin</label>
            </div>
            <div class="col-md-10">
                <div class="input-group">
                    <input type="text" id="plugin_logo" name="plugin_logo" class="form-control" value="<?php echo esc_attr($options['plugin_logo'] ?? 'https://ntbksense.web.id/logo.png'); ?>" placeholder="https://ntbksense.web.id/logo.png">
                    <input type="file" id="plugin_logo_file" name="plugin_logo_file" style="display: none;" accept="image/*">
                    <button class="btn btn-primary" type="button" id="upload_logo_button">
                        <span class="dashicons dashicons-cloud-upload"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
