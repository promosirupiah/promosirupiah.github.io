<?php
namespace madxartworkPro\Modules\ThemeBuilder\Widgets;

use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Typography;
use madxartwork\Scheme_Color;
use madxartwork\Scheme_Typography;
use madxartwork\Widget_Base;
use madxartworkPro\Modules\ThemeBuilder\Module;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Content extends Widget_Base {

	public function get_name() {
		// `theme` prefix is to avoid conflicts with a dynamic-tag with same name.
		return 'theme-post-content';
	}

	public function get_title() {
		return __( 'Post Content', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-post-content';
	}

	public function get_categories() {
		return [ 'theme-elements-single' ];
	}

	public function get_keywords() {
		return [ 'content', 'post' ];
	}

	public function show_in_panel() {
		// By default don't show.
		return false;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
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
					'justify' => [
						'title' => __( 'Justified', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		static $did_posts = [];

		$post = get_post();

		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );

			return;
		}

		// Avoid recursion
		if ( isset( $did_posts[ $post->ID ] ) ) {
			return;
		}

		$did_posts[ $post->ID ] = true;
		// End avoid recursion

		if ( Plugin::madxartwork()->preview->is_preview_mode( $post->ID ) ) {
			$content = Plugin::madxartwork()->preview->builder_wrapper( '' ); // XSS ok
		} else {
			$document = Module::instance()->get_document( $post->ID );
			// On view theme document show it's preview content.
			if ( $document ) {
				$preview_type = $document->get_settings( 'preview_type' );
				$preview_id = $document->get_settings( 'preview_id' );

				if ( 0 === strpos( $preview_type, 'single' ) && ! empty( $preview_id ) ) {
					$post = get_post( $preview_id );

					if ( ! $post ) {
						return;
					}
				}
			}

			$editor = Plugin::madxartwork()->editor;

			// Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline
			$is_edit_mode = $editor->is_edit_mode();
			$editor->set_edit_mode( false );

			// Print manually (and don't use `the_content()`) because it's within another `the_content` filter, and the madxartwork filter has been removed to avoid recursion.
			$content = Plugin::madxartwork()->frontend->get_builder_content( $post->ID, true );

			// Restore edit mode state
			Plugin::madxartwork()->editor->set_edit_mode( $is_edit_mode );

			if ( empty( $content ) ) {
				Plugin::madxartwork()->frontend->remove_content_filter();

				// Split to pages.
				setup_postdata( $post );

				/** This filter is documented in wp-includes/post-template.php */
				echo apply_filters( 'the_content', get_the_content() );

				wp_link_pages( [
					'before' => '<div class="page-links madxartwork-page-links"><span class="page-links-title madxartwork-page-links-title">' . __( 'Pages:', 'madxartwork-pro' ) . '</span>',
					'after' => '</div>',
					'link_before' => '<span>',
					'link_after' => '</span>',
					'pagelink' => '<span class="screen-reader-text">' . __( 'Page', 'madxartwork-pro' ) . ' </span>%',
					'separator' => '<span class="screen-reader-text">, </span>',
				] );

				Plugin::madxartwork()->frontend->add_content_filter();

				return;
			} else {
				$content = apply_filters( 'the_content', $content );
			}
		} // End if().

		echo $content; // XSS ok.
	}

	public function render_plain_content() {}
}
