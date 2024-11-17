<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    CLWC
 * @subpackage CLWC/includes
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    wp_die();
}

/**
 * Random String function.
 * 
 * @param int $length 
 * 
 * @since  1.0.0
 * @return string
 */
function clwc_get_random_string( $length = 6 ) {
    // Characters to use when creating random string.
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Create string.
    $string = '';

    // Add characters to string.
    for ( $i = 0; $i < $length; $i++ ) {
        $string .= $characters[mt_rand( 0, strlen( $characters ) - 1 )];
    }

    // Add prefix (if any).
    $string = clwc_rewards_card_coupon_prefix() . $string;

    // Filter string.
    $string = apply_filters( 'clwc_get_random_string', $string );

    return $string;
}

/**
 * Check if the Rewards Card is activated.
 *
 * Determines whether the rewards card feature is enabled based on plugin settings.
 *
 * @since  1.0.0
 * @return bool True if the rewards card is activated, false otherwise.
 */
function clwc_rewards_card_activate() {
    // Retrieve the rewards card settings option.
    $settings = get_option( 'clwc_rewards_card_settings' );

    // Default to false if the rewards card activation setting is not configured.
    $is_active = false;

    // Check if the rewards card activation setting is defined and true.
    if ( isset( $settings['activate_rewards_card'] ) && '1' === $settings['activate_rewards_card'] ) {
        $is_active = true;
    }

    /**
     * Filter the rewards card activation status.
     *
     * @param bool $is_active True if the rewards card is activated, false otherwise.
     */
    return apply_filters( 'clwc_rewards_card_activate', $is_active );
}

/**
 * Get the rewards card title.
 *
 * Retrieves the title displayed on the rewards card, with a default fallback if not set.
 *
 * @since  1.0.0
 * @return string Rewards card title.
 */
function clwc_rewards_card_title() {
    // Retrieve the rewards card settings option.
    $settings = get_option( 'clwc_rewards_card_settings' );

    // Default title.
    $title = esc_attr__( 'You earned a reward', 'customer-loyalty-for-woocommerce' );

    // Check if the rewards card title setting is defined and not empty.
    if ( isset( $settings['rewards_card_title'] ) && '' !== $settings['rewards_card_title'] ) {
        $title = sanitize_text_field( $settings['rewards_card_title'] );
    }

    /**
     * Filter the rewards card title.
     *
     * @param string $title Rewards card title.
     */
    return apply_filters( 'clwc_rewards_card_title', $title );
}

/**
 * Get the rewards card text.
 *
 * Retrieves the main text or message displayed on the rewards card.
 *
 * @since  1.0.0
 * @return string|bool Rewards card text, or false if not set.
 */
function clwc_rewards_card_text() {
    // Retrieve the rewards card settings option.
    $settings = get_option( 'clwc_rewards_card_settings' );

    // Default to false if the rewards card text setting is not configured.
    $text = false;

    // Check if the rewards card text setting is defined and not empty.
    if ( isset( $settings['rewards_card_text'] ) && '' !== $settings['rewards_card_text'] ) {
        $text = wp_kses_post( $settings['rewards_card_text'] );
    }

    /**
     * Filter the rewards card text.
     *
     * @param string|bool $text Rewards card text, or false if not set.
     */
    return apply_filters( 'clwc_rewards_card_text', $text );
}

/**
 * Get the rewards card image.
 *
 * Retrieves the URL or ID of the image displayed on the rewards card.
 *
 * @since  1.0.0
 * @return string|bool Rewards card image URL or ID, or false if not set.
 */
function clwc_rewards_card_image() {
    // Retrieve the rewards card settings option.
    $settings = get_option( 'clwc_rewards_card_settings' );

    // Default to false if the rewards card image setting is not configured.
    $image = false;

    // Check if the rewards card image setting is defined and not empty.
    if ( isset( $settings['rewards_card_image'] ) && '' !== $settings['rewards_card_image'] ) {
        $image = esc_url( $settings['rewards_card_image'] );
    }

    /**
     * Filter the rewards card image.
     *
     * @param string|bool $image Rewards card image URL or ID, or false if not set.
     */
    return apply_filters( 'clwc_rewards_card_image', $image );
}

/**
 * Get the rewards card required punches.
 *
 * Retrieves the number of punches required to redeem a rewards card coupon.
 *
 * @since  1.0.0
 * @return int|bool Number of punches required, or false if not set.
 */
function clwc_rewards_card_required_punches() {
    // Retrieve the rewards card settings option.
    $settings = get_option( 'clwc_rewards_card_settings' );

    // Default to false if required punches setting is not configured.
    $required_punches = false;

    // Check if the required punches setting is defined and not empty.
    if ( isset( $settings['required_punches'] ) && '' !== $settings['required_punches'] ) {
        $required_punches = (int) $settings['required_punches'];
    }

    /**
     * Filter the rewards card required punches.
     *
     * @param int|bool $required_punches Number of punches required.
     */
    return apply_filters( 'clwc_rewards_card_required_punches', $required_punches );
}

