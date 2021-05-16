<?php
namespace madxartworkPro\Modules\Woocommerce\Widgets;

use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Stock extends Widget_Base {

	public function get_name() {
		return 'woocommerce-product-stock';
	}

	public function get_title() {
		return __( 'Product Stock', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-product-stock';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'stock', 'quantity', 'product' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_product_stock_style',
			[
				'label' => __( 'Style', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_style_warning',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'madxartwork-pro' ),
				'content_classes' => 'madxartwork-panel-alert madxartwork-panel-alert-info',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .stock' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Typography', 'madxartwork-pro' ),
				'selector' => '.woocommerce {{WRAPPER}} .stock',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		global $product;
		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		echo wc_get_stock_html( $product );
	}

	public function render_plain_content() {}
}
