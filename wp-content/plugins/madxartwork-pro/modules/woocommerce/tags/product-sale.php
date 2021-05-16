<?php
namespace madxartworkPro\Modules\Woocommerce\Tags;

use madxartwork\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Sale extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-sale-tag';
	}

	public function get_title() {
		return __( 'Product Sale', 'madxartwork-pro' );
	}

	protected function _register_controls() {
		$this->add_control( 'text', [
			'label' => __( 'Text', 'madxartwork-pro' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Sale!', 'madxartwork-pro' ),
		] );
	}

	public function render() {
		$product = wc_get_product();
		if ( ! $product ) {
			return;
		}

		$value = '';

		if ( $product->is_on_sale() ) {
			$value = $this->get_settings( 'text' );
		}

		echo $value;
	}
}
