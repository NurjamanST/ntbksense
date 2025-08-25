<!-- Box Tes GEO IP -->
<div class="ntb-main-content">
    <h2 class="ntb-main-title"><span class="dashicons dashicons-location"></span> Tes GEO IP</h2>
    <hr>
    <div class="ntb-card">
        <div class="ntb-card-body">
            <!-- Form Input -->
            <form action="#ntb-geo-ip-results" data-geoip-form>
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
                
                Negara: <span class="country">-</span> • Region: <span class="region">-</span> • Kota: <span class="city">-</span> • ISP: <span class="isp">-</span> • Koordinat: <span class="lat">-</span>, <span class="lon">-</span>      
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



