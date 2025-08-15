<?php
// =========================================================================
// FILE: ntbksense/admin/views/Settings/countries.php
// FUNGSI: Menampilkan daftar negara untuk dropdown Select2.
// =========================================================================

if (isset($countries) && is_array($countries)) {
    foreach ($countries as $country) {
        $is_selected = (is_array($selected_countries) && in_array($country, $selected_countries)) ? 'selected' : '';
        echo '<option value="' . esc_attr($country) . '" ' . $is_selected . '>' . esc_html($country) . '</option>';
    }
}
?>
