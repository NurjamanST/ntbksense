<?php
class Template
{
    // Properti ini sekarang akan berisi objek $wpdb
    private $conn;
    private $table_name;

    // Properti publik lo tetap sama
    public $id, $slug, $status, $title, $title_option, $description, $inject_keywords,
        $description_option, $template_file, $template_name, $random_template_method,
        $random_template_file, $random_template_file_afs, $post_urls,
        $redirect_post_option, $timer_auto_refresh, $auto_refresh_option,
        $protect_elementor, $referrer_option, $device_view,
        $videos_floating_option, $timer_auto_pause_video, $video_urls,
        $universal_image_urls, $landing_image_urls, $number_images_displayed,
        $custom_html, $parameter_key, $parameter_value, $cloaking_url,
        $custom_template_builder;

    /**
     * Constructor sekarang menerima objek $wpdb dari WordPress
     */
    public function __construct($db_connection)
    {
        $this->conn = $db_connection; // $db_connection adalah global $wpdb
        // Mengambil nama tabel dengan prefix dinamis dari WordPress
        $this->table_name = 'wp_acp_landings'; // Ganti 'wp_' dengan $this->conn->prefix jika perlu
    }

    /**
     * Membuat data baru menggunakan metode $wpdb->insert()
     */
    public function create()
    {
        // Siapkan data dalam format array [nama_kolom => nilai]
        $data = [
            'slug' => $this->slug,
            'status' => $this->status,
            'title' => $this->title,
            'title_option' => $this->title_option,
            'description' => $this->description,
            'inject_keywords' => $this->inject_keywords,
            'description_option' => $this->description_option,
            'template_file' => $this->template_file,
            'template_name' => $this->template_name,
            'random_template_method' => $this->random_template_method,
            'random_template_file' => $this->random_template_file,
            'random_template_file_afs' => $this->random_template_file_afs,
            'post_urls' => $this->post_urls,
            'redirect_post_option' => $this->redirect_post_option,
            'timer_auto_refresh' => $this->timer_auto_refresh,
            'auto_refresh_option' => $this->auto_refresh_option,
            'protect_elementor' => $this->protect_elementor,
            'referrer_option' => $this->referrer_option,
            'device_view' => $this->device_view,
            'videos_floating_option' => $this->videos_floating_option,
            'timer_auto_pause_video' => $this->timer_auto_pause_video,
            'video_urls' => $this->video_urls,
            'universal_image_urls' => $this->universal_image_urls,
            'landing_image_urls' => $this->landing_image_urls,
            'number_images_displayed' => $this->number_images_displayed,
            'custom_html' => $this->custom_html,
            'parameter_key' => $this->parameter_key,
            'parameter_value' => $this->parameter_value,
            'cloaking_url' => $this->cloaking_url,
            'custom_template_builder' => $this->custom_template_builder
        ];

        // $wpdb->insert() jauh lebih simpel dan aman. Mengembalikan false jika gagal.
        $result = $this->conn->insert($this->table_name, $data);

        if ($result === false) {
            // KODE DEBUG: Tampilkan pesan error terakhir dari database
            http_response_code(500);
            echo json_encode(['db_error' => $this->conn->last_error]);
            exit; // Hentikan script di sini biar pesan errornya kelihatan
        }

        // Ambil ID dari data yang baru saja dimasukkan
        $this->id = $this->conn->insert_id;
        return true;
    }

    /**
     * Mengupdate data menggunakan metode $wpdb->update()
     */
    public function update()
    {
        $data = [ /* Array data yang sama seperti di fungsi create() */];
        $where = ['id' => $this->id]; // Kondisi WHERE

        // $wpdb->update() juga simpel dan aman
        $result = $this->conn->update($this->table_name, $data, $where);

        return $result !== false;
    }

    /**
     * Mencari data berdasarkan slug menggunakan $wpdb->get_row()
     */
    public function findBySlug($slug)
    {
        $query = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE slug = %s", $slug);
        return $this->conn->get_row($query, ARRAY_A);
    }

    /**
     * Mengecek apakah slug sudah ada
     */
    public function isSlugExists($slug)
    {
        $query = $this->conn->prepare("SELECT id FROM {$this->table_name} WHERE slug = %s", $slug);
        $result = $this->conn->get_var($query);
        return !empty($result);
    }

    /**
     * Mengecek apakah slug sudah dipakai oleh data LAIN (untuk validasi update)
     */
    public function isSlugTakenByAnother($slug, $currentId)
    {
        $query = $this->conn->prepare("SELECT id FROM {$this->table_name} WHERE slug = %s AND id != %d", $slug, $currentId);
        $result = $this->conn->get_var($query);
        return !empty($result);
    }
}
