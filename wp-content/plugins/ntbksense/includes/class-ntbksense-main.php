<?php
// =========================================================================
// FILE: ntbksense/includes/class-ntbksense-main.php
// =========================================================================

/**
 * Kelas inti plugin.
 *
 * Ini adalah kelas yang menginisialisasi semua hook, memuat dependensi,
 * dan mendefinisikan fungsionalitas internasionalisasi.
 *
 * @since      1.0.0
 * @package    Ntbksense
 * @author     Nama Anda <email@example.com>
 */
class NTBKSense_Main {

    /**
     * Objek loader yang bertanggung jawab untuk mendaftarkan semua hook dengan WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      NTBKSense_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * Pengidentifikasi unik untuk plugin ini.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * Versi saat ini dari plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Mendefinisikan fungsionalitas inti dari plugin.
     *
     * Mengatur nama dan versi plugin yang dapat digunakan di seluruh plugin.
     * Memuat dependensi, mendefinisikan locale, dan mengatur hook untuk area admin dan
     * sisi publik situs.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('NTBKSENSE_VERSION')) {
            $this->version = NTBKSENSE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'ntbksense';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Memuat dependensi yang diperlukan untuk plugin ini.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        // Kelas yang bertanggung jawab untuk mengatur tindakan dan filter.
        require_once NTBKSENSE_PLUGIN_DIR . 'includes/class-ntbksense-loader.php';

        // Kelas yang bertanggung jawab untuk mendefinisikan semua tindakan yang terjadi di area admin.
        require_once NTBKSENSE_PLUGIN_DIR . 'admin/class-ntbksense-admin.php';

        // Kelas yang bertanggung jawab untuk mendefinisikan semua tindakan yang terjadi di sisi publik.
        // require_once NTBKSENSE_PLUGIN_DIR . 'public/class-ntbksense-public.php';

        $this->loader = new NTBKSense_Loader();
    }

    /**
     * Mendefinisikan locale untuk plugin ini untuk internasionalisasi.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        add_action('plugins_loaded', function() {
            load_plugin_textdomain(
                'ntbksense',
                false,
                dirname(plugin_basename(__FILE__), 2) . '/languages/'
            );
        });
    }

    /**
     * Mendaftarkan semua hook yang terkait dengan fungsionalitas area admin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new NTBKSense_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        
        // Menambahkan menu admin
        $this->loader->add_action('admin_menu', $plugin_admin, 'setup_admin_menus');
        
        // Menambahkan pengaturan menggunakan Settings API
        $this->loader->add_action('admin_init', $plugin_admin, 'setup_settings_api');
    }

    /**
     * Mendaftarkan semua hook yang terkait dengan fungsionalitas sisi publik.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        // Untuk saat ini, tidak ada hook publik yang didefinisikan.
        // $plugin_public = new NTBKSense_Public($this->get_plugin_name(), $this->get_version());
        // $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        // $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Menjalankan loader untuk mengeksekusi semua hook dengan WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Nama plugin.
     *
     * @since     1.0.0
     * @return    string    Nama plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Versi plugin.
     *
     * @since     1.0.0
     * @return    string    Versi plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
?>