<?php
// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// =========================================================================
// FILE: ntbksense/ntbksense.php
// =========================================================================
/**
 * Plugin Name:       NTBKSense
 * Plugin URI:        https://ntbksense.id/plugin
 * Description:       Plugin dasar untuk sensasi pengguna di WordPress.
 * Version:           1.0.0
 * Author:            Kevin Ariodinoto
 * Author URI:        https://ntbksense.id
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ntbksense
 * Domain Path:       /languages/
 */

// Jika file ini dipanggil secara langsung, batalkan.
if (!defined('WPINC')) {
    die;
}

/**
 * Mendefinisikan konstanta utama plugin.
 * Ini membuat kode lebih mudah dikelola dan portabel.
 */
define('NTBKSENSE_VERSION', '1.0.0');
define('NTBKSENSE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NTBKSENSE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NTBKSENSE_PLUGIN_BASENAME', plugin_basename(__FILE__));


/**
 * Fungsi ini dijalankan selama aktivasi plugin.
 * Digunakan untuk menyiapkan opsi default, membuat tabel database, dll.
 */
function activate_ntbksense()
{
    require_once NTBKSENSE_PLUGIN_DIR . 'includes/class-ntbksense-activator.php';
    NTBKSense_Activator::activate();
}

/**
 * Fungsi ini dijalankan selama deaktivasi plugin.
 * Digunakan untuk membersihkan tugas terjadwal atau data sementara.
 */
function deactivate_ntbksense()
{
    require_once NTBKSENSE_PLUGIN_DIR . 'includes/class-ntbksense-deactivator.php';
    NTBKSense_Deactivator::deactivate();
}

// Mendaftarkan hook untuk aktivasi dan deaktivasi.
register_activation_hook(__FILE__, 'activate_ntbksense');
register_deactivation_hook(__FILE__, 'deactivate_ntbksense');

/**
 * Memuat kelas inti plugin yang bertanggung jawab untuk
 * mengatur dan menjalankan semua komponen plugin.
 */
require NTBKSENSE_PLUGIN_DIR . 'includes/class-ntbksense-main.php';

/**
 * Memulai eksekusi plugin.
 * Membuat instance dari kelas utama dan memanggil metode run().
 */
function run_ntbksense()
{
    $plugin = new NTBKSense_Main();
    $plugin->run();
}

// Mulai!
run_ntbksense();

// =========================================================================
// REST API Endpoint untuk mendapatkan data pengguna yang sedang login
// =========================================================================

/**
 * Mendaftarkan endpoint custom baru ke WordPress REST API.
 * Endpoint ini akan mengembalikan data pengguna yang sedang login.
 */
add_action('rest_api_init', function () {

    /**
     * Mendaftarkan route: /wp-json/ntbksense/v1/user/current
     *
     * @param string $namespace         Namespace unik untuk API lo.
     * @param string $route             URL endpoint spesifik.
     * @param array  $args              Argumen untuk endpoint.
     */
    register_rest_route('ntbksense/v1', '/user/current', [
        'methods'  => 'GET', // Metode request yang diizinkan
        'callback' => 'get_current_user_api_callback', // Fungsi yang akan dijalankan

        // Ini bagian keamanannya. WordPress akan otomatis mengecek
        // apakah pengguna sudah login sebelum menjalankan callback.
        'permission_callback' => function () {
            return is_user_logged_in();
        }
    ]);
});

/**
 * Fungsi callback yang akan dijalankan saat endpoint di atas diakses.
 *
 * @return WP_REST_Response Objek respons yang berisi data pengguna.
 */
function get_current_user_api_callback()
{
    // Kita tidak perlu lagi mengecek is_user_logged_in() di sini,
    // karena sudah ditangani oleh 'permission_callback'.
    $user = wp_get_current_user();

    // Pilih data yang ingin ditampilkan, jangan kirim semuanya
    $response_data = [
        'id'          => $user->ID,
        'username'    => $user->user_login,
        'email'       => $user->user_email,
        'displayName' => $user->display_name,
        'roles'       => $user->roles
    ];

    // Kembalikan data sebagai respons JSON yang benar
    return new WP_REST_Response($response_data, 200);
}

