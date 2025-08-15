<?php

/**
 * ===================================================================
 * HELPER FUNCTIONS (FUNGSI BANTU)
 * ===================================================================
 */

function is_fb_or_ig_browser()
{
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    return (strpos($user_agent, "FBAV") !== false) || (strpos($user_agent, "FBAN") !== false) || (strpos($user_agent, "Instagram") !== false);
}

function is_mobile_device()
{
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|rim)|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $user_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|4t|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($user_agent, 0, 4));
}

/**
 * ===================================================================
 * SCRIPT UTAMA
 * ===================================================================
 */

// LANGKAH 1: Memuat inti WordPress.
require_once('wp-load.php');

// LANGKAH 2: Ambil pengaturan & data
global $wpdb;
$settings_table_name = $wpdb->prefix . 'ntbksense_ads_settings';
$global_settings = $wpdb->get_row("SELECT * FROM {$settings_table_name} LIMIT 1", ARRAY_A);
if (!$global_settings) {
    $global_settings = [];
}

// [PERBAIKAN] Ambil slug dan parameter dari URL
$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$path = parse_url($request_uri, PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));
$slug_candidate = isset($path_parts[0]) ? $path_parts[0] : null;

$slug_parts = preg_split('/[?&]/', $slug_candidate, 2);
$slug = $slug_parts[0];

parse_str(parse_url($request_uri, PHP_URL_QUERY), $_GET);

if (!$slug) {
    http_response_code(404);
    echo "Halaman tidak ditemukan.";
    exit;
}

$table_name = $wpdb->prefix . 'acp_landings';
$query = $wpdb->prepare("SELECT * FROM $table_name WHERE slug = %s", $slug);
$templateData = $wpdb->get_row($query, ARRAY_A);

// LANGKAH 3: Validasi data awal.
if (!$templateData || $templateData['status'] == 0) {
    $fallback_url = isset($templateData['cloaking_url']) && !empty($templateData['cloaking_url']) ? $templateData['cloaking_url'] : 'https://www.youtube.com';
    wp_redirect($fallback_url);
    exit;
}

// [LOGIKA UTAMA YANG DIPERBAIKI] Cek Kunci Masuk, Auto Refresh, dan Aturan Perangkat
$secret_parameter = $templateData['parameter_key'] ?? 'bila';
$secret_value = $templateData['parameter_value'] ?? 'nanti';
$redirect_url = $templateData['cloaking_url'];
$showSafePage = false; // Defaultnya, semua traffic akan di-redirect

// Cek 1: Kunci sakti 'mode=ads'
if (isset($_GET['mode']) && $_GET['mode'] === 'ads') {
    $showSafePage = true;
}
// Cek 2: Kunci auto-refresh (dari JavaScript)
elseif (isset($_GET['auto_refresh']) && $_GET['auto_refresh'] === '1') {
    $showSafePage = true;
}
// Cek 3: Kunci biasa '?bila=nanti' DIIKUTI oleh cek perangkat
elseif (isset($_GET[$secret_parameter]) && $_GET[$secret_parameter] === $secret_value) {
    $device_rule = isset($templateData['device_view']) ? $templateData['device_view'] : 'semua';
    switch ($device_rule) {
        case 'fb_browser':
            if (is_fb_or_ig_browser()) $showSafePage = true;
            break;
        case 'ponsel':
            if (is_mobile_device()) $showSafePage = true;
            break;
        case 'desktop':
            if (!is_mobile_device()) $showSafePage = true;
            break;
        case 'semua':
        default:
            $showSafePage = true; // Jika aturan 'semua' dan ada kunci, tampilkan
            break;
    }
}


