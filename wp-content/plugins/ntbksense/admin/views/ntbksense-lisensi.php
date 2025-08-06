<?php
// =========================================================================
// FILE: ntbksense/admin/views/ntbksense-lisensi.php
// =========================================================================
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p><?php _e('Pengaturan lisensi telah dipindahkan ke halaman <strong>Setting</strong> untuk kemudahan pengelolaan.', 'ntbksense'); ?></p>
    <a href="<?php echo admin_url('admin.php?page=ntbksense-settings'); ?>" class="button button-primary"><?php _e('Buka Halaman Setting', 'ntbksense'); ?></a>
</div>