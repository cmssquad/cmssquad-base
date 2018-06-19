<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('CSOW_VERSION', '0.0.1');

class CMSSquad_SO_Widgets {

	/* singleton instance */
	private static $instance;

	/**
	 * Get a single instance of this class
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CMSSquad_SO_Widgets ) ) {
			self::$instance = new CMSSquad_SO_Widgets();
		}

		return self::$instance;
	}

	/**
	 * Disabled object clone
	 */
	private function __clone() {}

	/**
	 * CMSSquad_SO_Widgets constructor.
	 */
	private function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// load text domain

		//
		add_filter( 'siteorigin_widgets_widget_folders', array( $this, 'register_widget_folders' ) );
		add_filter( 'siteorigin_panels_widget_dialog_tabs', array( $this, 'register_widget_dialog_tabs' ), 20 );
		add_filter( 'siteorigin_widgets_field_class_prefixes', array( $this, 'register_fields_class_prefixes' ) );
		add_filter( 'siteorigin_widgets_field_class_paths', array( $this, 'register_fields_class_paths' ) );

		add_filter( 'wp_enqueue_scripts', array($this, 'register_general_scripts') );
		add_filter( 'wp_enqueue_scripts', array($this, 'enqueue_active_widgets_scripts') );

	}

	/**
	 * Enqueue scripts for admin page
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( 'post.php' != $hook ) {
			return;
		}
		wp_enqueue_style( 'cmssquad-so-widgets-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), CSOW_VERSION );
	}

	/**
	 * Register all CMSSquad SiteOrigin Widgets under "widgets/" directory.
	 * @param $folders
	 *
	 * @return array
	 */
	public function register_widget_folders( $folders ) {
		$folders[] = plugin_dir_path( __FILE__ ) . 'widgets/';

		return $folders;
	}

	/**
	 * Create a tab entry for CMSSquad SO Widgets and the grouping filter.
	 * @param $tabs
	 *
	 * @return array
	 */
	public function register_widget_dialog_tabs( $tabs ) {
		$tabs[] = array(
			'title'  => __( 'CMSSquad Widgets', 'cmssquad-base' ),
			'filter' => array(
				'groups' => array( 'cmssquad-base' )
			)
		);

		return $tabs;
	}

	public function register_general_scripts() {
		wp_register_script(
			'CMSSquad-Base-TweenMax',
			CMSSquad_Base::get_plugin_dir_url() . 'assets/js/TweenMax.min.js',
			array(),
			'2.0.1',
			true
		);

		wp_register_script(
			'CMSSquad-Base-imagesLoaded',
			CMSSquad_Base::get_plugin_dir_url() . 'assets/js/imagesloaded.pkgd.min.js',
			array( 'jquery' ),
			'4.1.4',
			true
		);
	}

	/**
	 * Ensure active widgets' scripts are enqueued at the right time.
	 */
	function enqueue_active_widgets_scripts() {
		global $wp_registered_widgets;
		$sidebars_widgets = wp_get_sidebars_widgets();
		if( empty($sidebars_widgets) ) return;
		foreach( $sidebars_widgets as $sidebar => $widgets ) {
			if ( ! empty( $widgets ) && $sidebar !== "wp_inactive_widgets") {
				foreach ( $widgets as $i => $id ) {
					if ( ! empty( $wp_registered_widgets[$id] ) ) {
						$widget = $wp_registered_widgets[$id]['callback'][0];
						if ( !empty($widget) && is_object($widget) && is_subclass_of($widget, 'SiteOrigin_Widget') && is_active_widget( false, false, $widget->id_base ) ) {
							/* @var $widget SiteOrigin_Widget */
							$opt_wid = get_option( 'widget_' . $widget->id_base );
							preg_match( '/-([0-9]+$)/', $id, $num_match );
							$widget_instance = $opt_wid[ $num_match[1] ];
							$widget->enqueue_frontend_scripts( $widget_instance);
							$widget->generate_and_enqueue_instance_styles( $widget_instance );
						}
					}
				}
			}
		}
	}

	/**
	 * Tell SO Widgets Bundle for the prefix of our custom field class names to avoid conflicts with other class names.
	 * @param $class_prefixes
	 *
	 * @return array
	 */
	public function register_fields_class_prefixes( $class_prefixes ) {
		$class_prefixes[] = 'CSOW_Custom_Field_';
		return $class_prefixes;
	}

	/**
	 * Tell SO Widgets Bundle for which directory that our custom field class files are kept for autoloading.
	 * @param $class_paths
	 *
	 * @return array
	 */
	public function register_fields_class_paths( $class_paths ) {
		$class_paths[] = plugin_dir_path( __FILE__ ) . 'custom-fields/';
		return $class_paths;
	}

}

/* Instantiate it */
CMSSquad_SO_Widgets::get_instance();