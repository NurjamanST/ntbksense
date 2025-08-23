<?php

/**
 * =========================================================================
 * FILE: api/page-generator/bulk-create-pages.php
 * FUNGSI: Menerima request, lalu membuat 3 halaman penting (Privacy Policy,
 * Disclaimer, Terms & Conditions) dan menerapkan template custom.
 * =========================================================================
 */

@set_time_limit(300);
header("Content-Type: application/json; charset=UTF-8");

// --- Memuat Environment WordPress ---
$wp_load_path = dirname(__FILE__, 6) . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'FATAL ERROR: Gagal memuat environment WordPress.']);
    exit;
}
require_once($wp_load_path);

// --- Keamanan Utama ---
if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ntbksense_page_gen_nonce')) {
    wp_send_json_error(['message' => 'Validasi keamanan gagal!'], 403);
}
if (!current_user_can('publish_pages')) {
    wp_send_json_error(['message' => 'Anda tidak memiliki izin untuk membuat halaman.'], 403);
}

// --- Proses Utama ---
$api_key = sanitize_text_field($_POST['api_key'] ?? '');
if (empty($api_key)) {
    wp_send_json_error(['message' => 'Kunci API tidak boleh kosong.'], 400);
}

$pages_to_create = [
    'Privacy Policy',
    'Disclaimer',
    'Terms & Conditions'
];

$logs = [];
$success_count = 0;
$site_name = get_bloginfo('name');
$site_url = home_url();

foreach ($pages_to_create as $page_title) {
    // Cek apakah halaman sudah ada
    if (get_page_by_title($page_title, OBJECT, 'page')) {
        $logs[] = "Info: Halaman '{$page_title}' sudah ada, proses dilewati.";
        continue;
    }

    // Buat prompt spesifik untuk setiap halaman
    $prompt = "Please create the content for a '{$page_title}' page for a website named '{$site_name}' located at '{$site_url}'. The content must be professional, comprehensive, and written in well-formatted HTML using tags like <p>, <h2>, <ul>, and <li>.\n\nThe response format must be a JSON object like this:\n{\n  \"konten\": \"<p>The first paragraph goes here.</p><h2>A Section Title</h2><p>The next paragraph...</p>...\"\n}";

    // Panggil API Gemini
    $gemini_api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $api_key;
    $response = wp_remote_post($gemini_api_url, [
        'method'    => 'POST',
        'headers'   => ['Content-Type' => 'application/json'],
        'body'      => json_encode(['contents' => [['parts' => [['text' => $prompt]]]]]),
        'timeout'   => 120,
    ]);

    if (is_wp_error($response)) {
        $logs[] = "Gagal menghubungi API untuk halaman '{$page_title}': " . $response->get_error_message();
        continue;
    }

    $response_body = wp_remote_retrieve_body($response);
    $response_data = json_decode($response_body, true);

    if (!isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
        $error_message = $response_data['error']['message'] ?? 'Format respons tidak dikenal.';
        $logs[] = "Gagal membuat konten untuk '{$page_title}': " . $error_message;
        continue;
    }

    $json_string = $response_data['candidates'][0]['content']['parts'][0]['text'];
    $json_string = preg_replace('/^```json\s*/', '', $json_string);
    $json_string = preg_replace('/\s*```$/', '', $json_string);
    $page_data = json_decode($json_string, true);

    if (json_last_error() !== JSON_ERROR_NONE || !isset($page_data['konten'])) {
        $logs[] = "Gagal mem-parsing JSON dari AI untuk halaman '{$page_title}'.";
        continue;
    }

    // Buat halaman baru di WordPress
    $new_page = [
        'post_title'    => wp_strip_all_tags($page_title),
        'post_content'  => wp_kses_post($page_data['konten']),
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id(),
        'post_type'     => 'page',
        // [PERBAIKAN UTAMA] Otomatis set template custom untuk halaman ini
        'meta_input'    => [
            '_wp_page_template' => 'template-ai-layout.php'
        ]
    ];

    $page_id = wp_insert_post($new_page);

    if ($page_id === 0 || is_wp_error($page_id)) {
        $logs[] = "Gagal menyimpan halaman '{$page_title}' ke database.";
    } else {
        $logs[] = "Sukses! Halaman '<a href='" . get_permalink($page_id) . "' target='_blank'>{$page_title}</a>' berhasil dibuat.";
        $success_count++;
    }
}

$final_message = "Proses selesai. {$success_count} dari " . count($pages_to_create) . " halaman berhasil dibuat atau sudah ada.";
wp_send_json_success(['message' => $final_message, 'logs' => $logs]);
