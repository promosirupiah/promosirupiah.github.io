<?php
namespace madxartworkPro\Modules\Slides\Widgets;

use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Typography;
use madxartwork\Repeater;
use madxartwork\Scheme_Typography;
use madxartworkPro\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Slides extends Base_Widget {

	public function get_name() {
		return 'slides';
	}

	public function get_title() {
		return __( 'Slides', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}

	public function get_keywords() {
		return [ 'slides', 'carousel', 'image', 'title', 'slider' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'jquery-slick' ];
	}

	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'madxartwork-pro' ),
			'sm' => __( 'Small', 'madxartwork-pro' ),
			'md' => __( 'Medium', 'madxartwork-pro' ),
			'lg' => __( 'Large', 'madxartwork-pro' ),
			'xl' => __( 'Extra Large', 'madxartwork-pro' ),
		];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => __( 'Slides', 'madxartwork-pro' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'slides_repeater' );

		$repeater->start_controls_tab( 'background', [ 'label' => __( 'Background', 'madxartwork-pro' ) ] );

		$repeater->add_control(
			'background_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#bbbbbb',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-bg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'background_image',
			[
				'label' => _x( 'Image', 'Background Control', 'madxartwork-pro' ),
				'type' => Controls_Manager::MEDIA,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-bg' => 'background-image: url({{URL}})',
				],
			]
		);

		$repeater->add_control(
			'background_size',
			[
				'label' => _x( 'Size', 'Background Control', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => _x( 'Cover', 'Background Control', 'madxartwork-pro' ),
					'contain' => _x( 'Contain', 'Background Control', 'madxartwork-pro' ),
					'auto' => _x( 'Auto', 'Background Control', 'madxartwork-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-bg' => 'background-size: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'background_ken_burns',
			[
				'label' => __( 'Ken Burns Effect', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'zoom_direction',
			[
				'label' => __( 'Zoom Direction', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'in',
				'options' => [
					'in' => __( 'In', 'madxartwork-pro' ),
					'out' => __( 'Out', 'madxartwork-pro' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_ken_burns',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'background_overlay',
			[
				'label' => __( 'Background Overlay', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'background_overlay_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_overlay',
							'value' => 'yes',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .madxartwork-background-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'background_overlay_blend_mode',
			[
				'label' => __( 'Blend Mode', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Normal', 'madxartwork-pro' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn' => 'Color Burn',
					'hue' => 'Hue',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'exclusion' => 'Exclusion',
					'luminosity' => 'Luminosity',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_overlay',
							'value' => 'yes',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .madxartwork-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'content', [ 'label' => __( 'Content', 'madxartwork-pro' ) ] );

		$repeater->add_control(
			'heading',
			[
				'label' => __( 'Title & Description', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Slide Heading', 'madxartwork-pro' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => __( 'Description', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'madxartwork-pro' ),
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'madxartwork-pro' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'madxartwork-pro' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'madxartwork-pro' ),
			]
		);

		$repeater->add_control(
			'link_click',
			[
				'label' => __( 'Apply Link On', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'slide' => __( 'Whole Slide', 'madxartwork-pro' ),
					'button' => __( 'Button Only', 'madxartwork-pro' ),
				],
				'default' => 'slide',
				'conditions' => [
					'terms' => [
						[
							'name' => 'link[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'style', [ 'label' => __( 'Style', 'madxartwork-pro' ) ] );

		$repeater->add_control(
			'custom_style',
			[
				'label' => __( 'Custom', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'Set custom style that will only affect this specific slide.', 'madxartwork-pro' ),
			]
		);

		$repeater->add_control(
			'horizontal_position',
			[
				'label' => __( 'Horizontal Position', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'madxartwork-pro' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'madxartwork-pro' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'madxartwork-pro' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .madxartwork-slide-content' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right' => 'margin-left: auto',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'vertical_position',
			[
				'label' => __( 'Vertical Position', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'madxartwork-pro' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'madxartwork-pro' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'madxartwork-pro' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'text_align',
			[
				'label' => __( 'Text Align', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner' => 'text-align: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'content_color',
			[
				'label' => __( 'Content Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .madxartwork-slide-heading' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .madxartwork-slide-description' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .madxartwork-slide-button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'madxartwork-pro' ),
				'type' => Controls_Manager::REPEATER,
				'show_label' => true,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'heading' => __( 'Slide 1 Heading', 'madxartwork-pro' ),
						'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'madxartwork-pro' ),
						'button_text' => __( 'Click Here', 'madxartwork-pro' ),
						'background_color' => '#833ca3',
					],
					[
						'heading' => __( 'Slide 2 Heading', 'madxartwork-pro' ),
						'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'madxartwork-pro' ),
						'button_text' => __( 'Click Here', 'madxartwork-pro' ),
						'background_color' => '#4054b2',
					],
					[
						'heading' => __( 'Slide 3 Heading', 'madxartwork-pro' ),
						'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'madxartwork-pro' ),
						'button_text' => __( 'Click Here', 'madxartwork-pro' ),
						'background_color' => '#1abc9c',
					],
				],
				'title_field' => '{{{ heading }}}',
			]
		);

		$this->add_responsive_control(
			'slides_height',
			[
				'label' => __( 'Height', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 400,
				],
				'size_units' => [ 'px', 'vh', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .slick-slide' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => __( 'Slider Options', 'madxartwork-pro' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'both' => __( 'Arrows and Dots', 'madxartwork-pro' ),
					'arrows' => __( 'Arrows', 'madxartwork-pro' ),
					'dots' => __( 'Dots', 'madxartwork-pro' ),
					'none' => __( 'None', 'madxartwork-pro' ),
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'madxartwork-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
				],
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' => __( 'Infinite Loop', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'transition',
			[
				'label' => __( 'Transition', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'madxartwork-pro' ),
					'fade' => __( 'Fade', 'madxartwork-pro' ),
				],
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label' => __( 'Transition Speed', 'madxartwork-pro' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
			]
		);

		$this->add_control(
			'content_animation',
			[
				'label' => __( 'Content Animation', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fadeInUp',
				'options' => [
					'' => __( 'None', 'madxartwork-pro' ),
					'fadeInDown' => __( 'Down', 'madxartwork-pro' ),
					'fadeInUp' => __( 'Up', 'madxartwork-pro' ),
					'fadeInRight' => __( 'Right', 'madxartwork-pro' ),
					'fadeInLeft' => __( 'Left', 'madxartwork-pro' ),
					'zoomIn' => __( 'Zoom', 'madxartwork-pro' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_slides',
			[
				'label' => __( 'Slides', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_max_width',
			[
				'label' => __( 'Content Width', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'size' => '66',
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slides_padding',
			[
				'label' => __( 'Padding', 'madxartwork-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .slick-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'slides_horizontal_position',
			[
				'label' => __( 'Horizontal Position', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'madxartwork-pro' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'madxartwork-pro' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'madxartwork-pro' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'madxartwork--h-position-',
			]
		);

		$this->add_control(
			'slides_vertical_position',
			[
				'label' => __( 'Vertical Position', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'middle',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'madxartwork-pro' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'madxartwork-pro' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'madxartwork-pro' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'prefix_class' => 'madxartwork--v-position-',
			]
		);

		$this->add_control(
			'slides_text_align',
			[
				'label' => __( 'Text Align', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .slick-slide-inner' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_spacing',
			[
				'label' => __( 'Spacing', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-slide-inner .madxartwork-slide-heading:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-heading' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .madxartwork-slide-heading',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			[
				'label' => __( 'Description', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_spacing',
			[
				'label' => __( 'Spacing', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-slide-inner .madxartwork-slide-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-description' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .madxartwork-slide-description',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => __( 'Button', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);

		$this->add_control( 'button_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-button' => 'color: {{VALUE}}; border-color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .madxartwork-slide-button',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->add_control(
			'button_border_width',
			[
				'label' => __( 'Border Width', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => __( 'Normal', 'madxartwork-pro' ) ] );

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Border Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', [ 'label' => __( 'Hover', 'madxartwork-pro' ) ] );

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => __( 'Background Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slide-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => __( 'Navigation', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'arrows', 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_arrows',
			[
				'label' => __( 'Arrows', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_position',
			[
				'label' => __( 'Arrows Position', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside' => __( 'Inside', 'madxartwork-pro' ),
					'outside' => __( 'Outside', 'madxartwork-pro' ),
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label' => __( 'Arrows Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slides-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .madxartwork-slides-wrapper .slick-slider .slick-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => __( 'Arrows Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slides-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .madxartwork-slides-wrapper .slick-slider .slick-next:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_dots',
			[
				'label' => __( 'Dots', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_position',
			[
				'label' => __( 'Dots Position', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'outside' => __( 'Outside', 'madxartwork-pro' ),
					'inside' => __( 'Inside', 'madxartwork-pro' ),
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_size',
			[
				'label' => __( 'Dots Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slides-wrapper .madxartwork-slides .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => __( 'Dots Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-slides-wrapper .madxartwork-slides .slick-dots li button:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$this->add_render_attribute( 'button', 'class', [ 'madxartwork-button', 'madxartwork-slide-button' ] );

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'madxartwork-size-' . $settings['button_size'] );
		}

		$slides = [];
		$slide_count = 0;

		foreach ( $settings['slides'] as $slide ) {
			$slide_html = '';
			$btn_attributes = '';
			$slide_attributes = '';
			$slide_element = 'div';
			$btn_element = 'div';
			$slide_url = $slide['link']['url'];

			if ( ! empty( $slide_url ) ) {
				$this->add_render_attribute( 'slide_link' . $slide_count, 'href', $slide_url );

				if ( $slide['link']['is_external'] ) {
					$this->add_render_attribute( 'slide_link' . $slide_count, 'target', '_blank' );
				}

				if ( 'button' === $slide['link_click'] ) {
					$btn_element = 'a';
					$btn_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				} else {
					$slide_element = 'a';
					$slide_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				}
			}

			if ( 'yes' === $slide['background_overlay'] ) {
				$slide_html .= '<div class="madxartwork-background-overlay"></div>';
			}

			$slide_html .= '<div class="madxartwork-slide-content">';

			if ( $slide['heading'] ) {
				$slide_html .= '<div class="madxartwork-slide-heading">' . $slide['heading'] . '</div>';
			}

			if ( $slide['description'] ) {
				$slide_html .= '<div class="madxartwork-slide-description">' . $slide['description'] . '</div>';
			}

			if ( $slide['button_text'] ) {
				$slide_html .= '<' . $btn_element . ' ' . $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' ) . '>' . $slide['button_text'] . '</' . $btn_element . '>';
			}

			$ken_class = '';

			if ( '' != $slide['background_ken_burns'] ) {
				$ken_class = ' madxartwork-ken-' . $slide['zoom_direction'];
			}

			$slide_html .= '</div>';
			$slide_html = '<div class="slick-slide-bg' . $ken_class . '"></div><' . $slide_element . ' ' . $slide_attributes . ' class="slick-slide-inner">' . $slide_html . '</' . $slide_element . '>';
			$slides[] = '<div class="madxartwork-repeater-item-' . $slide['_id'] . ' slick-slide">' . $slide_html . '</div>';
			$slide_count++;
		}

		$is_rtl = is_rtl();
		$direction = $is_rtl ? 'rtl' : 'ltr';
		$show_dots = ( in_array( $settings['navigation'], [ 'dots', 'both' ] ) );
		$show_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'both' ] ) );

		$slick_options = [
			'slidesToShow' => absint( 1 ),
			'autoplaySpeed' => absint( $settings['autoplay_speed'] ),
			'autoplay' => ( 'yes' === $settings['autoplay'] ),
			'infinite' => ( 'yes' === $settings['infinite'] ),
			'pauseOnHover' => ( 'yes' === $settings['pause_on_hover'] ),
			'speed' => absint( $settings['transition_speed'] ),
			'arrows' => $show_arrows,
			'dots' => $show_dots,
			'rtl' => $is_rtl,
		];

		if ( 'fade' === $settings['transition'] ) {
			$slick_options['fade'] = true;
		}

		$carousel_classes = [ 'madxartwork-slides' ];

		if ( $show_arrows ) {
			$carousel_classes[] = 'slick-arrows-' . $settings['arrows_position'];
		}

		if ( $show_dots ) {
			$carousel_classes[] = 'slick-dots-' . $settings['dots_position'];
		}

		$this->add_render_attribute( 'slides', [
			'class' => $carousel_classes,
			'data-slider_options' => wp_json_encode( $slick_options ),
			'data-animation' => $settings['content_animation'],
		] );

		?>
		<div class="madxartwork-slides-wrapper madxartwork-slick-slider" dir="<?php echo esc_attr( $direction ); ?>">
			<div <?php echo $this->get_render_attribute_string( 'slides' ); ?>>
				<?php echo implode( '', $slides ); ?>
			</div>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
			var isRtl           = <?php echo is_rtl() ? 'true' : 'false'; ?>,
				direction       = isRtl ? 'rtl' : 'ltr',
				navi            = settings.navigation,
				showDots        = ( 'dots' === navi || 'both' === navi ),
				showArrows      = ( 'arrows' === navi || 'both' === navi ),
				autoplay        = ( 'yes' === settings.autoplay ),
				infinite        = ( 'yes' === settings.infinite ),
				speed           = Math.abs( settings.transition_speed ),
				autoplaySpeed   = Math.abs( settings.autoplay_speed ),
				fade            = ( 'fade' === settings.transition ),
				buttonSize      = settings.button_size,
				sliderOptions = {
					"initialSlide": Math.max( 0, editSettings.activeItemIndex-1 ),
					"slidesToShow": 1,
					"autoplaySpeed": autoplaySpeed,
					"autoplay": false,
					"infinite": infinite,
					"pauseOnHover":true,
					"pauseOnFocus":true,
					"speed": speed,
					"arrows": showArrows,
					"dots": showDots,
					"rtl": isRtl,
					"fade": fade
				}
				sliderOptionsStr = JSON.stringify( sliderOptions );
			if ( showArrows ) {
				var arrowsClass = 'slick-arrows-' + settings.arrows_position;
			}

			if ( showDots ) {
				var dotsClass = 'slick-dots-' + settings.dots_position;
			}

		#>
		<div class="madxartwork-slides-wrapper madxartwork-slick-slider" dir="{{ direction }}">
			<div data-slider_options="{{ sliderOptionsStr }}" class="madxartwork-slides {{ dotsClass }} {{ arrowsClass }}" data-animation="{{ settings.content_animation }}">
				<# _.each( settings.slides, function( slide ) { #>
					<div class="madxartwork-repeater-item-{{ slide._id }} slick-slide">
						<#
						var kenClass = '';

						if ( '' != slide.background_ken_burns ) {
							kenClass = ' madxartwork-ken-' + slide.zoom_direction;
						}
						#>
						<div class="slick-slide-bg{{ kenClass }}"></div>
						<div class="slick-slide-inner">
								<# if ( 'yes' === slide.background_overlay ) { #>
							<div class="madxartwork-background-overlay"></div>
								<# } #>
							<div class="madxartwork-slide-content">
								<# if ( slide.heading ) { #>
									<div class="madxartwork-slide-heading">{{{ slide.heading }}}</div>
								<# }
								if ( slide.description ) { #>
									<div class="madxartwork-slide-description">{{{ slide.description }}}</div>
								<# }
								if ( slide.button_text ) { #>
									<div class="madxartwork-button madxartwork-slide-button madxartwork-size-{{ buttonSize }}">{{{ slide.button_text }}}</div>
								<# } #>
							</div>
						</div>
					</div>
				<# } ); #>
			</div>
		</div>
		<?php
	}
}
