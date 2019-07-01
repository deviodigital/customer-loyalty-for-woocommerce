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
        $title = FALSE;
    }

	return apply_filters( 'clwc_rewards_card_title', $title );
}
