<?php

/**
 * Fired during plugin activation
 *
 * @package    CLWC
 * @subpackage CLWC/includes
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    CLWC
 * @subpackage CLWC/includes
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 */
class Customer_Loyalty_Activator {

    /**
     * Activate
     *
     * @since  1.0.0
     * @return void
     */
    public static function activate() {
        clwc_flush_rewrite_rules();
    }

}
