<?php
namespace madxartworkPro\Modules\DynamicTags\Tags;

use madxartwork\Controls_Manager;
use madxartwork\Core\DynamicTags\Data_Tag;
use madxartworkPro\Modules\DynamicTags\Module;
use madxartworkPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Internal_URL extends Data_Tag {

	public function get_name() {
		return 'internal-url';
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_title() {
		return __( 'Internal URL', 'madxartwork-pro' );
	}

	public function get_panel_template() {
		return ' ({{ url }})';
	}

	public function get_value( array $options = [] ) {
		$settings = $this->get_settings();

		$type = $settings['type'];
		$url = '';

		if ( 'post' === $type && ! empty( $settings['post_id'] ) ) {
			$url = get_permalink( (int) $settings['post_id'] );
		} elseif ( 'taxonomy' === $type && ! empty( $settings['taxonomy_id'] ) ) {
			$url = get_term_link( (int) $settings['taxonomy_id'] );
		} elseif ( 'attachment' === $type && ! empty( $settings['attachment_id'] ) ) {
			$url = get_attachment_link( (int) $settings['attachment_id'] );
		} elseif ( 'author' === $type && ! empty( $settings['author_id'] ) ) {
			$url = get_author_posts_url( (int) $settings['author_id'] );
		}

		if ( ! is_wp_error( $url ) ) {
			return $url;
		}

		return '';
	}

	protected function _register_controls() {
		$this->add_control( 'type', [
			'label' => __( 'Type', 'madxartwork-pro' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'post' => __( 'Content', 'madxartwork-pro' ),
				'taxonomy' => __( 'Taxonomy', 'madxartwork-pro' ),
				'attachment' => __( 'Media', 'madxartwork-pro' ),
				'author' => __( 'Author', 'madxartwork-pro' ),
			],
		] );

		$this->add_control( 'post_id', [
			'label' => __( 'Search & Select', 'madxartwork-pro' ),
			'type' => QueryModule::QUERY_CONTROL_ID,
			'options' => [],
			'label_block' => true,
			'autocomplete' => [
				'object' => QueryModule::QUERY_OBJECT_POST,
				'display' => 'detailed',
				'query' => [
					'post_type' => 'any',
				],
			],
			'condition' => [
				'type' => 'post',
			],
		] );

		$this->add_control( 'taxonomy_id', [
			'label' => __( 'Search & Select', 'madxartwork-pro' ),
			'type' => QueryModule::QUERY_CONTROL_ID,
			'options' => [],
			'label_block' => true,
			'autocomplete' => [
				'object' => QueryModule::QUERY_OBJECT_TAX,
				'display' => 'detailed',
			],
			'condition' => [
				'type' => 'taxonomy',
			],
		] );

		$this->add_control( 'attachment_id', [
			'label' => __( 'Search & Select', 'madxartwork-pro' ),
			'type' => QueryModule::QUERY_CONTROL_ID,
			'options' => [],
			'label_block' => true,
			'autocomplete' => [
				'object' => QueryModule::QUERY_OBJECT_ATTACHMENT,
				'display' => 'detailed',
			],
			'condition' => [
				'type' => 'attachment',
			],
		] );

		$this->add_control( 'author_id', [
			'label' => __( 'Search & Select', 'madxartwork-pro' ),
			'type' => QueryModule::QUERY_CONTROL_ID,
			'options' => [],
			'label_block' => true,
			'autocomplete' => [
				'object' => QueryModule::QUERY_OBJECT_AUTHOR,
				'display' => 'detailed',
			],
			'condition' => [
				'type' => 'author',
			],
		] );
	}
}
