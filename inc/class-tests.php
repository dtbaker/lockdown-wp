<?php
/**
 * Lockdown WP:
 *
 * All the fun stuff happens in this class.
 *
 * @package Dtbaker/Lockdown_WP
 * @since 0.0.2
 */

namespace Lockdown_WP;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Some poorly written php code to test if the lockdown is working.
 *
 * @since 0.0.2
 */
class Tests extends Base {


	/**
	 * Initializing Lockdown WP plugin.
	 *
	 * @since 0.0.2
	 * @access private
	 */
	public function init() {
		add_shortcode( 'lockdown_test', [ $this, 'lockdown_shortcode' ] );
	}

	public function lockdown_shortcode() {
		echo 'test shortcode';
	}

}
