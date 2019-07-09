<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.deviodigital.com/
 * @since      1.0
 *
 * @package    DTWC
 * @subpackage DTWC/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Random String function.
function clwc_get_random_string( $length = 6 ) {
    // Characters to use when creating random string.
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Create string.
    $string = '';

    // Add characters to string.
    for ( $i = 0; $i < $length; $i++ ) {
        $string .= $characters[mt_rand( 0, strlen( $characters ) - 1 )];
    }

    $string = clwc_rewards_card_coupon_prefix() . $string;

    // Filter string.
    $string = apply_filters( 'clwc_get_random_string', $string );

    return $string;
}

/**
 * Check to see if the Rewards Card is activated.
 *
 * @return string|bool
 */
function clwc_rewards_card_activate() {
    $rewards_card = get_option( 'clwc_rewards_card' );

    if ( isset( $rewards_card['clwc_rewards_card_activate'] ) && '' !== $rewards_card['clwc_rewards_card_activate'] ) {
        $active = $rewards_card['clwc_rewards_card_activate'];
    } else {
        $active = FALSE;
    }

	return apply_filters( 'clwc_rewards_card_activate', $active );
}

/**
 * Get the rewards card title
 *
 * @return string|bool
 */
function clwc_rewards_card_title() {
    $rewards_card = get_option( 'clwc_rewards_card' );

    if ( isset( $rewards_card['clwc_rewards_card_title'] ) && '' !== $rewards_card['clwc_rewards_card_title'] ) {
        $title = $rewards_card['clwc_rewards_card_title'];
    } else {
        $title = __( 'You earned a reward', 'clwc' );
    }

	return apply_filters( 'clwc_helper_rewards_card_title', $title );
}

/**
 * Get the rewards card text
 *
 * @return string|bool
 */
function clwc_rewards_card_text() {
    $rewards_card = get_option( 'clwc_rewards_card' );

    if ( isset( $rewards_card['clwc_rewards_card_text'] ) && '' !== $rewards_card['clwc_rewards_card_text'] ) {
        $text = $rewards_card['clwc_rewards_card_text'];
    } else {
        $text = FALSE;
    }

	return apply_filters( 'clwc_helper_rewards_card_text', $text );
}

/**
 * Get the rewards card image
 *
 * @return string|bool
 */
function clwc_rewards_card_image() {
    $rewards_card = get_option( 'clwc_rewards_card' );

    if ( isset( $rewards_card['clwc_rewards_card_image'] ) && '' !== $rewards_card['clwc_rewards_card_image'] ) {
        $image = $rewards_card['clwc_rewards_card_image'];
    } else {
        $image = FALSE;
    }

	return apply_filters( 'clwc_helper_rewards_card_image', $image );
}

/**
 * Get the rewards card required punches
 *
 * @return string|bool
 */
function clwc_rewards_card_required_punches() {
    $rewards_card = get_option( 'clwc_rewards_card' );

    if ( isset( $rewards_card['clwc_rewards_card_required_punches'] ) && '' !== $rewards_card['clwc_rewards_card_required_punches'] ) {
        $punches = $rewards_card['clwc_rewards_card_required_punches'];
    } else {
        $punches = FALSE;
    }

	return apply_filters( 'clwc_rewards_card_required_punches', $punches );
}

/**
 * Get the rewards card coupon amount
 *
 * @return string|bool
 */
function clwc_rewards_card_coupon_amount() {
    $rewards_card = get_option( 'clwc_rewards_card' );

    if ( isset( $rewards_card['clwc_rewards_card_coupon_amount'] ) && '' !== $rewards_card['clwc_rewards_card_coupon_amount'] ) {
        $amount = $rewards_card['clwc_rewards_card_coupon_amount'];
    } else {
        $amount = FALSE;
    }

	return apply_filters( 'clwc_rewards_card_coupon_amount', $amount );
}

/**
 * Get the rewards card coupon type
 *
 * @return string|bool
 */
function clwc_rewards_card_coupon_type() {
    $rewards_card = get_option( 'clwc_rewards_card' );

    if ( isset( $rewards_card['clwc_rewards_card_coupon_type'] ) && '' !== $rewards_card['clwc_rewards_card_coupon_type'] ) {
        $coupon_type = $rewards_card['clwc_rewards_card_coupon_type'];
    } else {
        $coupon_type = FALSE;
    }

	return apply_filters( 'clwc_rewards_card_coupon_type', $coupon_type );
}

