<?php

class CSOW_Custom_Field_Csow_Image_Radio extends SiteOrigin_Widget_Field_Radio {

	protected function render_field( $value, $instance ) {
		if ( ! isset( $this->options ) || empty( $this->options ) ) return;
		$i = 0;
		foreach( $this->options as $k => $v ) {
			?>
            <div class="csow-custom-field-image-radio csow-custom-field">
                <input type="radio" name="<?php echo esc_attr( $this->element_name ) ?>"
                       id="<?php echo esc_attr( $this->element_id . '-' . $i ) ?>" class="radioImageSelect"
                       value="<?php echo esc_attr( $k ) ?>" <?php checked( $k, $value ) ?>
                       data-image="<?php echo esc_html( $v['image'] ) ?>" />
                <label for="<?php echo esc_attr( $this->element_id . '-' . $i ) ?>" class="csow-custom-field-label"><?php echo $v['label'] ?></label>
            </div>
			<?php
			$i += 1;
		}

	}

	protected function sanitize_field_input( $value, $instance ) {
		return parent::sanitize_field_input( $value, $instance );
	}


	public function enqueue_scripts() {
		wp_enqueue_style( 'jquery-radio-image-select', plugin_dir_url(__FILE__) . 'css/radio-image-select.min.css', array(), '1.0.1' );
		wp_enqueue_style( 'csow-image-radio', plugin_dir_url(__FILE__) . 'css/csow-image-radio.min.css', array(), '0.1' );

		wp_enqueue_script( 'jquery-radio-image-select', plugin_dir_url( __FILE__ ) . 'js/jquery.radioImageSelect.min.js', array( 'jquery' ), '1.0.1' );
		wp_enqueue_script( 'csow-image-radio', plugin_dir_url( __FILE__ ) . 'js/image-radio.min.js', array( 'jquery-radio-image-select' ), '0.1' );
	}

}