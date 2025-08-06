<?php
// =========================================================================
// FILE: ntbksense/admin/views/ntbksense-laporan.php
// =========================================================================
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p><?php _e('Halaman ini akan menampilkan data menggunakan WP_List_Table.', 'ntbksense'); ?></p>
    
    <?php
    // Memuat kelas WP_List_Table jika belum ada
    if (!class_exists('WP_List_Table')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    }

    /**
     * Kelas untuk membuat tabel laporan kustom.
     */
    class NTBKSense_Reports_Table extends WP_List_Table {
        
        // Contoh data
        private $example_data = array(
            array('ID' => 1, 'item' => 'Laporan Penjualan', 'tanggal' => '2023-10-27', 'status' => 'Selesai'),
            array('ID' => 2, 'item' => 'Analisis Pengguna', 'tanggal' => '2023-10-26', 'status' => 'Tertunda'),
            array('ID' => 3, 'item' => 'Log Aktivitas', 'tanggal' => '2023-10-25', 'status' => 'Selesai'),
        );

        function __construct() {
            parent::__construct(array(
                'singular' => __('Laporan', 'ntbksense'),
                'plural'   => __('Laporan', 'ntbksense'),
                'ajax'     => false
            ));
        }

        function get_columns() {
            $columns = array(
                'cb'      => '<input type="checkbox" />',
                'item'    => __('Item Laporan', 'ntbksense'),
                'tanggal' => __('Tanggal', 'ntbksense'),
                'status'  => __('Status', 'ntbksense')
            );
            return $columns;
        }

        function prepare_items() {
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);
            $this->items = $this->example_data;
        }
        
        function column_default($item, $column_name) {
            switch ($column_name) {
                case 'item':
                case 'tanggal':
                case 'status':
                    return $item[$column_name];
                default:
                    return print_r($item, true); // Tampilkan seluruh item untuk debug
            }
        }
        
        function column_cb($item) {
            return sprintf(
                '<input type="checkbox" name="laporan[]" value="%s" />', $item['ID']
            );
        }
        
        function get_sortable_columns() {
            $sortable_columns = array(
                'item'  => array('item', false),
                'tanggal' => array('tanggal', false),
                'status'   => array('status', false)
            );
            return $sortable_columns;
        }
    }

    // Membuat instance tabel dan menampilkannya
    $my_table = new NTBKSense_Reports_Table();
    $my_table->prepare_items();
    $my_table->display();
    ?>
</div>