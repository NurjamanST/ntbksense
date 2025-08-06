<?php
// =========================================================================
// FILE: ntbksense/admin/views/ntbksense-auto-post.php
// =========================================================================
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p><?php _e('Halaman ini akan menampilkan log dan status dari fitur Auto Post. Pengaturan utama ada di halaman Setting.', 'ntbksense'); ?></p>
    
    <h3><?php _e('Status Jadwal', 'ntbksense'); ?></h3>
    <?php
    $timestamp = wp_next_scheduled('ntbksense_auto_post_event');
    if ($timestamp) {
        $gmt_offset = get_option('gmt_offset') * HOUR_IN_SECONDS;
        $next_run_date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $timestamp + $gmt_offset);
        echo '<p>' . sprintf(__('Jadwal berikutnya akan berjalan pada: <strong>%s</strong>', 'ntbksense'), $next_run_date) . '</p>';
    } else {
        echo '<p>' . __('Tidak ada jadwal Auto Post yang aktif saat ini.', 'ntbksense') . '</p>';
    }
    ?>
</div>