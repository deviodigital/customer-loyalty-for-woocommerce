<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.deviodigital.com
 * @since      1.0
 *
 * @package    CLWC
 * @subpackage CLWC/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Add loyalty points on successful customer registration
 *
 * @param int $user_id
 * @since 1.0
 */
function clwc_customer_registration( $user_id ) {
    // Check settings before adding any points.
    if ( 'on' == clwc_loyalty_points_activate() && 0 != clwc_earning_points_customer_registration() ) {
        // Get user's loyalty points.
        $old_points = get_user_meta( $user_id, 'clwc_loyalty_points', TRUE );

        // Set empty variable to zero.
        if ( '' == $old_points ) {
            $old_points = 0;
        }

        // Add loyalty points for customer registration.
        $new_points = $old_points + clwc_earning_points_customer_registration();

        update_user_meta( $user_id, 'clwc_loyalty_points', $new_points, $old_points );
    }

}
add_action( 'user_register', 'clwc_customer_registration', 10, 1 );

/**
 * Add loyalty points on order completion
 *
 * @since 1.0
 */
function clwc_customer_first_order() {
    // Check settings before adding any points.
    if ( 'on' == clwc_loyalty_points_activate() && 0 != clwc_earning_points_order_complete() ) {
        // Get user's loyalty points.
        $old_points = get_user_meta( get_current_user_id(), 'clwc_loyalty_points', TRUE );

        // Set empty variable to zero.
        if ( '' == $old_points ) {
            $old_points = 0;
        }

        // Add loyalty points for completing an order.
        $new_points = $old_points + clwc_earning_points_order_complete();

        // Update customer loyalty points.
        update_user_meta( get_current_user_id(), 'clwc_loyalty_points', $new_points, $old_points );
    }

}
add_action( 'woocommerce_thankyou', 'clwc_customer_first_order', 10 );

/**
 * Add loyalty points for every dollar spent
 *
 * @since 1.0
 */
function clwc_customer_money_spent( $order_id ) {
    // Check settings before adding any points.
    if ( 'on' == clwc_loyalty_points_activate() && 0 != clwc_earning_points_money_spent() ) {
        // Get order data.
        $order = wc_get_order( $order_id );

        // Get order total.
        $order_total = $order->get_total();

        // Get order subtotal.
        if ( 'subtotal' === clwc_loyalty_points_redeem_points_calculation_type() ) {
            $order_total = $order->get_subtotal();
        }

        // Get user's loyalty points.
        $old_points = get_user_meta( get_current_user_id(), 'clwc_loyalty_points', TRUE );

        // Set empty variable to zero.
        if ( '' == $old_points ) {
            $old_points = 0;
        }

        // Get money spent loyalty points.
        $money_spent = $order_total * clwc_earning_points_money_spent();

        // Get new loyalty points total.
        $new_points = $old_points + round( $money_spent );

        // Update customer loyalty points.
        update_user_meta( get_current_user_id(), 'clwc_loyalty_points', $new_points, $old_points );
    }

}
add_action( 'woocommerce_thankyou', 'clwc_customer_money_spent', 10 );
