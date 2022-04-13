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
    //$user = get_userdata( $profileuser->ID );
    ?>
        <h2><?php esc_attr_e( 'Customer Loyalty for WooCommerce', 'customer-loyalty-for-woocommerce' ); ?></h2>

        <table class="form-table">
        <tr>
            <th scope="row"><?php esc_attr_e( 'Loyalty points', 'customer-loyalty-for-woocommerce' ); ?></th>
            <td>
                <input class="regular-text" type="number" name="clwc_loyalty_points" value="<?php echo esc_html( get_user_meta( $profileuser->ID, 'clwc_loyalty_points', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_attr_e( 'Rewards card punches', 'customer-loyalty-for-woocommerce' ); ?></th>
            <td>
                <input class="regular-text" type="number" name="clwc_rewards_card_punches" value="<?php echo esc_html( get_user_meta( $profileuser->ID, 'clwc_rewards_card_punches', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_attr_e( 'Rewards earned', 'customer-loyalty-for-woocommerce' ); ?></th>
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
    //$user = get_userdata( $user_id );

    // Update customer loyalty points.
    if ( isset( $_POST ) && isset( $_POST['clwc_loyalty_points'] ) ) {
        update_user_meta( $user_id, 'clwc_loyalty_points', sanitize_text_field( filter_input( INPUT_POST, 'clwc_loyalty_points' ) ) );
    }

    // Update customer rewards card punches.
    if ( isset( $_POST ) && isset( $_POST['clwc_rewards_card_punches'] ) ) {
        update_user_meta( $user_id, 'clwc_rewards_card_punches', sanitize_text_field( filter_input( INPUT_POST, 'clwc_rewards_card_punches' ) ) );
    }

    // Update customer card punches.
    if ( isset( $_POST ) && isset( $_POST['clwc_rewards_earned'] ) ) {
        update_user_meta( $user_id, 'clwc_rewards_earned', sanitize_text_field( filter_input( INPUT_POST, 'clwc_rewards_earned' ) ) );
    }

}
add_action( 'personal_options_update', 'clwc_save_custom_profile_fields' );
add_action( 'edit_user_profile_update', 'clwc_save_custom_profile_fields' );
