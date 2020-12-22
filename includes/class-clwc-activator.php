<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.deviodigital.com
 * @since      1.0
 *
 * @package    CLWC
 * @subpackage CLWC/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0
 * @package    CLWC
 * @subpackage CLWC/includes
 * @author     Devio Digital <contact@deviodigital.com>
 */
class Customer_Loyalty_Activator {

	/**
	 * Functions to run during activation.
	 *
	 * @since    1.0
	 */
	public static function activate() {
		clwc_flush_rewrite_rules();
	}

}
