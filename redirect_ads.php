<?php

/**
 * ===================================================================
 * HELPER FUNCTIONS (FUNGSI BANTU)
 * ===================================================================
 */

/**
 * Mendeteksi apakah User Agent berasal dari browser internal Facebook/Instagram.
 * @return bool True jika dari FB/IG, false jika bukan.
 */
function is_fb_or_ig_browser()
{
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    return (strpos($user_agent, "FBAV") !== false) || (strpos($user_agent, "FBAN") !== false) || (strpos($user_agent, "Instagram") !== false);
}

/**
 * Mendeteksi apakah User Agent berasal dari perangkat mobile.
 * @return bool True jika mobile, false jika bukan (dianggap desktop).
 */
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
require_once('wp-load.php'); // Memuat WordPress

// [PERUBAHAN UTAMA] Ambil pengaturan global dari tabel database
global $wpdb;
$settings_table_name = $wpdb->prefix . 'ntbk_ads_settings';
$global_settings = $wpdb->get_row("SELECT * FROM {$settings_table_name} LIMIT 1", ARRAY_A);
if (!$global_settings) {
    $global_settings = []; // Jika tabel kosong, buat array kosong biar nggak error
}

// LANGKAH 2: Ambil parameter & data dari DB (spesifik untuk landing page ini).
$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$mode = isset($_GET['mode']) ? $_GET['mode'] : null;

if (!$slug) {
    http_response_code(400);
    echo "Error: Parameter 'slug' wajib diisi.";
    exit;
}

$table_name = $wpdb->prefix . 'acp_landings';
$query = $wpdb->prepare("SELECT * FROM $table_name WHERE slug = %s", $slug);
$templateData = $wpdb->get_row($query, ARRAY_A);

// LANGKAH 3: Validasi data awal.
if (!$templateData || $templateData['status'] == 0) {
    $fallback_url = isset($templateData['cloaking_url']) && !empty($templateData['cloaking_url']) ? $templateData['cloaking_url'] : 'https://www.youtube.com/watch?v=rMtqRp4hUYc&list=RDrMtqRp4hUYc&start_radio=1';
    wp_redirect($fallback_url);
    exit;
}

// LANGKAH 4: LOGIKA UNTUK MENENTUKAN OUTPUT
$showSafePage = false; // Defaultnya, semua traffic dianggap biasa (akan di-redirect)

// Cek 1: Apakah ada parameter 'mode=ads'? (Prioritas tertinggi)
if ($mode === 'ads') {
    $showSafePage = true;
}
// Cek 2: Jika bukan 'mode=ads', cek aturan perangkat.
elseif ($mode !== 'public') {
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
            $showSafePage = true; // Jika aturan 'semua', tampilkan safe page
            break;
    }
}

