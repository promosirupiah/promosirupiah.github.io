<?php
namespace madxartworkPro\Modules\ThemeBuilder\Conditions;

use madxartworkPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class In_Taxonomy extends Condition_Base {


	/**
	 * @var \WP_Taxonomy
	 */
	private $taxonomy;

	public static function get_type() {
		return 'singular';
	}

	public static function get_priority() {
		return 40;
	}

	public function __construct( $data ) {
		parent::__construct();

		$this->taxonomy = $data['object'];
	}

	public function get_name() {
		return 'in_' . $this->taxonomy->name;
	}

	public function get_label() {
		/* translators: %s: Taxonomy label. */
		return sprintf( __( 'In %s', 'madxartwork-pro' ), $this->taxonomy->labels->singular_name );
	}

	public function check( $args ) {
		return is_singular() && has_term( (int) $args['id'], $this->taxonomy->name );
	}

	protected function _register_controls() {
		$this->add_control(
			'taxonomy',
			[
				'section' => 'settings',
				'type' => QueryModule::QUERY_CONTROL_ID,
				'select2options' => [
					'dropdownCssClass' => 'madxartwork-conditions-select2-dropdown',
				],
				'autocomplete' => [
					'object' => QueryModule::QUERY_OBJECT_TAX,
					'display' => 'detailed',
					'by_field' => 'term_id',
					'query' => [
						'taxonomy' => $this->taxonomy->name,
					],
				],
			]
		);
	}
}