if (!defined('NTBKSENSE_PLUGIN_FILE')) {
    define('NTBKSENSE_PLUGIN_FILE', __FILE__);
}

if (!function_exists('ntbksense_get_branding')) {
    function ntbksense_get_branding() {
        $settings = get_option('ntbksense_settings');

        /* Setting - Plugin Tab Section */
        $plugin_name = $settings['plugin_name'] ?? 'NTBKSense';
        $plugin_logo = (isset($settings['plugin_logo']) && filter_var($settings['plugin_logo'], FILTER_VALIDATE_URL))
            ? $settings['plugin_logo']
            : NTBKSENSE_PLUGIN_URL . 'assets/images/NTBKsense.png';
        $plugin_lang = $settings['plugin_language'] ?? '';

        /* Setting - Security Tab Section */
        $hidden_login_form = !empty($settings['hidden_login_form']);
        $url_login = $settings['url_login'] ?? 'custom-login';
        $aksi_login = $settings['aksi_login'] ?? '403';

        /* Setting - Optimization Tab Section */
        $hapus_tag_meta = !empty($settings['hapus_tag_meta']);
        $hapus_script_emoji = !empty($settings['hapus_script_emoji']);
        $hapus_versi_query = !empty($settings['hapus_versi_query']);
        $nonaktifkan_komentar = !empty($settings['nonaktifkan_komentar']);
        $judul_situs_otomatis = !empty($settings['judul_situs_otomatis']);
        $pengalihan_404 = !empty($settings['pengalihan_404']);

        /* Setting - Header & Footer Section */
        $header_code  = $settings['header_code'] ?? '';
        $footer_code  = $settings['footer_code'] ?? '';

        /* Lisensi */
        $license_key = $settings['license_key'] ?? '';
        $license_data = $settings['license_data'] ?? [];
        $license_status = $settings['license_status'] ?? 'inactive'; // active|inactive
        return [ 
            'plugin_name' => $plugin_name, 
            'plugin_logo' => $plugin_logo, 
            'locale' => $plugin_lang,
            'hidden_login_form' => $hidden_login_form,
            'url_login' => $url_login,
            'aksi_login' => $aksi_login,
            'hapus_tag_meta' => $hapus_tag_meta,
            'hapus_script_emoji' => $hapus_script_emoji,
            'hapus_versi_query' => $hapus_versi_query,
            'nonaktifkan_komentar' => $nonaktifkan_komentar,
            'judul_situs_otomatis' => $judul_situs_otomatis,
            'pengalihan_404' => $pengalihan_404,
            'header_code' => $header_code,
            'footer_code' => $footer_code,
            'license_key' => $license_key,
            'license_data' => $license_data,
            'license_status' => $license_status,
        ];
    }
}

if (!function_exists('ntbk_opt')) {
    function ntbk_opt($key, $default = null) { 
        $plugin = ntbksense_get_branding();
        return $plugin[$key] ?? $default; 
    }
}

if (!function_exists('ntbk_slug')) {
    function ntbk_slug() {
        $slug = trim((string) ntbk_opt('url_login', ''), "/ \t\n\r\0\x0B");
        return $slug !== '' ? $slug : 'custom-login';
    }
}

if (!function_exists('ntbk_on')) {
    function ntbk_on() { 
        return !empty(ntbk_opt('hidden_login_form')); 
    }
}

if (!function_exists('ntbk_is_public')) {
    function ntbk_is_public() {
        return !is_admin() && ($GLOBALS['pagenow'] ?? '') !== 'wp-login.php';
    }
}

if (!function_exists('ntbk_login_post_action_url')) {
    function ntbk_login_post_action_url($url, $path, $scheme) {
        if (!ntbk_on()) return $url;

        if ($scheme === 'login_post' && $path === 'wp-login.php') {
            return home_url('/' . ntbk_slug() . '/');
        }
        return $url;
    }
}

