<?php

class CSOW_Custom_Field_Csow_Toggle extends SiteOrigin_Widget_Field_Checkbox {

	protected function render_field( $value, $instance ) {
		?>
		<div class="csow-custom-field-toggle csow-custom-field">
			<label for="<?php echo esc_attr( $this->element_id ) ?>" class="so-checkbox-label csow-custom-field-label">
				<?php echo esc_attr( $this->label ) ?>
			</label>
			<input type="checkbox" name="<?php echo esc_attr( $this->element_name ) ?>" id="<?php echo esc_attr( $this->element_id ) ?>"
			       class="switchery" <?php checked( !empty( $value ) ) ?> />
		</div>
		<?php
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'switchery', plugin_dir_url(__FILE__) . 'css/switchery.min.css', array(), '0.8.2' );

		wp_enqueue_script( 'switchery', plugin_dir_url( __FILE__ ) . 'js/switchery.min.js', array( 'jquery' ), '0.8.2' );
		wp_enqueue_script( 'csow-toggle', plugin_dir_url( __FILE__ ) . 'js/toggle.min.js', array( 'switchery' ), '0.1' );
	}

}