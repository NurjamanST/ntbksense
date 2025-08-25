<?php
// =========================================================================
// FILE: ntbksense/admin/views/Lisensi/ntbksense-lisensi.php
// FUNGSI: Menampilkan dan mengelola halaman lisensi.
// =========================================================================

$plugin = ntbksense_get_branding();
$plugin_name  = $plugin['plugin_name'] ?? 'NTBKSense';
$license_key = $plugin["license_key"];
$license_status = $plugin["license_status"];
$license_data = $plugin["license_data"];
?>
<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>"><?php echo esc_html($plugin_name); ?></a> &gt; <span>Lisensi</span>
    </div>
    
    <!-- Main Content -->
    <div class="ntb-main-content">
        <h4 class="ntb-main-title"><i class="fas fa-key"></i> Lisensi</h4>
        <hr>

        <?php if ($license_status === 'active' && !empty($license_data)) : ?>
        <div class="license-active">
            <!-- TAMPILAN JIKA LISENSI AKTIF -->
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle"></i> Lisensi sah. Terima kasih telah menggunakan Plugin NTBKSense Beta Version.
            </div>

            <div class="ntb-license-table">
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Email</div>
                    <div class="ntb-license-value"><?php echo esc_html($license_data['email'] ?? 'N/A'); ?></div>
                </div>
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Kode Lisensi</div>
                    <div class="ntb-license-value"><code><?php echo esc_html($license_key); ?></code></div>
                </div>
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Tanggal Registrasi</div>
                    <div class="ntb-license-value"><?php echo esc_html(date('d F Y', strtotime($license_data['registration_date'] ?? 'now'))); ?></div>
                </div>
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Tanggal Kadaluwarsa</div>
                    <div class="ntb-license-value"><?php echo esc_html($license_data['expiry_date'] ? date('d F Y', strtotime($license_data['expiry_date'])) : 'Seumur Hidup'); ?></div>
                </div>
                <div class="ntb-license-row">
                    <div class="ntb-license-label">Status</div>
                    <div class="ntb-license-value">
                        <span class="badge bg-success">Aktif</span>
                    </div>
                </div>
            </div>

            <form>
                <input type="hidden" name="ntbksense_action" value="deactivate_license">
                <button type="submit" class="btn btn-danger btn-sm mt-3">Nonaktifkan Lisensi</button>
            </form>
        </div>
        <?php else : ?>
        <div class="license-inactive">
            <!-- TAMPILAN JIKA LISENSI TIDAK AKTIF -->
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Lisensi tidak aktif. Silakan aktivasi untuk menikmati semua fitur.
            </div>

            <div class="ntb-settings-box mb-4">
                <form>
                    <!-- INI BAGIAN PENTING YANG HILANG -->
                    <input type="hidden" name="ntbksense_action" value="activate_license">
                    <div class="mb-3">
                        <label for="license_key" class="form-label">Kode Lisensi</label>
                        <div class="input-group">
                            <input type="text" id="license_key" name="ntbksense_license_key" class="form-control" placeholder="Masukkan kode lisensi Anda">
                            <button type="submit" class="btn btn-primary">Aktivasi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php 
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>

<script>
jQuery(function($){
  // initial toggle berdasarkan status dari PHP
  (function initToggle() {
    var status = <?php echo json_encode(ntbk_opt('license_status', 'inactive')); ?>;
    if (status === 'active') {
      $('.license-active').removeClass('hidden');
      $('.license-inactive').addClass('hidden');
    } else {
      $('.license-active').addClass('hidden');
      $('.license-inactive').removeClass('hidden');
    }
  })();

  function ajaxPost(data, doneCb) {
    $.post(ajaxurl, data)
      .done(function(resp){
        if (resp && resp.success) {
          alert(resp.data?.message || 'Sukses.');
          if (typeof doneCb === 'function') doneCb(resp);
        } else {
          alert(resp?.data?.message || 'Gagal.');
        }
      })
            .fail(function(xhr){
                console.error('gagal banh:', xhr)
        alert('Koneksi gagal: ' + (xhr?.responseJSON?.data?.message || ''));
      });
  }

  // Form Aktivasi
  $('.license-inactive form').on('submit', function(e){
    e.preventDefault();
    var key = $.trim($(this).find('input[name="ntbksense_license_key"]').val() || '');
    if (!key) { alert('Masukkan kode lisensi.'); return; }

    ajaxPost({
      action:   'ntbksense_license_activate',
      security: '<?php echo wp_create_nonce("ntbksense_license_nonce"); ?>',
      license_key: key
    }, function(){
      // refresh UI
      location.reload();
    });
  });

  // Form Nonaktifkan
  $('.license-active form').on('submit', function(e){
    e.preventDefault();
    if (!confirm('Nonaktifkan lisensi untuk domain ini?')) return;
    ajaxPost({
      action:   'ntbksense_license_deactivate',
      security: '<?php echo wp_create_nonce("ntbksense_license_nonce"); ?>'
    }, function(){
      location.reload();
    });
  });
});
</script>

