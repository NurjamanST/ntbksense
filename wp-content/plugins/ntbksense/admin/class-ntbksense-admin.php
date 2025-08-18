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
        
        // Mendaftarkan AJAX action hook untuk mendapatkan post
        add_action('wp_ajax_ntbksense_get_published_posts', array($this, 'ajax_get_published_posts'));
        
        // Mendaftarkan AJAX action hook untuk pratinjau landing page
        add_action('wp_ajax_ntbksense-landing-preview', array($this, 'ajax_landing_preview'));
        add_action('wp_ajax_nopriv_ntbksense-landing-preview', array($this, 'ajax_landing_preview'));

        // Mendaftarkan AJAX action hook untuk menyimpan pengaturan
        add_action('wp_ajax_ntbksense_update_settings', array($this, 'ajax_update_settings'));
    }

    /**
     * Handler untuk AJAX request untuk menyimpan pengaturan.
     */
    public function ajax_update_settings() {
        check_ajax_referer('ntbksense_settings_nonce', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Anda tidak memiliki izin untuk melakukan tindakan ini.']);
            return;
        }

        $options = get_option('ntbksense_settings', []);
        
        // Loop melalui data POST dan sanitasi
        foreach ($_POST as $key => $value) {
            if ($key === 'action' || $key === 'security') continue;
            
            if (is_string($value)) {
                 $options[$key] = sanitize_text_field(stripslashes($value));
            } else {
                 $options[$key] = $value;
            }
        }
        
        update_option('ntbksense_settings', $options);

        wp_send_json_success(['message' => 'Pengaturan berhasil disimpan!']);

        wp_die();
    }

    /**
     * Handler untuk AJAX request untuk pratinjau iklan.
     */
    public function ajax_landing_preview() {
        check_ajax_referer('ntbksense_settings_nonce', 'security');

        // Mengambil parameter dari URL dengan pengecekan standar
        if (isset($_GET['opacity'])) {
            $opacity = intval($_GET['opacity']) / 100;
        } else {
            $opacity = 0.20; // Default opacity 20%
        }

        if (isset($_GET['margin_top_ads'])) {
            $margin_top = intval($_GET['margin_top_ads']);
        } else {
            $margin_top = 5;
        }

        if (isset($_GET['position'])) {
            $position = sanitize_text_field($_GET['position']);
        } else {
            $position = 'fixed';
        }

        if (isset($_GET['number_display_code'])) {
            $number_of_ads = intval($_GET['number_display_code']);
        } else {
            $number_of_ads = 2;
        }
        
        // Membuat daftar iklan yang tersedia (berdasarkan input yang tidak kosong)
        $available_ads = [];
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($_GET['ads'.$i])) {
                $available_ads[] = $i;
            }
        }

        // Membatasi jumlah iklan yang akan ditampilkan sesuai pilihan dropdown
        $ads_to_show = array_slice($available_ads, 0, $number_of_ads);

        // Mendapatkan URL gambar dari direktori plugin
        $background_image_url = NTBKSENSE_PLUGIN_URL . 'assets/images/long_screenshot.webp';
        $ad_image_url = NTBKSENSE_PLUGIN_URL . 'assets/images/sample-ad.png';
        
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ad Preview</title>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    background-color: #fff;
                }
                .landing-page-background {
                    background-image: url('<?php echo esc_url($background_image_url); ?>');
                    background-repeat: no-repeat;
                    background-size: 100% auto;
                    width: 100%;
                    position: relative;
                    /* Menghitung tinggi berdasarkan rasio gambar asli jika memungkinkan, atau set nilai yang besar */
                    height: 8000px; 
                }
                .ad-placeholder {
                    /* Mengganti div dengan gambar */
                    display: block;
                    width: 300px; /* Lebar iklan standar */
                    height: 250px; /* Tinggi iklan standar */
                    position: <?php echo esc_attr($position); ?>;
                    left: 50%;
                    transform: translateX(-50%);
                    box-sizing: border-box;
                    opacity: <?php echo esc_attr($opacity); ?>;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    border-radius: 4px;
                }
            </style>
        </head>
        <body>
            <div class="landing-page-background">
                <?php 
                $spacing = 270; // Jarak vertikal antar iklan
                foreach ($ads_to_show as $index => $ad_num) {
                    $top_position = $margin_top + ($index * $spacing);
                    echo '<img src="' . esc_url($ad_image_url) . '" class="ad-placeholder" style="top: ' . esc_attr($top_position) . 'px;" alt="Contoh Iklan ' . esc_attr($ad_num) . '">';
                }
                ?>
            </div>
        </body>
        </html>
        <?php
        wp_die();
    }


    public function enqueue_styles() {
        // wp_enqueue_style($this->plugin_name, NTBKSENSE_PLUGIN_URL . 'admin/css/ntbksense-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        // wp_enqueue_script($this->plugin_name, NTBKSENSE_PLUGIN_URL . 'admin/js/ntbksense-admin.js', array('jquery'), $this->version, false);
    }

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

        $submenus = [
            'beranda'          => ['title' => 'Beranda', 'slug_suffix' => ''],
            'landing-page'     => ['title' => 'Landing Page', 'slug_suffix' => '-landing-page'],
            'template-builder' => ['title' => 'Template Builder', 'slug_suffix' => '-template-builder'],
            'settings'         => ['title' => 'Setting', 'slug_suffix' => '-settings'],
            'laporan'          => ['title' => 'Laporan', 'slug_suffix' => '-laporan'],
            'auto-post'        => ['title' => 'Auto Post', 'slug_suffix' => '-auto-post'],
            'lisensi'          => ['title' => 'Lisensi', 'slug_suffix' => '-lisensi'],
            'privacy-policy'   => ['title' => 'Privacy Policy Generator', 'slug_suffix' => '-privacy-policy'],
            'maintenance'      => ['title' => 'Maintenance', 'slug_suffix' => '-maintenance'],
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

        add_submenu_page(
            null, 
            __('Buat LP Baru', 'ntbksense'),
            __('Buat LP Baru', 'ntbksense'),
            'manage_options',
            'ntbksense-create-landing', 
            array($this, 'render_create_landing_page')
        );

        add_submenu_page(
            null, 
            __('Edit Landing Page', 'ntbksense'),
            __('Edit Landing Page', 'ntbksense'),
            'manage_options',
            'ntbksense-edit-lp',
            array($this, 'render_edit_landing_page')
        );
    }

    public function ajax_get_published_posts() {
        check_ajax_referer('ntb_get_articles_nonce', 'security');

        $count = isset($_POST['count']) ? intval($_POST['count']) : -1;

        $args = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => $count,
            'fields'         => 'ids', 
        );

        $post_ids = get_posts($args);
        $urls = [];

        if (!empty($post_ids)) {
            foreach ($post_ids as $post_id) {
                $urls[] = get_permalink($post_id);
            }
        }

        if (!empty($urls)) {
            wp_send_json_success($urls);
        } else {
            wp_send_json_error(['message' => 'Tidak ada artikel yang diterbitkan ditemukan.']);
        }

        wp_die();
    }

    public function setup_settings_api() {
        register_setting(
            'ntbksense_option_group',
            'ntbksense_settings',
            array($this, 'sanitize_settings')
        );
    }
    
    public function sanitize_settings($input) {
        return $input;
    }
    
    // Callback render halaman-halaman admin
    public function render_beranda_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/Beranda/ntbksense-beranda.php'; }
    public function render_landing_page_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/LandingPage/ntbksense-landing-page.php'; }
    public function render_create_landing_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/LandingPage/ntbksense-create-landing.php'; }
    public function render_edit_landing_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/LandingPage/ntbksense-edit-landing.php'; }
    public function render_template_builder_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/Template/ntbksense-template-builder.php'; }
    public function render_settings_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/Settings/ntbksense-settings.php'; }
    public function render_laporan_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-laporan.php'; }
    public function render_auto_post_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/AutoPost/ntbksense-auto-post.php'; }

    public function render_lisensi_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/Lisensi/ntbksense-lisensi.php'; }
    public function render_privacy_policy_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/PrivacyPolicy/ntbksense-privacy-policy.php'; }
    public function render_maintenance_page() { require_once NTBKSENSE_PLUGIN_DIR . 'admin/views/ntbksense-maintenance.php'; }
}
