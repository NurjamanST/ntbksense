<?php
// Variabel didefinisikan di ntbksense-maintenance.php, kita hanya akan menggunakannya di sini.
global $geo_ip_result; 

// Logika untuk Info Perangkat
$device_info = [
    'os'      => 'N/A',
    'browser' => 'N/A',
    'device'  => 'N/A'
];
$current_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? esc_attr($_SERVER['HTTP_USER_AGENT']) : 'N/A';

if ($geo_ip_result) {
    $user_agent_input = sanitize_textarea_field( $_POST['user_agent'] ?? $current_user_agent );
    if ( ! empty($user_agent_input) ) {
        // Deteksi OS
        if (preg_match('/windows/i', $user_agent_input)) $device_info['os'] = 'Windows';
        elseif (preg_match('/macintosh|mac os x/i', $user_agent_input)) $device_info['os'] = 'Mac OS';
        elseif (preg_match('/linux/i', $user_agent_input)) $device_info['os'] = 'Linux';
        elseif (preg_match('/android/i', $user_agent_input)) $device_info['os'] = 'Android';
        elseif (preg_match('/iphone|ipad|ipod/i', $user_agent_input)) $device_info['os'] = 'iOS';
        
        // Deteksi Browser
        if (preg_match('/chrome/i', $user_agent_input) && !preg_match('/edge/i', $user_agent_input)) $device_info['browser'] = 'Chrome';
        elseif (preg_match('/firefox/i', $user_agent_input)) $device_info['browser'] = 'Firefox';
        elseif (preg_match('/safari/i', $user_agent_input) && !preg_match('/chrome/i', $user_agent_input)) $device_info['browser'] = 'Safari';
        elseif (preg_match('/edge/i', $user_agent_input)) $device_info['browser'] = 'Edge';
        elseif (preg_match('/opera|opr/i', $user_agent_input)) $device_info['browser'] = 'Opera';

        // Deteksi Perangkat
        if (preg_match('/mobi/i', $user_agent_input)) {
            $device_info['device'] = 'Mobile';
        } else {
            $device_info['device'] = 'Desktop';
        }
    }
}
?>

<!-- Box Tes GEO IP -->
<div class="ntb-main-content">
    <h2 class="ntb-main-title"><span class="dashicons dashicons-location"></span> Tes GEO IP</h2>
    <hr>
    <div class="ntb-card">
        <div class="ntb-card-body">
            <!-- Form Input -->
            <form method="POST" action="#ntb-geo-ip-results">
                <?php wp_nonce_field( 'ntbksense_test_ip_action', 'ntbksense_test_ip_nonce' ); ?>
                
                <div class="ntb-form-row">
                    <!-- IP Address Input -->
                    <div class="ntb-form-group" style="flex: 1;">
                        <label for="ip_address"><strong>IP Address</strong></label>
                        <input type="text" id="ip_address" name="ip_address" class="regular-text" placeholder="Kosongkan untuk IP Anda">
                    </div>
                    
                    <!-- User Agent Input -->
                    <div class="ntb-form-group" style="flex: 2;">
                        <label for="user_agent"><strong>User Agent</strong></label>
                        <input type="text" id="user_agent" name="user_agent" class="large-text" value="<?php echo $current_user_agent; ?>">
                    </div>

                    <!-- Tombol Submit -->
                    <div class="ntb-form-group">
                        <label>&nbsp;</label> <!-- Label kosong untuk alignment -->
                        <?php submit_button('Tes Sekarang', 'primary', 'submit_geo_ip_test', false); ?>
                    </div>
                </div>
            </form>

            <!-- Hasil -->
            <?php if ($geo_ip_result): ?>
            <div id="ntb-geo-ip-results" class="ntb-results-wrapper">
                <hr>
                <div class="ntb-results-container">
                    <!-- Info Database GEO -->
                    <div class="ntb-result-column">
                        <h4 class="ntb-result-title"><span class="dashicons dashicons-location-alt"></span> Database GEO</h4>
                        <?php if (isset($geo_ip_result['status']) && $geo_ip_result['status'] === 'success'): ?>
                            <table class="ntb-info-table">
                                <tbody>
                                    <tr>
                                        <th>Negara</th>
                                        <td>: <?php echo esc_html( $geo_ip_result['country'] ?? 'N/A' ); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kota</th>
                                        <td>: <?php echo esc_html( $geo_ip_result['city'] ?? 'N/A' ); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Benua</th>
                                        <td>: <?php echo esc_html( $geo_ip_result['continent'] ?? 'N/A' ); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Wilayah</th>
                                        <td>: <?php echo esc_html( $geo_ip_result['regionName'] ?? 'N/A' ); ?></td>
                                    </tr>
                                    <tr>
                                        <th>ISP</th>
                                        <td>: <?php echo esc_html( $geo_ip_result['isp'] ?? 'N/A' ); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p><em><?php echo esc_html( $geo_ip_result['message'] ?? 'Data tidak ditemukan.' ); ?></em></p>
                        <?php endif; ?>
                    </div>

                    <!-- Pemisah Vertikal -->
                    <div class="ntb-results-separator"></div>

                    <!-- Info Perangkat -->
                    <div class="ntb-result-column">
                        <h4 class="ntb-result-title"><span class="dashicons dashicons-desktop"></span> Info Perangkat</h4>
                        <table class="ntb-info-table">
                            <tbody>
                                <tr>
                                    <th>Sistem Operasi</th>
                                    <td>: <?php echo esc_html($device_info['os']); ?></td>
                                </tr>
                                <tr>
                                    <th>Browser</th>
                                    <td>: <?php echo esc_html($device_info['browser']); ?></td>
                                </tr>
                                <tr>
                                    <th>Perangkat</th>
                                    <td>: <?php echo esc_html($device_info['device']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>