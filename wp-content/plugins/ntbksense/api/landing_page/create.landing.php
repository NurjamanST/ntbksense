<?php
// (Kode debug ini boleh dihapus atau biarkan saja)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Set header untuk respons JSON dan izin CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Memuat inti WordPress
// require_once('../../../../../wp-load.php'); // Sesuaikan path jika perlu


// Membangun path ke folder root WordPress secara dinamis
$wp_root_path = dirname(__FILE__, 6); // Naik 6 tingkat folder dari file ini

// Memuat file wp-load.php menggunakan path yang sudah pasti benar
require_once($wp_root_path . '/wp-load.php');

// Include file Class Template
include_once 'template.landing.php';

// Ambil data JSON dari body request
$data = json_decode(file_get_contents("php://input"));

// Validasi input awal
if (empty($data->slug) || empty($data->title)) {
    http_response_code(400);
    echo json_encode(["message" => "Gagal: slug dan title wajib diisi."]);
    exit;
}

// Gunakan objek database global $wpdb dari WordPress
global $wpdb;

// Buat objek Template baru dan kasih koneksi $wpdb
$template = new Template($wpdb);

// Validasi slug unik
if ($template->isSlugExists($data->slug)) {
    http_response_code(409); // Conflict
    echo json_encode(["message" => "Gagal: Slug '{$data->slug}' sudah digunakan."]);
    exit;
}

// Sanitasi & isi semua properti objek Template
$template->slug = $data->slug;
$template->status = $data->status ?? '1';
$template->title = isset($data->title) ? htmlspecialchars(strip_tags($data->title)) : '';
$template->title_option = $data->title_option ?? 'random';
$template->description = isset($data->description) ? htmlspecialchars(strip_tags($data->description)) : '';
$template->inject_keywords = $data->inject_keywords ?? '';
$template->description_option = $data->description_option ?? 'custom';
$template->template_file = $data->template_file ?? '';
$template->template_name = $data->template_name ?? '';
$template->random_template_method = $data->random_template_method ?? '';
$template->random_template_file = $data->random_template_file ?? '';
$template->random_template_file_afs = $data->random_template_file_afs ?? '';
$template->post_urls = $data->post_urls ?? '';
$template->redirect_post_option = $data->redirect_post_option ?? 'custom';
$template->timer_auto_refresh = $data->timer_auto_refresh ?? '0';
$template->auto_refresh_option = $data->auto_refresh_option ?? '';
$template->protect_elementor = $data->protect_elementor ?? '';
$template->referrer_option = $data->referrer_option ?? '';
$template->device_view = $data->device_view ?? '';
$template->videos_floating_option = $data->videos_floating_option ?? '';
$template->timer_auto_pause_video = $data->timer_auto_pause_video ?? '0';
$template->video_urls = isset($data->video_urls) && is_array($data->video_urls) ? json_encode($data->video_urls) : '[]';
$template->universal_image_urls = isset($data->universal_image_urls) && is_array($data->universal_image_urls) ? json_encode($data->universal_image_urls) : '[]';
$template->landing_image_urls = $data->landing_image_urls ?? '';
$template->number_images_displayed = $data->number_images_displayed ?? 0;
$template->custom_html = $data->custom_html ?? '';
$template->parameter_key = $data->parameter_key ?? '';
$template->parameter_value = $data->parameter_value ?? '';
$template->cloaking_url = $data->cloaking_url ?? '';
$template->custom_template_builder = $data->custom_template_builder ?? '';


// Panggil method create()
if ($template->create()) {
    $baseUrl = home_url();

    http_response_code(201); // Created
    echo json_encode([
        "message" => "Template berhasil dibuat!",
        "new_id" => $template->id // Kirim balik ID yang baru dibuat
    ]);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["message" => "Gagal membuat template.", "db_error" => $wpdb->last_error]);
}