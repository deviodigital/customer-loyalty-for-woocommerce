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
     * @since 2.0.0
     */
    public function __construct() {
        parent::__construct( [
            'singular' => 'clwc_log_entry',
            'plural'   => 'clwc_log_entries',
            'ajax'     => false,
        ] );
    }

    /**
     * Define the columns displayed in the table.
     *
     * @since 2.0.0
     * @return array List of columns.
     */
    public function get_columns() {
        return [
            'name'   => esc_html__( 'Customer Name', 'customer-loyalty-for-woocommerce' ),
            'email'  => esc_html__( 'Email', 'customer-loyalty-for-woocommerce' ),
            'points' => esc_html__( 'Points', 'customer-loyalty-for-woocommerce' ),
        ];
    }    

    /**
     * Prepares the items for display in the table, including pagination.
     *
     * @since 2.0.0
     */
    public function prepare_items() {
        $per_page     = 10;
        $current_page = $this->get_pagenum();
    
        // Fetch log entries and ensure it's an array
        $all_items   = $this->get_log_entries();
        $total_items = is_array($all_items) ? count($all_items) : 0;
    
        // Slice items for pagination
        $this->items = is_array($all_items) ? array_slice( $all_items, ( $current_page - 1 ) * $per_page, $per_page ) : [];
    
        // Pagination arguments
        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page ),
        ] );
    }

    /**
     * Retrieve log entries from the database or other source.
     *
     * @since 2.0.0
     * @return array List of log entries.
     */
    private function get_log_entries() {
        $data = [
            [ 'ID' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'points' => '+50', 'details' => 'Points awarded for registration', 'date' => '2024-11-01' ],
            [ 'ID' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com', 'points' => '-20', 'details' => 'Points redeemed for discount', 'date' => '2024-11-01' ],
        ];
    
        return is_array($data) ? $data : []; // Ensure an array is returned
    }
    

    /**
     * Render the default column values.
     *
     * @since 2.0.0
     * 
     * @param array  $item         The log entry data.
     * @param string $column_name  The name of the column.
     * 
     * @return string The column content.
     */
    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'name':
                return $item['name'];
            case 'email':
                return $item['email'];
            case 'points':
                return sprintf(
                    '<input type="number" class="clwc-loyalty-points" data-user-id="%d" value="%d" />',
                    esc_attr( $item['ID'] ),
                    esc_attr( $item['points'] )
                );
            default:
                return ''; // Empty for any undefined columns
        }
    }
}
