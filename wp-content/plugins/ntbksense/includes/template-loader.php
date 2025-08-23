<?php

/**
 * =========================================================================
 * FILE: includes/template-loader.php
 * FUNGSI: Membuat "jembatan" agar WordPress bisa menemukan dan menggunakan
 * file template yang ada di dalam folder plugin.
 * =========================================================================
 */

// 1. Menambahkan template kita ke daftar pilihan template di editor post
add_filter('theme_post_templates', 'ntbksense_add_ai_template_to_dropdown');
function ntbksense_add_ai_template_to_dropdown($templates)
{
    // 'template-ai-layout.php' adalah nama file-nya
    // 'AI Full-Width Layout' adalah nama yang akan muncul di dropdown
    $templates['template-ai-layout.php'] = __('AI Full-Width Layout', 'ntbksense');
    return $templates;
}

// 2. "Membajak" proses loading template WordPress
add_filter('template_include', 'ntbksense_load_ai_template_from_plugin');
function ntbksense_load_ai_template_from_plugin($template)
{
    // Hanya jalankan di halaman artikel tunggal
    if (is_singular('post')) {
        // Cek template apa yang dipilih untuk post ini
        $post_template = get_post_meta(get_the_ID(), '_wp_page_template', true);

        // Jika post ini disuruh pake template AI kita...
        if ($post_template === 'template-ai-layout.php') {
            // ...cari file-nya di dalem folder plugin kita
            // (Asumsi lo nyimpennya di ntbksense/template/template-ai-layout.php)
            $plugin_template_path = plugin_dir_path(__DIR__) . 'template/template-ai-layout.php';

            // Kalau filenya ada, paksa WordPress buat pake file itu
            if (file_exists($plugin_template_path)) {
                return $plugin_template_path;
            }
        }
    } elseif (is_singular('page')) {
        // Cek template apa yang dipilih untuk halaman ini
        $page_template = get_post_meta(get_the_ID(), '_wp_page_template', true);

        // Jika halaman ini disuruh pake template AI kita...
        if ($page_template === 'template-ai-layout.php') {
            // ...cari file-nya di dalem folder plugin kita
            // (Asumsi lo nyimpennya di ntbksense/template/template-ai-layout.php)
            $plugin_template_path = plugin_dir_path(__DIR__) . 'template/template-ai-layout.php';

            // Kalau filenya ada, paksa WordPress buat pake file itu
            if (file_exists($plugin_template_path)) {
                return $plugin_template_path;
            }
        }
    }elseif (is_home() || is_archive()) {
        // Cek apakah ada post dengan template AI di halaman arsip atau beranda
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'   => '_wp_page_template',
                    'value' => 'template-ai-layout.php',
                ],
            ],
        ];
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            // Ada post dengan template AI, paksa pake template AI
            $plugin_template_path = plugin_dir_path(__DIR__) . 'template/template-ai-layout.php';
            if (file_exists($plugin_template_path)) {
                wp_reset_postdata();
                return $plugin_template_path;
            }
        }
        wp_reset_postdata();
    }

    // Kalau bukan, pake aja template default dari tema
    return $template;
}
