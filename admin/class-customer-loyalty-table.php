<?php

/**
 * Customer Loyalty for WooCommerce - Customer Points Table
 *
 * Displays customer data and their loyalty points in the Manage Points tab.
 *
 * @package    CLWC
 * @subpackage CLWC/admin
 * @since      1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    wp_die();
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class CLWC_Customer_Loyalty_Table
 * 
 * A custom table class to display and manage customer loyalty points.
 */
class CLWC_Customer_Loyalty_Table extends WP_List_Table {

    /**
     * CLWC_Customer_Loyalty_Table constructor.
     */
    public function __construct() {
        parent::__construct( [
            'singular' => 'clwc_customer',
            'plural'   => 'clwc_customers',
            'ajax'     => false,
        ] );
    }
    /**
     * Define table columns.
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
    
    public function single_row( $item ) {
        echo '<tr>';
        foreach ( $this->get_columns() as $column_name => $column_display_name ) {
            echo '<td>';
            // Use column-specific methods or column_default
            if ( method_exists( $this, 'column_' . $column_name ) ) {
                echo call_user_func( [ $this, 'column_' . $column_name ], $item );
            } else {
                echo $this->column_default( $item, $column_name );
            }
            echo '</td>';
        }
        echo '</tr>';
    }    
    
    /**
     * Prepare items for display in the table, including pagination.
     *
     * @since 2.0.0
     * @return void
     */
    public function prepare_items() {
        $per_page = 10;
        $current_page = $this->get_pagenum();
    
        // Retrieve data and set items
        $all_items = $this->get_customers_with_points();
        $this->items = array_slice( $all_items, ( $current_page - 1 ) * $per_page, $per_page );
    
        $total_items = count( $all_items );
        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page ),
        ] );
    
        // Column headers required for display
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = [];
        $this->_column_headers = [ $columns, $hidden, $sortable ];
    }
    

    /**
     * Retrieve customers and their loyalty points.
     *
     * @since 2.0.0
     * @return array List of customers with loyalty points.
     */
    private function get_customers_with_points() {
        $users = get_users( [ 'role' => 'customer' ] ); // Using a simple role query
        $data  = [];
    
        foreach ( $users as $user ) {
            $points = (int) get_user_meta( $user->ID, 'clwc_loyalty_points', true ); // Default to 0 if not set
            $data[] = [
                'ID'     => $user->ID,
                'name'   => sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( get_edit_user_link( $user->ID ) ),
                    esc_html( $user->display_name )
                ),
                'email'  => esc_html( $user->user_email ),
                'points' => $points,
            ];
        }
        
        error_log( print_r( $data, true ) );

        return $data;
     }

    /**
     * Render default column values.
     *
     * @since 2.0.0
     * @param array  $item         The customer data.
     * @param string $column_name  The name of the column.
     * @return string The column content.
     */
    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'name':
            case 'email':
                return $item[ $column_name ];
            case 'points':
                return sprintf(
                    '<input type="number" class="clwc-loyalty-points" data-user-id="%d" value="%d" />',
                    esc_attr( $item['ID'] ),
                    esc_attr( $item['points'] )
                );
            default:
                return 'N/A';
        }
    }
}