if (!function_exists('ntbk_strip_ver_query')) {
    function ntbk_strip_ver_query($src) {
        if (!ntbk_is_public() || empty(ntbk_opt('hapus_versi_query', 0))) return $src;
        if (strpos($src, 'admin-ajax.php') !== false) return $src;
        return explode('?', $src)[0];
    }
}

if (!function_exists('ntbksense_license_is_active')) {
    function ntbksense_license_is_active(): bool {
        return ntbk_opt('license_status', 'inactive') === 'active';
    }
}

if (!function_exists('ntb_license_page_slug')) {
    function ntb_license_page_slug(): string {
      return 'ntbksense-license';
    }
}
if (!function_exists('ntbksense_current_domain')) {
    function ntbksense_current_domain(): string {
        $host = parse_url(site_url(), PHP_URL_HOST) ?: '';
        return preg_replace('/^www\./i', '', $host);
    }
}

if (!function_exists('ntbk_license_bypass_active')) {
    function ntbk_license_bypass_active(): bool {
        if (defined('NTBKSENSE_LICENSE_BYPASS') && NTBKSENSE_LICENSE_BYPASS) return true;
        if (isset($_GET['ntbkallow']) && $_GET['ntbkallow'] == '1') return true;
        return false;
    }
}

if (!function_exists('ntbksense_enqueue_assets')) {
    function ntbksense_enqueue_assets() {
        $screen = get_current_screen();
        
        if (strpos($screen->id, 'ntbksense-lisensi') === false) {
            return;
        }
        
        wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css');
        wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', [], null, true);
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
    }
}
if (!function_exists('ntbksense_validate_license')) {
    function ntbksense_validate_license($force = false): array {
        $cached = get_transient('ntbksense_license_valid_cache');
        if (!$force && $cached && is_array($cached)) {
            return $cached;
        }

        $license_key = ntbk_opt('license_key', '');
        if ($license_key === '') {
            $result = ['valid' => false, 'message' => 'License kosong'];
            set_transient('ntbksense_license_valid_cache', $result, HOUR_IN_SECONDS);
            return $result;
        }

        $body = [
            'license_key' => $license_key,
            'domain'      => ntbksense_current_domain(),
        ];

        $resp = wp_remote_post(
            NTBKSENSE_LICENSE_API_BASE . '/license/validate-license',
            [
                'timeout'   => 15,
                'headers'   => ['Accept' => 'application/json'],
                'body'      => $body,
                'sslverify' => false,
            ]
        );

        if (is_wp_error($resp)) {
            $result = ['valid' => false, 'message' => $resp->get_error_message()];
            set_transient('ntbksense_license_valid_cache', $result, HOUR_IN_SECONDS);
            return $result;
        }

        $code = wp_remote_retrieve_response_code($resp);
        $json = json_decode(wp_remote_retrieve_body($resp), true);

        $options = get_option('ntbksense_settings', []);
        if ($code >= 200 && $code < 300 && !empty($json['valid'])) {
            $options['license_status'] = 'active';
            update_option('ntbksense_settings', $options);

            $result = ['valid' => true, 'expires_at' => $json['expires_at'] ?? null];
            set_transient('ntbksense_license_valid_cache', $result, DAY_IN_SECONDS);
            return $result;
        }

        // invalid / expired
        $options['license_status'] = 'inactive';
        update_option('ntbksense_settings', $options);
        $result = ['valid' => false, 'message' => $json['message'] ?? 'Invalid or expired'];
        set_transient('ntbksense_license_valid_cache', $result, HOUR_IN_SECONDS);
        return $result;
    }
}

if (!function_exists('ntbk_find_license_page_slug')) {
    function ntbk_find_license_page_slug(): ?string {
        $candidates = [
            'ntbksense-license', 'ntbksense_lisensi', 'ntbksense-lisensi',
            'ntbksense_license', 'ntbksense-lisence', 'ntbksense-lisensi-page'
        ];

        foreach ($candidates as $slug) {
            $url = menu_page_url($slug, false);
            if ($url) return $slug;
        }

        $possible_parent = 'ntbksense';
        $url = menu_page_url($possible_parent, false);
        if ($url && stripos($url, 'lisens') !== false) return $possible_parent;

        return null;
    }
}

