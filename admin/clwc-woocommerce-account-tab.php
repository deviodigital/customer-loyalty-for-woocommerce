<?php

/**
 * WooCommerce Account Tab - Customer Loyalty & Rewards
 *
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 *
 * @package    CLWC
 * @subpackage CLWC/admin
 */

/**
 * Register new endpoint to use inside My Account page.
 *
 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
 */
function clwc_endpoints() {
	add_rewrite_endpoint( 'customer-loyalty', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'clwc_endpoints' );

/**
 * Add new query var.
 *
 * @param array $vars
 * @return array
 * 
 * @since 1.2
 */
add_filter( 'woocommerce_get_query_vars', function ( $vars ) {
    foreach ( ['customer-loyalty'] as $e ) {
        $vars[$e] = $e;
    }
    return $vars;
} );

// Flush rewrite rules.
function clwc_flush_rewrite_rules() {
	add_rewrite_endpoint( 'customer-loyalty', EP_ROOT | EP_PAGES );
	flush_rewrite_rules();
}

/**
 * Insert the new endpoint into the My Account menu.
 *
 * @param array $items
 * @return array
 */
function clwc_my_account_menu_items( $items ) {
	// Get customer-logout menu item.
	$logout = $items['customer-logout'];
	// Remove the customer-logout menu item.
	unset( $items['customer-logout'] );
	// Insert the customer-loyalty endpoint.
	$items['customer-loyalty'] = apply_filters( 'clwc_my_account_menu_item_title', __( 'Customer Loyalty', 'customer-loyalty-for-woocommerce' ) );
	// Insert back the customer-logout item.
	$items['customer-logout'] = $logout;

	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'clwc_my_account_menu_items' );

/**
 * Endpoint HTML content.
 */
function clwc_endpoint_content() {
	echo do_shortcode( '[clwc_dashboard]' );
}
add_action( 'woocommerce_account_customer-loyalty_endpoint', 'clwc_endpoint_content', 99, 1 );

/**
 * Change endpoint title.
 *
 * @param string $title
 * @return string
 */
function clwc_endpoint_page_title( $title, $id ) {
	// Update 'Customer Loyalty' page title.
	if ( is_wc_endpoint_url( 'customer-loyalty' ) && in_the_loop() ) {
		$title = apply_filters( 'clwc_my_account_endpoint_page_title', __( 'Customer Loyalty', 'customer-loyalty-for-woocommerce' ) );
	}
	return $title;
}
add_filter( 'the_title', 'clwc_endpoint_page_title', 10, 2 );
