<?php
/**
 * Plugin Name: Lockdown WP
 * Description: Lockdown WP
 * Author: dtbaker
 * Author URI: https://dtbaker.net
 * Version: 0.0.1
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * Text Domain: lockdown-wp
 *
 * @package Dtbaker/Lockdown_WP
 *
 * Lockdown WP for WordPress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LOCKDOWN_WP_SLUG', 'lockdown-wp' );
define( 'LOCKDOWN_WP_VER', '0.0.1' );
define( 'LOCKDOWN_WP_FILE', __FILE__ );
define( 'LOCKDOWN_WP_DIR', plugin_dir_path( LOCKDOWN_WP_FILE ) );
define( 'LOCKDOWN_WP_URI', plugins_url( '/', LOCKDOWN_WP_FILE ) );
define( 'LOCKDOWN_WP_CONTENT_NAME', 'Template Kit' );
define( 'LOCKDOWN_WP_PHP_VERSION', '5.6' );

add_action( 'plugins_loaded', 'lockdown_wp_load_plugin_textdomain' );

if ( ! version_compare( PHP_VERSION, LOCKDOWN_WP_PHP_VERSION, '>=' ) ) {
	add_action( 'admin_notices', 'lockdown_wp_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.6', '>=' ) ) {
	add_action( 'admin_notices', 'lockdown_wp_3fail_wp_version' );
} else {
	require LOCKDOWN_WP_DIR . 'inc/bootstrap.php';
}


/**
 * Load Lockdown WP textdomain.
 *
 * Load gettext translate for Lockdown WP text domain.
 *
 * @since 0.0.2
 *
 * @return void
 */
function lockdown_wp_load_plugin_textdomain() {
	load_plugin_textdomain( 'lockdown-wp' );
}


/**
 * Lockdown WP admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 0.0.2
 *
 * @return void
 */
function lockdown_wp_fail_php_version() {
	$message = sprintf(
	/* translators: %s: PHP version */
		esc_html__( 'Lockdown WP requires PHP version %s+, plugin is currently NOT ACTIVE. Please contact the hosting provider. WordPress recommends version %s.', 'lockdown-wp' ),
		LOCKDOWN_WP_PHP_VERSION,
		sprintf(
			'<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://wordpress.org/about/requirements/' ),
			esc_html__( '7.2 or above', 'lockdown-wp' )
		)
	);

	$html_message = sprintf( '<div class="error">%s</div> ', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Lockdown WP admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version .
 *
 * @since 0.0.2
 *
 * @return void
 */
function lockdown_wp_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( 'Lockdown WP requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT ACTIVE.', 'lockdown-wp' ), '4.6' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
