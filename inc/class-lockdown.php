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
 * Lockdown code
 *
 * @since 0.0.2
 */
class Lockdown extends Base {


	/**
	 * Initializing Lockdown WP plugin.
	 *
	 * @since 0.0.2
	 * @access private
	 */
	public function __construct() {
		$this->apply_chosen_lockdowns();
		parent::__construct();
	}

	public function get_lockdown_options() {
		$options = [];

		$options['superglobals']     = [
			'title'       => 'Disable PHP superglobals',
			'description' => 'PHP superglobals are $_POST, $_GET, $_COOKIES, $_FILES and $_REQUEST. ',
			'pro'         => 'Some badly written WordPress themes and plugins will allow hackers to break your website by not properly sanitising input variables or file uploads. By disabling these it completely prevents this type of attack vector.',
			'con'         => 'This only works if you have WordPress pretty permalinks enabled (Settings > Permalinks). This may stop some more advanced features on your website from working (e.g. Shopping carts, Membership systems). After enabling this option please thorougly test each page of your website to confirm it still works.',
		];
		$options['rss']              = [
			'title'       => 'Disable RSS',
			'description' => 'RSS feeds are an aging technology that is not used very often any more.',
			'pro'         => 'Disabling this will remove the RSS feature from the website and clean up html output.',
			'con'         => 'Anyone who is still using an RSS reader may not get notifications about new blog posts.'
		];
		$options['rest']             = [
			'title'       => 'Disable REST API',
			'description' => 'The REST API is not used on a lot of websites and can usually be safely disabled.',
			'pro'         => 'There are some concerns around',
			'con'         => 'Anyone who is still using an RSS reader may not get notifications about new blog posts.'
		];
		$options['user_enumeration'] = [
			'title'       => 'Disable User Enumeration',
			'description' => 'It can be possible to find a list of usernames on on WordPress install. This can help attackers guess the login to your website.',
			'pro'         => 'Attackers will no longer be able to enumerate a list of users on the blog',
			'con'         => 'All is good, disable this one.'
		];
		$options['other']            = [
			'title'       => 'Disable Other',
			'description' => 'There are a few other things that I like to disable (emoji code, oembed api, admin_canonical_url, w3tc comment, wpseo comment, WordPress version in header)',
			'pro'         => 'Usually safe to remove. Hides some additional information from your website.',
			'con'         => 'This may stop some emojis from displaying and may stop some oembeds from working.'
		];

		// Filter which ones are available.
		$options         = apply_filters( 'lockdown_wp_options', $options );
		$enabled_options = Options::get_instance()->get( 'enabled_options' );
		if ( is_array( $enabled_options ) && ! empty( $enabled_options ) ) {
			foreach ( $enabled_options as $enabled_option => $tf ) {
				if ( isset( $options[ $enabled_option ] ) ) {
					$options[ $enabled_option ]['enabled'] = true;
				}
			}
		}


		return $options;
	}

	/**
	 * Fires when object is created and we apply any chosen lock downs from the UI.
	 *
	 * @since 0.0.2
	 * @access public
	 */
	public function apply_chosen_lockdowns() {


		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'wp_generator' );


		remove_action( 'template_redirect', 'maybe_redirect_404' );

		// disable wp-json
		add_filter( 'rest_authentication_errors', function ( $result ) {
			return is_user_logged_in() ? $result : new \WP_Error( 'disabled', 'Disabled', array( 'status' => 401 ) );
		} );

		add_action( 'after_setup_theme', function () {

			// Filters for WP-API version 1.x
			add_filter( 'json_enabled', '__return_false' );
			add_filter( 'json_jsonp_enabled', '__return_false' );

			// Filters for WP-API version 2.x
			add_filter( 'rest_enabled', '__return_false' );
			add_filter( 'rest_jsonp_enabled', '__return_false' );

			// remove the wp-json header output
			remove_action( 'rest_api_init', 'wp_oembed_register_route' );
			remove_action( 'wp_head', 'rest_output_link_wp_head' );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			remove_action( 'template_redirect', 'rest_output_link_header', 11 );
			// Turn off oEmbed auto discovery.
			add_filter( 'embed_oembed_discover', '__return_false' );
			// Don't filter oEmbed results.
			remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
			// Remove oEmbed-specific JavaScript from the front-end and back-end.
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
			// Remove all embeds rewrite rules.
			//add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

			// remove s.w.org dns-prefetch
			remove_action( 'wp_head', 'wp_resource_hints', 2 );

			if ( class_exists( '\WPSEO_Frontend' ) ) {
				$inst = \WPSEO_Frontend::get_instance();
				remove_action( 'wpseo_head', array( $inst, 'debug_mark' ), 2 );
			}

			//remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			//remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			//add_filter( 'tiny_mce_plugins', 'machete_disable_emojicons_tinymce' );
		} );


		add_filter( 'disable_wpseo_json_ld_search', '__return_true' );

		// remove version number from all scripts/styles.
		add_filter( 'style_loader_src', function ( $src, $handle ) {
			//	$src = remove_query_arg( 'ver', $src );
			return $src;
		}, 1, 2 );
		add_filter( 'script_loader_src', function ( $src, $handle ) {
			//	$src = remove_query_arg( 'ver', $src );
			return $src;
		}, 1, 2 );

		add_action( 'wp', function () {
			if ( ! is_admin() ) { // maybe is_user_logged_in() as an option too?
				// remove super globals.
				// wow. I feel dirty writing this.
				// this will capture any bad code that slips through the cracks, and prevent reflection attacks.
				$_POST    = array();
				$_GET     = array();
				$_FILES   = array();
				$_COOKIE  = array();
				$_REQUEST = array();
			}
		} );


		add_action( 'admin_init', function () {
			remove_action( 'admin_head', 'wp_admin_canonical_url' );
		} );
		// Disable W3TC footer comment for all users
		add_filter( 'w3tc_can_print_comment', '__return_false', 10, 1 );


		// xml rpc


	}

}
