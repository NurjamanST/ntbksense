<?php
// class Template
// {
//     // Properti ini sekarang akan berisi objek $wpdb
//     private $conn;
//     private $wpdb;
//     // public $table_name;
//     // private $;

//     private $table_name = 'wp_acp_landings';

//     public $id, $slug, $status, $title, $title_option, $description, $inject_keywords,
//         $description_option, $template_file, $template_name, $random_template_method,
//         $random_template_file, $random_template_file_afs, $post_urls,
//         $redirect_post_option, $timer_auto_refresh, $auto_refresh_option,
//         $protect_elementor, $referrer_option, $device_view,
//         $videos_floating_option, $timer_auto_pause_video, $video_urls,
//         $universal_image_urls, $landing_image_urls, $number_images_displayed,
//         $custom_html, $parameter_key, $parameter_value, $cloaking_url,
//         $custom_template_builder;

//     public function __construct($db, $db_connection)
//     {
//         $this->conn = $db;
//         $this->wpdb = $db_connection;

//         // Definisikan nama tabel di sini
//         $this->table_name = $this->wpdb->prefix . 'acp_landings';
//     }

//     public function isSlugExists($slug)
//     {
//         $query = $this->conn->prepare("SELECT id FROM {$this->table_name} WHERE slug = %s", $slug);
//         $result = $this->conn->get_var($query);
//         return !empty($result);
//     }

//     public function create()
//     {
//         // Siapkan data lengkap dengan membaca properti dari object ini ($this)
//         $data = [
//             'slug' => $this->slug,
//             'status' => $this->status,
//             'title' => $this->title,
//             'title_option' => $this->title_option,
//             'description' => $this->description,
//             'inject_keywords' => $this->inject_keywords,
//             'description_option' => $this->description_option,
//             'template_file' => $this->template_file,
//             'template_name' => $this->template_name,
//             'random_template_method' => $this->random_template_method,
//             'random_template_file' => $this->random_template_file,
//             'random_template_file_afs' => $this->random_template_file_afs,
//             'post_urls' => $this->post_urls,
//             'redirect_post_option' => $this->redirect_post_option,
//             'timer_auto_refresh' => $this->timer_auto_refresh,
//             'auto_refresh_option' => $this->auto_refresh_option,
//             'protect_elementor' => $this->protect_elementor,
//             'referrer_option' => $this->referrer_option,
//             'device_view' => $this->device_view,
//             'videos_floating_option' => $this->videos_floating_option,
//             'timer_auto_pause_video' => $this->timer_auto_pause_video,
//             'video_urls' => $this->video_urls,
//             'universal_image_urls' => $this->universal_image_urls,
//             'landing_image_urls' => $this->landing_image_urls,
//             'number_images_displayed' => $this->number_images_displayed,
//             'custom_html' => $this->custom_html,
//             'parameter_key' => $this->parameter_key,
//             'parameter_value' => $this->parameter_value,
//             'cloaking_url' => $this->cloaking_url,
//             'custom_template_builder' => $this->custom_template_builder
//         ];

//         // Siapkan format tipe data untuk setiap kolom
//         $format = [
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%d',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s'
//         ]; // %s untuk string, %d untuk angka

//         $result = $this->conn->insert($this->table_name, $data, $format);

//         if ($result === false) {
//             return false;
//         }

//         $this->id = $this->conn->insert_id;
//         return true;
//     }

//     /**
//      * Mengupdate data menggunakan metode $wpdb->update()
//      */
//     // Method untuk update data
//     public function update()
//     {
//         // Siapkan semua kolom yang akan diupdate dalam bentuk array
//         $fields = [
//             'slug' => $this->slug,
//             'status' => $this->status,
//             'title' => $this->title,
//             'title_option' => $this->title_option,
//             'description' => $this->description,
//             'inject_keywords' => $this->inject_keywords,
//             'description_option' => $this->description_option,
//             'template_file' => $this->template_file,
//             'template_name' => $this->template_name,
//             'redirect_post_option' => $this->redirect_post_option,
//             'timer_auto_refresh' => $this->timer_auto_refresh,
//             'auto_refresh_option' => $this->auto_refresh_option,
//             'protect_elementor' => $this->protect_elementor,
//             'referrer_option' => $this->referrer_option,
//             'device_view' => $this->device_view,
//             'videos_floating_option' => $this->videos_floating_option,
//             'timer_auto_pause_video' => $this->timer_auto_pause_video,
//             'cloaking_url' => $this->cloaking_url,
//             'post_urls' => $this->post_urls,
//             'video_urls' => $this->video_urls,
//             'universal_image_urls' => $this->universal_image_urls,
//             'landing_image_urls' => $this->landing_image_urls,
//             'number_images_displayed' => $this->number_images_displayed,
//             'custom_html' => $this->custom_html,
//             'parameter_key' => $this->parameter_key,
//             'parameter_value' => $this->parameter_value,
//             'custom_template_builder' => $this->custom_template_builder,
//             'random_template_method' => $this->random_template_method,
//             'random_template_file' => $this->random_template_file,
//             'random_template_file_afs' => $this->random_template_file_afs
//         ];

//         // Gunakan metode $wpdb->update yang aman
//         $result = $this->wpdb->update(
//             $this->table_name,
//             $fields,
//             ['id' => $this->id] // Kondisi WHERE
//         );

//         return ($result !== false);
//     }

//     // Method untuk cek slug duplikat saat update
//     // public function isSlugTakenByAnother($slug, $current_id)
//     // {
//     //     $found = $this->wpdb->get_var(
//     //         $this->wpdb->prepare(
//     //             "SELECT slug FROM {$this->table_name} WHERE slug = %s AND id != %d",
//     //             $slug,
//     //             $current_id
//     //         )
//     //     );
//     //     return !is_null($found);
//     // }

