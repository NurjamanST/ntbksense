<?php
/**
 * LANGKAH 1: Memuat inti WordPress.
 * Diasumsikan file ini ada di folder root WordPress, sejajar dengan wp-load.php.
 * Jika tidak, sesuaikan path-nya.
 */
require_once('wp-load.php');

// Ambil parameter dari URL
$slug = $_GET['slug'] ?? null;
$mode = $_GET['mode'] ?? null;

// Validasi slug
if (!$slug) {
    http_response_code(400);
    echo "Slug tidak ditemukan.";
    exit;
}

/**
 * LANGKAH 2: Gunakan $wpdb untuk mengambil data
 */
global $wpdb;
$table_name = 'wp_acp_landings'; // Ganti 'wp_' dengan $wpdb->prefix jika perlu

// Siapkan query yang aman dan ambil satu baris data
$query = $wpdb->prepare("SELECT * FROM $table_name WHERE slug = %s", $slug);
$templateData = $wpdb->get_row($query, ARRAY_A); // ARRAY_A untuk hasil array

// Cek apakah data ditemukan
if (!$templateData) {
    http_response_code(404);
    echo "Template tidak ditemukan.";
    exit;
}

// --- Logika penentuan $isFromAds (kode lo udah bener, jadi kita pakai lagi) ---
if ($mode === 'ads') {
    $isFromAds = true;
} elseif ($mode === 'public') {
    $isFromAds = false;
} else {
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $isFromAds = preg_match('/facebook\.com|t\.co|googleadservices\.com|doubleclick\.net/', $referer);
}

// Parsing data dari database
$post_urls = preg_split("/\r\n|\n|\r/", $templateData['post_urls']);
$video_urls = json_decode($templateData['video_urls'], true);
$img_urls = json_decode($templateData['universal_image_urls'], true);


// --- Logika output (juga sudah bener) ---
if ($isFromAds) {
    // Tampilkan halaman "aman" (safe page)
    header("Content-Type: text/html; charset=UTF-8");
    echo "<!DOCTYPE html><html><head><title>" . esc_html($templateData['title']) . "</title></head><body style='text-align:center;padding:20px;'>";
    echo "<h2>" . esc_html($templateData['title']) . "</h2>";

    if (!empty($video_urls[0])) {
        echo "<video width='640' controls autoplay muted loop playsinline>
                <source src='" . esc_url($video_urls[0]) . "' type='video/webm'>
                Browser tidak support video.
              </video><br><br>";
    }

    if (!empty($img_urls[0])) {
        echo "<img src='" . esc_url($img_urls[0]) . "' alt='Gambar' width='640'><br>";
    }

    echo "</body></html>";

} else {
    // Redirect ke URL cloaking/post
    if (!empty($post_urls) && !empty(trim($post_urls[0]))) {
         // Pilih URL acak dari daftar
        $redirectTo = $post_urls[array_rand($post_urls)];
        header("Location: " . trim($redirectTo));
    } else {
        // Fallback jika post_urls kosong
        echo "URL tujuan tidak valid.";
    }
}

exit;