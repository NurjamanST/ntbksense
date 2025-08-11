<?php
class Template
{
    // Properti ini sekarang akan berisi objek $wpdb
    private $conn;
    // private $table_name;

    private $table_name = 'wp_acp_landings';

    public $id, $slug, $status, $title, $title_option, $description, $inject_keywords,
        $description_option, $template_file, $template_name, $random_template_method,
        $random_template_file, $random_template_file_afs, $post_urls,
        $redirect_post_option, $timer_auto_refresh, $auto_refresh_option,
        $protect_elementor, $referrer_option, $device_view,
        $videos_floating_option, $timer_auto_pause_video, $video_urls,
        $universal_image_urls, $landing_image_urls, $number_images_displayed,
        $custom_html, $parameter_key, $parameter_value, $cloaking_url,
        $custom_template_builder;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function isSlugExists($slug)
    {
        $query = $this->conn->prepare("SELECT id FROM {$this->table_name} WHERE slug = %s", $slug);
        $result = $this->conn->get_var($query);
        return !empty($result);
    }

    public function create()
    {
        // Siapkan data lengkap dengan membaca properti dari object ini ($this)
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

        // Siapkan format tipe data untuk setiap kolom
        $format = [
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        ]; // %s untuk string, %d untuk angka

        $result = $this->conn->insert($this->table_name, $data, $format);

        if ($result === false) {
            return false;
        }

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
    // public function isSlugExists($slug)
    // {
    //     $query = $this->conn->prepare("SELECT id FROM {$this->table_name} WHERE slug = %s", $slug);
    //     $result = $this->conn->get_var($query);
    //     return !empty($result);
    // }

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
