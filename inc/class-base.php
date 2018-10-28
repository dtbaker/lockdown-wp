<?php
/**
 * Lockdown WP: Base
 *
 * Base class that we extend on
 *
 * @package Dtbaker/Lockdown_WP
 * @since 0.0.7
 */

namespace Lockdown_WP;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Envato Elements plugin.
 *
 * The main plugin handler class is responsible for initializing Envato Elements. The
 * class registers and all the components required to run the plugin.
 *
 * @since 0.0.2
 */
class Base {

	const PAGE_SLUG = LOCKDOWN_WP_SLUG;
	/**
	 * Holds the plugin instance.
	 *
	 * @since 0.0.2
	 * @access protected
	 * @static
	 *
	 * @var Base
	 */
	private static $instances = [];

	/**
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 0.0.2
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'lockdown-wp' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @access public
	 * @since 0.0.2
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'lockdown-wp' ), '1.0.0' );
	}

	/**
	 * Sets up a single instance of the plugin.
	 *
	 * @since 0.0.2
	 * @access public
	 * @static
	 *
	 * @return static An instance of the class.
	 */
	public static function get_instance() {
		$module = get_called_class();
		if ( ! isset( self::$instances[ $module ] ) ) {
			self::$instances[ $module ] = new $module();
		}

		return self::$instances[ $module ];
	}

	/**
	 * Initializing Envato Elements plugin.
	 *
	 * @since 0.0.2
	 * @access private
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'init' ], 0 );

	}

	public $content = '';

	/**
	 * Runs in the init WordPress hook and sets everything up.
	 *
	 * @since 0.0.2
	 * @access public
	 */
	public function init() {

	}

	public function get_url() {
		return admin_url( 'admin.php?page=' . self::PAGE_SLUG );
	}

	/**
	 * Render a template
	 *
	 * @param  string $default_template_path The path to the template, relative to the plugin's `views` folder
	 *
	 * @return string
	 */
	protected function render_template( $default_template_path, $variables = [] ) {
		do_action( 'elements_render_template_pre', $default_template_path, $this );
		$template_path = LOCKDOWN_WP_DIR . 'views/' . $default_template_path;
		$template_path = apply_filters( 'lockdown_wp_template_path', $template_path );
		if ( is_file( $template_path ) ) {
			ob_start();
			extract( $variables );
			require $template_path;
			$template_content = apply_filters( 'lockdown_wp_template_content', ob_get_clean(), $default_template_path, $template_path, $this );
		} else {
			$template_content = '';
		}
		do_action( 'lockdown_wp_render_template_post', $default_template_path, $this, $template_path, $template_content );

		return $template_content;
	}

}
