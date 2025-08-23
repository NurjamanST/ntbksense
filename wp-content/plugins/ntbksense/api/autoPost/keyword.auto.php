<?php

/**
 * =========================================================================
 * FILE: api/autoPost/keyword.auto.php
 * VERSI: Final dengan model API yang sudah diupdate.
 * =========================================================================
 */

// Menangkap semua error untuk dilaporkan
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

try {
    @set_time_limit(180); // 3 menit

    $wp_load_path = dirname(__FILE__, 6) . '/wp-load.php';
    if (!file_exists($wp_load_path)) {
        throw new Exception('FATAL ERROR: Gagal memuat environment WordPress. Path salah: ' . $wp_load_path);
    }
    require_once($wp_load_path);

    header("Content-Type: application/json; charset=UTF-8");

    // Keamanan
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ntbksense_autopost_nonce')) {
        wp_send_json_error(['message' => 'Validasi keamanan gagal!'], 403);
    }
    if (!current_user_can('publish_posts')) {
        wp_send_json_error(['message' => 'Anda tidak memiliki izin.'], 403);
    }

    // =========================================================================
    // LOGIKA INTI DIMULAI DI SINI
    // =========================================================================

    // 1. Ambil dan Bersihkan Data
    $api_key      = sanitize_text_field($_POST['api_key'] ?? '');
    $base_keyword = sanitize_text_field($_POST['base_keyword'] ?? '');
    $total        = absint($_POST['total_keyword'] ?? 10);

    if (empty($api_key) || empty($base_keyword)) {
        wp_send_json_error(['message' => 'Kunci API dan Kata Kunci Dasar tidak boleh kosong.'], 400);
    }

    // 2. Buat Prompt untuk AI Gemini
    $prompt = "Berikan {$total} variasi kata kunci long-tail yang relevan dan SEO friendly berdasarkan kata kunci dasar ini: '{$base_keyword}'.\n\nPastikan setiap kata kunci berada di baris baru. Jangan berikan nomor, bullet point, atau teks tambahan apa pun, hanya daftar kata kunci saja.";

    // 3. Panggil API Gemini
    // [PERBAIKAN UTAMA] Ganti model 'gemini-pro' menjadi 'gemini-1.5-flash-latest'
    $gemini_api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $api_key;
    $response = wp_remote_post($gemini_api_url, [
        'method'    => 'POST',
        'headers'   => ['Content-Type' => 'application/json'],
        'body'      => json_encode(['contents' => [['parts' => [['text' => $prompt]]]]]),
        'timeout'   => 120, // 2 menit
    ]);

    // 4. Proses Respons dari API
    if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'Gagal menghubungi API Gemini: ' . $response->get_error_message()], 500);
    }

    $response_body = wp_remote_retrieve_body($response);
    $response_data = json_decode($response_body, true);

    if (!isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
        $error_message = $response_data['error']['message'] ?? 'Format respons tidak dikenal. Cek kembali API Key Anda.';
        wp_send_json_error(['message' => 'Gagal membuat kata kunci: ' . $error_message], 500);
    }

    // Ambil hasil kata kunci dari AI
    $generated_keywords = trim($response_data['candidates'][0]['content']['parts'][0]['text']);

    // 5. Kirim Hasil Akhir
    wp_send_json_success([
        'message'  => 'Kata kunci berhasil dibuat.',
        'keywords' => $generated_keywords
    ]);
} catch (Throwable $e) {
    // Jika ada error apa pun, tangkap dan laporkan sebagai JSON
    if (!headers_sent()) {
        header("Content-Type: application/json; charset=UTF-8");
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi fatal error di server.',
        'error_details' => [
            'type'    => get_class($e),
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]
    ]);
    exit;
}
