<?php
/**
 * Lockdown WP: Bootstrap File
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


spl_autoload_register(
	function ( $class ) {
		$prefix   = __NAMESPACE__;
		$base_dir = __DIR__;
		$len      = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}
		$relative_class = strtolower( substr( $class, $len + 1 ) );
		$relative_class = 'class-' . $relative_class;
		$file           = $base_dir . DIRECTORY_SEPARATOR . str_replace( [ '\\', '_' ], [
				'/',
				'-'
			], $relative_class ) . '.php';
		if ( file_exists( $file ) ) {
			require $file;
		} else {
			die( esc_html( basename( $file ) . ' missing.' ) );
		}
	}
);


Plugin::get_instance();
Section::get_instance();
Tests::get_instance();