/* Tab: Setting - Plugin  */
add_action('admin_menu', function () {
    $plugin = ntbksense_get_branding();

    global $menu;
    if (empty($menu)) return;

    $slug = 'ntbksense'; 
    foreach ($menu as $i => $m) {
        if (!empty($m[2]) && $m[2] === $slug) {
            $menu[$i][0] = esc_html($plugin['plugin_name']); 
        }
    }
}, 99);
/* End Tab: Setting - Plugin  */

/* Tab: Setting - Keamanan */
add_action('init', function() {
    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE', true);
    }
}, 0);

add_action('parse_request', function($wp) {
    if (!ntbk_on()) return;

    $target = wp_parse_url(home_url('/' . ntbk_slug() . '/', 'relative'), PHP_URL_PATH);
    $current = wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

    if (rtrim($target, '/') !== rtrim($current, '/')) return;

    remove_action('template_redirect', 'redirect_canonical');

    $_GET['ntbkl'] = '1';
    $GLOBALS['pagenow'] = 'wp-login.php';

    require_once ABSPATH . 'wp-login.php';
    exit;
}, 0);

add_filter('login_url', function($url, $redirect) {
    if (!ntbk_on()) return $url;
    $new_url = home_url('/' . ntbk_slug() . '/');
    return $redirect ? add_query_arg('redirect_to', urlencode($redirect), $new_url) : $new_url;
}, 10, 2);

add_filter('logout_url', function($logout_url, $redirect) {
    if (!ntbk_on()) return $logout_url;
    $url = home_url('/' . ntbk_slug() . '/');
    $url = add_query_arg(['action' => 'logout', '_wpnonce' => wp_create_nonce('log-out')], $url);
    return $redirect ? add_query_arg('redirect_to', urlencode($redirect), $url) : $url;
}, 10, 2);

add_action('login_init', function() {
    if (!ntbk_on()) return;

    if (isset($_GET['action']) && $_GET['action'] === 'logout') return;
    if (isset($_GET['ntbkl']) && $_GET['ntbkl'] == '1') return;
    if (defined('DOING_CRON') && DOING_CRON) return;

    $act = ntbk_opt('aksi_login', '403');
    if ($act === '404') { 
        status_header(404); 
        nocache_headers(); 
        wp_die('Not Found', 404); 
    } elseif ($act === 'redirect') { 
        wp_safe_redirect(home_url('/'), 302); 
        exit; 
    } else { 
        status_header(403); 
        nocache_headers(); 
        wp_die('Forbidden', 403);
    }
});

add_action('init', function() {
    if (!ntbk_on() || is_user_logged_in()) return;

    $script = basename($_SERVER['SCRIPT_NAME'] ?? '');
    if ($script === 'admin-ajax.php') return;
    if (defined('DOING_CRON') && DOING_CRON) return;

    if (is_admin()) {
        $act = ntbk_opt('aksi_login', '403');
        if ($act === '404') { 
            status_header(404); 
            nocache_headers(); 
            wp_die('Not Found', 404); 
        } elseif ($act === 'redirect') { 
            wp_safe_redirect(home_url('/'), 302); 
            exit; 
        } else { 
            status_header(403); 
            nocache_headers(); 
            wp_die('Forbidden', 403);
        }
    }
});

add_action('login_init', function() {
    if (!ntbk_on()) return;
    if (defined('NTBKSENSE_LOGIN_BYPASS') && isset($_GET['ntbkpass']) && hash_equals(NTBKSENSE_LOGIN_BYPASS, $_GET['ntbkpass'])) {
        return; 
    }
});

add_filter('site_url', 'ntbk_login_post_action_url', 10, 3);
add_filter('network_site_url', 'ntbk_login_post_action_url', 10, 3);

add_filter('login_headerurl', function() {
    return home_url('/' . ntbk_slug() . '/');
});

