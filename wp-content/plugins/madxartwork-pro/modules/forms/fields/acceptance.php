<?php
namespace madxartworkPro\Modules\Forms\Fields;

use madxartwork\Controls_Manager;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Acceptance extends Field_Base {

	public function get_type() {
		return 'acceptance';
	}

	public function get_name() {
		return __( 'Acceptance', 'madxartwork-pro' );
	}

	public function update_controls( $widget ) {
		$madxartwork = Plugin::madxartwork();

		$control_data = $madxartwork->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'acceptance_text' => [
				'name' => 'acceptance_text',
				'label' => __( 'Acceptance Text', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'checked_by_default' => [
				'name' => 'checked_by_default',
				'label' => __( 'Checked by Default', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );
		$widget->update_control( 'form_fields', $control_data );
	}

	public function render( $item, $item_index, $form ) {
		$label = '';
		$form->add_render_attribute( 'input' . $item_index, 'class', 'madxartwork-acceptance-field' );
		$form->add_render_attribute( 'input' . $item_index, 'type', 'checkbox', true );

		if ( ! empty( $item['acceptance_text'] ) ) {
			$label = '<label for="' . $form->get_attribute_id( $item ) . '">' . $item['acceptance_text'] . '</label>';
		}

		if ( ! empty( $item['checked_by_default'] ) ) {
			$form->add_render_attribute( 'input' . $item_index, 'checked', 'checked' );
		}

		echo '<div class="madxartwork-field-subgroup"><span class="madxartwork-field-option"><input ' . $form->get_render_attribute_string( 'input' . $item_index ) . '> ' . $label . '</span></div>';
	}
}
