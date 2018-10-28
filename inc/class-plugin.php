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
 * Lockdown WP plugin.
 *
 * The main plugin handler class is responsible for initializing Lockdown WP. The
 * class registers and all the components required to run the plugin.
 *
 * @since 0.0.2
 */
class Plugin extends Base {


	/**
	 * Initializing Lockdown WP plugin.
	 *
	 * @since 0.0.2
	 * @access private
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_init', [ $this, 'admin_init' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'plugins_loaded', [ $this, 'db_upgrade_check' ] );
		add_action( 'lockdown_wp_cron', [ $this, 'run_cron' ] );
		add_action( 'admin_action_lockdown_wp_options', [ $this, 'save_options' ] );

	}


	/**
	 * Runs in the admin init WordPress hook and sets everything up.
	 *
	 * @since 0.0.2
	 * @access public
	 */
	public function admin_init() {

	}

	/**
	 * Runs the daily cron action.
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function run_cron() {

	}

	/**
	 * Sets up the admin menu options.
	 *
	 * @since 0.0.2
	 * @access public
	 */
	public function admin_menu() {

		$page = add_menu_page(
			__( 'Lockdown WP', 'lockdown-wp' ),
			__( 'Lockdown WP', 'lockdown-wp' ),
			'manage_options',
			LOCKDOWN_WP_SLUG,
			[ Section::get_instance(), 'admin_menu_open' ],
			'',
			99
		);
		add_action( 'admin_print_scripts-' . $page, [ $this, 'admin_page_assets' ] );


	}

	public function admin_page_assets() {

		wp_enqueue_style( 'lockdown-wp-admin', LOCKDOWN_WP_URI . 'assets/css/main.min.css', [], LOCKDOWN_WP_VER );
		wp_enqueue_script( 'lockdown-wp-admin', LOCKDOWN_WP_URI . 'assets/js/app.min.js', [], LOCKDOWN_WP_VER );
	}

	public function db_upgrade_check() {
		if ( Options::get_instance()->get( 'installed_version' ) != LOCKDOWN_WP_VER ) {
			$this->activation();
		}
	}

	public function activation() {
		Options::get_instance()->set( 'installed_version', LOCKDOWN_WP_VER );
	}

	public function save_options() {
		// Check if our nonce is set.
		if ( ! isset( $_POST['lockdown_wp_options'] ) ) { // WPCS: input var okay.
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['lockdown_wp_options'], 'lockdown_wp_options' ) ) { // WPCS: sanitization ok. input var okay.
			return;
		}

		$save_options = isset( $_POST['lockdown_wp'] ) && is_array( $_POST['lockdown_wp'] ) ? $_POST['lockdown_wp'] : [];
		if ( $save_options ) {
			$options_to_save  = [];
			$lockdown         = Lockdown::get_instance();
			$lockdown_options = $lockdown->get_lockdown_options();
			// reset all enabled options
			foreach ( $lockdown_options as $key => $val ) {
				unset( $lockdown_options[ $key ]['enabled'] );
				if ( ! empty( $save_options[ $key ] ) ) {
					$options_to_save[ $key ] = 1;
				}
			}
			Options::get_instance()->set( 'enabled_options', $options_to_save );
		}

		wp_safe_redirect( admin_url( 'admin.php?page=' . LOCKDOWN_WP_SLUG ) );

	}

}
