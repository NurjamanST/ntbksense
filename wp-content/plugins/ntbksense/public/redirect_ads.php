<?php

/**
 * NTBKSense – redirect_ads.php (full, memakai semua kolom wp_ntbk_ads_settings)
 */

if (!defined('ABSPATH')) {
    $wp_load = dirname(__FILE__, 5) . '/wp-load.php';
    if (file_exists($wp_load)) require_once $wp_load;
    if (!defined('ABSPATH')) exit;
}

/* ===================== Helpers ===================== */
function ntbk_is_fb_or_ig(): bool
{
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    return (strpos($ua, 'FBAV') !== false) || (strpos($ua, 'FBAN') !== false) || (strpos($ua, 'Instagram') !== false);
}
function ntbk_is_mobile(): bool
{
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    return (bool)preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|rim)|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $ua)
        || (bool)preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|4t|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($ua, 0, 4));
}
function ntbk_maybe_b64($s)
{
    $s = (string)$s;
    $d = base64_decode($s, true);
    return ($d !== false && $d !== '') ? $d : $s;
}
function ntbk_first_video($raw): string
{
    if ($raw === null) return '';
    $raw = trim((string)$raw);
    if ($raw === '') return '';
    $j = json_decode($raw, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        if (is_array($j)) {
            foreach ($j as $it) {
                if (is_string($it) && $it !== '') return preg_replace('#\\\/#', '/', $it);
                if (is_array($it) && !empty($it['url'])) return preg_replace('#\\\/#', '/', $it['url']);
            }
        } elseif (is_string($j) && $j !== '') {
            return preg_replace('#\\\/#', '/', $j);
        }
    }
    if (preg_match('#https?://[^\s"\'<>]+#i', $raw, $m)) return $m[0];
    return $raw;
}
function ntbk_get_ip(): string
{
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = trim(explode(',', $_SERVER[$k])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
        }
    }
    return '0.0.0.0';
}
function ntbk_list($txt): array
{
    if ($txt === null) return [];
    if (is_array($txt)) return $txt;
    $txt = trim((string)$txt);
    if ($txt === '') return [];
    $j = json_decode($txt, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($j)) return array_values(array_filter(array_map('trim', $j)));
    $txt = str_replace([';', "\n", "\r"], ',', $txt);
    $arr = array_values(array_filter(array_map('trim', explode(',', $txt))));
    return $arr;
}
function ntbk_country(): string
{
    foreach (['HTTP_CF_IPCOUNTRY', 'GEOIP_COUNTRY_CODE', 'HTTP_X_COUNTRY_CODE'] as $k) {
        if (!empty($_SERVER[$k])) return strtoupper(substr($_SERVER[$k], 0, 2));
    }
    return '';
}
function ntbk_asn(): string
{
    foreach (['HTTP_CF_ASN', 'HTTP_X_ASN', 'HTTP_X_IP_ASN'] as $k) {
        if (!empty($_SERVER[$k])) return strtoupper(trim($_SERVER[$k]));
    }
    return '';
}

/* ===================== Resolve slug ===================== */
global $ntbksense_slug;
$slug = '';
if (!empty($ntbksense_slug)) $slug = $ntbksense_slug;
elseif (!empty($_GET['ntbksense_lp_slug'])) $slug = sanitize_title($_GET['ntbksense_lp_slug']);
elseif ($q = get_query_var('ntbksense_lp_slug')) $slug = sanitize_title($q);
if ($slug === '') {
    status_header(404);
    echo 'Slug kosong.';
    exit;
}

/* ===================== Load DB ===================== */
global $wpdb;
$settings = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ntbk_ads_settings LIMIT 1", ARRAY_A) ?: [];
$row      = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}acp_landings WHERE slug=%s LIMIT 1", $slug), ARRAY_A);

if (!$row || (int)($row['status'] ?? 0) === 0) {
    $fb = $row['cloaking_url'] ?? ($row['post_url'] ?? home_url('/'));
    wp_safe_redirect(esc_url_raw($fb));
    exit;
}

/* ===================== Access filters ===================== */
$client_ip = ntbk_get_ip();

/* blokir IP */
$block_ips = ntbk_list($settings['blokir_ip_address'] ?? '');
if ($block_ips && in_array($client_ip, $block_ips, true)) {
    wp_safe_redirect(esc_url_raw($row['cloaking_url'] ?? home_url('/')));
    exit;
}

