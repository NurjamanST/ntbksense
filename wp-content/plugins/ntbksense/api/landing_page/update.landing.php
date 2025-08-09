<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

include_once '../../config/config.php';
include_once 'template.landing.php';

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    http_response_code(400);
    echo json_encode(["message" => "ID wajib disertakan untuk update."]);
    exit;
}

$template = new Template($connection);

// Isi semua field dari body
$template->id = (int) $data->id;
$template->slug = $data->slug ?? '';
$template->status = $data->status ?? 1;
$template->title = $data->title ?? '';
$template->title_option = $data->title_option ?? '';
$template->description = $data->description ?? '';
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
$template->custom_html = $data->custom_html ?? '';
$template->parameter_key = $data->parameter_key ?? '';
$template->parameter_value = $data->parameter_value ?? '';
$template->cloaking_url = $data->cloaking_url ?? '';
$template->custom_template_builder = $data->custom_template_builder ?? '';

// Format khusus
$template->post_urls = isset($data->post_urls) && is_array($data->post_urls)
    ? implode("\r\n", $data->post_urls)
    : '';

$template->video_urls = isset($data->video_urls) && is_array($data->video_urls)
    ? json_encode($data->video_urls)
    : '[]';

$template->universal_image_urls = isset($data->universal_image_urls) && is_array($data->universal_image_urls)
    ? json_encode($data->universal_image_urls)
    : '[]';


// echo json_encode([
//     'debug_message' => 'INI NILAI YANG MASUK KE FUNGSI PENGECEKAN',
//     'slug_yang_dicek' => $template->slug,
//     'id_yang_dikecualikan' => $template->id,
//     'tipe_data_id' => gettype($template->id)
// ]);
// exit; // HENTIKAN SCRIPT DI SINI UNTUK MELIHAT HASILNYA

// =================================================================
// >>> BLOK PENGECEKAN SLUG DITEMPEL DI SINI <<<
// =================================================================
if (!empty($template->slug) && $template->isSlugTakenByAnother($template->slug, $template->id)) {
    // Jika slug sudah ada, kirim response error 409 (Conflict)
    http_response_code(409);
    echo json_encode(["message" => "Gagal: Slug '{$template->slug}' sudah digunakan oleh template lain."]);
    exit; // Hentikan eksekusi
}
// =================================================================


if ($template->update()) {
    http_response_code(200);
    echo json_encode(["message" => "Template berhasil diupdate."]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Gagal mengupdate template."]);
}
