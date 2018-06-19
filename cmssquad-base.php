<?php
/*
Plugin Name: CMSSquad Base
Plugin URI: https://github.com/cmssquad/cmssquad-base
Description: Collection of base features to build a theme with Bootstrap and SiteOrigin Page Builder.
Version: 1.0
Author: CMSSquad
Author URI: https://github.com/cmssquad
License: MIT
License URI: https://opensource.org/licenses/MIT
Text Domain: cmssquad-base
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('CMSSQUAD_BASE_VERSION', '0.0.1');

class CMSSquad_Base {

	/* singleton instance */
	private static $instance;

	private static $plugin_dir_url = "", $plugin_dir_path = "";

	/**
	 * Get a single instance of this class
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CMSSquad_Base ) ) {
			self::$instance = new CMSSquad_Base();
		}

		return self::$instance;
	}

	/**
	 * Disable object clone
	 */
	private function __clone() {}

	public function __construct() {

		// set static variables
		self::$plugin_dir_path = plugin_dir_path( __FILE__ );
		self::$plugin_dir_url  = plugin_dir_url( __FILE__ );

		// includes CMSSquad SiteOrigin Grid
		require_once( self::get_plugin_dir_path() . 'cmssquad-so-grid/cmssquad-so-grid.php' );

		// includes CMSSquad SiteOrigin Widgets
		require_once( self::get_plugin_dir_path() . 'cmssquad-so-widgets/cmssquad-so-widgets.php' );

		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_base_styles') );

	}

	/**
	 * Enqueue general CSS files
	 */
	public static function enqueue_base_styles() {

		// FontAwesome
		wp_enqueue_style('cmssquad-base-fontawesome',
			self::get_plugin_dir_url() . 'assets/css/fontawesome-all.min.css',
			array(),
			'5.0.13');

	}


	/**
	 * Get this plugin root directory path.
	 * @return string The plugin's directory path or empty when it's failed.
	 */
	public static function get_plugin_dir_path() {
		return self::$plugin_dir_path;
	}

	/**
	 * Get this plugin URL.
	 * @return string URL to the plugin's root directory or empty when it's failed.
	 */
	public static function get_plugin_dir_url() {
		return self::$plugin_dir_url;
	}


}

// instantiate
CMSSquad_Base::get_instance();