// LANGKAH 5: Tampilkan output berdasarkan hasil akhir
if ($showSafePage) {
    // Tampilkan halaman "aman" (safe page)
    $video_urls = json_decode($templateData['video_urls'], true);
    $img_urls = json_decode($templateData['universal_image_urls'], true);

    $is_video_floating = (isset($templateData['videos_floating_option']) && $templateData['videos_floating_option'] === 'on');

    // [LOGIKA IKLAN DARI PENGATURAN GLOBAL]
    $ads_opacity = (int)(isset($global_settings['opacity']) ? $global_settings['opacity'] : 100);
    $ads_margin_top = (int)(isset($global_settings['margin_top']) ? $global_settings['margin_top'] : 5);
    $ads_margin_bottom = (int)(isset($global_settings['margin_bottom']) ? $global_settings['margin_bottom'] : 5);
    $ads_code_1 = isset($global_settings['kode_iklan_1']) ? $global_settings['kode_iklan_1'] : '';
    $ads_code_2 = isset($global_settings['kode_iklan_2']) ? $global_settings['kode_iklan_2'] : '';
    // [LOGIKA BARU] Ambil kode iklan ke-3 untuk overlay video
    $ads_code_3_for_video = isset($global_settings['kode_iklan_3']) ? $global_settings['kode_iklan_3'] : '';
    $ads_show_close_btn = !empty($global_settings['tampilkan_close_ads']);
    $ads_auto_scroll = !empty($global_settings['auto_scroll']);
    $ads_hide_blank = !empty($global_settings['sembunyikan_elemen_blank']);

    // [LOGIKA TIMER]
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

    // [PERBAIKAN] CSS untuk semua fitur
    echo "<style>
        body { margin: 0; padding: 0; font-family: sans-serif; background-color: #333; }
        .content-wrapper { text-align: center; padding: 20px; }
        .main-image { width: 100%; max-width: 120%; height: auto; display: block; margin: 0 auto 20px; }
        .video-wrapper { position: relative; max-width: 100%; margin: 0 auto; }
        .video-ad-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5; cursor: pointer; }
        .floating-video-container { 
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
            z-index: 1000; width: 90%; max-width: 480px; /* Ukuran default untuk mobile */
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-radius: 10px; overflow: hidden;
        }
        .floating-video-container video, .video-wrapper video { width: 100%; height: auto; display: block; }
        #ntb-close-ads-btn { 
            position: fixed; bottom: 10px; right: 10px; 
            background-color: rgba(0,0,0,0.7); color: white; border: none; 
            border-radius: 5px; padding: 10px 15px; cursor: pointer; z-index: 9999; 
        }

        /* Aturan untuk Desktop (layar lebih besar dari 768px) */
        @media (min-width: 768px) {
            .main-image { max-width: 98%; }
            .video-wrapper { max-width: 800px; }
            .floating-video-container { max-width: 720px; }
        }
    </style>";

    echo "</head><body>";

    echo "<div class='content-wrapper'>";

    if (!empty($ads_code_1)) {
        echo "<div class='ad-container' style='opacity: " . ($ads_opacity / 100) . "; margin-bottom: " . $ads_margin_bottom . "px;'>" . $ads_code_1 . "</div>";
    }

    // echo "<h2 style='color: white;'>" . esc_html($templateData['title']) . "</h2>";

    if (!empty($ads_code_2)) {
        echo "<div class='ad-container' style='opacity: " . ($ads_opacity / 100) . "; margin-top: " . $ads_margin_top . "px; margin-bottom: " . $ads_margin_bottom . "px;'>" . $ads_code_2 . "</div>";
    }

    if (!empty($img_urls)) {
        $random_image_url = $img_urls[array_rand($img_urls)];
        if (!empty($random_image_url)) {
            echo "<img src='" . esc_url($random_image_url) . "' alt='Gambar' class='main-image'>";
        }
    }

    // [PERBAIKAN] Logika Tampilan Video dengan Ad Overlay
    if (!empty($video_urls[0])) {
        // Hapus 'autoplay' jika ada ad overlay
        $autoplay_attr = empty($ads_code_3_for_video) ? 'autoplay' : '';
        $video_tag = "<video id='safe-page-video' controls {$autoplay_attr} muted loop playsinline><source src='" . esc_url($video_urls[0]) . "' type='video/webm'>Browser tidak support video.</video>";

        // Bungkus video dalam wrapper
        $video_html = "<div class='video-wrapper'>";
        // Tambahkan ad overlay jika ada kodenya
        if (!empty($ads_code_3_for_video)) {
            $video_html .= "<div class='video-ad-overlay'>{$ads_code_3_for_video}</div>";
        }
        $video_html .= $video_tag;
        $video_html .= "</div>";

        if ($is_video_floating) {
            echo "<div class='floating-video-container'>{$video_html}</div>";
        } else {
            echo $video_html;
        }
    }

    if ($ads_show_close_btn) {
        echo "<button id='ntb-close-ads-btn'>Close ADS</button>";
    }

    echo "</div>"; // Penutup .content-wrapper

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('safe-page-video');
            
            // [LOGIKA BARU] Ad Overlay untuk Video
            const adOverlay = document.querySelector('.video-ad-overlay');
            if (adOverlay && video) {
                adOverlay.addEventListener('click', function() {
                    this.style.display = 'none'; // Sembunyikan overlay
                    video.play(); // Langsung putar video setelah overlay di-klik
                }, { once: true }); // Event ini hanya jalan sekali
            }

            if (video) {
                let pauseTimerSet = false;
                video.addEventListener('playing', function() {
                    const pauseTimeInMs = " . $random_pause_time_ms . ";
                    if (pauseTimeInMs > 0 && !pauseTimerSet) {
                        pauseTimerSet = true;
                        setTimeout(function() {
                            video.pause();
                        }, pauseTimeInMs);
                    }
                });
            }

            // ... (sisa kode JavaScript untuk refresh, close ads, dll tetap sama) ...
        });
    </script>";

    echo "</body></html>";
} else {
    // Redirect ke URL cloaking/post untuk traffic biasa
    $cloaking_url = $templateData['cloaking_url'];
    $post_urls = preg_split("/\r\n|\n|\r/", $templateData['post_urls']);

    if (!empty($post_urls) && !empty(trim($post_urls[0]))) {
        $redirectTo = $post_urls[array_rand($post_urls)];
        wp_redirect(trim($redirectTo));
    } else {
        wp_redirect($cloaking_url);
    }
}

exit;
