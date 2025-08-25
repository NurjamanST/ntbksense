<?php
// Pastikan gak diakses langsung
if (!defined('ABSPATH')) exit;

/**
 * Handler rewrite – disertakan di file utama plugin,
 * tapi tetep aman kalau dipanggil sendiri.
 */

// Query var
add_filter('query_vars', function ($vars) {
    $vars[] = 'ntbksense_lp_slug';
    return $vars;
}, 5);

// Rewrite rule
add_action('init', function () {
    add_rewrite_tag('%ntbksense_lp_slug%', '([^&]+)');
    add_rewrite_rule('^go/([^/]+)/?$', 'index.php?ntbksense_lp_slug=$matches[1]', 'top');
}, 5);

// Router → include public/redirect_ads.php
add_action('parse_request', function ($wp) {
    if (empty($wp->query_vars['ntbksense_lp_slug'])) return;

    $slug = sanitize_title($wp->query_vars['ntbksense_lp_slug']);
    $GLOBALS['ntbksense_slug'] = $slug;

    $redirect_script = plugin_dir_path(__DIR__) . 'public/redirect_ads.php';
    if (file_exists($redirect_script)) {
        require $redirect_script;
        exit;
    }
    status_header(500);
    echo 'redirect_ads.php tidak ditemukan.';
    exit;
}, 0);
