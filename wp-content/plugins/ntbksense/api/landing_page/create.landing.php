<?php
// Set header untuk respons JSON dan izin CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * LANGKAH 1: Memuat inti WordPress untuk akses fungsi dan database
 */
include_once('../../../../../wp-load.php');

/**
 * LANGKAH 2: Include file Class Template
 */
include_once 'template.landing.php';

// Ambil data JSON dari body request
$data = json_decode(file_get_contents("php://input"));

// Validasi input awal
if (empty($data->slug) || empty($data->title)) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Gagal: slug dan title wajib diisi."]);
    exit;
}

/**
 * LANGKAH 3: Gunakan objek database global $wpdb dari WordPress
 */
global $wpdb;

// Buat objek Template baru dan kasih koneksi $wpdb
$template = new Template($wpdb);

// Validasi slug unik (memanggil fungsi dari class)
if ($template->isSlugExists($data->slug)) {
    http_response_code(409); // Conflict
    echo json_encode(["message" => "Gagal: Slug '{$data->slug}' sudah digunakan. Coba slug lain."]);
    exit;
}

$template->slug = $data->slug; // Slug biasanya tidak perlu htmlspecialchars, tapi pastikan bersih
$template->status = $data->status ?? 1;
$template->title = htmlspecialchars(strip_tags($data->title));
$template->title_option = isset($data->title_option) ? htmlspecialchars(strip_tags($data->title_option)) : '';
$template->description = isset($data->description) ? htmlspecialchars(strip_tags($data->description)) : '';
// ... sanitasi properti lainnya sesuai kebutuhan ...
$template->inject_keywords = $data->inject_keywords ?? '';
$template->description_option = $data->description_option ?? '';
$template->template_file = $data->template_file ?? '';
$template->template_name = $data->template_name ?? '';
$template->random_template_method = $data->random_template_method ?? '';
$template->random_template_file = $data->random_template_file ?? '';
$template->random_template_file_afs = $data->random_template_file_afs ?? '';
$template->redirect_post_option = $data->redirect_post_option ?? '';
$template->timer_auto_refresh = $data->timer_auto_refresh ?? 0;
$template->auto_refresh_option = $data->auto_refresh_option ?? '';
$template->protect_elementor = $data->protect_elementor ?? '';
$template->referrer_option = $data->referrer_option ?? '';
$template->device_view = $data->device_view ?? '';
$template->videos_floating_option = $data->videos_floating_option ?? '';
$template->timer_auto_pause_video = $data->timer_auto_pause_video ?? 0;
$template->landing_image_urls = $data->landing_image_urls ?? '';
$template->number_images_displayed = $data->number_images_displayed ?? 0;
$template->custom_html = $data->custom_html ?? ''; // Hati-hati dengan ini, pastikan inputnya terpercaya
$template->parameter_key = $data->parameter_key ?? '';
$template->parameter_value = $data->parameter_value ?? '';
$template->cloaking_url = $data->cloaking_url ?? '';
$template->custom_template_builder = $data->custom_template_builder ?? '';

// $template->post_urls = isset($data->post_urls) && is_array($data->post_urls) ? implode("\r\n", $data->post_urls) : '';

if (isset($data->post_urls) && is_string($data->post_urls)) {
    $template->post_urls = $data->post_urls;
} else {
    $template->post_urls = '';
}

// var_dump($template->post_urls); // Debugging: Cek apakah post_urls sudah benar
$template->video_urls = isset($data->video_urls) && is_array($data->video_urls) ? json_encode($data->video_urls) : '[]';
$template->universal_image_urls = isset($data->universal_image_urls) && is_array($data->universal_image_urls) ? json_encode($data->universal_image_urls) : '[]';

// Coba simpan data menggunakan method create() dari class
if ($template->create()) {
    $baseUrl = home_url(); // Gunakan fungsi WordPress untuk base URL

    $adsUrl = "{$baseUrl}/redirect_ads.php?slug=" . urlencode($template->slug);
    $publicUrl = "{$baseUrl}/redirect_ads.php?slug=" . urlencode($template->slug);
    $cekURL = "{$baseUrl}/redirect_ads.php?slug=" . urlencode($template->slug) . "&mode=ads";

    http_response_code(201); // Created
    echo json_encode([
        "message" => "Template berhasil dibuat!",
        "ads_url" => $adsUrl,
        "public_url" => $publicUrl,
        "cek_url" => $cekURL
    ]);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["message" => "Gagal membuat template. Terjadi kesalahan pada server."]);
}