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
 * Run specific tasks on payment completion in WooCommerce.
 *
 * This function checks if the rewards card is active and, if so, increments 
 * the user's punches or issues a coupon if required punches are met.
 *
 * @param int $order_id WooCommerce order ID.
 * 
 * @since  1.0.0
 * @return void
 */
function clwc_payment_complete( $order_id ) {
    $order = wc_get_order( $order_id );
    $user  = $order->get_user();

    // Retrieve rewards card settings.
    $settings = get_option( 'clwc_rewards_card_settings', [] );

    if ( '1' !== $settings['activate_rewards_card'] ) {
        return;
    }

    if ( $user ) {
        $required_punches = (int) ($settings['required_punches'] ?? 10);
        $coupon_amount    = (float) ($settings['coupon_amount'] ?? 5.00);
        $discount_type    = $settings['coupon_type'] ?? 'fixed_cart';

        $card_punches = (int) get_user_meta( $user->ID, 'clwc_rewards_card_punches', true ) ?: 0;
        $rewards_earned = (int) get_user_meta( $user->ID, 'clwc_rewards_earned', true ) ?: 0;

        if ( $card_punches >= ($required_punches - 1) ) {
            $coupon_code = clwc_get_random_string();

            // Create coupon.
            $coupon = [
                'post_title'   => $coupon_code,
                'post_content' => '',
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_type'    => 'shop_coupon'
            ];

            $new_coupon_id = wp_insert_post( $coupon );
            update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
            update_post_meta( $new_coupon_id, 'coupon_amount', $coupon_amount );
            update_post_meta( $new_coupon_id, 'usage_limit', '1' );
            update_post_meta( $order_id, 'clwc_customer_coupon_code', $coupon_code );

            $card_punches = 0; // Reset punches after coupon issue.
            $rewards_earned++;
            update_user_meta( $user->ID, 'clwc_rewards_earned', $rewards_earned );
        } else {
            $card_punches++;
        }

        update_user_meta( $user->ID, 'clwc_rewards_card_punches', $card_punches );
    }
}
add_action( 'woocommerce_thankyou', 'clwc_payment_complete', 10 );

/**
 * Display Coupon Code for X punches to the Punch Card.
 * 
 * @param object $order 
 *
 * @return void
 */
function clwc_order_customer_coupon_code( $order ) {
    global $woocommerce, $post;

    // Get order id.
    $order_id = trim( str_replace( '#', '', $order->get_order_number() ) );

    // Get coupon code that was added to the order meta data.
    $coupon_code = get_post_meta( $order_id, 'clwc_customer_coupon_code', true );

    /**
     * Display reward notice
     * 
     * Only displays if a coupon code was created during order completion.
     * 
     * @return string
     */
    if ( $coupon_code ) {
        // Set rewards card image.
        if ( false == clwc_rewards_card_image() ) {
            // Default rewards card image.
            $rewards_card_image = apply_filters( 'clwc_rewards_card_image_default', plugins_url( '/public/images/rewards-card-image-default.jpg', dirname(__FILE__) ) );
        } else {
            // Get rewards card image.
            $rewards_card_image = clwc_rewards_card_image();
        }

        // Set the rewards card image.
        $rewards_card_img = apply_filters( 'clwc_rewards_card_image', $rewards_card_image );

        // Display coupon to customer.
        echo '<div class="clwc-rewards-card">';
        echo '<p><img src="' . esc_url( $rewards_card_img ) . '" alt="' . esc_attr__( 'Rewards Card', 'customer-loyalty-for-woocommerce' ) . '" class="clwc-rewards-card-image" /></p>';
        echo '<h2 class="clwc-rewards-card-title">' . apply_filters( 'clwc_rewards_card_title', clwc_rewards_card_title() ) . '</h2>';
        echo '<p class="clwc-rewards-card-text">' . apply_filters( 'clwc_rewards_card_text', clwc_rewards_card_text() ) . '</p>';
        echo '<p class="clwc-rewards-card-coupon-code"><strong>' . esc_attr__( 'Coupon', 'customer-loyalty-for-woocommerce' ) . ': ' . esc_attr__( $coupon_code ) . '</strong></p>';
        echo '</div>';
    }
}
add_action( 'woocommerce_order_details_before_order_table', 'clwc_order_customer_coupon_code' );
