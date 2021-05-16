<?php
namespace madxartworkPro\Modules\ThemeBuilder\Skins;

use madxartworkPro\Modules\Posts\Skins\Skin_Classic;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Posts_Archive_Skin_Classic extends Skin_Classic {

	protected function _register_controls_actions() {
		add_action( 'madxartwork/element/archive-posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'madxartwork/element/archive-posts/section_layout/after_section_end', [ $this, 'register_style_sections' ] );
	}

	public function get_id() {
		return 'archive_classic';
	}

	public function get_title() {
		return __( 'Classic', 'madxartwork-pro' );
	}

	public function render() {
		$this->parent->query_posts();

		$wp_query = $this->parent->get_query();

		if ( ! $wp_query->found_posts ) {
			$this->render_loop_header();

			$should_escape = apply_filters( 'madxartwork_pro/theme_builder/archive/escape_nothing_found_message', true );

			$message = $this->parent->get_settings_for_display( 'nothing_found_message' );
			if ( $should_escape ) {
				$message = esc_html( $message );
			}

			echo '<div class="madxartwork-posts-nothing-found">' . $message . '</div>';

			$this->render_loop_footer();

			return;
		}

		parent::render();
	}

	public function get_container_class() {
		// Use parent class and parent css.
		return 'madxartwork-posts--skin-classic';
	}

	/* Remove `posts_per_page` control */
	protected function register_post_count_control(){}
}
