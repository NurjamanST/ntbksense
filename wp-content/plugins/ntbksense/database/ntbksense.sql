-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 25 Agu 2025 pada 10.39
-- Versi server: 8.0.30
-- Versi PHP: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ntbksense`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `wp_ntbk_landings`
--

CREATE TABLE `wp_ntbk_landings` (
  `id` int NOT NULL,
  `slug` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `title_option` varchar(10) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `inject_keywords` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description_option` varchar(10) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `template_file` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `template_name` varchar(225) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `random_template_method` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'random',
  `random_template_file` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `random_template_file_afs` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_urls` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `redirect_post_option` varchar(10) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `timer_auto_refresh` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0-0',
  `auto_refresh_option` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `protect_elementor` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'off',
  `referrer_option` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `device_view` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `videos_floating_option` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `timer_auto_pause_video` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0-0',
  `video_urls` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `universal_image_urls` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `landing_image_urls` longtext COLLATE utf8mb4_unicode_520_ci,
  `number_images_displayed` int NOT NULL DEFAULT '10',
  `custom_html` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `parameter_key` varchar(225) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `parameter_value` varchar(250) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cloaking_url` longtext COLLATE utf8mb4_unicode_520_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `custom_template_builder` varchar(250) COLLATE utf8mb4_unicode_520_ci DEFAULT '[]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `wp_ntbk_logs`
--

CREATE TABLE `wp_ntbk_logs` (
  `id` int NOT NULL,
  `slug` varchar(225) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `status` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `ip_address` varchar(225) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `isp` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `asn` varchar(225) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `os_name` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `device` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `brand` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `model` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `browser_name` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `request_uri` text COLLATE utf8mb4_unicode_520_ci,
  `destination` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `referrer` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_agent` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `show_ads` int NOT NULL DEFAULT '0',
  `visit_duration` int NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `wp_ntbk_template_builder`
--

CREATE TABLE `wp_ntbk_template_builder` (
  `id` int NOT NULL,
  `name` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `icon_url` varchar(500) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `status` varchar(25) COLLATE utf8mb4_unicode_520_ci DEFAULT 'draft',
  `bootstrap_version` varchar(5) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `html` longtext COLLATE utf8mb4_unicode_520_ci,
  `floating_video` varchar(25) COLLATE utf8mb4_unicode_520_ci DEFAULT 'no',
  `jquery` varchar(25) COLLATE utf8mb4_unicode_520_ci DEFAULT 'no',
  `font_awesome` varchar(25) COLLATE utf8mb4_unicode_520_ci DEFAULT 'no',
  `custom_css` longtext COLLATE utf8mb4_unicode_520_ci,
  `custom_js` longtext COLLATE utf8mb4_unicode_520_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `wp_ntbk_ads_settings`
--

CREATE TABLE `wp_ntbk_ads_settings` (
  `id` int NOT NULL,
  `id_publisher` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `opacity` int DEFAULT '100',
  `jumlah_iklan` int DEFAULT '5',
  `mode_tampilan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Berurutan',
  `posisi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Fixed',
  `margin_top` int DEFAULT '5',
  `margin_bottom` int DEFAULT '5',
  `kode_iklan_1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `kode_iklan_2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `kode_iklan_3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `kode_iklan_4` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `kode_iklan_5` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `refresh_blank` int DEFAULT '0',
  `sembunyikan_vinyet` tinyint(1) DEFAULT '0',
  `sembunyikan_anchor` tinyint(1) DEFAULT '0',
  `sembunyikan_elemen_blank` tinyint(1) DEFAULT '0',
  `tampilkan_close_ads` tinyint(1) DEFAULT '0',
  `auto_scroll` tinyint(1) DEFAULT '0',
  `jenis_pengalihan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '302 Temporary - PHP',
  `akses_lokasi_mode` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'off',
  `akses_lokasi_countries` text COLLATE utf8mb4_general_ci,
  `blokir_ip_address` text COLLATE utf8mb4_general_ci,
  `akses_asn_mode` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'off',
  `akses_asn_list` text COLLATE utf8mb4_general_ci,
  `detektor_perangkat_tinggi` tinyint(1) DEFAULT '0',
  `detektor_perangkat_sedang` tinyint(1) DEFAULT '0',
  `detektor_perangkat_rendah` tinyint(1) DEFAULT '0',
  `simpan_log` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Aktifkan',
  `simpan_durasi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Tidak (Default)',
  `interval_durasi` int DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `wp_ntbk_landings`
--
ALTER TABLE `wp_ntbk_landings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `wp_ntbk_logs`
--
ALTER TABLE `wp_ntbk_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `wp_ntbk_template_builder`
--
ALTER TABLE `wp_ntbk_template_builder`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `wp_ntbk_ads_settings`
--
ALTER TABLE `wp_ntbk_ads_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `wp_ntbk_landings`
--
ALTER TABLE `wp_ntbk_landings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `wp_ntbk_logs`
--
ALTER TABLE `wp_ntbk_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `wp_ntbk_template_builder`
--
ALTER TABLE `wp_ntbk_template_builder`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `wp_ntbk_ads_settings`
--
ALTER TABLE `wp_ntbk_ads_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