add_filter('lostpassword_url', function($lostpassword_url, $redirect) {
    if (!ntbk_on()) return $lostpassword_url;
    $url = home_url('/' . ntbk_slug() . '/');
    $url = add_query_arg('action', 'lostpassword', $url);
    return $redirect ? add_query_arg('redirect_to', urlencode($redirect), $url) : $url;
}, 10, 2);
/* End Tab: Setting - Keamanan */

/* Tab: Setting - Optimasi */
/** 1) Hapus Tag Meta WP Header (rsd_link, wlwmanifest, generator, shortlink) */
add_action('init', function() {
    if (!ntbk_is_public() || empty(ntbk_opt('hapus_tag_meta', 0))) return;

    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
}, 11);

/** 2) Hapus Script Emoji */
add_action('init', function() {
    if (!ntbk_is_public() || empty(ntbk_opt('hapus_script_emoji', 0))) return;

    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
});

/** 3) Hapus versi query (?ver=) pada JS & CSS */
add_filter('script_loader_src', 'ntbk_strip_ver_query', 15);
add_filter('style_loader_src', 'ntbk_strip_ver_query', 15);

/** 4) Nonaktifkan komentar (front-end & wp-comments-post) */
add_action('init', function() {
    if (empty(ntbk_opt('nonaktifkan_komentar', 0))) return;

    // Tutup comment & ping
    add_filter('comments_open', '__return_false', 99, 2);
    add_filter('pings_open', '__return_false', 99, 2);
    add_filter('comments_array', '__return_empty_array', 99);

    // Hilangkan support comments dari semua post type publik (hanya efek ke UI/theme)
    foreach (get_post_types(['public' => true], 'names') as $pt) {
        remove_post_type_support($pt, 'comments');
        remove_post_type_support($pt, 'trackbacks');
    }

    // Blokir submit langsung ke wp-comments-post.php
    add_action('init', function() {
        if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'wp-comments-post.php') {
            wp_die('Comments are disabled.', 403);
        }
    }, 1);
});

/** 5) Judul Situs Otomatis (tampilkan sesuai domain, tanpa mengubah opsi di DB) */
if (!function_exists('ntbk_hostname_to_title')) {
    function ntbk_hostname_to_title($host) {
        $host = preg_replace('~^www\.~i', '', (string)$host);
        $parts = explode('.', $host);
        if (count($parts) >= 2) array_pop($parts);
        $sld = end($parts) ?: $host;
        return ucwords(str_replace(['-', '_'], ' ', $sld));
    }
}

add_filter('option_blogname', function($value) {
    if (empty(ntbk_opt('judul_situs_otomatis', 0))) return $value;
    $host = wp_parse_url(home_url(), PHP_URL_HOST);
    return $host ? ntbk_hostname_to_title($host) : $value;
});

/** 6) Pengalihan 404 Not Found → beranda (302) */
add_action('template_redirect', function() {
    if (!ntbk_is_public() || empty(ntbk_opt('pengalihan_404', 0))) return;
    if (is_404()) {
        wp_safe_redirect(home_url('/'), 302);
        exit;
    }
}, 1);
/* End Tab: Setting - Optimasi */

/* Tab: Setting - Header & Footer */
// Header: masukkan seawal mungkin di head
add_action('wp_head', function() {
    if (!ntbk_is_public() || empty(ntbk_opt('header_code'))) return;

    echo "\n<!-- NTBKSense Header Code -->\n". ntbk_opt('header_code'). "\n<!-- /NTBKSense Header Code -->\n"; 
});

// Footer: sebelum </body>
add_action('wp_footer', function() {
    if (!ntbk_is_public() || empty(ntbk_opt('footer_code'))) return;

    echo "\n<!-- NTBKSense Footer Code -->\n". ntbk_opt('footer_code'). "\n<!-- /NTBKSense Footer Code -->\n";
});
/* End Tab: Setting - Header & Footer */

/* Tab: Lisensi */
if (!defined('NTBKSENSE_LICENSE_API_BASE')) {
    define('NTBKSENSE_LICENSE_API_BASE', 'http://localhost:8000/api'); // ganti jika beda host
}

