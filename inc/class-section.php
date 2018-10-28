<?php
/**
 * Lockdown WP: About Page
 *
 * Handles the display of the about page.
 *
 * @package Dtbaker/Lockdown_WP
 * @since 0.0.7
 */

namespace Lockdown_WP;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Feedback registration and management.
 *
 * @since 0.0.2
 */
class Section extends Base {

	/**
	 * Called when the user visits our the about page.
	 */
	public function admin_menu_open() {

		$this->content = $this->render_template( 'sections/settings.php' );
		echo $this->render_template( 'wrapper.php' );  // WPCS: XSS ok.

	}

}
