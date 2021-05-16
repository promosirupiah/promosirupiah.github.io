<?php
namespace madxartworkPro\Modules\Carousel\Widgets;

use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Box_Shadow;
use madxartwork\Group_Control_Typography;
use madxartwork\Icons_Manager;
use madxartwork\Repeater;
use madxartwork\Scheme_Color;
use madxartwork\Scheme_Typography;
use madxartwork\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Reviews extends Base {

	public function get_name() {
		return 'reviews';
	}

	public function get_title() {
		return __( 'Reviews', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-review';
	}

	public function get_keywords() {
		return [ 'reviews', 'social', 'rating', 'testimonial', 'carousel' ];
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'slide_padding',
			[
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__header' => 'padding-top: {{TOP}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .madxartwork-testimonial__content' => 'padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->start_injection( [
			'of' => 'slide_padding',
		] );

		$this->add_control(
			'heading_header',
			[
				'label' => __( 'Header', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_background_color',
			[
				'label' => __( 'Background Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__header' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => __( 'Gap', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__header' => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}} .madxartwork-testimonial__content' => 'padding-top: calc({{SIZE}}{{UNIT}} / 2)',
				],
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => __( 'Separator', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'madxartwork-pro' ),
				'label_on' => __( 'Show', 'madxartwork-pro' ),
				'default' => 'has-separator',
				'return_value' => 'has-separator',
				'prefix_class' => 'madxartwork-review--',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__header' => 'border-bottom-color: {{VALUE}}',
				],
				'condition' => [
					'show_separator!' => '',
				],
			]
		);

		$this->add_control(
			'separator_size',
			[
				'label' => __( 'Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					'show_separator!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__header' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'at' => 'before',
			'of' => 'section_navigation',
		] );

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Text', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'name_title_style',
			[
				'label' => __( 'Name', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .madxartwork-testimonial__header, {{WRAPPER}} .madxartwork-testimonial__name',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => __( 'Title', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .madxartwork-testimonial__title',
			]
		);

		$this->add_control(
			'heading_review_style',
			[
				'label' => __( 'Review', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .madxartwork-testimonial__text',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => __( 'Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_gap',
			[
				'label' => __( 'Gap', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .madxartwork-testimonial__image + cite' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
					'body.rtl {{WRAPPER}} .madxartwork-testimonial__image + cite' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left:0;',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Official', 'madxartwork-pro' ),
					'custom' => __( 'Custom', 'madxartwork-pro' ),
				],
			]
		);

		$this->add_control(
			'icon_custom_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__icon:not(.madxartwork-testimonial__rating)' => 'color: {{VALUE}};',
					'{{WRAPPER}} .madxartwork-testimonial__icon:not(.madxartwork-testimonial__rating) svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-testimonial__icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .madxartwork-testimonial__icon svg' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rating_style',
			[
				'label' => __( 'Rating', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'star_style',
			[
				'label' => __( 'Icon', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'star_fontawesome' => 'Font Awesome',
					'star_unicode' => 'Unicode',
				],
				'default' => 'star_fontawesome',
				'render_type' => 'template',
				'prefix_class' => 'madxartwork--star-style-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'unmarked_star_style',
			[
				'label' => __( 'Unmarked Style', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => __( 'Solid', 'madxartwork-pro' ),
						'icon' => 'eicon-star',
					],
					'outline' => [
						'title' => __( 'Outline', 'madxartwork-pro' ),
						'icon' => 'eicon-star-o',
					],
				],
				'default' => 'solid',
			]
		);

		$this->add_control(
			'star_size',
			[
				'label' => __( 'Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'star_space',
			[
				'label' => __( 'Spacing', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .madxartwork-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .madxartwork-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'stars_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-star-rating i:before' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'stars_unmarked_color',
			[
				'label' => __( 'Unmarked Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-star-rating i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->end_injection();

		$this->update_responsive_control(
			'width',
			[
				'selectors' => [
					'{{WRAPPER}}.madxartwork-arrows-yes .madxartwork-main-swiper' => 'width: calc( {{SIZE}}{{UNIT}} - 40px )',
					'{{WRAPPER}} .madxartwork-main-swiper' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->update_responsive_control(
			'slides_per_view',
			[
				'condition' => null,
			]
		);

		$this->update_control(
			'slides_to_scroll',
			[
				'condition' => null,
			]
		);

		$this->remove_control( 'effect' );
		$this->remove_responsive_control( 'height' );
		$this->remove_control( 'pagination_position' );
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'madxartwork-pro' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'name',
			[
				'label' => __( 'Name', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'John Doe', 'madxartwork-pro' ),
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '@username',
			]
		);

		$repeater->add_control(
			'rating',
			[
				'label' => __( 'Rating', 'madxartwork-pro' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
			]
		);

		$repeater->add_control(
			'selected_social_icon',
			[
				'label' => __( 'Icon', 'madxartwork-pro' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'social_icon',
				'label_block' => true,
				'default' => [
					'value' => 'fab fa-twitter',
					'library' => 'fa-brands',
				],
				'recommended' => [
					'fa-solid' => [
						'rss',
						'shopping-cart',
						'thumbtack',
					],
					'fa-brands' => [
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'digg',
						'dribbble',
						'envelope',
						'facebook',
						'flickr',
						'foursquare',
						'github',
						'google-plus',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'stumbleupon',
						'telegram',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'vimeo',
						'fa-vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					],
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'madxartwork-pro' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'madxartwork-pro' ),

			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => __( 'Review', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'madxartwork-pro' ),
			]
		);
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'content' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'madxartwork-pro' ),
				'name' => __( 'John Doe', 'madxartwork-pro' ),
				'title' => '@username',
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'madxartwork-pro' ),
				'name' => __( 'John Doe', 'madxartwork-pro' ),
				'title' => '@username',
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'madxartwork-pro' ),
				'name' => __( 'John Doe', 'madxartwork-pro' ),
				'title' => '@username',
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
		];
	}

	private function print_cite( $slide, $settings ) {
		if ( empty( $slide['name'] ) && empty( $slide['title'] ) ) {
			return '';
		}

		$html = '<cite class="madxartwork-testimonial__cite">';

		if ( ! empty( $slide['name'] ) ) {
			$html .= '<span class="madxartwork-testimonial__name">' . $slide['name'] . '</span>';
		}

		if ( ! empty( $slide['rating'] ) ) {
			$html .= $this->render_stars( $slide, $settings );
		}

		if ( ! empty( $slide['title'] ) ) {
			$html .= '<span class="madxartwork-testimonial__title">' . $slide['title'] . '</span>';
		}
		$html .= '</cite>';

		return $html;
	}

	protected function render_stars( $slide, $settings ) {
		$icon = '&#xE934;';

		if ( 'star_fontawesome' === $settings['star_style'] ) {
			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#xE933;';
			}
		} elseif ( 'star_unicode' === $settings['star_style'] ) {
			$icon = '&#9733;';

			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#9734;';
			}
		}

		$rating = (float) $slide['rating'] > 5 ? 5 : $slide['rating'];
		$floored_rating = (int) $rating;
		$stars_html = '';

		for ( $stars = 1; $stars <= 5; $stars++ ) {
			if ( $stars <= $floored_rating ) {
				$stars_html .= '<i class="madxartwork-star-full">' . $icon . '</i>';
			} elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
				$stars_html .= '<i class="madxartwork-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
			} else {
				$stars_html .= '<i class="madxartwork-star-empty">' . $icon . '</i>';
			}
		}

		return '<div class="madxartwork-star-rating">' . $stars_html . '</div>';
	}

	private function print_icon( $slide, $element_key ) {
		$migration_allowed = Icons_Manager::is_migration_allowed();
		if ( ! isset( $slide['social_icon'] ) && ! $migration_allowed ) {
			// add old default
			$slide['social_icon'] = 'fa fa-twitter';
		}

		if ( empty( $slide['social_icon'] ) && empty( $slide['selected_social_icon'] ) ) {
			return '';
		}

		$migrated = isset( $slide['__fa4_migrated']['selected_social_icon'] );
		$is_new = empty( $slide['social_icon'] ) && $migration_allowed;
		$social = '';

		if ( $is_new || $migrated ) {
			ob_start();
			Icons_Manager::render_icon( $slide['selected_social_icon'], [ 'aria-hidden' => 'true' ] );
			$icon = ob_get_clean();
		} else {
			$icon = '<i class="' . esc_attr( $slide['social_icon'] ) . '" aria-hidden="true"></i>';
		}

		if ( ! empty( $slide['social_icon'] ) ) {
			$social = str_replace( 'fa fa-', '', $slide['social_icon'] );
		}

		if ( ( $is_new || $migrated ) && 'svg' !== $slide['selected_social_icon']['library'] ) {
			$social = explode( ' ', $slide['selected_social_icon']['value'], 2 );
			if ( empty( $social[1] ) ) {
				$social = '';
			} else {
				$social = str_replace( 'fa-', '', $social[1] );
			}
		}
		if ( 'svg' === $slide['selected_social_icon']['library'] ) {
			$social = '';
		}

		$this->add_render_attribute( 'icon_wrapper_' . $element_key, 'class', 'madxartwork-testimonial__icon madxartwork-icon' );

		$icon .= '<span class="madxartwork-screen-only">' . esc_html__( 'Read More', 'madxartwork-pro' ) . '</span>';
		$this->add_render_attribute( 'icon_wrapper_' . $element_key, 'class', 'madxartwork-icon-' . $social );

		return '<div ' . $this->get_render_attribute_string( 'icon_wrapper_' . $element_key ) . '>' . $icon . '</div>';
	}

	protected function print_slide( array $slide, array $settings, $element_key ) {
		$this->add_render_attribute( $element_key . '-testimonial', [
			'class' => 'madxartwork-testimonial',
		] );

		$this->add_render_attribute( $element_key . '-testimonial', [
			'class' => 'madxartwork-repeater-item-' . $slide['_id'],
		] );

		if ( ! empty( $slide['image']['url'] ) ) {
			$this->add_render_attribute( $element_key . '-image', [
				'src' => $this->get_slide_image_url( $slide, $settings ),
				'alt' => ! empty( $slide['name'] ) ? $slide['name'] : '',
			] );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( $element_key . '-testimonial' ); ?>>
			<?php if ( $slide['image']['url'] || ! empty( $slide['name'] ) || ! empty( $slide['title'] ) ) :

				$link_url = empty( $slide['link']['url'] ) ? false : $slide['link']['url'];
				$header_tag = ! empty( $link_url ) ? 'a' : 'div';
				$header_element = 'header_' . $slide['_id'];

				$this->add_render_attribute( $header_element, 'class', 'madxartwork-testimonial__header' );

				if ( ! empty( $link_url ) ) {
					$this->add_render_attribute( $header_element, 'href', $link_url );

					if ( $slide['link']['is_external'] ) {
						$this->add_render_attribute( $header_element, 'target', '_blank' );
					}

					if ( ! empty( $slide['link']['nofollow'] ) ) {
						$this->add_render_attribute( $header_element, 'rel', 'nofollow' );
					}
				}
				?>
				<<?php echo $header_tag; ?> <?php echo $this->get_render_attribute_string( $header_element ); ?>>
					<?php if ( $slide['image']['url'] ) : ?>
						<div class="madxartwork-testimonial__image">
							<img <?php echo $this->get_render_attribute_string( $element_key . '-image' ); ?>>
						</div>
					<?php endif; ?>
					<?php echo $this->print_cite( $slide, $settings ); ?>
					<?php echo $this->print_icon( $slide, $element_key ); ?>
				</<?php echo $header_tag; ?>>
			<?php endif; ?>
			<?php if ( $slide['content'] ) : ?>
				<div class="madxartwork-testimonial__content">
					<div class="madxartwork-testimonial__text">
						<?php echo $slide['content']; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function render() {
		$this->print_slider();
	}
}
