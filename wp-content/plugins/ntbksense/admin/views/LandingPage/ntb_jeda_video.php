<?php
    // 1. Ambil data string dari database, misal: "20-25".
    $pause_range_str = $data['timer_auto_pause_video'] ?? '5-15';

    // 2. Pisahkan string jadi array berdasarkan tanda '-'.
    $range_parts = explode('-', $pause_range_str);

    // 3. Tentukan nilai min dan max, kasih default jika datanya salah.
    $min_value = isset($range_parts[0]) ? (int)$range_parts[0] : 5;
    $max_value = isset($range_parts[1]) ? (int)$range_parts[1] : 15;

?>