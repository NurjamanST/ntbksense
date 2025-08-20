<?php
// =========================================================================
// FILE: ntbksense/admin/views/Beranda/add_action_beranda.php
// =========================================================================

    // Menambahkan JavaScript khusus untuk halaman Beranda
    add_action('admin_footer', function() {
        // Hanya jalankan skrip di halaman beranda
        $screen = get_current_screen();
        if ('toplevel_page_ntbksense' !== $screen->id) {
            return;
        }
    ?>
    <script>
        jQuery(document).ready(function($) {
            const apiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/beranda/dashboard.php'); ?>';

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const stats = data.data.stats;
                        const recentLps = data.data.recent_lps;

                        // Update kartu statistik
                        $('#total-lp-value').text(stats.total_lp);
                        $('#active-lp-value').text(stats.active_lp);
                        // Anda bisa update placeholder lain di sini jika API sudah mendukungnya
                        // $('#total-autopost-value').text(stats.total_autopost);
                        // $('#total-laporan-value').text(stats.total_laporan);

                        // Update tabel aktivitas terbaru
                        const tbody = $('#recent-lps-tbody');
                        tbody.empty(); // Kosongkan pesan "Memuat data..."

                        if (recentLps.length > 0) {
                            recentLps.forEach(lp => {
                                const statusClass = lp.status === '1' ? 'ntb-status-active' : 'ntb-status-inactive';
                                const statusText = lp.status === '1' ? 'Aktif' : 'Tidak Aktif';
                                const editUrl = `<?php echo esc_url(admin_url('admin.php?page=ntbksense-edit-lp&id=')); ?>${lp.id}`;
                                
                                const row = `
                                    <tr>
                                        <td><strong><a href="${editUrl}">${lp.title || '(Tanpa Judul)'}</a></strong><br><small>/${lp.slug}</small></td>
                                        <td><span class="${statusClass}">${statusText}</span></td>
                                        <td>${lp.created_at}</td>
                                        <td><a href="${editUrl}" class="button button-small">Edit</a></td>
                                    </tr>
                                `;
                                tbody.append(row);
                            });
                        } else {
                            tbody.append('<tr><td colspan="4">Belum ada aktivitas.</td></tr>');
                        }
                    } else {
                        console.error('Gagal mengambil data dasbor:', data.data.message);
                        $('#recent-lps-tbody').find('td').text('Gagal memuat data.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#recent-lps-tbody').find('td').text('Terjadi kesalahan jaringan.');
                });

            // --- Tab Functionality ---
            $('.ntb-tab-item').on('click', function(e) {
                e.preventDefault();
                
                // Hapus kelas aktif dari semua tab dan panel
                $('.ntb-tab-item').removeClass('active');
                $('.ntb-tab-pane').removeClass('active');
                
                // Tambahkan kelas aktif ke tab yang diklik
                $(this).addClass('active');
                
                // Tampilkan panel yang sesuai
                const tabId = $(this).data('tab');
                $('#' + tabId).addClass('active');
            });
        });
    </script>
    <?php
    });
?>