<?php
/**
 * Lockdown WP:
 *
 * This starts things up. Registers the SPL and starts up some classes.
 *
 * @package Dtbaker/Lockdown_WP
 * @since 0.0.2
 */

namespace Lockdown_WP;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Template class for our CPT to store imported data separate to Elementor or Beaver Builder etc..
 *
 * @since 0.0.2
 */
class Template extends Base {

	/**
	 * Template constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	public function init() {

	}

}