/**
 * Get the rewards card coupon prefix
 *
 * @return string|bool
 */
function clwc_rewards_card_coupon_prefix() {
    $rewards_card = get_option( 'clwc_rewards_card' );

    if ( isset( $rewards_card['clwc_rewards_card_coupon_prefix'] ) && '' !== $rewards_card['clwc_rewards_card_coupon_prefix'] ) {
        $prefix = $rewards_card['clwc_rewards_card_coupon_prefix'];
    } else {
        $prefix = FALSE;
    }

	return apply_filters( 'clwc_rewards_card_coupon_prefix', $prefix );
}

/**
 * Check to see if Loyalty Points is activated.
 *
 * @return string|bool
 */
function clwc_loyalty_points_activate() {
    $loyalty_points = get_option( 'clwc_loyalty_points' );

    if ( isset( $loyalty_points['clwc_loyalty_points_activate'] ) && '' !== $loyalty_points['clwc_loyalty_points_activate'] ) {
        $active = $loyalty_points['clwc_loyalty_points_activate'];
    } else {
        $active = FALSE;
    }

	return apply_filters( 'clwc_loyalty_points_activate', $active );
}

/**
 * Get the loyalty points redeem points minimum.
 *
 * @return string|bool
 */
function clwc_loyalty_points_redeem_points_minimum() {
    $loyalty_points = get_option( 'clwc_loyalty_points' );

    if ( isset( $loyalty_points['clwc_loyalty_points_redeem_points_minimum'] ) && '' !== $loyalty_points['clwc_loyalty_points_redeem_points_minimum'] ) {
        $minimum = $loyalty_points['clwc_loyalty_points_redeem_points_minimum'];
    } else {
        $minimum = FALSE;
    }

	return apply_filters( 'clwc_loyalty_points_redeem_points_minimum', $minimum );
}

/**
 * Get the loyalty points redeem points value.
 *
 * @return string|bool
 */
function clwc_loyalty_points_redeem_points_value() {
    $loyalty_points = get_option( 'clwc_loyalty_points' );

    if ( isset( $loyalty_points['clwc_loyalty_points_redeem_points_value'] ) && '' !== $loyalty_points['clwc_loyalty_points_redeem_points_value'] ) {
        $value = $loyalty_points['clwc_loyalty_points_redeem_points_value'];
    } else {
        $value = FALSE;
    }

	return apply_filters( 'clwc_loyalty_points_redeem_points_value', $value );
}

/**
 * Get the earning points customer registration.
 *
 * @return string|bool
 */
function clwc_earning_points_customer_registration() {
    $loyalty_points = get_option( 'clwc_loyalty_points' );

    if ( isset( $loyalty_points['clwc_earning_points_customer_registration'] ) && '' !== $loyalty_points['clwc_earning_points_customer_registration'] ) {
        $points = $loyalty_points['clwc_earning_points_customer_registration'];
    } else {
        $points = 0;
    }

	return apply_filters( 'clwc_earning_points_customer_registration', $points );
}

/**
 * Get the earning points order complete.
 *
 * @return string|bool
 */
function clwc_earning_points_order_complete() {
    $loyalty_points = get_option( 'clwc_loyalty_points' );

    if ( isset( $loyalty_points['clwc_earning_points_order_complete'] ) && '' !== $loyalty_points['clwc_earning_points_order_complete'] ) {
        $points = $loyalty_points['clwc_earning_points_order_complete'];
    } else {
        $points = 0;
    }

	return apply_filters( 'clwc_earning_points_order_complete', $points );
}

/**
 * Get the earning points money spent.
 *
 * @return string|bool
 */
function clwc_earning_points_money_spent() {
    $loyalty_points = get_option( 'clwc_loyalty_points' );

    if ( isset( $loyalty_points['clwc_earning_points_money_spent'] ) && '' !== $loyalty_points['clwc_earning_points_money_spent'] ) {
        $points = $loyalty_points['clwc_earning_points_money_spent'];
    } else {
        $points = 0;
    }

	return apply_filters( 'clwc_earning_points_money_spent', $points );
}

