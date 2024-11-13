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
 * Awards loyalty points to the user upon registration if the loyalty points
 * system is activated in the plugin settings.
 *
 * @since 1.0.0
 * @param int $user_id The ID of the registered user.
 * @return void
 */
function clwc_customer_registration( $user_id ) {
    $settings = get_option( 'clwc_loyalty_points_settings', [] );
    $registration_points = (int) ($settings['customer_registration'] ?? 0);

    if ( clwc_loyalty_points_activate() && $registration_points ) {
        $user_info  = get_userdata( $user_id );
        $user_email = $user_info ? $user_info->user_email : '';
        $user_name  = $user_info ? $user_info->display_name : '';

        $old_points = (int) get_user_meta( $user_id, 'clwc_loyalty_points', true ) ?: 0;
        $new_points = $old_points + $registration_points;

        update_user_meta( $user_id, 'clwc_loyalty_points', $new_points, $old_points );

        $details = sprintf(
            esc_html__( 'Customer awarded %d loyalty points for registering an account.', 'customer-loyalty-for-woocommerce' ),
            $registration_points
        );

        clwc_insert_loyalty_log_entry( $user_id, $user_name, $user_email, $new_points, $details );
    }
}
add_action( 'user_register', 'clwc_customer_registration', 10, 1 );

/**
 * Add loyalty points on order completion.
 *
 * Awards loyalty points to the user when an order is completed if the loyalty points
 * system is activated in the plugin settings.
 *
 * @since 1.0.0
 * @param int $order_id WooCommerce order ID.
 * @return void
 */
function clwc_customer_first_order( $order_id ) {
    $settings              = get_option( 'clwc_loyalty_points_settings', [] );
    $order_complete_points = (int) ($settings['order_complete'] ?? 0);

    if ( clwc_loyalty_points_activate() && $order_complete_points ) {
        $order   = wc_get_order( $order_id );
        $user_id = $order->get_user_id();

        if ( $user_id ) {
            $old_points = (int) get_user_meta( $user_id, 'clwc_loyalty_points', true ) ?: 0;
            $new_points = $old_points + $order_complete_points;

            update_user_meta( $user_id, 'clwc_loyalty_points', $new_points, $old_points );

            $user_info  = get_userdata( $user_id );
            $user_name  = $user_info ? $user_info->display_name : '';
            $user_email = $user_info ? $user_info->user_email : '';

            $details = sprintf(
                esc_html__( 'Customer awarded %d loyalty points for completing an order.', 'customer-loyalty-for-woocommerce' ),
                $order_complete_points
            );
            clwc_insert_loyalty_log_entry( $user_id, $user_name, $user_email, $order_complete_points, $details );
        }
    }
}
add_action( 'woocommerce_thankyou', 'clwc_customer_first_order', 10 );

/**
 * Add loyalty points for every dollar spent.
 *
 * Awards loyalty points to the user based on the total amount spent, if the loyalty points
 * system is activated and set up in the plugin settings.
 *
 * @param int $order_id WooCommerce order ID.
 * 
 * @since 1.0.0
 * @return void
 */
function clwc_customer_money_spent( $order_id ) {
    $settings          = get_option( 'clwc_loyalty_points_settings', [] );
    $points_per_dollar = (int) ($settings['money_spent'] ?? 0);

    if ( clwc_loyalty_points_activate() && $points_per_dollar ) {
        $order   = wc_get_order( $order_id );
        $user_id = $order->get_user_id();

        if ( $user_id ) {
            $order_total = ( 'order_subtotal' === clwc_loyalty_points_redeem_points_calculation_type() )
                ? $order->get_subtotal()
                : $order->get_total();

            $old_points    = (int) get_user_meta( $user_id, 'clwc_loyalty_points', true ) ?: 0;
            $earned_points = round( $order_total * $points_per_dollar );
            $new_points    = $old_points + $earned_points;

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
