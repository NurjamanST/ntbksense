<?php
    // 1. Ambil data string dari database, misal: "20-25".
    $auto_refresh = $data['timer_auto_refresh'] ?? '5-15';

    // 2. Pisahkan string jadi array berdasarkan tanda '-'.
    $range_parts = explode('-', $auto_refresh);

    // 3. Tentukan nilai min dan max, kasih default jika datanya salah.
    $min_refresh = isset($range_parts[0]) ? (int)$range_parts[0] : 5;
    $max_refresh = isset($range_parts[1]) ? (int)$range_parts[1] : 15;
?>