// ====== AKTIVASI LISENSI ======
add_action('wp_ajax_ntbksense_license_activate', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized'], 403);
    }
    check_ajax_referer('ntbksense_license_nonce', 'security');

    $license_key = isset($_POST['license_key']) ? sanitize_text_field($_POST['license_key']) : '';
    if ($license_key === '') {
        wp_send_json_error(['message' => 'License key kosong.'], 400);
    }

    $body = [
        'license_key' => $license_key,
        'domain'      => ntbksense_current_domain(),
    ];

    $resp = wp_remote_post(
        NTBKSENSE_LICENSE_API_BASE . '/license/activate',
        [
            'timeout'   => 15,
            'headers'   => ['Accept' => 'application/json'],
            'body'      => $body,
            'sslverify' => false, // dev HTTP/localhost aman; matikan jika pakai HTTPS valid
        ]
    );

    if (is_wp_error($resp)) {
        wp_send_json_error(['message' => $resp->get_error_message()], 500);
    }

    $code = wp_remote_retrieve_response_code($resp);
    $json = json_decode(wp_remote_retrieve_body($resp), true);

    if ($code >= 200 && $code < 300 && !empty($json['success'])) {
        // simpan option
        $options = get_option('ntbksense_settings', []);
        $options['license_key'] = $license_key;
        $options['license_status'] = 'active'; 

        $data = [
            'email' => $json['data']['email'] ?? '',
            'registration_date' => $json['registration_date'] ?? '',
            'expiry_date' => $json['data']['expires_at'] ?? null,
            'raw' => $json,
        ];
        $options['license_data'] = $data;
        update_option('ntbksense_settings', $options);

        set_transient('ntbksense_license_valid_cache', ['valid' => true, 'checked_at' => time()], DAY_IN_SECONDS);

        wp_send_json_success(['message' => 'Lisensi berhasil diaktivasi.', 'data' => $data]);
    }

    $msg = $json['message'] ?? ('License server error (HTTP '.$code.')');
    wp_send_json_error(['message' => $msg], $code ?: 400);
});

// ====== NONAKTIFKAN LISENSI ======
add_action('wp_ajax_ntbksense_license_deactivate', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized'], 403);
    }
    check_ajax_referer('ntbksense_license_nonce', 'security');

    $license_key = ntbk_opt('license_key', '');
    if ($license_key === '') {
        wp_send_json_error(['message' => 'Belum ada license key tersimpan.'], 400);
    }

    $body = [
        'license_key' => $license_key,
        'domain'      => ntbksense_current_domain(),
    ];

    $resp = wp_remote_post(
        NTBKSENSE_LICENSE_API_BASE . '/license/deactivate',
        [
            'timeout'   => 15,
            'headers'   => ['Accept' => 'application/json'],
            'body'      => $body,
            'sslverify' => false,
        ]
    );
 
    if (is_wp_error($resp)) {
        wp_send_json_error(['message' => $resp->get_error_message()], 500);
    } 

    $code = wp_remote_retrieve_response_code($resp);
    $json = json_decode(wp_remote_retrieve_body($resp), true);
    if ($code >= 200 && $code < 300 && !empty($json['success'])) { 
        $options = get_option('ntbksense_settings', []);
        $options['license_key'] = '';
        $options['license_status'] = 'inactive';
        $options['license_data'] = [];
        update_option('ntbksense_settings', $options);

        delete_transient('ntbksense_license_valid_cache');
        wp_send_json_success(['message' => 'Lisensi berhasil di-deaktivasi.']);
    }
    $msg = $json['message'] ?? ('License server error (HTTP '.$code.')');
    wp_send_json_error(['message' => $msg], $code ?: 400);
});

