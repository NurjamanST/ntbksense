<?php
// =========================================================================
// FILE: ntbksense/admin/views/ntbksense-settings.php
// =========================================================================
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form action="options.php" method="post">
        <?php
        // Menambahkan bidang keamanan (nonce, action, dll.)
        settings_fields('ntbksense_option_group');
        
        // Menampilkan semua seksi dan bidang yang terdaftar untuk halaman ini
        do_settings_sections('ntbksense-settings');
        
        // Menampilkan tombol simpan
        submit_button(__('Simpan Pengaturan', 'ntbksense'));
       ?>
    </form>
</div>