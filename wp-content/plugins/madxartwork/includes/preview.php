<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * madxartwork preview.
 *
 * madxartwork preview handler class is responsible for initializing madxartwork in
 * preview mode.
 *
 * @since 1.0.0
 */
class Preview {

	/**
	 * Post ID.
	 *
	 * Holds the ID of the current post being previewed.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var int Post ID.
	 */
	private $post_id;

	/**
	 * Init.
	 *
	 * Initialize madxartwork preview mode.
	 *
	 * Fired by `template_redirect` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		if ( is_admin() || ! $this->is_preview_mode() ) {
			return;
		}

		if ( isset( $_GET['preview-debug'] ) ) {
			register_shutdown_function( function () {
				$e = error_get_last();
				if ( $e ) {
					echo '<div id="madxartwork-preview-debug-error"><pre>';
					echo $e['message'];
					echo '</pre></div>';
				}
			} );
		}

		$this->post_id = get_the_ID();

		// Don't redirect to permalink.
		remove_action( 'template_redirect', 'redirect_canonical' );

		// Compatibility with Yoast SEO plugin when 'Removes unneeded query variables from the URL' enabled.
		// TODO: Move this code to `includes/compatibility.php`.
		if ( class_exists( 'WPSEO_Frontend' ) ) {
			remove_action( 'template_redirect', [ \WPSEO_Frontend::get_instance(), 'clean_permalink' ], 1 );
		}

		// Disable the WP admin bar in preview mode.
		add_filter( 'show_admin_bar', '__return_false' );

		add_action( 'wp_enqueue_scripts', function() {
			$this->enqueue_styles();
			$this->enqueue_scripts();
		} );

		add_filter( 'the_content', [ $this, 'builder_wrapper' ], 999999 );

		add_action( 'wp_footer', [ $this, 'wp_footer' ] );

		// Avoid Cloudflare's Rocket Loader lazy load the editor iframe
		add_filter( 'script_loader_tag', [ $this, 'rocket_loader_filter' ], 10, 3 );

		// Tell to WP Cache plugins do not cache this request.
		Utils::do_not_cache();

		/**
		 * Preview init.
		 *
		 * Fires on madxartwork preview init, after madxartwork preview has finished
		 * loading but before any headers are sent.
		 *
		 * @since 1.0.0
		 *
		 * @param Preview $this The current preview.
		 */
		do_action( 'madxartwork/preview/init', $this );
	}

	/**
	 * Retrieve post ID.
	 *
	 * Get the ID of the current post.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return int Post ID.
	 */
	public function get_post_id() {
		return $this->post_id;
	}

	/**
	 * Whether preview mode is active.
	 *
	 * Used to determine whether we are in the preview mode (iframe).
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $post_id Optional. Post ID. Default is `0`.
	 *
	 * @return bool Whether preview mode is active.
	 */
	public function is_preview_mode( $post_id = 0 ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( ! User::is_current_user_can_edit( $post_id ) ) {
			return false;
		}

		if ( ! isset( $_GET['madxartwork-preview'] ) || $post_id !== (int) $_GET['madxartwork-preview'] ) {
			return false;
		}

		return true;
	}

	/**
	 * Builder wrapper.
	 *
	 * Used to add an empty HTML wrapper for the builder, the javascript will add
	 * the content later.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $content The content of the builder.
	 *
	 * @return string HTML wrapper for the builder.
	 */
	public function builder_wrapper( $content ) {
		if ( get_the_ID() === $this->post_id ) {
			$document = Plugin::$instance->documents->get( $this->post_id );

			$attributes = $document->get_container_attributes();

			$attributes['id'] = 'madxartwork';

			$attributes['class'] .= ' madxartwork-edit-mode';

			$content = '<div ' . Utils::render_html_attributes( $attributes ) . '></div>';
		}

		return $content;
	}

	/**
	 * Enqueue preview styles.
	 *
	 * Registers all the preview styles and enqueues them.
	 *
	 * Fired by `wp_enqueue_scripts` action.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function enqueue_styles() {
		// Hold-on all jQuery plugins after all HTML markup render.
		wp_add_inline_script( 'jquery-migrate', 'jQuery.holdReady( true );' );

		Plugin::$instance->frontend->enqueue_styles();

		Plugin::$instance->widgets_manager->enqueue_widgets_styles();

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		wp_register_style(
			'madxartwork-select2',
			madxartwork_ASSETS_URL . 'lib/e-select2/css/e-select2' . $suffix . '.css',
			[],
			'4.0.6-rc.1'
		);

		wp_register_style(
			'editor-preview',
			madxartwork_ASSETS_URL . 'css/editor-preview' . $direction_suffix . $suffix . '.css',
			[
				'madxartwork-select2',
			],
			madxartwork_VERSION
		);

		wp_enqueue_style( 'editor-preview' );

		/**
		 * Preview enqueue styles.
		 *
		 * Fires after madxartwork preview styles are enqueued.
		 *
		 * @since 1.0.0
		 */
		do_action( 'madxartwork/preview/enqueue_styles' );
	}

	/**
	 * Enqueue preview scripts.
	 *
	 * Registers all the preview scripts and enqueues them.
	 *
	 * Fired by `wp_enqueue_scripts` action.
	 *
	 * @since 1.5.4
	 * @access private
	 */
	private function enqueue_scripts() {
		Plugin::$instance->frontend->register_scripts();

		Plugin::$instance->widgets_manager->enqueue_widgets_scripts();

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'madxartwork-inline-editor',
			madxartwork_ASSETS_URL . 'lib/inline-editor/js/inline-editor' . $suffix . '.js',
			[],
			madxartwork_VERSION,
			true
		);

		/**
		 * Preview enqueue scripts.
		 *
		 * Fires after madxartwork preview scripts are enqueued.
		 *
		 * @since 1.5.4
		 */
		do_action( 'madxartwork/preview/enqueue_scripts' );
	}

	public function rocket_loader_filter( $tag, $handle, $src ) {
		return str_replace( '<script', '<script data-cfasync="false"', $tag );
	}

	/**
	 * madxartwork Preview footer scripts and styles.
	 *
	 * Handle styles and scripts from frontend.
	 *
	 * Fired by `wp_footer` action.
	 *
	 * @since 2.0.9
	 * @access public
	 */
	public function wp_footer() {
		$frontend = Plugin::$instance->frontend;
		if ( $frontend->has_madxartwork_in_page() ) {
			// Has header/footer/widget-template - enqueue all style/scripts/fonts.
			$frontend->wp_footer();
		} else {
			// Enqueue only scripts.
			$frontend->enqueue_scripts();
		}
	}

	/**
	 * Preview constructor.
	 *
	 * Initializing madxartwork preview.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'template_redirect', [ $this, 'init' ], 0 );
	}
}
