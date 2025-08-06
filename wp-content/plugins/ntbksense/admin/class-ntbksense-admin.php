<?php
// =========================================================================
// FILE: ntbksense/admin/class-ntbksense-admin.php
// =========================================================================

/**
 * Fungsionalitas khusus admin dari plugin.
 *
 * @package    Ntbksense
 * @subpackage Ntbksense/admin
 * @author     Nama Anda <email@example.com>
 */
class NTBKSense_Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        // wp_enqueue_style($this->plugin_name, NTBKSENSE_PLUGIN_URL . 'admin/css/ntbksense-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        // wp_enqueue_script($this->plugin_name, NTBKSENSE_PLUGIN_URL . 'admin/js/ntbksense-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Mendaftarkan semua menu dan sub-menu untuk plugin.
     */
    public function setup_admin_menus() {
        $parent_slug = 'ntbksense';

        add_menu_page(
            __('NTBKSense Beranda', 'ntbksense'),
            'NTBKSense',
            'manage_options',
            $parent_slug,
            array($this, 'render_beranda_page'),
            'dashicons-shortcode',
            65
        );

        // Loop untuk membuat sub-menu secara dinamis
        $submenus = [
            'beranda' => ['title' => 'Beranda', 'slug_suffix' => ''],
            'landing-page' => ['title' => 'Landing Page', 'slug_suffix' => '-landing-page'],
            'table-builder' => ['title' => 'Table Builder', 'slug_suffix' => '-table-builder'],
            'settings' => ['title' => 'Setting', 'slug_suffix' => '-settings'],
            'laporan' => ['title' => 'Laporan', 'slug_suffix' => '-laporan'],
            'auto-post' => ['title' => 'Auto Post', 'slug_suffix' => '-auto-post'],
            'lisensi' => ['title' => 'Lisensi', 'slug_suffix' => '-lisensi'],
            'privacy-policy' => ['title' => 'Privacy Policy Generator', 'slug_suffix' => '-privacy-policy'],
            'maintenance' => ['title' => 'Maintenance', 'slug_suffix' => '-maintenance'],
        ];

        foreach ($submenus as $key => $submenu) {
            $slug = ($key === 'beranda') ? $parent_slug : $parent_slug . $submenu['slug_suffix'];
            $callback_method = 'render_' . str_replace('-', '_', $key) . '_page';
            
            add_submenu_page(
                $parent_slug,
                __($submenu['title'], 'ntbksense'),
                __($submenu['title'], 'ntbksense'),
                'manage_options',
                $slug,
                array($this, $callback_method)
            );
        }
    }

    /**
     * Mendaftarkan pengaturan menggunakan Settings API.
     */
    public function setup_settings_api() {
        register_setting(
            'ntbksense_option_group',      // Nama grup opsi
            'ntbksense_settings',          // Nama opsi di database
            array($this, 'sanitize_settings') // Callback untuk sanitasi
        );

        add_settings_section(
            'ntbksense_general_section',       // ID seksi
            __('Pengaturan Umum & Lisensi', 'ntbksense'), // Judul seksi
            array($this, 'render_general_section_text'), // Callback untuk deskripsi seksi
            'ntbksense-settings'               // Halaman tempat seksi ini ditampilkan
        );

        add_settings_field(
            'license_key',                     // ID bidang
            __('Kunci Lisensi', 'ntbksense'),  // Label bidang
            array($this, 'render_license_key_field'), // Callback untuk merender bidang
            'ntbksense-settings',              // Halaman
            'ntbksense_general_section'        // Seksi
        );
        
        add_settings_field(
            'license_status',
            __('Status Lisensi', 'ntbksense'),
            array($this, 'render_license_status_field'),
            'ntbksense-settings',
            'ntbksense_general_section'
        );
        
        add_settings_section(
            'ntbksense_autopost_section',
            __('Pengaturan Auto Post', 'ntbksense'),
            array($this, 'render_autopost_section_text'),
            'ntbksense-settings'
        );
        
        add_settings_field(
            'autopost_enabled',
            __('Aktifkan Auto Post', 'ntbksense'),
            array($this, 'render_autopost_enabled_field'),
            'ntbksense-settings',
            'ntbksense_autopost_section'
        );
        
        add_settings_field(
            'autopost_interval',
            __('Interval Posting', 'ntbksense'),
            array($this, 'render_autopost_interval_field'),
            'ntbksense-settings',
            'ntbksense_autopost_section'
        );
    }
    
    /**
     * Callback untuk sanitasi data sebelum disimpan.
     */
    public function sanitize_settings($input) {
        $new_input = array();
        if (isset($input['license_key'])) {
            $new_input['license_key'] = sanitize_text_field($input['license_key']);
        }
        if (isset($input['autopost_enabled'])) {
            $new_input['autopost_enabled'] = absint($input['autopost_enabled']);
        }
        if (isset($input['autopost_interval'])) {
            $new_input['autopost_interval'] = sanitize_text_field($input['autopost_interval']);
        }
        
        // Atur ulang jadwal cron berdasarkan pengaturan baru
        $this->reschedule_cron_event($new_input);
        
        return $new_input;
    }
    
    // Fungsi untuk menjadwalkan ulang event cron berdasarkan pengaturan
    private function reschedule_cron_event($settings) {
        // Hapus jadwal yang ada terlebih dahulu
        wp_clear_scheduled_hook('ntbksense_auto_post_event');
        
        // Jika diaktifkan, jadwalkan ulang
        if (!empty($settings['autopost_enabled']) && $settings['autopost_enabled'] == 1) {
            wp_schedule_event(time(), $settings['autopost_interval'], 'ntbksense_auto_post_event');
        }
    }

    // Callback untuk merender teks deskripsi seksi
    public function render_general_section_text() {
        echo '<p>' . __('Masukkan kunci lisensi Anda untuk mendapatkan pembaruan dan dukungan.', 'ntbksense') . '</p>';
    }
    public function render_autopost_section_text() {
        echo '<p>' . __('Konfigurasi fitur posting otomatis.', 'ntbksense') . '</p>';
    }

    // Callback untuk merender bidang-bidang form
    public function render_license_key_field() {
        $options = get_option('ntbksense_settings');
        printf(
            '<input type="text" id="license_key" name="ntbksense_settings[license_key]" value="%s" class="regular-text" />',
            isset($options['license_key']) ? esc_attr($options['license_key']) : ''
        );
    }
    
    public function render_license_status_field() {
        $options = get_option('ntbksense_settings');
        $status = isset($options['license_status']) ? esc_html($options['license_status']) : 'Tidak Aktif';
        echo "<strong>$status</strong>";
    }
    
    public function render_autopost_enabled_field() {
        $options = get_option('ntbksense_settings');
        $checked = isset($options['autopost_enabled']) && $options['autopost_enabled'] == 1 ? 'checked' : '';
        echo '<input type="checkbox" id="autopost_enabled" name="ntbksense_settings[autopost_enabled]" value="1" ' . $checked . ' />';
        echo '<label for="autopost_enabled"> ' . __('Ya, aktifkan fitur ini', 'ntbksense') . '</label>';
    }
    
    public function render_autopost_interval_field() {
        $options = get_option('ntbksense_settings');
        $current_interval = isset($options['autopost_interval']) ? $options['autopost_interval'] : 'daily';
        $schedules = wp_get_schedules();
        
        echo '<select id="autopost_interval" name="ntbksense_settings[autopost_interval]">';
        foreach ($schedules as $key => $schedule) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($key),
                selected($current_interval, $key, false),
                esc_html($schedule['display'])
            );
        }
        echo '</select>';
    }

    // Callback untuk merender halaman-halaman admin
    public function render_beranda_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-beranda.php'; }
    public function render_landing_page_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-landing-page.php'; }
    public function render_table_builder_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-table-builder.php'; }
    public function render_settings_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-settings.php'; }
    public function render_laporan_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-laporan.php'; }
    public function render_auto_post_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-auto-post.php'; }
    public function render_lisensi_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-lisensi.php'; }
    public function render_privacy_policy_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-privacy-policy.php'; }
    public function render_maintenance_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-maintenance.php'; }
}
?>