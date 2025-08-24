<!-- Box Tes GEO IP -->
<div class="ntb-main-content">
    <h2 class="ntb-main-title"><span class="dashicons dashicons-location"></span> Tes GEO IP</h2>
    <hr>
    <div class="ntb-card">
        <div class="ntb-card-body">
            <!-- Form Input -->
            <form action="#ntb-geo-ip-results">
                <div class="ntb-form-row">
                    <!-- IP Address Input -->
                    <div class="ntb-form-group" style="flex: 1;">
                        <label for="ip_address"><strong>IP Address</strong></label>
                        <input type="text" id="ip_address" name="ip_address" class="regular-text" value="103.17.177.1">
                    </div>
                    
                    <!-- User Agent Input -->
                    <div class="ntb-form-group" style="flex: 2;">
                        <label for="user_agent"><strong>User Agent</strong></label>
                        <input type="text" id="user_agent" name="user_agent" class="large-text" value="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36">
                    </div>

                    <!-- Tombol Submit -->
                    <div class="ntb-form-group">
                        <label>&nbsp;</label> <!-- Label kosong untuk alignment -->
                        <button type="submit" class="button button-primary">Tes Sekarang</button>
                    </div>
                </div>
            </form>

            <!-- Hasil -->
            <div id="ntb-geo-ip-results" class="ntb-results-wrapper">
                <hr>
                <div class="ntb-results-container">
                    <!-- Info Database GEO -->
                    <div class="ntb-result-column">
                        <h4 class="ntb-result-title"><span class="dashicons dashicons-location-alt"></span> Database GEO</h4>
                        <div id="geo-editor-container" class="monaco-editor-container"></div>
                    </div>

                    <!-- Pemisah Vertikal -->
                    <div class="ntb-results-separator"></div>

                    <!-- Info Perangkat -->
                    <div class="ntb-result-column">
                        <h4 class="ntb-result-title"><span class="dashicons dashicons-desktop"></span> Info Perangkat</h4>
                        <div id="device-editor-container" class="monaco-editor-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monaco Editor Loader & Initializer -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.33.0/min/vs/loader.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Contoh Data JSON Sesuai Gambar ---
        const geoIpData = {
            "country": {
                "continent": "AS",
                "continent_name": "Asia",
                "country": "ID",
                "country_name": "Indonesia"
            },
            "asn": {
                "asn": "AS23693",
                "domain": "telkomsel.com",
                "name": "PT. Telekomunikasi Selular"
            }
        };

        const deviceInfoData = {
            "is_bot": false,
            "bot_info": null,
            "client": {
                "type": "browser",
                "name": "Chrome",
                "short_name": "CH",
                "version": "138.0.0.0",
                "engine": "Blink",
                "engine_version": "138.0.0.0",
                "family": "Chrome"
            },
            "os": {
                "name": "Windows",
                "short_name": "WIN",
                "version": "10",
                "platform": "x64",
                "family": "Windows"
            },
            "device": "desktop",
            "brand": "",
            "model": "",
            "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36",
            "is_smartphone": false,
            "is_feature_phone": false,
            "is_tablet": false,
            "is_phablet": false,
            "is_console": false,
            "is_portable_media": false,
            "is_car_browser": false,
            "is_tv": false,
            "is_smart_display": false,
            "is_smart_speaker": false,
            "is_camera": false,
            "is_wearable": false,
            "is_peripheral": false,
            "is_browser": true,
            "is_feed_reader": false,
            "is_mobile_app": false,
            "is_pim": false,
            "is_library": false,
            "is_media_player": false,
            "is_facebook_browser": false
        };
        // --- Akhir Contoh Data JSON ---

        require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.33.0/min/vs' }});
        
        require(['vs/editor/editor.main'], function() {
            // Inisialisasi Editor untuk Database GEO
            monaco.editor.create(document.getElementById('geo-editor-container'), {
                value: JSON.stringify(geoIpData, null, 4), // Menggunakan data contoh baru
                language: 'json',
                theme: 'vs-dark',
                readOnly: true,
                automaticLayout: true,
                minimap: { enabled: false }
            });

            // Inisialisasi Editor untuk Info Perangkat
            monaco.editor.create(document.getElementById('device-editor-container'), {
                value: JSON.stringify(deviceInfoData, null, 4),
                language: 'json',
                theme: 'vs-dark',
                readOnly: true,
                automaticLayout: true,
                minimap: { enabled: false }
            });
        });
    });
</script>