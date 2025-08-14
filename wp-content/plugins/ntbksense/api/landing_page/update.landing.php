<?php
// Set header sebagai JSON di paling awal
header("Content-Type: application/json; charset=UTF-8");

// Memuat environment WordPress dengan aman
$wp_load_path = dirname(__FILE__, 6) . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'data' => ['message' => 'FATAL ERROR: Gagal memuat environment WordPress.']]);
    exit;
}
require_once($wp_load_path);

// Memuat file Class Template
$template_class_path = dirname(__FILE__) . '/template.landing.php';
if (!file_exists($template_class_path)) {
    wp_send_json_error(['message' => 'FATAL ERROR: File class template.landing.php tidak ditemukan.']);
    exit;
}
require_once($template_class_path);

// Ambil data JSON dari body request
$data = json_decode(file_get_contents('php://input'), true);

// Validasi ID
$landing_page_id = isset($data['id']) ? (int)$data['id'] : 0;
if ($landing_page_id <= 0) {
    wp_send_json_error(['message' => 'ID Landing Page tidak valid.']);
    exit;
}

global $wpdb;
$template = new Template($wpdb);

// Validasi slug duplikat
$slug_to_check = sanitize_title($data['slug']);
if (!empty($slug_to_check) && $template->isSlugTakenByAnother($slug_to_check, $landing_page_id)) {
    wp_send_json_error(['message' => "Gagal: Slug '{$slug_to_check}' sudah digunakan."]);
    exit;
}

// Isi semua properti object Template
// $template->id = sanitize_text_field($data['id']);
$template->id = (int)$landing_page_id; // Pastikan ID adalah integer
$template->slug = $slug_to_check;
$template->status = sanitize_text_field($data['status'] ?? '1');
$template->title = sanitize_text_field($data['title']);
// ... (isi semua properti lain di sini, sama seperti di JS)
$template->title_option = sanitize_text_field($data['title_option']);
$template->template_name = sanitize_text_field($data['template_name']);
$template->template_file = sanitize_text_field($data['template_file']);
$template->description = wp_kses_post($data['description'] ?? '');
$template->inject_keywords = sanitize_textarea_field($data['inject_keywords'] ?? '');
$template->description_option = sanitize_text_field($data['description_option'] ?? 'custom');
$template->post_urls = sanitize_textarea_field($data['post_urls'] ?? '');
$template->video_urls = isset($data['video_urls']) ? stripslashes($data['video_urls']) : '[]';
$template->universal_image_urls = isset($data['universal_image_urls']) ? stripslashes($data['universal_image_urls']) : '[]';
$template->landing_image_urls = isset($data['landing_image_urls']) ? stripslashes($data['landing_image_urls']) : '[]';
$template->number_images_displayed = (int)($data['number_images_displayed'] ?? 0);
$template->redirect_post_option = sanitize_text_field($data['redirect_post_option'] ?? 'custom');
$template->timer_auto_refresh = sanitize_text_field($data['timer_auto_refresh'] ?? '0');
$template->auto_refresh_option = sanitize_text_field($data['auto_refresh_option'] ?? '');
$template->protect_elementor = sanitize_text_field($data['protect_elementor'] ?? '');
$template->referrer_option = sanitize_text_field($data['referrer_option'] ?? '');
$template->device_view = sanitize_text_field($data['device_view'] ?? '');
$template->videos_floating_option = sanitize_text_field($data['videos_floating_option'] ?? '');
$template->timer_auto_pause_video = sanitize_text_field($data['timer_auto_pause_video'] ?? '0');
$template->parameter_key = sanitize_text_field($data['parameter_key'] ?? '');
$template->parameter_value = sanitize_text_field($data['parameter_value'] ?? '');
$template->cloaking_url = esc_url_raw($data['cloaking_url'] ?? '');
$template->custom_html = $data['custom_html'] ?? '';
$template->custom_template_builder = $data['custom_template_builder'] ?? '[]';
$template->random_template_method = sanitize_text_field($data['random_template_method'] ?? 'random');
$template->random_template_file = isset($data['random_template_file']) ? stripslashes($data['random_template_file']) : '[]';
$template->random_template_file_afs = isset($data['random_template_file_afs']) ? stripslashes($data['random_template_file_afs']) : '[]';


// Panggil method update dari Class
if ($template->update()) {
    wp_send_json_success(['message' => 'Template berhasil diupdate.']);
} else {
    wp_send_json_error(['message' => 'Gagal mengupdate template.', 'db_error' => $wpdb->last_error]);
}
