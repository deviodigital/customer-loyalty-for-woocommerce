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

/**
 * Add Customer Loyalty profile options to Edit User screen
 * 
 * @since 1.0
 */
function clwc_add_customer_loyalty_profile_options( $profileuser ) {

    // Get user data.
    $user = get_userdata( $profileuser->ID );
    ?>
        <h2><?php _e( 'Customer Loyalty for WooCommerce', 'clwc' ); ?></h2>

        <table class="form-table">
        <tr>
            <th scope="row"><?php _e( 'Loyalty points', 'clwc' ); ?></th>
            <td>
                <input class="regular-text" type="number" name="clwc_loyalty_points" value="<?php echo esc_html( get_user_meta( $profileuser->ID, 'clwc_loyalty_points', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e( 'Rewards card punches', 'clwc' ); ?></th>
            <td>
                <input class="regular-text" type="number" name="clwc_rewards_card_punches" value="<?php echo esc_html( get_user_meta( $profileuser->ID, 'clwc_rewards_card_punches', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e( 'Rewards earned', 'clwc' ); ?></th>
            <td>
                <input class="regular-text" type="number" name="clwc_rewards_earned" value="<?php echo esc_html( get_user_meta( $profileuser->ID, 'clwc_rewards_earned', true ) ); ?>" />
            </td>
        </tr>
        </table>

    <?php
}
add_action( 'show_user_profile', 'clwc_add_customer_loyalty_profile_options' );
add_action( 'edit_user_profile', 'clwc_add_customer_loyalty_profile_options' );

/**
 * Save customer punch card punches.
 * 
 * @since 1.0
 */
function clwc_save_custom_profile_fields( $user_id ) {

    // Get user.
    $user = get_userdata( $user_id );

    // Update customer loyalty points.
    if ( isset( $_POST['clwc_loyalty_points'] ) ) {
        update_user_meta( $user_id, 'clwc_loyalty_points', esc_html( $_POST['clwc_loyalty_points'] ) );
    }

    // Update customer rewards card punches.
    if ( isset( $_POST['clwc_rewards_card_punches'] ) ) {
        update_user_meta( $user_id, 'clwc_rewards_card_punches', esc_html( $_POST['clwc_rewards_card_punches'] ) );
    }

    // Update customer card punches.
    if ( isset( $_POST['clwc_rewards_earned'] ) ) {
        update_user_meta( $user_id, 'clwc_rewards_earned', esc_html( $_POST['clwc_rewards_earned'] ) );
    }

}
add_action( 'personal_options_update', 'clwc_save_custom_profile_fields' );
add_action( 'edit_user_profile_update', 'clwc_save_custom_profile_fields' );
