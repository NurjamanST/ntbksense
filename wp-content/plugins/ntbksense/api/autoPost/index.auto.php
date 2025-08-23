<?php

/**
 * =========================================================================
 * FILE: api/autoPost/index.auto.php
 * FUNGSI: Menerima request, menyuruh AI membuat KONTEN ARTIKEL (bukan layout),
 * dan mempublikasikannya dengan template custom.
 * VERSI: Final.
 * =========================================================================
 */

@set_time_limit(600);
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
if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ntbksense_autopost_nonce')) {
    wp_send_json_error(['message' => 'Validasi keamanan gagal!'], 403);
}
if (!current_user_can('publish_posts')) {
    wp_send_json_error(['message' => 'Anda tidak memiliki izin.'], 403);
}

// --- Ambil dan Bersihkan Data dari Form ---
$api_keys       = isset($_POST['kunci_api']) ? array_map('sanitize_text_field', (array)$_POST['kunci_api']) : [];
$rotasi_api     = sanitize_text_field($_POST['rotasi'] ?? 'acak');
$bahasa         = sanitize_text_field($_POST['bahasa'] ?? 'English');
$gaya_bahasa    = sanitize_text_field($_POST['gaya_bahasa'] ?? 'Bahasa Santai Resmi');
$kedalaman      = sanitize_text_field($_POST['kedalaman_artikel'] ?? 'Oasis of knowledge (L4)');
$status_artikel = strtolower(sanitize_text_field($_POST['status_artikel'] ?? 'draft'));
$author_id      = absint($_POST['nama_pengguna'] ?? get_current_user_id());
$kategori_id    = absint($_POST['kategori'] ?? get_option('default_category'));
$kata_kunci_raw = sanitize_textarea_field($_POST['kata_kunci'] ?? '');
$delay          = absint($_POST['delay'] ?? 0);

// Validasi data
$api_keys = array_filter($api_keys);
if (empty($api_keys) || empty($kata_kunci_raw)) {
    wp_send_json_error(['message' => 'Kunci API dan Kata Kunci tidak boleh kosong.'], 400);
}
$kata_kunci_list = array_filter(array_map('trim', explode("\n", $kata_kunci_raw)));
if (empty($kata_kunci_list)) {
    wp_send_json_error(['message' => 'Tidak ada kata kunci valid untuk diproses.'], 400);
}
$allowed_statuses = ['publish', 'draft', 'pending', 'private'];
if (!in_array($status_artikel, $allowed_statuses)) {
    $status_artikel = 'draft';
}

$logs = [];
$sukses_count = 0;
$api_key_index = 0;

// --- Loop untuk Setiap Kata Kunci ---
foreach ($kata_kunci_list as $keyword) {
    // Logika rotasi API key
    if ($rotasi_api === 'acak') {
        $current_api_key = $api_keys[array_rand($api_keys)];
    } else {
        $current_api_key = $api_keys[$api_key_index % count($api_keys)];
        $api_key_index++;
    }

    // [PERBAIKAN UTAMA] Prompt disederhanakan, hanya minta judul dan konten artikel
    $prompt = "Tolong buatkan artikel SEO friendly yang menarik dan informatif dalam bahasa {$bahasa} dengan gaya bahasa '{$gaya_bahasa}'. Artikel harus memiliki kedalaman materi '{$kedalaman}'. Topik utama artikel adalah: '{$keyword}'.\n\nFormat balasan harus dalam bentuk JSON seperti ini:\n{\n  \"judul\": \"Judul Artikel Di Sini\",\n  \"konten\": \"<p>Paragraf pertama di sini.</p><p>Paragraf kedua di sini.</p>...\"\n}";

    // Panggil API Gemini
    $gemini_api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $current_api_key;
    $response = wp_remote_post($gemini_api_url, [
        'method'    => 'POST',
        'headers'   => ['Content-Type' => 'application/json'],
        'body'      => json_encode(['contents' => [['parts' => [['text' => $prompt]]]]]),
        'timeout'   => 120,
    ]);

    // Proses respons
    if (is_wp_error($response)) {
        $logs[] = "Gagal menghubungi API untuk '{$keyword}': " . $response->get_error_message();
        continue;
    }
    $response_body = wp_remote_retrieve_body($response);
    $response_data = json_decode($response_body, true);
    if (!isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
        $error_message = $response_data['error']['message'] ?? 'Format respons tidak dikenal.';
        $logs[] = "Gagal membuat artikel untuk '{$keyword}': " . $error_message;
        continue;
    }
    $artikel_json_string = $response_data['candidates'][0]['content']['parts'][0]['text'];
    $artikel_json_string = preg_replace('/^```json\s*/', '', $artikel_json_string);
    $artikel_json_string = preg_replace('/\s*```$/', '', $artikel_json_string);
    $artikel_data = json_decode($artikel_json_string, true);
    if (json_last_error() !== JSON_ERROR_NONE || !isset($artikel_data['judul']) || !isset($artikel_data['konten'])) {
        $logs[] = "Gagal mem-parsing JSON dari AI untuk '{$keyword}'.";
        continue;
    }

    // Buat post di WordPress
    $post_data = [
        'post_title'    => sanitize_text_field($artikel_data['judul']),
        'post_content'  => wp_kses_post($artikel_data['konten']),
        'post_status'   => $status_artikel,
        'post_author'   => $author_id,
        'post_category' => [$kategori_id],
        // Otomatis set template custom untuk post ini
        'meta_input'    => [
            '_wp_page_template' => 'template-ai-layout.php'
        ]
    ];

    $post_id = wp_insert_post($post_data, true);

    // Laporan log
    if (is_wp_error($post_id) || $post_id === 0) {
        $error_string = is_wp_error($post_id) ? $post_id->get_error_message() : 'Unknown error';
        $logs[] = "Gagal menyimpan artikel '{$artikel_data['judul']}': " . $error_string;
    } else {
        $logs[] = "Sukses! Artikel '<a href='" . get_permalink($post_id) . "' target='_blank'>{$artikel_data['judul']}</a>' dibuat dengan status [" . ucfirst($status_artikel) . "].";
        $sukses_count++;
    }

    if ($delay > 0) {
        sleep($delay);
    }
}

// --- Kirim Hasil Akhir ---
$final_message = "Proses selesai. {$sukses_count} dari " . count($kata_kunci_list) . " artikel berhasil dibuat.";
wp_send_json_success(['message' => $final_message, 'logs' => $logs]);
