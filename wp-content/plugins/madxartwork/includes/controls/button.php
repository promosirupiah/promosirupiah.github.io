<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * madxartwork button control.
 *
 * A base control for creating a button control. Displays a button that can
 * trigger an event.
 *
 * @since 1.9.0
 */
class Control_Button extends Base_UI_Control {

	/**
	 * Get button control type.
	 *
	 * Retrieve the control type, in this case `button`.
	 *
	 * @since 1.9.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'button';
	}

	/**
	 * Get button control default settings.
	 *
	 * Retrieve the default settings of the button control. Used to
	 * return the default settings while initializing the button
	 * control.
	 *
	 * @since 1.9.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'text' => '',
			'event' => '',
			'button_type' => 'default',
		];
	}

	/**
	 * Render button control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.9.0
	 * @access public
	 */
	public function content_template() {
		?>
		<div class="madxartwork-control-field">
			<label class="madxartwork-control-title">{{{ data.label }}}</label>
			<div class="madxartwork-control-input-wrapper">
				<button type="button" class="madxartwork-button madxartwork-button-{{{ data.button_type }}}" data-event="{{{ data.event }}}">{{{ data.text }}}</button>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="madxartwork-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
