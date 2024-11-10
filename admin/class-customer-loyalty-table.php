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

    public function __construct() {
        parent::__construct( [
            'singular' => 'clwc_customer',
            'plural'   => 'clwc_customers',
            'ajax'     => false,
        ] );
    }

    public function get_columns() {
        return [
            'name'   => esc_html__( 'Customer Name', 'customer-loyalty-for-woocommerce' ),
            'email'  => esc_html__( 'Email', 'customer-loyalty-for-woocommerce' ),
            'points' => esc_html__( 'Points', 'customer-loyalty-for-woocommerce' ),
        ];
    }

    public function get_sortable_columns() {
        return [
            'name'   => ['name', true],
            'email'  => ['email', false],
            'points' => ['points', false],
        ];
    }

    public function prepare_items() {
        $per_page     = 10;
        $current_page = $this->get_pagenum();
        $search       = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
    
        $all_items = $this->get_customers_with_points( $search );
    
        // Sorting
        $orderby = !empty( $_REQUEST['orderby'] ) ? $_REQUEST['orderby'] : 'name';
        $order   = !empty( $_REQUEST['order'] ) && $_REQUEST['order'] === 'desc' ? 'desc' : 'asc';
    
        usort( $all_items, function ( $a, $b ) use ( $orderby, $order ) {
            if ( $orderby === 'points' ) {
                // Sort points as integers
                $result = (int) $a['points'] <=> (int) $b['points'];
            } else {
                // Sort name and email as strings, stripping HTML from name
                $valA = $orderby === 'name' ? strip_tags( $a['name'] ) : $a[$orderby];
                $valB = $orderby === 'name' ? strip_tags( $b['name'] ) : $b[$orderby];
                $result = strcmp( $valA, $valB );
            }
            return ( $order === 'asc' ) ? $result : -$result;
        });
    
        $this->items = array_slice( $all_items, ( $current_page - 1 ) * $per_page, $per_page );
    
        $total_items = count( $all_items );
        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page ),
        ] );
    
        $columns   = $this->get_columns();
        $hidden    = [];
        $sortable  = $this->get_sortable_columns();
        $this->_column_headers = [ $columns, $hidden, $sortable ];
    }
    
    private function get_customers_with_points( $search = '' ) {
        $args = [
            'role' => 'customer',
            'search' => $search ? '*' . esc_attr( $search ) . '*' : '',
            'search_columns' => ['display_name', 'user_email']
        ];

        $users = get_users( $args );
        $data  = [];

        foreach ( $users as $user ) {
            $points = (int) get_user_meta( $user->ID, 'clwc_loyalty_points', true );
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

        return $data;
    }

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

    public function extra_tablenav( $which ) {
        if ( 'top' === $which ) {
            $this->search_box( __( 'Search Customers', 'customer-loyalty-for-woocommerce' ), 'clwc_search' );
        }
    }
}