//     /**
//      * Mencari data berdasarkan slug menggunakan $wpdb->get_row()
//      */
//     public function findBySlug($slug)
//     {
//         $query = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE slug = %s", $slug);
//         return $this->conn->get_row($query, ARRAY_A);
//     }

//     /**
//      * Mengecek apakah slug sudah ada
//      */
//     // public function isSlugExists($slug)
//     // {
//     //     $query = $this->conn->prepare("SELECT id FROM {$this->table_name} WHERE slug = %s", $slug);
//     //     $result = $this->conn->get_var($query);
//     //     return !empty($result);
//     // }

//     /**
//      * Mengecek apakah slug sudah dipakai oleh data LAIN (untuk validasi update)
//      */
//     public function isSlugTakenByAnother($slug, $currentId)
//     {
//         $query = $this->conn->prepare("SELECT id FROM {$this->table_name} WHERE slug = %s AND id != %d", $slug, $currentId);
//         $result = $this->conn->get_var($query);
//         return !empty($result);
//     }
// }


// Ganti seluruh isi file template.landing.php lo dengan ini

class Template
{
    // Properti untuk koneksi database & nama tabel
    private $wpdb;
    public $table_name;

    // Properti untuk semua kolom di tabel
    public $id;
    public $slug;
    public $status;
    public $title;
    public $title_option;
    public $description;
    public $inject_keywords;
    public $description_option;
    public $template_file;
    public $template_name;
    public $redirect_post_option;
    public $timer_auto_refresh;
    public $auto_refresh_option;
    public $protect_elementor;
    public $referrer_option;
    public $device_view;
    public $videos_floating_option;
    public $timer_auto_pause_video;
    public $cloaking_url;
    public $post_urls;
    public $video_urls;
    public $universal_image_urls;
    public $landing_image_urls;
    public $number_images_displayed;
    public $custom_html;
    public $parameter_key;
    public $parameter_value;
    public $custom_template_builder;
    public $random_template_method;
    public $random_template_file;
    public $random_template_file_afs;

    /**
     * Constructor untuk mengenalkan $wpdb ke dalam Class.
     * @param wpdb $db_connection Objek $wpdb global dari WordPress.
     */
    public function __construct($db_connection)
    {
        $this->wpdb = $db_connection;
        $this->table_name = $this->wpdb->prefix . 'acp_landings'; // Ganti nama tabel jika beda
    }

    /**
     * Membuat data baru di database.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function create()
    {
        $data = $this->get_all_properties_as_array();
        $result = $this->wpdb->insert($this->table_name, $data);

        if ($result === false) {
            return false;
        }

        // Simpan ID yang baru dibuat ke dalam properti object
        $this->id = $this->wpdb->insert_id;
        return true;
    }

    /**
     * Mengupdate data yang sudah ada di database.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function update()
    {
        $data = $this->get_all_properties_as_array();

        $result = $this->wpdb->update(
            $this->table_name,
            $data,
            ['id' => $this->id] // Kondisi WHERE
        );

        return ($result !== false);
    }

    /**
     * Mengecek apakah slug sudah ada (untuk create).
     * @param string $slug Slug yang akan dicek.
     * @return bool True jika slug sudah ada, false jika belum.
     */
    public function isSlugExists($slug)
    {
        $query = $this->wpdb->prepare("SELECT id FROM {$this->table_name} WHERE slug = %s", $slug);
        $result = $this->wpdb->get_var($query);
        return !empty($result);
    }

    /**
     * Mengecek apakah slug sudah dipakai oleh data LAIN (untuk validasi update).
     * @param string $slug Slug yang akan dicek.
     * @param int $current_id ID dari data yang sedang diedit.
     * @return bool True jika slug sudah dipakai data lain, false jika tidak.
     */
    public function isSlugTakenByAnother($slug, $current_id)
    {
        $query = $this->wpdb->prepare("SELECT id FROM {$this->table_name} WHERE slug = %s AND id != %d", $slug, $current_id);
        $result = $this->wpdb->get_var($query);
        return !empty($result);
    }

    /**
     * Helper function untuk mengumpulkan semua properti ke dalam array.
     * @return array Array berisi semua properti dan nilainya.
     */
    private function get_all_properties_as_array()
    {
        return [
            'slug' => $this->slug,
            'status' => $this->status,
            'title' => $this->title,
            'title_option' => $this->title_option,
            'description' => $this->description,
            'inject_keywords' => $this->inject_keywords,
            'description_option' => $this->description_option,
            'template_file' => $this->template_file,
            'template_name' => $this->template_name,
            'redirect_post_option' => $this->redirect_post_option,
            'timer_auto_refresh' => $this->timer_auto_refresh,
            'auto_refresh_option' => $this->auto_refresh_option,
            'protect_elementor' => $this->protect_elementor,
            'referrer_option' => $this->referrer_option,
            'device_view' => $this->device_view,
            'videos_floating_option' => $this->videos_floating_option,
            'timer_auto_pause_video' => $this->timer_auto_pause_video,
            'cloaking_url' => $this->cloaking_url,
            'post_urls' => $this->post_urls,
            'video_urls' => $this->video_urls,
            'universal_image_urls' => $this->universal_image_urls,
            'landing_image_urls' => $this->landing_image_urls,
            'number_images_displayed' => $this->number_images_displayed,
            'custom_html' => $this->custom_html,
            'parameter_key' => $this->parameter_key,
            'parameter_value' => $this->parameter_value,
            'custom_template_builder' => $this->custom_template_builder,
            'random_template_method' => $this->random_template_method,
            'random_template_file' => $this->random_template_file,
            'random_template_file_afs' => $this->random_template_file_afs
        ];
    }
}
