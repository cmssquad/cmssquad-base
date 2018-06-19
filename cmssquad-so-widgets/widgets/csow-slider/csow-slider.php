<?php

/*
Widget Name: CMSSquad Slider
Description: Image slider widget from CMSSquad.
Author: CMSSquad
Author URI: https://github.com/cmssquad
*/

class CSOW_Slider_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'csow-slider',
			__( 'CMSSquad Slider', 'cmssquad-base' ),
			array(
				'description'   => __( 'A simple image slider.', 'cmssquad-base' ),
				"panels_icon"   => "csow-widget-icon",
				'panels_groups' => array( 'csow-widgets' ),
				'help'          => ''
			),
			array(),
			false,
			plugin_dir_path( __FILE__ )
		);

	}

	/**
	 * Override: register frontend scripts and styles
	 */
	public function initialize() {

		$this->register_frontend_scripts(
			array(
				array(
					'csow-slider-js',
					plugin_dir_url( __FILE__ ) . 'js/csow-slider.js',
					array( 'jquery', 'CMSSquad-Base-TweenMax', 'CMSSquad-Base-imagesLoaded' ),
					CSOW_VERSION,
					true
				)
			)
		);

	}

	/**
	 * Defines the slider input fields.
	 *
	 * @return array
	 */
	function get_widget_form() {
		return array(
			'slides' => array(
				'type'       => 'repeater',
				'label'      => __( 'Image Slides', 'cmssquad-base' ),
				'item_name'  => __( 'Slide', 'cmssquad-base' ),
				'item_label' => array(
					'selector'     => "[id*='slides-title']",
					'update_event' => 'change',
					'value_method' => 'val'
				),

				'fields' => array(

					'image' => array(
						'type'     => 'media',
						'label'    => __( 'Image', 'cmssquad-base' ),
						'library'  => 'image',
						'fallback' => true,
					),

					'text' => array(
						'type'  => 'tinymce',
						'label' => __( 'Caption text', 'cmssquad-base' ),
					),

					'link' => array(
						'type'        => 'link',
						'label'       => __( 'Link', 'cmssquad-base' ),
						'description' => __( 'URL for "more info" link on caption text. Leave empty for no link.', 'cmssquad-base' ),
					),

					'link_text' => array(
						'type'        => 'text',
						'label'       => __( 'Link text', 'cmssquad-base' ),
						'description' => __( 'Text for the "more info" link on caption text.', 'cmssquad-base' ),
					),
				),
			),

			'slider-options' => array(
				'type'   => 'section',
				'label'  => __( 'Slider Options', 'cmssquad-base' ),
				'fields' => array(

					'type' => array(
						'type'    => 'select',
						'label'   => __( 'Slider type', 'cmssquad-so-widgets' ),
						'description' => __( 'Choose the visual layout for the slider.', 'cmssquad-base' ),
						'default' => 'fullscreen',
						'options' => array(
							"fullscreen" => __( 'Fullscreen', 'cmssquad-so-widgets' ),
							"standard"   => __( 'Standard', 'cmssquad-so-widgets' )
						),
					),

					'autoplay' => array(
						'type'        => 'csow-toggle',
						'label'       => __( 'Autoplay', 'cmssquad-so-widgets' ),
						'description' => __( 'Automatically play slider transition.', 'cmssquad-base' ),
						'default'     => true,
					),

					'show_nav' => array(
						'type'        => 'csow-toggle',
						'label'       => __( 'Show navigation', 'cmssquad-so-widgets' ),
						'description' => __( 'Show or hide navigation button.', 'cmssquad-base' ),
						'default'     => true,
					),

					'pause_hover' => array(
						'type'        => 'csow-toggle',
						'label'       => __( 'Pause on mouseover', 'cmssquad-so-widgets' ),
						'description' => __( 'Pause slider transition on mouseover event.', 'cmssquad-base' ),
						'default'     => true,
					),

					'timeout' => array(
						'type'        => 'number',
						'label'       => __( 'Timeout', 'cmssquad-base' ),
						'description' => __( 'How long a slide will stay before move on to the next slide when autoplay is activated (in miliseconds).', 'cmssquad-base' ),
						'default'     => 10000,
					),
				)
			),
		);
	}

	/**
	 * Get variables defined on get_widget_form() above and send it to the template file (tpl/default.php)
	 *
	 * @param $instance
	 * @param $args
	 *
	 * @return array
	 */
	function get_template_variables( $instance, $args ) {
		if ( empty( $instance ) ) {
			return array();
		}

		$slides = empty( $instance['slides'] ) ? array() : $instance['slides'];

		foreach($slides as &$slide) {
			$slide['src'] = wp_get_attachment_image_src($slide['image'], 'full');
			$slide['alt_text'] = get_post_meta( $slide['image'], '_wp_attachment_image_alt', true);
		}

		return array(
			'slides' => $slides,
			'type' => $instance['slider-options']['type'],
			'show_nav' => $instance['slider-options']['show_nav'],
			'timeout' => $instance['slider-options']['timeout'],
			'autoplay' => $instance['slider-options']['autoplay'],
			'pause_hover' => $instance['slider-options']['pause_hover'],
		);
	}

}

siteorigin_widget_register( 'csow-slider', __FILE__, 'CSOW_Slider_Widget' );