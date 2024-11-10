<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    CLWC
 * @subpackage CLWC/admin
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    wp_die();
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class CLWC_Customer_Loyalty_Log_Table
 * 
 * A custom table class to display the loyalty log entries.
 *
 * @since 2.0.0
 */
class CLWC_Customer_Loyalty_Log_Table extends WP_List_Table {

    /**
     * CLWC_Customer_Loyalty_Log_Table constructor.
     *
     * Initializes the table's singular and plural names.
     */
    public function __construct() {
        parent::__construct( [
            'singular' => 'clwc_log_entry',
            'plural'   => 'clwc_log_entries',
            'ajax'     => false,
        ] );
    }

    /**
     * Defines the columns displayed in the table.
     *
     * @return array List of columns with column IDs as keys and column titles as values.
     */
    public function get_columns() {
        return [
            'name'    => esc_html__( 'Customer Name', 'customer-loyalty-for-woocommerce' ),
            'email'   => esc_html__( 'Email', 'customer-loyalty-for-woocommerce' ),
            'points'  => esc_html__( 'Points', 'customer-loyalty-for-woocommerce' ),
            'details' => esc_html__( 'Details', 'customer-loyalty-for-woocommerce' ),
            'date'    => esc_html__( 'Date', 'customer-loyalty-for-woocommerce' ),
        ];
    }

    /**
     * Defines which columns are sortable.
     *
     * @return array Associative array with column keys as keys and boolean values indicating sortable status.
     */
    public function get_sortable_columns() {
        return [
            'name'   => ['name', false],
            'email'  => ['email', false],
            'points' => ['points', false],
            'date'   => ['date', false],
        ];
    }

    /**
     * Prepares items for display, including pagination and sorting.
     *
     * Fetches data from the database, processes pagination, and sets column headers.
     */
    public function prepare_items() {
        global $wpdb;

        $per_page     = 10;
        $current_page = $this->get_pagenum();
        $orderby      = !empty( $_REQUEST['orderby'] ) ? sanitize_sql_orderby( $_REQUEST['orderby'] ) : 'date';
        $order        = !empty( $_REQUEST['order'] ) && 'asc' === strtolower( $_REQUEST['order'] ) ? 'ASC' : 'DESC';

        // Query to fetch entries from the custom table.
        $table_name   = $wpdb->prefix . 'clwc_loyalty_log';
        $offset       = ( $current_page - 1 ) * $per_page;

        // Total item count for pagination.
        $total_items  = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

        // Fetch the entries with sorting and pagination
        $this->items = $wpdb->get_results( 
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d",
                $per_page,
                $offset
            ),
            ARRAY_A
        );

        // Set pagination arguments
        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page ),
        ] );

        // Set column headers, including sortable columns
        $columns   = $this->get_columns();
        $hidden    = [];
        $sortable  = $this->get_sortable_columns();
        $this->_column_headers = [ $columns, $hidden, $sortable ];
    }

    /**
     * Renders default column values.
     *
     * Provides values for columns that do not have a custom rendering method.
     *
     * @param array  $item         The log entry data for a single row.
     * @param string $column_name  The name of the column being rendered.
     * 
     * @return string The content to display in the column.
     */
    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'name':
            case 'email':
            case 'points':
            case 'details':
            case 'date':
                return esc_html( $item[ $column_name ] );
            default:
                return ''; // Empty for undefined columns
        }
    }

    /**
     * Renders and displays the table.
     *
     * Calls `prepare_items()` and then `display()` to output the table.
     */
    public function display_table() {
        $this->prepare_items();
        $this->display();
    }
}
