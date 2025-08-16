<?php
// =========================================================================
// FILE: ntbksense/admin/views/AutoPost/add_action_autopost.php
// FUNGSI: Menangani logika backend, aset, dan persiapan variabel untuk Auto Post.
// =========================================================================

// Menambahkan aset khusus untuk halaman ini
add_action('admin_footer', function() {
    $screen = get_current_screen();
    if ( 'ntbksense_page_ntbksense-auto-post' !== $screen->id ) {
        return;
    }
?>
    <!-- Bootstrap 5 for layout -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Monaco Editor Loader -->
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs/loader.js"></script>

    <script>
        jQuery(document).ready(function($) {
            // **MODIFIED:** Logika untuk menambah dan menghapus field Kunci API
            $('#add-api-key-link').on('click', function(e) {
                e.preventDefault();
                const newField = `
                    <div class="input-group mt-2">
                        <input type="text" class="form-control" name="kunci_api[]" placeholder="Kunci API">
                        <button class="btn btn-success" type="button">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button class="btn btn-danger remove-api-key" type="button">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`;
                $('#api-keys-container').append(newField);
            });

            $(document).on('click', '.remove-api-key', function() {
                $(this).closest('.input-group').remove();
            });
            // Logika untuk slider interaktif
            function updateSliderLabel(sliderId, labelId, textTemplate) {
                const slider = $('#' + sliderId);
                const label = $('#' + labelId);
                
                function updateText() {
                    const value = slider.val();
                    const newText = textTemplate.replace('{value}', value);
                    label.text(newText);
                }

                slider.on('input', updateText);
                updateText(); // Panggil saat inisialisasi
            }

            updateSliderLabel('multi_thread', 'multi_thread_label', 'Multi-thread ({value} thread)');
            updateSliderLabel('upaya_percobaan', 'upaya_percobaan_label', 'Upaya Percobaan ({value}x Upaya Percobaan)');
            updateSliderLabel('delay', 'delay_label', 'Delay ({value} second)');

            // **NEW:** Inisialisasi Monaco Editor
            let keywordEditor;
            require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs' }});
            require(['vs/editor/editor.main'], function() {
                keywordEditor = monaco.editor.create(document.getElementById('kata_kunci_editor'), {
                    value: '',
                    language: 'plaintext',
                    theme: 'vs-light',
                    automaticLayout: true,
                    wordWrap: 'on'
                });

                // Sinkronkan konten editor ke textarea tersembunyi saat ada perubahan
                keywordEditor.onDidChangeModelContent(function() {
                    $('#kata_kunci').val(keywordEditor.getValue());
                });
            });

            // **NEW:** Logika untuk modal "Generate Keywords"
            $('#generate-keywords-submit').on('click', function() {
                const total = $('#total_keyword').val();
                const keyword = $('#base_keyword').val();
                const modalElement = document.getElementById('generateKeywordsModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                const submitButton = $(this);

                if (!keyword) {
                    alert('Harap masukkan kata kunci dasar.');
                    return;
                }

                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...');

                // Simulasi panggilan API untuk menghasilkan kata kunci
                setTimeout(() => {
                    let generated_keywords = `Simulasi Generated based on: ${keyword}\n`;
                    for(let i = 1; i <= total; i++) {
                        generated_keywords += `${keyword} variation ${i}\n`;
                    }

                    // Set nilai ke Monaco Editor
                    if(keywordEditor) {
                        keywordEditor.setValue(generated_keywords);
                    }

                    // Sembunyikan modal dan reset tombol
                    modal.hide();
                    submitButton.prop('disabled', false).html('<i class="fas fa-check"></i> Submit');
                }, 1500); // Penundaan 1.5 detik untuk simulasi
            });
        });
    </script>
<?php
});

// Mengambil data pengguna untuk dropdown
$users = get_users(['fields' => ['ID', 'display_name']]);

// Mengambil kategori untuk dropdown
$categories = get_categories(['hide_empty' => false]);
?>