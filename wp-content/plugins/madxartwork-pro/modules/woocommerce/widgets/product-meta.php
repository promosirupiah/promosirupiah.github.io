<?php
namespace madxartworkPro\Modules\Woocommerce\Widgets;

use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Typography;
use madxartwork\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Meta extends Widget_Base {

	public function get_name() {
		return 'woocommerce-product-meta';
	}

	public function get_title() {
		return __( 'Product Meta', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-product-meta';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'meta', 'data', 'product' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_product_meta_style',
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
			'view',
			[
				'label' => __( 'View', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'inline',
				'options' => [
					'table' => __( 'Table', 'madxartwork-pro' ),
					'stacked' => __( 'Stacked', 'madxartwork-pro' ),
					'inline' => __( 'Inline', 'madxartwork-pro' ),
				],
				'prefix_class' => 'madxartwork-woo-meta--view-',
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.madxartwork-woo-meta--view-inline) .product_meta .detail-container:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}:not(.madxartwork-woo-meta--view-inline) .product_meta .detail-container:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.madxartwork-woo-meta--view-inline .product_meta .detail-container' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.madxartwork-woo-meta--view-inline .product_meta' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body:not(.rtl) {{WRAPPER}}.madxartwork-woo-meta--view-inline .detail-container:after' => 'right: calc( (-{{SIZE}}{{UNIT}}/2) + (-{{divider_weight.SIZE}}px/2) )',
					'body:not.rtl {{WRAPPER}}.madxartwork-woo-meta--view-inline .detail-container:after' => 'left: calc( (-{{SIZE}}{{UNIT}}/2) - ({{divider_weight.SIZE}}px/2) )',
				],
			]
		);

		$this->add_control(
			'divider',
			[
				'label' => __( 'Divider', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'madxartwork-pro' ),
				'label_on' => __( 'On', 'madxartwork-pro' ),
				'selectors' => [
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'content: ""',
				],
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'madxartwork-pro' ),
					'double' => __( 'Double', 'madxartwork-pro' ),
					'dotted' => __( 'Dotted', 'madxartwork-pro' ),
					'dashed' => __( 'Dashed', 'madxartwork-pro' ),
				],
				'default' => 'solid',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}:not(.madxartwork-woo-meta--view-inline) .product_meta .detail-container:not(:last-child):after' => 'border-top-style: {{VALUE}}',
					'{{WRAPPER}}.madxartwork-woo-meta--view-inline .product_meta .detail-container:not(:last-child):after' => 'border-left-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}:not(.madxartwork-woo-meta--view-inline) .product_meta .detail-container:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}; margin-bottom: calc(-{{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.madxartwork-woo-meta--view-inline .product_meta .detail-container:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_width',
			[
				'label' => __( 'Width', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'condition' => [
					'divider' => 'yes',
					'view!' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label' => __( 'Height', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_text_style',
			[
				'label' => __( 'Text', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_link_style',
			[
				'label' => __( 'Link', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_typography',
				'selector' => '{{WRAPPER}} a',
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_meta_captions',
			[
				'label' => __( 'Captions', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_category_caption',
			[
				'label' => __( 'Category', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'category_caption_single',
			[
				'label' => __( 'Singular', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Category', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'category_caption_plural',
			[
				'label' => __( 'Plural', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Categories', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'heading_tag_caption',
			[
				'label' => __( 'Tag', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tag_caption_single',
			[
				'label' => __( 'Singular', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Tag', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'tag_caption_plural',
			[
				'label' => __( 'Plural', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Tags', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'heading_sku_caption',
			[
				'label' => __( 'SKU', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sku_caption',
			[
				'label' => __( 'SKU', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'SKU', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'sku_missing_caption',
			[
				'label' => __( 'Missing', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'N/A', 'madxartwork-pro' ),
			]
		);

		$this->end_controls_section();
	}

	private function get_plural_or_single( $single, $plural, $count ) {
		return 1 === $count ? $single : $plural;
	}

	protected function render() {
		global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		$sku = $product->get_sku();

		$settings = $this->get_settings_for_display();
		$sku_caption = ! empty( $settings['sku_caption'] ) ? $settings['sku_caption'] : __( 'SKU', 'madxartwork-pro' );
		$sku_missing = ! empty( $settings['sku_missing_caption'] ) ? $settings['sku_missing_caption'] : __( 'N/A', 'madxartwork-pro' );
		$category_caption_single = ! empty( $settings['category_caption_single'] ) ? $settings['category_caption_single'] : __( 'Category', 'madxartwork-pro' );
		$category_caption_plural = ! empty( $settings['category_caption_plural'] ) ? $settings['category_caption_plural'] : __( 'Categories', 'madxartwork-pro' );
		$tag_caption_single = ! empty( $settings['tag_caption_single'] ) ? $settings['tag_caption_single'] : __( 'Tag', 'madxartwork-pro' );
		$tag_caption_plural = ! empty( $settings['tag_caption_plural'] ) ? $settings['tag_caption_plural'] : __( 'Tags', 'madxartwork-pro' );
		?>
		<div class="product_meta">

			<?php do_action( 'woocommerce_product_meta_start' ); ?>

			<?php if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
				<span class="sku_wrapper detail-container"><span class="detail-label"><?php echo esc_html( $sku_caption ); ?></span> <span class="sku"><?php echo $sku ? $sku : esc_html( $sku_missing ); ?></span></span>
			<?php endif; ?>

			<?php if ( count( $product->get_category_ids() ) ) : ?>
				<span class="posted_in detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $category_caption_single, $category_caption_plural, count( $product->get_category_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_cat', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php if ( count( $product->get_tag_ids() ) ) : ?>
				<span class="tagged_as detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $tag_caption_single, $tag_caption_plural, count( $product->get_tag_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_tag', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php do_action( 'woocommerce_product_meta_end' ); ?>

		</div>
		<?php
	}

	public function render_plain_content() {}
}