/* lokasi negara */
$loc_mode   = strtolower(trim($settings['akses_lokasi_mode'] ?? '')); // allow|deny|''(ignore)
$loc_list   = array_map('strtoupper', ntbk_list($settings['akses_lokasi_countries'] ?? ''));
$cc = ntbk_country();
if ($loc_mode === 'deny' && $cc && in_array($cc, $loc_list, true)) {
    wp_safe_redirect(esc_url_raw($row['cloaking_url'] ?? home_url('/')));
    exit;
}
if ($loc_mode === 'allow' && $loc_list && (! $cc || !in_array($cc, $loc_list, true))) {
    wp_safe_redirect(esc_url_raw($row['cloaking_url'] ?? home_url('/')));
    exit;
}

/* ASN allow/deny (butuh header dari reverse proxy) */
$asn_mode = strtolower(trim($settings['akses_asn_mode'] ?? '')); // allow|deny|''(ignore)
$asn_list = array_map('strtoupper', ntbk_list($settings['akses_asn_list'] ?? ''));
$asn = ntbk_asn();
if ($asn && $asn_mode === 'deny'  && in_array($asn, $asn_list, true)) {
    wp_safe_redirect(esc_url_raw($row['cloaking_url'] ?? home_url('/')));
    exit;
}
if ($asn && $asn_mode === 'allow' && $asn_list && !in_array($asn, $asn_list, true)) {
    wp_safe_redirect(esc_url_raw($row['cloaking_url'] ?? home_url('/')));
    exit;
}

/* device rules dari landing */
$show = false;
$cloaking_url = $row['cloaking_url'] ?? home_url('/'); 
if (isset($_GET['mode']) && $_GET['mode'] === 'test') $show = true;
else { 
    $rule = $row['device_view'] ?? 'semua';
    $show = ($rule === 'semua')
        || ($rule === 'desktop' && !ntbk_is_mobile())
        || ($rule === 'ponsel'  &&  ntbk_is_mobile())
        || ($rule === 'fb_browser' && ntbk_is_fb_or_ig());
}
if (!$show) {
    $host = parse_url($cloaking_url, PHP_URL_HOST);
    if ($host) {
        add_filter('allowed_redirect_hosts', function($hosts) use ($host) {
            $hosts[] = $host;
            return $hosts;
        });
    }
    wp_safe_redirect(esc_url_raw($cloaking_url));
    exit;
}

/* ===================== Page data ===================== */
$title     = $row['title'] ?? 'Safe Page';
$img_arr   = json_decode($row['universal_image_urls'] ?? '[]', true);
$img_arr = is_array($img_arr) ? $img_arr : [];
$img_pick  = !empty($img_arr) ? $img_arr[array_rand($img_arr)] : '';

$video_src = ntbk_first_video($row['video_urls'] ?? '');
$is_float  = !empty($row['videos_floating_option']) && $row['videos_floating_option'] === 'on';
$poster    = $img_pick ?: '';

/* Ads settings (table settings) */
$publisher_id  = trim($settings['id_publisher'] ?? '');
$opacity       = max(0, min(100, (int)($settings['opacity'] ?? 40)));
$jumlah_iklan  = max(0, (int)($settings['jumlah_iklan'] ?? 0));          // 0..5
$mode_tampilan = strtolower(trim($settings['mode_tampilan'] ?? 'strip')); // 'strip' (default)
$posisi        = strtolower(trim($settings['posisi'] ?? 'atas-bawah'));   // 'atas','bawah','atas-bawah'
$mt            = (int)($settings['margin_top'] ?? 5);
$mb            = (int)($settings['margin_bottom'] ?? 5);

$ads_code = [];
for ($i = 1; $i <= 5; $i++) {
    $k = ntbk_maybe_b64($settings["kode_iklan_$i"] ?? '');
    if ($k !== '') $ads_code[] = $k;
}
$show_close_btn       = !empty($settings['tampilkan_close_ads']);
$hide_blank_elements  = !empty($settings['sembunyikan_elemen_blank']);
$refresh_blank        = max(0, (int)($settings['refresh_blank'] ?? 0)); // detik jika ingin reload saat blank
$auto_scroll          = !empty($settings['auto_scroll']);              // simple smooth scroll

/* timers dari landing */
$refresh_raw = trim($row['timer_auto_refresh'] ?? '');
$auto_seconds = 0;
if ($refresh_raw !== '') {
    if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $refresh_raw, $m)) $auto_seconds = rand((int)$m[1], (int)$m[2]);
    elseif (ctype_digit($refresh_raw)) $auto_seconds = (int)$refresh_raw;
}
$pause_raw = trim($row['timer_auto_pause_video'] ?? '');
$pause_ms  = 0;
if ($pause_raw !== '') {
    if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $pause_raw, $m)) $pause_ms = rand((int)$m[1], (int)$m[2]) * 1000;
    elseif (ctype_digit($pause_raw)) $pause_ms = (int)$pause_raw * 1000;
}

