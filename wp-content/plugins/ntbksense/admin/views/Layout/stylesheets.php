<?php
// =========================================================================
// FILE: ntbksense\admin\views\Layout\stylesheets.php
// FUNGSI: Menyimpan semua CSS.
// VERSI REFAKTOR: Menggabungkan semua style, menghilangkan duplikasi,
// dan menggunakan variabel CSS untuk konsistensi.
// =========================================================================
?>
<style>
    /* ============================================= */
    /* 1. Variabel Global (CSS Custom Properties)
    /* ============================================= */
        :root {
            --primary-color: #007bff;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --text-dark: #1d2327;
            --text-medium: #50575e;
            --text-light: #646970;
            --border-color-strong: #c3c4c7;
            --border-color-soft: #e0e0e0;
            --bg-white: #ffffff;
            --bg-light-gray: #ffffffff;
            --bg-medium-gray: #f6f7f7;
            --wp-link-color: #0073aa;
            --border-radius-lg: 10px;
            --border-radius-sm: 4px;
            --box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        }

    /* ============================================= */
    /* 2. Layout & Struktur Umum
    /* ============================================= */
        #ntb-lp-builder {
            background-color: var(--bg-white);
            padding: 0;
            margin: 0px 0px 0px -20px;
            /* Menyesuaikan margin admin WordPress */
        }

        .ntb-main-content {
            background: var(--bg-white);
            border: 1px solid var(--border-color-strong);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow);
            padding: 24px;
            margin: 9px;
        }

        .ntb-main-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-dark);
        }

    /* ============================================= */
    /* 3. Komponen UI
    /* ============================================= */

    /* ----- Navbar ----- */
        .ntb-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: var(--bg-white);
            border-bottom: 1px solid var(--border-color-strong);
        }

        .ntb-navbar-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .ntb-navbar-version {
            font-size: 12px;
            color: var(--text-light);
            margin-left: 8px;
            background-color: var(--bg-light-gray);
            padding: 2px 6px;
            border-radius: var(--border-radius-sm);
        }

        .ntb-navbar-right .ntb-navbar-icon {
            color: var(--text-medium);
            text-decoration: none;
            margin-left: 15px;
        }

        .ntb-navbar-right .ntb-navbar-icon .dashicons {
            font-size: 20px;
            vertical-align: middle;
        }

    /* ----- Breadcrumb ----- */
        .ntb-breadcrumb {
            padding: 15px 20px;
            color: var(--text-medium);
            font-size: 14px;
            border-bottom: 1px solid var(--border-color-soft);
        }

        .ntb-breadcrumb a {
            text-decoration: none;
            color: var(--wp-link-color);
        }

        .ntb-breadcrumb a:hover {
            text-decoration: underline;
        }

        .ntb-breadcrumb span {
            color: var(--text-dark);
            font-weight: 600;
        }

    /* ----- Cards ----- */
        .ntb-card {
            background: var(--bg-white);
            border: 1px solid var(--border-color-strong);
            box-shadow: var(--box-shadow);
        }

        .ntb-card-header {
            padding: 10px 15px;
            border-bottom: 1px solid var(--border-color-strong);
            background-color: var(--bg-medium-gray);
        }

        .ntb-card-header h5 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .ntb-card-body {
            padding: 20px;
        }

        .ntb-logs-body {
            height: 1050px;
            overflow-y: auto;
        }
        

    /* ----- Form & Input ----- */
        .ntb-form-container {
            background: var(--bg-white);
            border-radius: 20px;
            padding: 0;
            margin-top: 10px;
        }

        .ntb-form-group {
            margin-bottom: 1rem;
        }

        .ntb-form-group label,
        .form-label {
            margin-bottom: 0.5rem;
            display: block;
            font-size: 12px;
            font-weight: 600;
        }

        .ntb-form-footer {
            margin-top: 20px;
            padding: 15px 20px;
            background: var(--bg-white);
            border-top: 1px solid var(--border-color-strong);
            text-align: right;
        }

        .url-lp-input-group,
        .ntb-param-group {
            display: flex;
            align-items: center;
            gap: 2px;
            background-color: var(--bg-medium-gray);
            border: 1px solid var(--border-color-soft);
            border-radius: 6px;
            padding: 2px;
            height: 32px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .05);
        }

        .url-lp-input,
        .ntb-param-input {
            flex-grow: 1;
            border: none;
            background-color: transparent;
            padding: 4px 8px;
            font-size: 13px;
            color: var(--text-dark);
            outline: none;
        }

        .url-lp-action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: var(--border-radius-sm);
            transition: background-color 0.2s, color 0.2s;
        }

        .url-lp-action-btn svg {
            width: 16px;
            height: 16px;
            color: var(--text-light);
        }

        .url-lp-action-btn:hover {
            background-color: #e0e0e0;
        }

    /* Form Sections & Fields */
        .ntb-settings-section {
            padding: 20px;
            border: 1px solid #e5e5e5;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .ntb-settings-section:last-child {
            margin-bottom: 0;
        }

        .ntb-settings-section .section-title {
            font-size: 14px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .ntb-settings-box {
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 4px;
            height: 100%;
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
        }

        .form-control,
        .form-select {
            font-size: 13px;
        }
        .form-text a {
            text-decoration: none;
        }
        .ntb-feature-list {
            list-style: none;
            padding-left: 0;
            font-size: 13px;
        }
        .ntb-feature-list li {
            margin-bottom: 5px;
        }
        .ntb-feature-list .dashicons {
            vertical-align: middle;
            margin-right: 5px;
        }

    /* ----- Tombol (Buttons) & Aksi ----- */
        .ntb-actions-bar {
            margin-bottom: 20px;
        }

        .ntb-actions-bar .button {
            margin-right: 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .ntb-header-btn .dashicons {
            margin-right: 5px;
            font-size: 16px;
            vertical-align: text-top;
        }

    /* Field Header Styling */
        .ntb-field-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .ntb-field-header label {
            margin-bottom: 0; /* Remove bottom margin from label inside header */
        }
        

    /* CATATAN: Penggunaan !important sebaiknya dihindari.
       Ini mungkin diperlukan untuk menimpa style default WordPress,
       namun jika memungkinkan, gunakan selector yang lebih spesifik. */
        .ntb-btn-primary {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
        }

        .ntb-btn-danger {
            background: var(--danger-color) !important;
            border-color: var(--danger-color) !important;
            color: white !important;
        }

        .ntb-btn-success {
            background: var(--success-color) !important;
            border-color: var(--success-color) !important;
            color: white !important;
        }

        .ntb-btn-warning {
            background: var(--warning-color) !important;
            border-color: var(--warning-color) !important;
            color: var(--text-dark) !important;
        }

    /* ----- Toggle Switch ----- */
        .ntb-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .ntb-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

    /* ----- Slider ----- */
        .ntb-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        .ntb-range-slider {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .ntb-range-slider .slider-track {
            margin: 10px 0;
        }
        .ntb-range-slider .ui-slider {
            background: #e9e9e9;
            border: none;
            height: 6px;
        }
        .ntb-range-slider .ui-slider-range {
            background: #007bff;
        }
        .ntb-range-slider .ui-slider-handle {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #007bff;
            cursor: pointer;
            top: -6px;
        }
        .ntb-range-slider .slider-inputs {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        .ntb-range-slider .range-value-min, .ntb-range-slider .range-value-max {
            width: 60px;
            text-align: center;
        }
        .ntb-range-slider .range-separator {
            font-weight: bold;
        }
        .ntb-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.ntb-slider {
            background-color: var(--primary-color);
        }

        input:focus+.ntb-slider {
            box-shadow: 0 0 1px var(--primary-color);
        }

        input:checked+.ntb-slider:before {
            transform: translateX(20px);
        }


    /* ----- DataTables & Tabel Umum ----- */
        .dataTables_wrapper {
            margin-top: 15px;
        }

        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 15px;
        }

        .dataTables_length select {
            padding: 8px 12px;
            font-size: 14px;
            height: auto;
            width: 5vw;
            margin: 0 10px;
            border-radius: var(--border-radius-sm);
            border-color: #8c8f94;
        }

        .dataTables_filter input[type="search"] {
            border-radius: 3px;
            border-color: #8c8f94;
        }

        #ntb-landing-page-table {
            border-collapse: collapse !important;
            border: 1px solid var(--border-color-strong);
        }

        #ntb-landing-page-table th,
        #ntb-landing-page-table td {
            padding: 8px 10px;
            border-bottom: 1px solid var(--border-color-soft);
        }

        #ntb-landing-page-table thead th {
            background-color: var(--bg-medium-gray);
            font-weight: 600;
            white-space: nowrap;
        }

    /* ----- Notifikasi ----- */
        #ntb-copy-notification {
            visibility: hidden;
            min-width: 250px;
            background-color: var(--text-dark);
            color: var(--bg-white);
            text-align: center;
            border-radius: 5px;
            padding: 16px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.5s, visibility 0.5s;
        }

        #ntb-copy-notification.show {
            visibility: visible;
            opacity: 1;
        }

    /* ----- Repeater Fields ----- */
        .ntb-add-repeater {
            text-decoration: none;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

    /* Override for a smaller, cleaner toggle switch specifically within the optimization tab */
        #optimasi .ntb-switch {
            width: 44px;
            height: 24px;
        }
        #optimasi .ntb-switch .ntb-slider:before {
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
        }
        #optimasi .ntb-switch input:checked + .ntb-slider:before {
            transform: translateX(20px);
        }
    /* Mobile Preview */
        .ntb-preview-container {
            position: sticky;
            top: 50px;
        }

        .ntb-mobile-preview {
            width: 300px;
            /* Lebar iPhone 13 Mini */
            height: 615px;
            /* Tinggi iPhone 13 Mini */
            background: #fff;
            border: 8px solid #111;
            border-radius: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }

        .ntb-mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 20px;
            color: #111;
            position: relative;
            background-color: #fff;
            border-top-left-radius: 32px;
            border-top-right-radius: 32px;
        }

        .ntb-mobile-header .time {
            font-weight: 600;
            font-size: 14px;
        }

        .ntb-mobile-header .notch {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 25px;
            background: #111;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .ntb-mobile-header .status-icons {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
        }

        .ntb-mobile-screen {
            background: #000;
            flex-grow: 1;
        }

        .ntb-mobile-footer {
            background-color: #f0f0f1;
            padding: 8px 12px 15px 12px;
            border-bottom-left-radius: 32px;
            border-bottom-right-radius: 32px;
        }

        .ntb-address-bar {
            background-color: #e1e1e1;
            border-radius: 8px;
            padding: 5px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
        }

        .ntb-address-bar .url-text {
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .ntb-address-bar .url-text .dashicons {
            font-size: 14px;
        }

        .ntb-nav-buttons {
            display: flex;
            justify-content: space-around;
            padding-top: 15px;
            color: #007aff;
            font-size: 22px;
        }

        .ntb-nav-buttons .dashicons {
            font-size: 24px;
            height: auto;
            width: auto;
        }

        .ntb-nav-buttons .bi-box-arrow-up {
            font-size: 20px;
        }


    /* ----- Form Footer ----- */
        .ntb-form-footer {
            margin-top: 20px;
            padding: 20px 20px;
            background: var(--bg-white);
            border-top: 1px solid var(--border-color-strong);
            text-align: right;
        }
        .ntb-form-footer button {
            padding: 10px 20px;
            border-radius: var(--border-radius-sm);
            font-size: 14px;
            font-weight: 600;
        }
        .ntb-form-footer a {
            margin-right: 10px;
            text-decoration: none;
            color: var(--text-dark);
        }
        .ntb-form-footer a:hover {
            color: var(--primary-color);
        }
        .ntb-form-footer button.button-primary {
            background-color: var(--primary-color);
            color: var(--bg-white);
            border: none;
        }
        .ntb-form-footer span.dashicons {
            font-size: 16px;
            vertical-align: middle;

            margin-right: 5px;
        }
    /* URL LP Input Group */
        .url-lp-input-group {
            display: flex;
            align-items: center;
            gap: 2px;
            /* Jarak lebih rapat */
            background-color: #f6f7f7;
            border: 1px solid #e2e4e7;
            border-radius: 6px;
            padding: 2px;
            /* Padding lebih kecil */
            height: 32px;
            /* Tinggi konsisten */
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .05);
        }
        .url-lp-input {
            flex-grow: 1;
            border: none;
            background-color: transparent;
            padding: 4px 8px;
            font-size: 13px;
            color: #3c434a;
            outline: none;
            cursor: text;
        }
        .url-lp-input:focus {
            outline: none;
        }
        .url-lp-action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            /* Ukuran tombol ikon */
            height: 28px;
            border-radius: 4px;
            transition: background-color 0.2s, color 0.2s;
        }
        .url-lp-action-btn svg {
            width: 16px;
            height: 16px;
            color: #646970;
        }
        .url-lp-action-btn:hover {
            background-color: #e0e0e0;
            color: #007bff;
        }
        .url-lp-action-btn.copydanger-btn svg {
            color: #dc3545;
        }
        .url-lp-action-btn.copydanger-btn:hover {
            background-color: #f8d7da;
            color: #dc3545;
        }
        
    /* Parameter Input Group */
        .ntb-param-group {
            display: flex;
            align-items: center;
            gap: 2px;
            background-color: #f6f7f7;
            border: 1px solid #cdd0d4;
            /* Sedikit lebih gelap agar cocok dengan gambar */
            border-radius: 6px;
            padding: 2px;
            height: 32px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .05);
        }
        .ntb-param-input {
            flex-grow: 1;
            border: none;
            background-color: transparent;
            padding: 4px 8px;
            font-size: 13px;
            color: #3c434a;
            outline: none;
            cursor: text;
        }


        .ntb-param-copy-btn {
            background: #ffffff;
            /* Latar belakang putih seperti di gambar */
            border: 1px solid #cdd0d4;
            /* Border tipis di sekitar tombol */
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }


        .ntb-param-copy-btn svg {
            width: 16px;
            height: 16px;
            color: #50575e;
            /* Warna ikon sedikit lebih gelap */
        }


        .ntb-param-copy-btn:hover {
            background-color: #f0f0f1;
        }
    /* Form Actions */
        .ntb-form-actions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    /* License Table */
        .ntb-license-table {
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }
        .ntb-license-row {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
        }
        .ntb-license-row:last-child {
            border-bottom: none;
        }
        .ntb-license-label {
            font-weight: 600;
            padding: 12px 15px;
            width: 200px;
            background-color: #f9f9f9;
            border-right: 1px solid #e0e0e0;
        }
        .ntb-license-value {
            padding: 12px 15px;
            flex-grow: 1;
            font-family: monospace;
        }
        .ntb-license-value a {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            text-decoration: none;
        }
</style>    