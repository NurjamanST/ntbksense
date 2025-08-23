<?php

/**
 * =========================================================================
 * FILE: templates/template-ai-layout.php
 * FUNGSI: Template custom dari plugin untuk menampilkan artikel AI
 * dengan layout full-width, navbar, sidebar, dan iklan dinamis.
 * VERSI: 2.2 - Ditambahkan Breadcrumbs dan Thumbnail di Sidebar.
 * =========================================================================
 */

global $wpdb;
$table_name = $wpdb->prefix . 'ntbk_ads_settings';
$ads_settings = $wpdb->get_row("SELECT kode_iklan_1, kode_iklan_2 FROM $table_name WHERE id = 1", ARRAY_A);

// Ambil dan decode kode iklan
$bottom_ad_code = isset($ads_settings['kode_iklan_1']) ? base64_decode($ads_settings['kode_iklan_1']) : '<!-- Iklan Bawah Tidak Ditemukan -->';
$sidebar_ad_code = isset($ads_settings['kode_iklan_2']) ? base64_decode($ads_settings['kode_iklan_2']) : '<!-- Iklan Sidebar Tidak Ditemukan -->';

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <style>
        /* CSS Reset Sederhana & Global */
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .container-img {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 15px;
        }

        a {
            color: #1a73e8;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* --- Header & Navbar --- */
        .site-header {
            padding: 1.5rem 0;
            background-color: #fff;
            border-bottom: 1px solid #eee;
        }

        .site-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .site-header .container-img {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .site-title a {
            font-size: 1.8rem;
            font-weight: bold;
            color: #1a73e8;
        }

        .site-navigation {
            background-color: #222;
        }

        .main-navigation ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .main-navigation ul li a {
            color: #fff;
            padding: 1rem 1.5rem;
            display: block;
            text-transform: uppercase;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .main-navigation ul li a:hover {
            background-color: #444;
        }

        /* [BARU] Breadcrumbs */
        .breadcrumb-container {
            background-color: #f1f1f1;
            padding: 0.75rem 0;
            border-bottom: 1px solid #ddd;
        }

        .breadcrumb {
            font-size: 0.85rem;
            color: #555;
        }

        .breadcrumb a {
            color: #1a73e8;
        }

        .breadcrumb span {
            color: #888;
        }

        /* --- Layout Utama --- */
        .site-content-container {
            padding: 2rem 0;
        }

        .site-container {
            display: flex;
            gap: 3rem;
        }

        .main-content-area {
            flex: 1;
            min-width: 0;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* --- Konten Artikel --- */
        .post-header {
            margin-bottom: 2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1.5rem;
        }

        .post-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            text-align: left;
        }

        .post-meta {
            color: #666;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        .post-thumbnail img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .post-content {
            line-height: 1.8;
        }

        .post-content h2,
        .post-content h3 {
            margin-top: 2.5rem;
        }

        /* --- Sidebar --- */
        .sidebar-area {
            width: 300px;
            flex-shrink: 0;
        }

        .sidebar-widget {
            margin-bottom: 2rem;
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .widget-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 1rem;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        .sidebar-widget ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-widget ul li {
            margin-bottom: 1rem;
        }

        /* [BARU] Sidebar Artikel Terbaru dengan Thumbnail */
        .recent-post-link {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .recent-post-thumbnail img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .recent-post-title {
            font-weight: 500;
        }

        /* --- Slot Iklan --- */
        .ad-slot {
            text-align: center;
            margin: 2rem 0;
        }

        .sidebar-ad .ad-slot {
            margin: 0;
        }

        /* --- Footer --- */
        .site-footer {
            background-color: #222;
            color: #aaa;
            padding: 2rem 0;
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
        }

        .footer-nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin-bottom: 1rem;
        }

        .footer-nav ul li {
            margin: 0 10px;
        }

        .footer-nav ul li a {
            color: #fff;
        }

        .setting-logo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: var(--bg-white);
            border-bottom: 1px solid var(--border-color-strong);
        }

        .ntb-navbar-logo {
            width: 15%;
        }

        .ntb-navbar-title {
            font-size: 16px;
            font-weight: 600;
            color: #1d2327;
        }
        .color-w{
            color: #1d2327 !important;
            font-weight: 500;
            font-size: 20px;
            margin-left: -425px;
            text-decoration: none;

        }

        /* --- Responsif --- */
        @media (max-width: 900px) {
            .site-container {
                flex-direction: column;
            }

            .sidebar-area {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .post-title {
                font-size: 2rem;
            }

            .main-navigation ul,
            .footer-nav ul {
                flex-direction: column;
                text-align: center;
            }

            .footer-nav ul li {
                margin: 5px 0;
            }


        }
    </style>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="site-header">
        <div class="container-img">
            <div class="setting-logo">
                <img src="<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'assets/images/NTBKsense.png'); ?>" alt="Logo" class="ntb-navbar-logo">
                <div class="ntb-navbar-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="color-w"><?php bloginfo('name'); ?></a></div>
            </div>
        </div>
    </header>
    <nav class="site-navigation" role="navigation">
        <div class="container">
            <div class="main-navigation">
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                    <?php
                    $disclaimer_page = get_page_by_title('Disclaimer');
                    $terms_page = get_page_by_title('Terms & Conditions');
                    $privacy_page = get_page_by_title('Privacy Policy');
                    if ($disclaimer_page) echo '<li><a href="' . get_permalink($disclaimer_page) . '">Disclaimer</a></li>';
                    if ($terms_page) echo '<li><a href="' . get_permalink($terms_page) . '">Terms & Conditions</a></li>';
                    if ($privacy_page) echo '<li><a href="' . get_permalink($privacy_page) . '">Privacy Policy</a></li>';
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- [BARU] Bagian Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="container breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> &raquo;
            <?php the_category(' &raquo; '); ?> &raquo;
            <span><?php the_title(); ?></span>
        </div>
    </div>


    <div id="content" class="site-content-container container">
        <div class="site-container">
            <main id="primary" class="main-content-area">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <header class="post-header">
                                <h1 class="post-title"><?php the_title(); ?></h1>
                                <div class="post-meta">
                                    <span>Oleh <?php the_author_posts_link(); ?></span> |
                                    <span><?php echo get_the_date(); ?></span>
                                </div>
                            </header>

                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="post-content">
                                <?php the_content(); ?>
                            </div>
                        </article>

                        <div class="ad-slot bottom-ad">
                            <?php echo $bottom_ad_code; ?>
                        </div>

                <?php
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;

                    endwhile;
                endif;
                ?>
            </main>

            <aside id="secondary" class="sidebar-area">
                <div class="sidebar-widget sidebar-ad">
                    <div class="ad-slot">
                        <?php echo $sidebar_ad_code; ?>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <h3 class="widget-title">Artikel Terbaru</h3>
                    <ul>
                        <?php
                        $recent_posts = new WP_Query(['posts_per_page' => 5, 'post__not_in' => [get_the_ID()]]);
                        if ($recent_posts->have_posts()) :
                            while ($recent_posts->have_posts()) : $recent_posts->the_post();
                        ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>" class="recent-post-link">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="recent-post-thumbnail">
                                                <?php the_post_thumbnail([60, 60]); // Set ukuran thumbnail 
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        <span class="recent-post-title"><?php the_title(); ?></span>
                                    </a>
                                </li>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    <footer class="site-footer">
        <div class="container">
            <nav class="footer-nav">
                <ul>
                    <?php
                    if ($disclaimer_page) echo '<li><a href="' . get_permalink($disclaimer_page) . '">Disclaimer</a></li>';
                    if ($terms_page) echo '<li><a href="' . get_permalink($terms_page) . '">Terms & Conditions</a></li>';
                    if ($privacy_page) echo '<li><a href="' . get_permalink($privacy_page) . '">Privacy Policy</a></li>';
                    ?>
                </ul>
            </nav>
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>

</html>