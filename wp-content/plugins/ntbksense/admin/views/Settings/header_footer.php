<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/header_footer.php
// FUNGSI: Menampilkan konten untuk tab "Header & Footer".
// =========================================================================

$plugin = ntbksense_get_branding();
$header_code  = $plugin['header_code'];
$footer_code  = $plugin['footer_code'];
?>
<div class="tab-pane fade" id="header-footer" role="tabpanel">
    <form id="headfootsettingsForm" method="post">
        <div class="">
            <div class="ntb-settings-section">
                <h5 class="section-title">Header</h5>
                <div class="mb-3">
                    <label for="header_code" class="form-label">Kode Header</label>
                    <textarea id="header_code" name="header_code" class="form-control" rows="8"><?php echo esc_textarea($header_code); ?></textarea>
                    <small class="form-text text-muted">Masukkan kode HTML/JS yang ingin ditambahkan di bagian <code>&lt;head&gt;</code> situs Anda.</small>
                </div>
            </div>
            <div class="ntb-settings-section">
                <h5 class="section-title">Footer</h5>
                <div class="mb-3">
                    <label for="footer_code" class="form-label">Kode Footer</label>
                    <textarea id="footer_code" name="footer_code" class="form-control" rows="8"><?php echo esc_textarea($footer_code); ?></textarea>
                    <small class="form-text text-muted">Masukkan kode HTML/JS yang ingin ditambahkan sebelum tag penutup <code>&lt;/body&gt;</code>.</small>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Form -->
        <div class="ntb-form-actions">
            <a href="#" class="button button-secondary prev-tab" data-prev="#advance">&larr; Kembali</a>
            <button type="submit" class="button button-primary btn-update-settings">Simpan Header Footer</button>
            <a href="#" class="button button-secondary next-tab" data-next="#plugin">Selanjutnya &rarr;</a>
        </div>
    </form>
</div>

<script>
jQuery(function($){
    $('#headfootsettingsForm').on('submit', function(e){
        e.preventDefault();
        const $btn = $('.btn-update-settings').prop('disabled', true).text('Menyimpan...');

        const data = [
            {name:'header_code', value: $('#header_code').val() || ''},
            {name:'footer_code', value: $('#footer_code').val() || ''},
            {name:'action',      value: 'ntbksense_update_settings'},
            {name:'security',    value: '<?php echo wp_create_nonce("ntbksense_settings_nonce"); ?>'}
        ];

        $.post(ajaxurl, data)
            .done(function(resp){
                if (resp && resp.success) {
                    alert(resp.data?.message || 'Pengaturan disimpan.');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    alert(resp?.data?.message || 'Gagal menyimpan.');
                }
            })
            .fail(function(){ alert('Koneksi gagal.'); })
            .always(function(){ $btn.prop('disabled', false).text('Simpan Header & Footer'); });
        });

        // Navigasi tab (opsional, kalau di layout kamu dipakai)
        $('.prev-tab, .next-tab').on('click', function(e){
        e.preventDefault();
        const target = $(this).data($(this).hasClass('prev-tab') ? 'prev' : 'next');
        if (target) {
            $('a[href="'+target+'"]').tab('show');
            document.querySelector(target)?.scrollIntoView({behavior:'smooth', block:'start'});
        }
    });
});
</script>