/* masking target */
$post_url_raw = trim($row['post_url'] ?? ($row['post_urls'] ?? ''));
$post_url = '';
if ($post_url_raw !== '') {
    if ($post_url_raw[0] === '[') {
        $arr = json_decode($post_url_raw, true);
        if (is_array($arr) && !empty($arr)) $post_url = $arr[array_rand($arr)];
    } else $post_url = $post_url_raw;
}
$safe_url = home_url('/go/' . rawurlencode($slug));

/* AdSense gating utk dev env (biar gak 403 waktu test .test/localhost) */
$host        = $_SERVER['HTTP_HOST'] ?? '';
$is_https    = is_ssl();
$is_dev_host = (bool)preg_match('/(\.test$|localhost$|\.local$|^192\.168\.|^10\.)/i', $host);
$allowed_hosts_json = $settings['ads_allowed_hosts'] ?? '[]';
$allowed_hosts = json_decode($allowed_hosts_json, true);
if (!is_array($allowed_hosts) || empty($allowed_hosts)) $allowed_hosts = [$host];
$can_load_adsense = $publisher_id && $is_https && in_array($host, $allowed_hosts, true) && !$is_dev_host && empty($_GET['mode']);
// DEMO PLACEHOLDER saat dev/test (atau pakai ?demo=1)
$want_demo = isset($_GET['demo']) || (!$can_load_adsense && empty($ads_code));
if ($want_demo) {
    // angka mengikuti jumlah_iklan & posisi (atas/bawah)
    $demoUnit = '<div class="demo-ad"><div><small>Contoh iklan Otomatis</small><strong>Iklan dalam halaman</strong></div></div>';
    $need = max(1, (int)($jumlah_iklan ?: 2)); // default 2
    for ($i = 0; $i < $need; $i++) $ads_code[] = $demoUnit;
}

/* fallback auto unit jika kode kosong dan boleh load */
if ($can_load_adsense && empty($ads_code)) {
    $ads_code[] = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-' . esc_attr($publisher_id) . '"
        data-ad-slot="1234567890" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <script>(adsbygoogle=window.adsbygoogle||[]).push({});</script>';
}

/* ===================== Output ===================== */
header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <title><?php echo esc_html($title); ?></title>
    <?php if ($can_load_adsense): ?>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-<?php echo esc_attr($publisher_id); ?>" crossorigin="anonymous"></script>
    <?php endif; ?>
    <style>
        :root {
            color-scheme: dark;
            --pad: clamp(12px, 2vw, 20px);
            --radius: 12px;
            --adDim: <?php echo number_format($opacity / 100, 2, '.', ''); ?>;
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #121212;
            color: #eee;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif
        }

        body {
            padding-bottom: max(16px, env(safe-area-inset-bottom));
            -webkit-font-smoothing: antialiased
        }

        .container {
            max-width: min(1100px, 96vw);
            margin: 0 auto;
            padding: var(--pad)
        }

        .hero-img {
            width: 100%;
            /* height: auto; */
            border-radius: var(--radius);
            display: block;
            object-fit: cover
        }

        .video-shell {
            width: 100%;
            aspect-ratio: 16/9;
            background: #000;
            border-radius: var(--radius);
            overflow: hidden;
        }

        .video-shell>video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block
        }

        .float {
            position: fixed;
            inset: auto 50% 5dvh 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: min(900px, 96vw);
            background: #000;
            border-radius: var(--radius);
            box-shadow: 0 10px 32px rgba(0, 0, 0, .55);
            overflow: hidden
        }

        @media (max-width:480px) {
            .float {
                inset: auto 2.5vw 2.5vh 2.5vw;
                transform: none;
                width: auto
            }
        }

        .hint {
            opacity: .75;
            font-size: clamp(12px, 2.3vw, 14px)
        }

        /* STRIP ADS */
        .ad-strip {
            position: fixed;
            left: 0;
            right: 0;
            z-index: 1200;
            background: rgba(255, 255, 255, var(--adDim));
            pointer-events: none;
            display: flex;
            justify-content: center;
            padding: <?php echo max(0, $mt); ?>px 0 <?php echo max(0, $mb); ?>px 0;
        }

        .ad-strip.top {
            top: 0
        }

        .ad-strip.bottom {
            bottom: 0
        }

        .ad-inner {
            pointer-events: auto;
            width: min(1200px, 96vw)
        }

        @media (min-width:1440px) {
            .ad-inner {
                width: min(1280px, 96vw)
            }
        }

        /* inline ads stack */
        .inline-ads {
            display: grid;
            gap: 12px;
            margin: 16px 0
        }

        /* Close ADS */
        #ntb-close {
            position: fixed;
            right: 12px;
            bottom: calc(12px + env(safe-area-inset-bottom));
            z-index: 1300;
            background: rgba(0, 0, 0, .7);
            color: #fff;
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent
        }

        /* Auto-pause overlay */
        #ntb-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .55);
            z-index: 1100;
            padding: var(--pad)
        }

        #ntb-overlay .box {
            background: #1c1c1c;
            border-radius: 16px;
            padding: 18px 20px;
            color: #fff;
            text-align: center;
            max-width: 92vw
        }

        #ntb-overlay button {
            margin-top: 10px;
            background: #0a84ff;
            border: 0;
            color: #fff;
            padding: 10px 14px;
            border-radius: 10px;
            cursor: pointer
        }

        .demo-ad {
            background: #fff;
            color: #111;
            border-radius: 12px;
            padding: 16px;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font: 14px/1.4 system-ui;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .08);
        }

        .demo-ad small {
            display: block;
            opacity: .55;
        }

        .demo-ad strong {
            display: block;
            font-weight: 700;
        }
    </style>
    <!-- <script>
        (function() {
            if (!("crypto" in window)) window.crypto = {};
            if (typeof window.crypto.randomUUID !== "function") {
                window.crypto.randomUUID = function() {
                    return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function(c) {
                        var r = Math.random() * 16 | 0,
                            v = c === "x" ? r : (r & 0x3 | 0x8);
                        return v.toString(16);
                    });
                };
            }
        })();
    </script> -->