/**
 * Get the rewards card coupon amount.
 *
 * Retrieves the coupon amount that is awarded after the required punches are met.
 *
 * @since  1.0.0
 * @return float|bool Coupon amount, or false if not set.
 */
function clwc_rewards_card_coupon_amount() {
    // Retrieve the rewards card settings option.
    $settings = get_option( 'clwc_rewards_card_settings' );

    // Default to false if coupon amount setting is not configured.
    $coupon_amount = false;

    // Check if the coupon amount setting is defined and not empty.
    if ( isset( $settings['coupon_amount'] ) && '' !== $settings['coupon_amount'] ) {
        $coupon_amount = (float) $settings['coupon_amount'];
    }

    /**
     * Filter the rewards card coupon amount.
     *
     * @param float|bool $coupon_amount Coupon amount.
     */
    return apply_filters( 'clwc_rewards_card_coupon_amount', $coupon_amount );
}

/**
 * Get the rewards card coupon type.
 *
 * Retrieves the type of coupon awarded, such as fixed cart discount or percentage discount.
 *
 * @since  1.0.0
 * @return string|bool Coupon type (e.g., 'fixed_cart' or 'percentage'), or false if not set.
 */
function clwc_rewards_card_coupon_type() {
    // Retrieve the rewards card settings option.
    $settings = get_option( 'clwc_rewards_card_settings' );

    // Default to false if coupon type setting is not configured.
    $coupon_type = false;

    // Check if the coupon type setting is defined and not empty.
    if ( isset( $settings['coupon_type'] ) && '' !== $settings['coupon_type'] ) {
        $coupon_type = sanitize_text_field( $settings['coupon_type'] );
    }

    /**
     * Filter the rewards card coupon type.
     *
     * @param string|bool $coupon_type Coupon type.
     */
    return apply_filters( 'clwc_rewards_card_coupon_type', $coupon_type );
}

/**
 * Get the rewards card coupon prefix.
 *
 * Retrieves the prefix applied to the coupon code for rewards card redemptions.
 *
 * @since  1.0.0
 * @return string|bool Coupon prefix, or false if not set.
 */
function clwc_rewards_card_coupon_prefix() {
    // Retrieve the rewards card settings option.
    $settings = get_option( 'clwc_rewards_card_settings' );

    // Default to false if coupon prefix setting is not configured.
    $coupon_prefix = false;

    // Check if the coupon prefix setting is defined and not empty.
    if ( isset( $settings['coupon_prefix'] ) && '' !== $settings['coupon_prefix'] ) {
        $coupon_prefix = sanitize_text_field( $settings['coupon_prefix'] );
    }

    /**
     * Filter the rewards card coupon prefix.
     *
     * @param string|bool $coupon_prefix Coupon prefix.
     */
    return apply_filters( 'clwc_rewards_card_coupon_prefix', $coupon_prefix );
}

/**
 * Check if Loyalty Points is activated.
 *
 * Determines whether the loyalty points system is enabled based on the plugin settings.
 *
 * @since  1.0.0
 * @return bool True if loyalty points is activated, false otherwise.
 */
function clwc_loyalty_points_activate() {
    // Retrieve the loyalty points settings option.
    $settings = get_option( 'clwc_loyalty_points_settings' );

    // Default to false if the loyalty points activation setting is not configured.
    $is_active = false;

    // Check if the loyalty points activation setting is defined and is set to '1'.
    if ( isset( $settings['activate_loyalty_points'] ) && '1' === (string) $settings['activate_loyalty_points'] ) {
        $is_active = true;
    }

    return apply_filters( 'clwc_loyalty_points_activate', $is_active );
}
/**
 * Get the loyalty points redeem points calculation type.
 *
 * Retrieves the type of calculation to use for loyalty points redemption (e.g., order subtotal or order total).
 *
 * @since  1.0.0
 * @return string|bool Calculation type for points redemption, or false if not set.
 */
function clwc_loyalty_points_redeem_points_calculation_type() {
    // Retrieve the loyalty points settings option.
    $settings = get_option( 'clwc_loyalty_points_settings' );

    // Default to false if the calculation type setting is not configured.
    $calculation_type = false;

    // Check if the calculation type setting is defined and not empty.
    if ( isset( $settings['calculation_type'] ) && '' !== $settings['calculation_type'] ) {
        $calculation_type = sanitize_text_field( $settings['calculation_type'] );
    }

    /**
     * Filter the loyalty points redeem points calculation type.
     *
     * @param string|bool $calculation_type Calculation type for points redemption.
     */
    return apply_filters( 'clwc_loyalty_points_redeem_points_calculation_type', $calculation_type );
}

/**
 * Get the loyalty points redeem points minimum.
 *
 * Retrieves the minimum number of points required to redeem, based on plugin settings.
 *
 * @since  1.0.0
 * @return int|bool Minimum number of points required for redemption, or false if not set.
 */
