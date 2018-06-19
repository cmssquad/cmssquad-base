<?php

class CMSSquad_SO_Grid {

	/* singleton instance */
	private static $instance;

	/**
	 * Get a single instance of this class
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CMSSquad_SO_Grid ) ) {
			self::$instance = new CMSSquad_SO_Grid();

		}

		return self::$instance;
	}

	/**
	 * Disabled object clone
	 */
	private function __clone() {
	}

	private function __construct() {
		add_filter( 'siteorigin_panels_settings_defaults', array( $this, 'settings_defaults_bootstrap' ), 1 );
		add_filter( 'siteorigin_panels_settings_fields', array( $this, 'settings_fields_bootstrap' ), 20 );

		add_action( 'init', array( $this, 'load_includable_files' ) );

	}

	function settings_defaults_bootstrap( $defaults ) {
		// enable bootstrap grid by default
		$defaults['bootstrap-grid']    = true;
		$defaults['bootstrap-assets']  = true;
		$defaults['bootstrap-version'] = '4.1.0';
		$defaults['grid-options']      = 'col-md';

		return $defaults;
	}

	function settings_fields_bootstrap( $fields ) {

		$fields['grid'] = array(
			'title'  => __( 'Bootstrap', 'cmssquad-base' ),
			'fields' => array(),
		);

		$fields['grid']['fields']['bootstrap-grid'] = array(
			'type'        => 'checkbox',
			'label'       => __( 'Bootstrap Grid', 'cmssquad-base' ),
			'description' => __( 'Use Bootstrap grid instead of the default grid', 'cmssquad-base' ),
		);

		$fields['grid']['fields']['bootstrap-assets'] = array(
			'type'        => 'checkbox',
			'label'       => __( 'Include Bootstrap Assets', 'cmssquad-base' ),
			'description' => __( 'Include Bootstrap core files. Only enable this if you are not using Bootstrap in your theme.', 'cmssquad-base' ),
		);

		$fields['grid']['fields']['bootstrap-version'] = array(
			'type'        => 'select',
			'label'       => __( 'Bootstrap version', 'cmssquad-base' ),
			'options'     => array(
				'3.3.7' => 'v3.3.7',
				'4.1.0' => 'v4.1.0',
			),
			'description' => __( 'Select which Bootstrap version if you include Boostrap code files (see above).', 'cmssquad-base' ),
		);

		$fields['grid']['fields']['grid-options'] = array(
			'type'        => 'select',
			'label'       => __( 'Default Grid Option', 'cmssquad-base' ),
			'options'     => array(
				'col-xs' => 'col-xs',
				'col-sm' => 'col-sm',
				'col-md' => 'col-md',
				'col-lg' => 'col-lg',
			),
			'description' => __( 'The default grid column to be used. Look at <a href="http://getbootstrap.com/css/#grid-options">Grid Options</a> for more details.', 'cmssquad-base' ),
		);

		return $fields;
	}

	function load_includable_files() {

		if ( function_exists( 'siteorigin_panels_setting' ) ) {
			if ( siteorigin_panels_setting( 'bootstrap-grid' ) ) {

				require_once plugin_dir_path( __FILE__ ) . 'includes/backend/hooks.php';
				require_once plugin_dir_path( __FILE__ ) . 'includes/backend/functions.php';
				require_once plugin_dir_path( __FILE__ ) . 'includes/frontend/hooks.php';
				require_once plugin_dir_path( __FILE__ ) . 'includes/frontend/functions.php';


			}
		}


	}

}

// instantiate
CMSSquad_SO_Grid::get_instance();