</head>

<body>

    <!-- <div class="container"> -->
    <?php if ($img_pick): ?>
        <img class="hero-img" src="<?php echo esc_url($img_pick); ?>" alt="">
    <?php endif; ?>

    <?php if ($video_src): ?>
        <?php
        $videoTag = "<video id='safe-video' " . ($poster ? "poster='" . esc_url($poster) . "' " : "")
            . "src='" . esc_url($video_src) . "' controls autoplay muted playsinline webkit-playsinline preload='metadata'></video>";
        ?>
        <?php if ($is_float): ?>
            <div class="float"><?php echo $videoTag; ?></div>
        <?php else: ?>
            <div class="video-shell"><?php echo $videoTag; ?></div>
        <?php endif; ?>
        <div class="hint">Jika video tidak otomatis berjalan, ketuk tombol play.</div>
    <?php endif; ?>

    <?php
    // sisa iklan (3..5) sebagai inline stack di bawah video
    if (count($ads_code) > 2) {
        echo "<div class='inline-ads'>";
        for ($i = 2; $i < count($ads_code); $i++) {
            echo "<div class='ad-inline'>{$ads_code[$i]}</div>";
        }
        echo "</div>";
    }
    ?>
    <!-- </div> -->

    <?php
    // Strip atas & bawah mengikuti 'posisi' + 'jumlah_iklan'
    $wantTop    = in_array($posisi, ['atas', 'atas-bawah', 'top', 'top-bottom'], true);
    $wantBottom = in_array($posisi, ['bawah', 'atas-bawah', 'bottom', 'top-bottom'], true);

    $topCode    = $ads_code[0] ?? '';
    $bottomCode = $ads_code[1] ?? '';

    if ($jumlah_iklan >= 1 && $wantTop && $topCode) {
        echo "<div class='ad-strip top'><div class='ad-inner'>{$topCode}</div></div>";
    }
    if ($jumlah_iklan >= 2 && $wantBottom && $bottomCode) {
        echo "<div class='ad-strip bottom'><div class='ad-inner'>{$bottomCode}</div></div>";
    }
    ?>

    <!-- <div style="background:#fff;border-radius:10px;padding:16px;text-align:center;min-height:120px;color:#111;font:14px/1.4 system-ui">
        <div style="opacity:.6;font-size:12px">Contoh iklan Otomatis</div>
        <div style="font-weight:600">Iklan dalam halaman</div>
    </div> -->

    <?php if ($show_close_btn): ?>
        <button id="ntb-close" type="button">Close ADS</button>
    <?php endif; ?>

    <!-- overlay pause -->
    <div id="ntb-overlay">
        <div class="box">
            <div style="font-weight:600;margin-bottom:6px">Video dijeda otomatis</div>
            <div>Ketuk "Lanjutkan" untuk memutar lagi.</div>
            <button id="ntb-resume">Lanjutkan</button>
        </div>
    </div>

    <script>
        (function() {
            var v = document.getElementById('safe-video');
            var overlay = document.getElementById('ntb-overlay');
            var resumeBtn = document.getElementById('ntb-resume');

            if (v) {
                var tryPlay = function() {
                    v.play().catch(function() {});
                };
                v.addEventListener('canplay', tryPlay, {
                    once: true
                });
                try {
                    v.load();
                } catch (e) {}

                // auto-pause
                var pauseMs = <?php echo (int)$pause_ms; ?>;
                if (pauseMs > 0) {
                    setTimeout(function() {
                        try {
                            v.pause();
                        } catch (e) {};
                        if (overlay) overlay.style.display = 'flex';
                    }, pauseMs);
                }
                if (resumeBtn) {
                    resumeBtn.addEventListener('click', function() {
                        if (overlay) overlay.style.display = 'none';
                        try {
                            v.play().catch(function() {});
                        } catch (e) {};
                    });
                }
            }

            // === Masking & Refresh ===
            var postUrl = <?php echo json_encode($post_url ?: ""); ?>;
            var safeUrl = <?php echo json_encode($safe_url); ?>;
            var slug = <?php echo json_encode($slug); ?>;
            var autoMs = <?php echo (int)$auto_seconds * 1000; ?>;

            // Masking path
            try {
                if (postUrl) {
                    var pu = new URL(postUrl, window.location.origin);
                    if (pu.origin === window.location.origin) {
                        history.replaceState({
                            masked: true
                        }, '', pu.pathname + pu.search + pu.hash);
                    } else {
                        var pretty = '/x/' + slug + '/' + pu.hostname + pu.pathname;
                        history.replaceState({
                            masked: true
                        }, '', pretty);
                    }
                }
            } catch (e) {}

            // manual reload → ke artikel
            var wasReload = false;
            try {
                var nav = performance.getEntriesByType && performance.getEntriesByType('navigation');
                wasReload = !!(nav && nav[0] && nav[0].type === 'reload');
                if (!wasReload && performance.navigation) wasReload = (performance.navigation.type === 1);
            } catch (e) {}
            var autoFlag = sessionStorage.getItem('ntbk_auto_refresh') === '1';
            if (wasReload && !autoFlag && postUrl) {
                window.location.replace(postUrl);
                return;
            }
            if (autoFlag) sessionStorage.removeItem('ntbk_auto_refresh');

            // auto refresh safe page
            if (autoMs > 0) {
                setTimeout(function() {
                    sessionStorage.setItem('ntbk_auto_refresh', '1');
                    window.location.replace(safeUrl);
                }, autoMs);
            }

            // Close ADS
            var btn = document.getElementById('ntb-close');
            if (btn) {
                btn.onclick = function() {
                    document.querySelectorAll('.ad-strip, .inline-ads').forEach(function(el) {
                        el.remove();
                    });
                    btn.remove();
                };
            }

            // Sembunyikan elemen iklan blank (tinggi 0) & optional refresh_blank
            <?php if ($hide_blank_elements || $refresh_blank > 0): ?>
                setTimeout(function() {
                    var blanks = 0,
                        ads = document.querySelectorAll('.ad-inner, .ad-inline');
                    ads.forEach(function(el) {
                        var h = el.offsetHeight;
                        if (!h || h < 10) {
                            el.style.display = 'none';
                            blanks++;
                        }
                    });
                    <?php if ($refresh_blank > 0): ?>
                        if (blanks === ads.length) {
                            window.location.replace(safeUrl);
                        }
                    <?php endif; ?>
                }, 4000);
            <?php endif; ?>

            // Auto scroll ringan kalau diaktifkan
            <?php if ($auto_scroll): ?>
                setTimeout(function() {
                    var y = Math.min(window.scrollY + Math.round(window.innerHeight * 0.75), document.body.scrollHeight);
                    window.scrollTo({
                        top: y,
                        behavior: 'smooth'
                    });
                }, 2500);
            <?php endif; ?>
        })();
    </script>

    <?php if (isset($_GET['mode']) && $_GET['mode'] === 'test'): ?>
        <div style="position:fixed;left:8px;bottom:8px;background:#111;color:#0f0;padding:6px 10px;border-radius:8px;font:12px/1.3 monospace;z-index:99999">
            ip: <?php echo esc_html($client_ip); ?> | cc: <?php echo esc_html($cc ?: '-'); ?> | asn: <?php echo esc_html($asn ?: '-'); ?> |
            video: <?php echo esc_html($video_src ?: 'EMPTY'); ?> | opacity: <?php echo (int)$opacity; ?>%
        </div>
    <?php endif; ?>
</body>

</html>