<?php
// =========================================================================
// FILE: ntbksense/admin/views/Template/ntbksense-edit-tb.php
// =========================================================================

// Ambil ID template dari URL
$template_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Jika tidak ada ID, tampilkan pesan error dan hentikan eksekusi
if ($template_id === 0) {
    echo '<div class="wrap"><h1>Error: Template ID tidak ditemukan.</h1><p><a href="' . esc_url(admin_url('admin.php?page=ntbksense-template-builder')) . '">Kembali ke Template Builder</a></p></div>';
    return;
}

// Muat aset (CSS & JS) yang diperlukan untuk halaman ini
add_action('admin_footer', function() {
    // Pastikan skrip hanya dimuat di halaman yang benar
    $screen = get_current_screen();
    if ('admin_page_ntbksense-edit-tb' !== $screen->id) {
        return;
    }

    // Muat library CodeMirror untuk editor kode
    wp_enqueue_style('codemirror-css', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css');
    wp_enqueue_script('codemirror-js', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js', [], false, true);
    wp_enqueue_script('codemirror-html', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js', ['codemirror-js'], false, true);
    ?>
    <style>
        .ntb-editor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .ntb-editor-header .ntb-form-group {
            flex-grow: 1;
            margin-bottom: 0;
        }
        #ntb-template-name {
            font-size: 1.7em;
            padding: 8px;
        }
        .ntb-editor-actions {
            display: flex;
            gap: 10px;
        }
        .CodeMirror {
            border: 1px solid #ddd;
            height: 65vh; /* Membuat editor lebih tinggi */
        }
    </style>
    <script>
    jQuery(document).ready(function($) {
        const apiUrl = '<?php echo esc_url(NTBKSENSE_PLUGIN_URL . 'api/template_builder/'); ?>';
        const templateId = $('#ntb-template-id').val();
        let currentStatus = 'draft'; // Status default

        // 1. Inisialisasi CodeMirror Editor
        const editor = CodeMirror.fromTextArea(document.getElementById('ntb-template-content'), {
            lineNumbers: true,
            mode: 'htmlmixed',
            theme: 'default'
        });

        // 2. Fungsi untuk memuat data template dari API
        function loadTemplateData() {
            fetch(`${apiUrl}read.php?id=${templateId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#ntb-template-name').val(data.data.name);
                        editor.setValue(data.data.html || '<!-- Konten template masih kosong -->');
                        currentStatus = data.data.status;
                        updateStatusButton();
                        setTimeout(() => editor.refresh(), 1); // Refresh editor setelah modal terlihat
                    } else {
                        alert('Error: ' + data.data.message);
                        $('#ntb-template-name').val('Gagal memuat data').prop('disabled', true);
                        editor.setValue('Gagal memuat konten.');
                    }
                });
        }
        
        // 3. Fungsi untuk memperbarui teks dan warna tombol status
        function updateStatusButton() {
            const $button = $('#ntb-update-status-btn');
            if (currentStatus === 'published') {
                $button.text('Jadikan Draft').removeClass('ntb-btn-success').addClass('ntb-btn-warning');
            } else {
                $button.text('Publikasikan').removeClass('ntb-btn-warning').addClass('ntb-btn-success');
            }
        }

        // 4. Event handler untuk menyimpan perubahan (nama dan konten)
        $('#ntb-edit-template-form').on('submit', function(e) {
            e.preventDefault();
            
            const $button = $('#ntb-save-template-btn');
            const originalText = $button.html();
            $button.html('<span class="spinner is-active" style="float: left; margin-top: 4px;"></span> Menyimpan...');
            $button.prop('disabled', true);

            const payload = {
                id: templateId,
                name: $('#ntb-template-name').val(),
                html: editor.getValue()
            };

            fetch(apiUrl + 'update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.data.message);
                } else {
                    alert('Gagal: ' + data.data.message);
                }
            })
            .finally(() => {
                $button.html(originalText).prop('disabled', false);
            });
        });
        
        // 5. Event handler untuk mengubah status (publish/draft)
        $('#ntb-update-status-btn').on('click', function(e) {
             e.preventDefault();
             
            const newStatus = (currentStatus === 'published') ? 'draft' : 'published';
            
            const payload = {
                id: templateId,
                name: $('#ntb-template-name').val(), // Menambahkan nama
                status: newStatus
            };
            
            fetch(apiUrl + 'update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Template berhasil diubah menjadi: ${newStatus}`);
                    currentStatus = newStatus;
                    updateStatusButton();
                } else {
                    alert('Gagal mengubah status: ' + data.data.message);
                }
            });
        });

        // Panggil fungsi untuk memuat data saat halaman siap
        loadTemplateData();
    });
    </script>
    <?php
});
?>

<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/navbar.php"; ?>

    <!-- Breadcrumb -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt;
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-template-builder')); ?>">Template Builder</a> &gt;
        <span>Edit Template #<?php echo esc_html($template_id); ?></span>
    </div>

    <div class="ntb-main-content">
        <form id="ntb-edit-template-form" onsubmit="return false;">
            <input type="hidden" id="ntb-template-id" value="<?php echo esc_attr($template_id); ?>">
            
            <div class="ntb-editor-header">
                <div class="ntb-form-group">
                    <input type="text" id="ntb-template-name" name="name" class="large-text" placeholder="Masukkan Nama Template Anda di sini" required>
                </div>
                <div class="ntb-editor-actions">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-template-builder')); ?>" class="button">Kembali</a>
                    <button type="button" id="ntb-update-status-btn" class="button"></button> <!-- Teks diatur oleh JS -->
                    <button type="submit" id="ntb-save-template-btn" class="button button-primary">
                        <span class="dashicons dashicons-saved"></span> Simpan Perubahan
                    </button>
                </div>
            </div>
            
            <hr>

            <div class="ntb-form-group">
                <label for="ntb-template-content" class="screen-reader-text">Konten Template</label>
                <textarea id="ntb-template-content" name="html"></textarea>
            </div>
        </form>
    </div>
</div>


<?php 
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>