function clwc_loyalty_points_redeem_points_minimum() {
    // Retrieve the loyalty points settings option.
    $settings = get_option( 'clwc_loyalty_points_settings' );

    // Default to false if the minimum points setting is not configured.
    $redeem_points_minimum = false;

    // Check if the redeem points minimum setting is defined and not empty.
    if ( isset( $settings['minimum_points'] ) && '' !== $settings['minimum_points'] ) {
        $redeem_points_minimum = (int) $settings['minimum_points'];
    }

    /**
     * Filter the loyalty points redeem points minimum.
     *
     * @param int|bool $redeem_points_minimum Minimum number of points required for redemption.
     */
    return apply_filters( 'clwc_loyalty_points_redeem_points_minimum', $redeem_points_minimum );
}

/**
 * Get the loyalty points redeem points value.
 *
 * Retrieves the value set for redeeming loyalty points, based on plugin settings.
 *
 * @since  1.0.0
 * @return int|bool Number of points needed for redemption, or false if not set.
 */
function clwc_loyalty_points_redeem_points_value() {
    // Retrieve the loyalty points settings option.
    $settings = get_option( 'clwc_loyalty_points_settings' );

    // Default to false if the redeem points setting is not configured.
    $redeem_points_value = false;

    // Check if the redeem points value setting is defined and not empty.
    if ( isset( $settings['redeemable_value'] ) && '' !== $settings['redeemable_value'] ) {
        $redeem_points_value = (int) $settings['redeemable_value'];
    }

    /**
     * Filter the loyalty points redeem points value.
     *
     * @param int|bool $redeem_points_value The number of points needed for redemption.
     */
    return apply_filters( 'clwc_loyalty_points_redeem_points_value', $redeem_points_value );
}

/**
 * Get the earning points for customer registration.
 *
 * Retrieves the number of loyalty points awarded upon customer registration, based on plugin settings.
 *
 * @since  1.0.0
 * @return int Number of points to award on customer registration.
 */
function clwc_earning_points_customer_registration() {
    // Retrieve the loyalty points settings option.
    $settings = get_option( 'clwc_loyalty_points_settings' );

    // Default to 0 points if setting is not configured.
    $registration_points = 0;

    // Check if the customer registration points setting is defined and not empty.
    if ( isset( $settings['customer_registration'] ) && '' !== $settings['customer_registration'] ) {
        $registration_points = (int) $settings['customer_registration'];
    }

    /**
     * Filter the number of points awarded on customer registration.
     *
     * @param int $registration_points Number of points for customer registration.
     */
    return apply_filters( 'clwc_earning_points_customer_registration', $registration_points );
}

/**
 * Get the earning points for order completion.
 *
 * Retrieves the number of loyalty points awarded upon completing an order, based on plugin settings.
 *
 * @since  1.0.0
 * @return int Points awarded on order completion.
 */
function clwc_earning_points_order_complete() {
    // Retrieve the loyalty points settings option.
    $settings = get_option( 'clwc_loyalty_points_settings' );

    // Default to 0 points if the order complete setting is not configured.
    $complete_points = 0;

    // Check if the order complete points setting is defined and not empty.
    if ( isset( $settings['order_complete'] ) && '' !== $settings['order_complete'] ) {
        $complete_points = (int) $settings['order_complete'];
    }

    /**
     * Filter the points awarded for order completion.
     *
     * @param int $complete_points Points awarded for completing an order.
     */
    return apply_filters( 'clwc_earning_points_order_complete', $complete_points );
}

/**
 * Get the earning points for money spent.
 *
 * Retrieves the number of loyalty points awarded based on the amount of money spent, as set in the plugin settings.
 *
 * @since  1.0.0
 * @return int Points awarded per amount spent.
 */
function clwc_earning_points_money_spent() {
    // Retrieve the loyalty points settings option.
    $settings = get_option( 'clwc_loyalty_points_settings' );

    // Default to 0 if the money spent setting is not configured.
    $points_per_money_spent = 0;

    // Check if the money spent points setting is defined and not empty.
    if ( isset( $settings['money_spent'] ) && '' !== $settings['money_spent'] ) {
        $points_per_money_spent = (int) $settings['money_spent'];
    }

    /**
     * Filter the points awarded per amount of money spent.
     *
     * @param int $points_per_money_spent Points awarded per amount spent.
     */
    return apply_filters( 'clwc_earning_points_money_spent', $points_per_money_spent );
}

/**
 * Insert a loyalty log entry.
 *
 * @param int    $user_id User ID.
 * @param string $name    User display name.
 * @param string $email   User email.
 * @param int    $points  Points awarded or redeemed.
 * @param string $details Action details.
 *
 * @since  1.0.0
 * @return void
 */
function clwc_insert_loyalty_log_entry( $user_id, $name, $email, $points, $details ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'clwc_loyalty_log';

    // Attempt to insert the log entry.
    $result = $wpdb->insert(
        $table_name,
        [
            'user_id' => $user_id,
            'name'    => $name,
            'email'   => $email,
            'points'  => $points,
            'details' => $details,
            'date'    => current_time( 'mysql' ),
        ],
        [
            '%d',
            '%s',
            '%s',
            '%d',
            '%s',
            '%s',
        ]
    );

    // Log any database errors for debugging.
    if ( false === $result ) {
        error_log( 'Failed to insert log entry: ' . $wpdb->last_error );
    } else {
        error_log( 'Log entry added for user ID ' . $user_id );
    }
}
