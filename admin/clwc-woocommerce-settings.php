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
 * Run specific tasks on payment complete in WooCommerce
 * 
 * @since 1.0
 * @return string
 */
function clwc_payment_complete( $order_id ) {
    // Get order details.
    $order = wc_get_order( $order_id );

    // Get user from order.
    $user  = $order->get_user();

    // Bail if the rewards card is not activated.
    if ( 'on' !== clwc_rewards_card_activate() ) {
        return false;
    }

    // Run code if user is attached to the order.
    if ( $user ) {

        // Card punches.
        $card_punches = get_user_meta( $user->ID, 'clwc_rewards_card_punches', TRUE );
        $old_punches  = get_user_meta( $user->ID, 'clwc_rewards_card_punches', TRUE );

        // Set a default of zero.
        if ( '' == $card_punches ) {
            $card_punches = 0;
        }

        // Rewards earned.
        $rewards_earned = get_user_meta( $user->ID, 'clwc_rewards_earned', TRUE );
        $old_rewards    = get_user_meta( $user->ID, 'clwc_rewards_earned', TRUE );

        // Set a default of zero.
        if ( '' == $rewards_earned ) {
            $rewards_earned = 0;
        }

        $card_check = clwc_rewards_card_required_punches() - 1;

        // Check if user needs new coupon.
        if ( $card_check == $card_punches ) {

            /**
             * Create a coupon programatically
             */
            $coupon_code   = clwc_get_random_string(); // Code.
            $amount        = clwc_rewards_card_coupon_amount(); // Amount.
            $discount_type = clwc_rewards_card_coupon_type(); // Type: fixed_cart, percent, fixed_product, percent_product.

            // Coupon args.
            $coupon = array(
                'post_title'   => $coupon_code,
                'post_content' => '',
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_type'    => 'shop_coupon'
            );

            // Filter the args.
            $coupon = apply_filters( 'clwc_create_coupon_args', $coupon );

            // Get newly create coupon's ID #
            $new_coupon_id = wp_insert_post( $coupon );

            // Add custom meta data to the newly created coupon.
            update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
            update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
            update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
            update_post_meta( $new_coupon_id, 'product_ids', '' );
            update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
            update_post_meta( $new_coupon_id, 'usage_limit', '1' );
            update_post_meta( $new_coupon_id, 'expiry_date', '' );
            update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
            update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

            // Add coupon code to order meta data.
            update_post_meta( $order_id, 'clwc_customer_coupon_code', $coupon_code );

            // Reset punches.
            $card_punches = 0;

            // Add 1 to rewards earned.
            $rewards_earned = $rewards_earned + 1;

            // Update user meta - rewards earned number.
            update_user_meta( $user->ID, 'clwc_rewards_earned', $rewards_earned, $old_rewards );

        } else {

            // Add 1 to punches count.
            $card_punches = $card_punches + 1;

        }

        // Update user meta - punch card number.
        update_user_meta( $user->ID, 'clwc_rewards_card_punches', $card_punches, $old_punches );

    }
}
add_action( 'woocommerce_thankyou', 'clwc_payment_complete', 10 );

/**
 * Display Coupon Code for 10 punches to the Punch Card.
 *
 * @return string
 */
function clwc_order_customer_coupon_code( $order ) {
    global $woocommerce, $post;

    // Get order id.
    $order_id = trim( str_replace( '#', '', $order->get_order_number() ) );

    // Get coupon code that was added to the order meta data.
    $coupon_code = get_post_meta( $order_id, 'clwc_customer_coupon_code', TRUE );

    /**
     * Display reward notice
     * 
     * Only displays if a coupon code was created during order completion.
     * 
     * @return string
     */
    if ( $coupon_code ) {
        // Set rewards card image.
        if ( FALSE == clwc_rewards_card_image() ) {
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
