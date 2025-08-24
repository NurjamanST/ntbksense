<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/optimization_settings.php
// FUNGSI: Menampilkan konten untuk tab "Optimasi" + handle submit AJAX.
// =========================================================================

$options = ntbksense_get_branding();
?>

<div class="tab-pane fade" id="optimasi" role="tabpanel">
    <form id="optimizationSettingsForm" method="post">
        <div class="ntb-settings-section">

            <?php
            $settings = [
                [
                    'id' => 'hapus_tag_meta',
                    'label' => 'Hapus Tag Meta WP Header.',
                    'description' => 'Hapus tag meta tidak perlu di head agar lebih ringan dan cepat terindeks.',
                    'checked' => $options['hapus_tag_meta'] ?? 1,
                ],
                [
                    'id' => 'hapus_script_emoji',
                    'label' => 'Hapus Script Emoji.',
                    'description' => 'Centang ini jika Anda ingin menghapus skrip emoji dari bagian <code>&lt;head&gt;</code>. Ini dapat meningkatkan kinerja web Anda.',
                    'checked' => $options['hapus_script_emoji'] ?? 1,
                ],
                [
                    'id' => 'hapus_versi_query',
                    'label' => 'Hapus Versi Query pada JS & CSS.',
                    'description' => 'Centang ini jika Anda ingin menghapus versi query dari file <code>.js</code> &amp; <code>.css</code>. Ini dapat meningkatkan performa dan cache website Anda.',
                    'checked' => $options['hapus_versi_query'] ?? 1,
                ],
                [
                    'id' => 'nonaktifkan_komentar',
                    'label' => 'Nonaktifkan Komentar.',
                    'description' => 'Centang ini jika Anda ingin menonaktifkan komentar.',
                    'checked' => $options['nonaktifkan_komentar'] ?? 1,
                ],
                [
                    'id' => 'judul_situs_otomatis',
                    'label' => 'Judul Situs Otomatis.',
                    'description' => 'Centang ini jika Anda ingin judul WordPress secara otomatis mengikuti nama domain.',
                    'checked' => $options['judul_situs_otomatis'] ?? 0,
                ],
                [
                    'id' => 'pengalihan_404',
                    'label' => 'Pengalihan 404 Not Found.',
                    'description' => 'Centang ini jika ingin halaman 404 otomatis diarahkan ke halaman utama atau halaman khusus.',
                    'checked' => $options['pengalihan_404'] ?? 1,
                ],
            ];

            foreach ($settings as $setting) {
                ?>
                <div class="row mb-4 align-items-center">
                    <div class="col-lg-4">
                        <label for="<?php echo $setting['id']; ?>" class="form-label fw-bold mb-0"><?php echo $setting['label']; ?></label>
                    </div>
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center">
                            <label class="ntb-switch me-3 flex-shrink-0">
                                <input type="checkbox" value="1" id="<?php echo $setting['id']; ?>" name="<?php echo $setting['id']; ?>" <?php checked($setting['checked'], 1); ?>>
                                <span class="ntb-slider"></span>
                            </label>
                            <label class="text-muted mb-0" for="<?php echo $setting['id']; ?>">
                                <?php echo $setting['description']; ?>
                            </label>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

        </div>

        <div class="ntb-form-actions mt-4">
            <button type="submit" class="button button-primary btn-save-optimization">Simpan Optimasi</button>
        </div>
    </form>
</div>

<script>
jQuery(function($) {
    $('#optimizationSettingsForm').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('.btn-save-optimization').prop('disabled', true).text('Menyimpan...');

        const data = [];
        const checks = [
            'hapus_tag_meta',
            'hapus_script_emoji',
            'hapus_versi_query',
            'nonaktifkan_komentar',
            'judul_situs_otomatis',
            'pengalihan_404'
        ];
        checks.forEach(function(name) {
            const $el = $('#' + name);
            data.push({name: name, value: $el.is(':checked') ? 1 : 0});
        });

        data.push({name: 'action', value: 'ntbksense_update_settings'});
        data.push({name: 'security', value: '<?php echo wp_create_nonce("ntbksense_settings_nonce"); ?>'});

        $.post(ajaxurl, data)
            .done(function(resp) {
                if (resp && resp.success) {
                    alert(resp.data?.message || 'Pengaturan Optimasi tersimpan.');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    alert(resp?.data?.message || 'Gagal menyimpan.');
                }
            })
            .fail(function() {
                alert('Koneksi gagal.');
            })
            .always(function() {
                $btn.prop('disabled', false).text('Simpan Optimasi');
            });
    });
});
</script>
