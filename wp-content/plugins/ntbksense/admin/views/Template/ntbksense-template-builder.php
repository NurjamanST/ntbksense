<?php include "add_action_template_builder.php"; ?>
<div class="wrap" id="ntb-lp-builder">
    <!-- Navbar -->
    <?php include NTBKSENSE_PLUGIN_DIR."admin/views/Layout/navbar.php"; ?>

    <!-- START: Breadcrumb Kustom -->
    <div class="ntb-breadcrumb">
        <span class="dashicons dashicons-admin-home"></span>
        <a href="<?php echo esc_url(admin_url('admin.php?page=ntbksense')); ?>">NTBKSense</a> &gt; <span>Template Builder</span>
    </div>
    <!-- END: Breadcrumb Kustom -->
    
    <div class="ntb-main-content">
        <h2 class="ntb-main-title"><span class="dashicons dashicons-layout"></span> Template Builder</h2>
        <hr>
        <!-- Tombol Aksi Utama -->
        <div class="ntb-actions-bar">
           <!-- **MODIFIED:** Changed to button and added Bootstrap modal attributes -->
            <button type="button" class="button ntb-btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                <span class="dashicons dashicons-plus-alt"></span> Template Baru
            </button>
        </div>

        <!-- Tabel HTML Standar untuk DataTables -->
        <table id="ntb-template-builder-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-templates" /></th>
                    <th>Template</th>
                    <th>Status</th>
                    <th>Versi Bootstrap</th>
                    <th>Tanggal dibuat</th>
                    <th>Tanggal update</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($template_data)) : ?>
                    <?php foreach ($template_data as $item) : ?>
                        <tr>
                            <td><input type="checkbox" name="template_id[]" value="<?php echo esc_attr($item['id']); ?>" /></td>
                            <td>
                                <strong><a style="text-decoration:none;" href="#"><?php echo esc_html($item['name']); ?></a></strong>
                                <div class="row-actions">
                                    <a style="text-decoration:none;" href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-edit-tb&id=' . $item['id'])); ?>" class="text-primary">
                                        <span class="dashicons dashicons-edit"></span>
                                    </a>
                                    <!-- <a style="text-decoration:none;" href="<?php echo esc_url(admin_url('admin.php?page=ntbksense-duplicate-tb&id=' . $item['id'])); ?>" class="text-secondary">
                                        <span class="dashicons dashicons-admin-page"></span>
                                    </a> -->
                                    <a style="text-decoration:none;" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=ntbksense-template_builder&action=delete&id=' . $item['id']), 'ntb_delete_lp_nonce')); ?>" class="text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus Template Builder ini?');">
                                        <span class="dashicons dashicons-trash"></span>
                                    </a>
                                </div>
                            </td>
                            <td><?php echo $item['status']; // Badge HTML, tidak perlu di-escape ?></td>
                            <td><?php echo esc_html($item['bootstrap_version']); ?></td>
                            <td><?php echo esc_html($item['created_date']); ?></td>
                            <td><?php echo esc_html($item['updated_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Modal untuk Tambah Template Baru -->
    <!-- **NEW:** Modal for Creating a New Template -->
    <div class="modal fade" id="createTemplateModal" tabindex="-1" aria-labelledby="createTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTemplateModalLabel">Buat Draft Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-template-form">
                        <div class="mb-3">
                            <label for="template_name" class="form-label">Nama Template</label>
                            <input type="text" class="form-control" id="template_name" name="template_name" placeholder="Xnxx New">
                        </div>
                        <div class="mb-3">
                            <label for="template_icon" class="form-label">Ikon Template</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="template_icon" name="template_icon" placeholder="https://example.com/icon.png">
                                <!-- **NEW:** Hidden file input -->
                                <input type="file" id="template_icon_file" name="template_icon_file" style="display: none;" accept="image/*">
                                <button class="btn btn-primary" type="button" id="upload_icon_button">
                                    <span class="dashicons dashicons-cloud-upload"></span>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="bootstrap_version" class="form-label">Versi Bootstrap</label>
                            <select class="form-select" id="bootstrap_version" name="bootstrap_version">
                                <option selected>5.3.1</option>
                                <option>5.2.0</option>
                                <option>4.6.0</option>
                                <option>Tidak menggunakan</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">
                        <span class="dashicons dashicons-arrow-right-alt2" style="vertical-align: middle; margin-top: -2px;"></span> Buat Draft
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    include NTBKSENSE_PLUGIN_DIR . "admin/views/Layout/stylesheets.php";
?>