/* add_action('current_screen', function ($screen) { */
/*     // contoh: kalau nonaktif, tampilkan notice di halaman plugin kamu */
/*     if (!ntbksense_license_is_active() && strpos($screen->id ?? '', 'ntbksense') !== false) { */
/*         add_action('admin_notices', function () { */
/*             echo '<div class="notice notice-warning"><p><b>NTBKSense:</b> Lisensi belum aktif. Beberapa fitur mungkin terbatas.</p></div>'; */
/*         }); */
/*     } */
/* }); */

add_action('admin_footer', 'ntbksense_enqueue_assets');

add_action('admin_init', function () {
    ntbksense_validate_license(false);
});

add_action('ntbksense_license_validate_event', function () {
    ntbksense_validate_license(true);
});

add_filter('cron_schedules', function ($schedules) {
    $schedules['every_minute'] = [
        'interval' => 60,
        'display'  => 'Every Minute',
    ];
    return $schedules;
});

add_action('admin_init', function () {
    if (!wp_next_scheduled('ntbksense_license_validate_event')) {
        wp_schedule_event(time() + 60, 'every_minute', 'ntbksense_license_validate_event');
    } else {
        $ts = wp_next_scheduled('ntbksense_license_validate_event');
        $cr = _get_cron_array();
        $sched = 'daily';
        foreach ($cr as $t => $hooks) {
            if (isset($hooks['ntbksense_license_validate_event'])) {
                foreach ($hooks['ntbksense_license_validate_event'] as $sig => $args) {
                    $sched = $args['schedule'] ?? $sched;
                }
            }
        }
        if ($sched !== 'every_minute') {
            wp_unschedule_event($ts, 'ntbksense_license_validate_event');
            wp_schedule_event(time() + 60, 'every_minute', 'ntbksense_license_validate_event');
        }
    }
});

/* add_action('current_screen', function ($screen) { */
/*     if (ntbksense_license_is_active() || ntbk_license_bypass_active()) return; */
/*     if (!$screen || empty($screen->id)) return; */
/**/
/*     $is_ntb = (strpos($screen->id, 'ntbksense') !== false); */
/*     if (!$is_ntb) return; */
/**/
/*     $license_slug = ntbk_find_license_page_slug(); */
/*     if (!$license_slug) { */
/*         return; */
/*     } */
/**/
/*     $current_page = isset($_GET['page']) ? sanitize_key($_GET['page']) : ''; */
/*     if ($current_page === $license_slug) return;  */
/**/
/*     add_action('admin_notices', function () { */
/*         echo '<div class="notice notice-warning"><p><b>NTBKSense:</b> Lisensi belum aktif / kedaluwarsa. Anda dialihkan ke halaman Lisensi.</p></div>'; */
/*     }); */
/**/
/*     $url = menu_page_url($license_slug, false); */
/*     if (!$url) {  */
/*         $url = admin_url('admin.php?page=' . $license_slug); */
/*     } */
/*     wp_safe_redirect($url); */
/*     exit; */
/* }, 20); */

add_action('admin_menu', function () {
    if (ntbksense_license_is_active()) return;

    $parent_slug = 'ntbksense'; 
    $license_slug= ntb_license_page_slug();

    $subpages = [
        'ntbksense-landing-page',
        'ntbksense-template-builder', 
        'ntbksense-settings',
        'ntbksense-laporan',
        'ntbksense-auto-post',
        'ntbksense-privacy-policy',
        'ntbksense-maintenance',
    ];

    foreach ($subpages as $slug) {
        if ($slug !== $license_slug) {
          remove_submenu_page($parent_slug, $slug);
        }
    }
}, 99);

/**
* (Opsional keras) Batasi AJAX NTBKSense jika lisensi nonaktif
* Boleh dihapus kalau belum perlu.
*/
/* add_action('admin_init', function () { */
/*     if (ntbksense_license_is_active()) return; */
/**/
/*     $is_ntb_ajax = isset($_POST['action']) && is_string($_POST['action']) && strpos($_POST['action'], 'ntbksense_') === 0; */
/*     if ($is_ntb_ajax) { */
/*         wp_send_json_error(['message' => 'Lisensi tidak aktif.'], 403); */
/*     } */
/* }); */

/* End Tab: Lisensi */

