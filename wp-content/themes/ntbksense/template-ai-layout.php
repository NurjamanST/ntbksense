<?php
/**
 * Template Name: AI Full-Width Layout
 * Template Post Type: post
 */

// Memulai loop WordPress untuk mengambil konten
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        // Langsung cetak konten mentah dari database tanpa filter apa pun
        // Ini memastikan layout dari AI tampil 100% utuh
        echo get_the_content();
    endwhile;
endif;
?>