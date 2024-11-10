<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    CLWC
 * @subpackage CLWC/admin
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    wp_die();
}

/**
 * Add loyalty points on successful customer registration.
 *
 * @param int $user_id The ID of the registered user.
 *
 * @since 1.0.0
 * @return void
 */
function clwc_customer_registration( $user_id ) {
    error_log( 'Running clwc_customer_registration for user ID: ' . $user_id );
    
    if ( clwc_loyalty_points_activate() && 0 != clwc_earning_points_customer_registration() ) {
        $user_info  = get_userdata( $user_id );
        $user_email = $user_info ? $user_info->user_email : '';
        $user_name  = $user_info ? $user_info->display_name : '';
        
        $old_points = (int) get_user_meta( $user_id, 'clwc_loyalty_points', true ) ?: 0;
        $new_points = $old_points + clwc_earning_points_customer_registration();
        
        update_user_meta( $user_id, 'clwc_loyalty_points', $new_points, $old_points );

        $details = sprintf(
            esc_html__( 'Customer awarded %d loyalty points for registering an account.', 'customer-loyalty-for-woocommerce' ),
            clwc_earning_points_customer_registration()
        );

        $log_result = clwc_insert_loyalty_log_entry( $user_id, $user_name, $user_email, $new_points, $details );
        error_log( 'Loyalty log entry result: ' . print_r( $log_result, true ) );
    } else {
        error_log( 'Loyalty points not activated or registration points not set.' );
    }
}
add_action( 'user_register', 'clwc_customer_registration', 10, 1 );

/**
 * Add loyalty points on order completion.
 *
 * @param int $order_id WooCommerce order ID.
 *
 * @since 1.0.0
 * @return void
 */
function clwc_customer_first_order( $order_id ) {
    if ( clwc_loyalty_points_activate() && 0 != clwc_earning_points_order_complete() ) {
        $order = wc_get_order( $order_id );
        $user_id = $order->get_user_id();

        if ( $user_id ) {
            $old_points    = (int) get_user_meta( $user_id, 'clwc_loyalty_points', true ) ?: 0;
            $earned_points = clwc_earning_points_order_complete();
            $new_points    = $old_points + $earned_points;

            update_user_meta( $user_id, 'clwc_loyalty_points', $new_points, $old_points );

            $user_info  = get_userdata( $user_id );
            $user_name  = $user_info ? $user_info->display_name : '';
            $user_email = $user_info ? $user_info->user_email : '';

            $details = sprintf(
                esc_html__( 'Customer awarded %d loyalty points for completing an order.', 'customer-loyalty-for-woocommerce' ),
                $earned_points
            );
            clwc_insert_loyalty_log_entry( $user_id, $user_name, $user_email, $earned_points, $details );
        }
    }
}
add_action( 'woocommerce_thankyou', 'clwc_customer_first_order', 10 );

/**
 * Add loyalty points for every dollar spent.
 *
 * @param int $order_id WooCommerce order ID.
 *
 * @since 1.0.0
 * @return void
 */
function clwc_customer_money_spent( $order_id ) {
    if ( clwc_loyalty_points_activate() && 0 != clwc_earning_points_money_spent() ) {
        $order   = wc_get_order( $order_id );
        $user_id = $order->get_user_id();

        if ( $user_id ) {
            $order_total = ( 'order_subtotal' === clwc_loyalty_points_redeem_points_calculation_type() )
                ? $order->get_subtotal()
                : $order->get_total();

            $old_points        = (int) get_user_meta( $user_id, 'clwc_loyalty_points', true ) ?: 0;
            $points_per_dollar = clwc_earning_points_money_spent();
            $earned_points     = round( $order_total * $points_per_dollar );
            $new_points        = $old_points + $earned_points;

            update_user_meta( $user_id, 'clwc_loyalty_points', $new_points, $old_points );

            $user_info  = get_userdata( $user_id );
            $user_name  = $user_info ? $user_info->display_name : '';
            $user_email = $user_info ? $user_info->user_email : '';

            $details = sprintf(
                esc_html__( 'Customer awarded %d loyalty points for spending $%s.', 'customer-loyalty-for-woocommerce' ),
                $earned_points,
                number_format( $order_total, 2 )
            );
            clwc_insert_loyalty_log_entry( $user_id, $user_name, $user_email, $earned_points, $details );
        }
    }
}
add_action( 'woocommerce_thankyou', 'clwc_customer_money_spent', 10 );