if ($showSafePage) {

    // --- KUNJUNGAN PERTAMA (DENGAN KUNCI YANG BENAR ATAU PERANGKAT SESUAI) ---
    // Tampilkan halaman "aman" (safe page)

    $video_urls = json_decode($templateData['video_urls'], true);
    $img_urls = json_decode($templateData['universal_image_urls'], true);
    $is_video_floating = (isset($templateData['videos_floating_option']) && $templateData['videos_floating_option'] === 'on');

    $ads_code_1 = $global_settings['kode_iklan_1'] ?? '';
    $ads_code_2 = $global_settings['kode_iklan_2'] ?? '';
    $ads_opacity = (int)($global_settings['opacity'] ?? 100);
    $ads_margin_bottom = (int)($global_settings['margin_bottom'] ?? 5);
    $ads_margin_top = (int)($global_settings['margin_top'] ?? 5);
    $ads_show_close_btn = !empty($global_settings['tampilkan_close_ads']);
    $ads_auto_scroll = !empty($global_settings['auto_scroll']);
    $ads_hide_blank = !empty($global_settings['sembunyikan_elemen_blank']);
    $pause_range_str = isset($templateData['timer_auto_pause_video']) ? $templateData['timer_auto_pause_video'] : '';
    $refresh_timer_sec = (int)(isset($templateData['timer_auto_refresh']) ? $templateData['timer_auto_refresh'] : 0);

    $random_pause_time_ms = 0;
    if (preg_match('/^(\d+)-(\d+)$/', $pause_range_str, $matches)) {
        $min_sec = (int)$matches[1];
        $max_sec = (int)$matches[2];
        if ($min_sec < $max_sec) {
            $random_pause_time_ms = rand($min_sec * 1000, $max_sec * 1000);
        }
    }

    header("Content-Type: text/html; charset=UTF-8");
    echo "<!DOCTYPE html><html><head><title>" . esc_html($templateData['title']) . "</title>";

    // CSS lengkap
    echo "<style>
        body { margin: 0; padding: 0; font-family: sans-serif; background-color: #333; }
        .content-wrapper { text-align: center; padding: 20px; }
        .main-image { width: 100%; max-width: 100%; height: auto; display: block; margin: 0 auto 20px; }
        .video-wrapper { position: relative; max-width: 100%; margin: 0 auto; }
        .video-ad-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5; cursor: pointer; }
        .floating-video-container { 
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
            z-index: 1000; width: 95%; max-width: 720px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-radius: 10px; overflow: hidden;
        }
        .floating-video-container video, .video-wrapper video { width: 100%; height: auto; display: block; }
        #ntb-close-ads-btn { 
            position: fixed; bottom: 10px; right: 10px; 
            background-color: rgba(0,0,0,0.7); color: white; border: none; 
            border-radius: 5px; padding: 10px 15px; cursor: pointer; z-index: 9999; 
        }
        @media (max-width: 767px) {
            .main-image { width: 100%; max-width: 100%; height: auto; display: block; margin: 0 auto 20px; }
            .floating-video-container { max-width: 480px; }
        }
    </style>";

    echo "</head><body>";

    echo "<div class='content-wrapper'>";
    if (!empty($ads_code_1)) {
        echo "<div class='ad-container' style='opacity: " . ($ads_opacity / 100) . "; margin-bottom: " . $ads_margin_bottom . "px;'>{$ads_code_1}</div>";
    }
    // echo "<h2 style='color: white;'>" . esc_html($templateData['title']) . "</h2>";
    if (!empty($ads_code_2)) {
        echo "<div class='ad-container' style='opacity: " . ($ads_opacity / 100) . "; margin-top: " . $ads_margin_top . "px; margin-bottom: " . $ads_margin_bottom . "px;'>{$ads_code_2}</div>";
    }

    if (!empty($img_urls)) {
        $random_image_url = $img_urls[array_rand($img_urls)];
        if (!empty($random_image_url)) {
            echo "<img src='" . esc_url($random_image_url) . "' alt='Gambar' class='main-image'>";
        }
    }

    if (!empty($video_urls[0])) {
        $video_tag = "<video id='safe-page-video' controls autoplay muted loop playsinline><source src='" . esc_url($video_urls[0]) . "' type='video/webm'></video>";
        if ($is_video_floating) {
            echo "<div class='floating-video-container'>{$video_tag}</div>";
        } else {
            echo "<div class='video-wrapper'>{$video_tag}</div>";
        }
    }

    if ($ads_show_close_btn) {
        echo "<button id='ntb-close-ads-btn'>Close ADS</button>";
    }
    echo "</div>";

    echo "<script>
        window.onload = function() {
            const cleanUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
            window.history.pushState({}, '', cleanUrl);
        };

        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('safe-page-video');
            if (video) {
                let pauseTimerSet = false;
                video.addEventListener('playing', function() {
                    const pauseTimeInMs = " . $random_pause_time_ms . ";
                    if (pauseTimeInMs > 0 && !pauseTimerSet) {
                        pauseTimerSet = true;
                        setTimeout(function() { video.pause(); }, pauseTimeInMs);
                    }
                });
            }
            
            // [PERBAIKAN] Logika Auto Refresh
            const refreshTimeInMs = " . ($refresh_timer_sec * 1000) . ";
            if (refreshTimeInMs > 0) {
                setTimeout(function() {
                    // Tambahkan parameter khusus untuk menandai auto-refresh
                    window.location.href = window.location.pathname + '?auto_refresh=1';
                }, refreshTimeInMs);
            }

            const showCloseBtn = " . ($ads_show_close_btn ? 'true' : 'false') . ";
            if (showCloseBtn) {
                const closeBtn = document.getElementById('ntb-close-ads-btn');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        document.querySelectorAll('.ad-container').forEach(function(c) { c.style.display = 'none'; });
                        this.style.display = 'none';
                    });
                }
            }
            const hideBlankAds = " . ($ads_hide_blank ? 'true' : 'false') . ";
            if (hideBlankAds) {
                setTimeout(function() {
                    document.querySelectorAll('.ad-container').forEach(function(c) {
                        if (c.innerHTML.trim() === '' || c.offsetHeight < 5) { c.style.display = 'none'; }
                    });
                }, 2000);
            }
            const autoScroll = " . ($ads_auto_scroll ? 'true' : 'false') . ";
            if (autoScroll) {
                window.scrollBy({ top: window.innerHeight, left: 0, behavior: 'smooth' });
            }
        });
    </script>";

    echo "</body></html>";
} else {

    // --- KUNJUNGAN KEDUA (REFRESH MANUAL ATAU TANPA KUNCI) ---
    wp_redirect($redirect_url);
    exit;
}

exit;
