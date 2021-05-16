<?php
namespace madxartworkPro;

use madxartworkPro\Core\Connect;
use madxartwork\Core\Responsive\Files\Frontend as FrontendFile;
use madxartwork\Core\Responsive\Responsive;
use madxartwork\Utils;
use madxartworkPro\Core\Editor\Editor;
use madxartworkPro\Core\Upgrade\Manager as UpgradeManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Main class plugin
 */
class Plugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	public $modules_manager;

	/**
	 * @var UpgradeManager
	 */
	public $upgrade;

	/**
	 * @var Editor
	 */
	public $editor;

	/**
	 * @var Admin
	 */
	public $admin;

	/**
	 * @var License\Admin
	 */
	public $license_admin;

	private $classes_aliases = [
		'madxartworkPro\Modules\PanelPostsControl\Module' => 'madxartworkPro\Modules\QueryControl\Module',
		'madxartworkPro\Modules\PanelPostsControl\Controls\Group_Control_Posts' => 'madxartworkPro\Modules\QueryControl\Controls\Group_Control_Posts',
		'madxartworkPro\Modules\PanelPostsControl\Controls\Query' => 'madxartworkPro\Modules\QueryControl\Controls\Query',
	];

	/**
	 * @deprecated since 1.1.0 Use `madxartwork_PRO_VERSION` instead
	 *
	 * @return string
	 */
	public function get_version() {
		_deprecated_function( __METHOD__, '1.1.0' );

		return madxartwork_PRO_VERSION;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Something went wrong.', 'madxartwork-pro' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Something went wrong.', 'madxartwork-pro' ), '1.0.0' );
	}

	/**
	 * @return \madxartwork\Plugin
	 */

	public static function madxartwork() {
		return \madxartwork\Plugin::$instance;
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function includes() {
		require madxartwork_PRO_PATH . 'includes/modules-manager.php';

		if ( is_admin() ) {
			require madxartwork_PRO_PATH . 'includes/admin.php';
		}
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = madxartwork_PRO_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	public function enqueue_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		$frontend_file_name = 'frontend' . $direction_suffix . $suffix . '.css';

		$has_custom_file = Responsive::has_custom_breakpoints();

		if ( $has_custom_file ) {
			$frontend_file = new FrontendFile( 'custom-pro-' . $frontend_file_name, self::get_responsive_templates_path() . $frontend_file_name );

			$time = $frontend_file->get_meta( 'time' );

			if ( ! $time ) {
				$frontend_file->update();
			}

			$frontend_file_url = $frontend_file->get_url();
		} else {
			$frontend_file_url = madxartwork_PRO_ASSETS_URL . 'css/' . $frontend_file_name;
		}

		wp_enqueue_style(
			'madxartwork-pro',
			$frontend_file_url,
			[],
			$has_custom_file ? null : madxartwork_PRO_VERSION
		);
	}

	public function enqueue_frontend_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'madxartwork-pro-frontend',
			madxartwork_PRO_URL . 'assets/js/frontend' . $suffix . '.js',
			[
				'madxartwork-frontend-modules',
				'madxartwork-sticky',
			],
			madxartwork_PRO_VERSION,
			true
		);

		$locale_settings = [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'madxartwork-pro-frontend' ),
		];

		/**
		 * Localize frontend settings.
		 *
		 * Filters the frontend localized settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $locale_settings Localized settings.
		 */
		$locale_settings = apply_filters( 'madxartwork_pro/frontend/localize_settings', $locale_settings );

		Utils::print_js_config(
			'madxartwork-pro-frontend',
			'madxartworkProFrontendConfig',
			$locale_settings
		);
	}

	public function register_frontend_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'smartmenus',
			madxartwork_PRO_URL . 'assets/lib/smartmenus/jquery.smartmenus' . $suffix . '.js',
			[
				'jquery',
			],
			'1.0.1',
			true
		);

		wp_register_script(
			'social-share',
			madxartwork_PRO_URL . 'assets/lib/social-share/social-share' . $suffix . '.js',
			[
				'jquery',
			],
			'0.2.17',
			true
		);

		wp_register_script(
			'madxartwork-sticky',
			madxartwork_PRO_URL . 'assets/lib/sticky/jquery.sticky' . $suffix . '.js',
			[
				'jquery',
			],
			madxartwork_PRO_VERSION,
			true
		);
	}

	public function get_responsive_stylesheet_templates( $templates ) {
		$templates_paths = glob( self::get_responsive_templates_path() . '*.css' );

		foreach ( $templates_paths as $template_path ) {
			$file_name = 'custom-pro-' . basename( $template_path );

			$templates[ $file_name ] = $template_path;
		}

		return $templates;
	}

	public function on_madxartwork_init() {
		$this->modules_manager = new Manager();

		/** TODO: BC for madxartwork v2.4.0 */
		if ( class_exists( '\madxartwork\Core\Upgrade\Manager' ) ) {
			$this->upgrade = UpgradeManager::instance();
		}

		/**
		 * madxartwork Pro init.
		 *
		 * Fires on madxartwork Pro init, after madxartwork has finished loading but
		 * before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		do_action( 'madxartwork_pro/init' );
	}

	/**
	 * @param \madxartwork\Core\Base\Document $document
	 */
	public function on_document_save_version( $document ) {
		$document->update_meta( '_madxartwork_pro_version', madxartwork_PRO_VERSION );
	}

	private function get_responsive_templates_path() {
		return madxartwork_PRO_ASSETS_PATH . 'css/templates/';
	}

	private function setup_hooks() {
		add_action( 'madxartwork/init', [ $this, 'on_madxartwork_init' ] );

		add_action( 'madxartwork/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );

		add_action( 'madxartwork/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'madxartwork/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );

		add_filter( 'madxartwork/core/responsive/get_stylesheet_templates', [ $this, 'get_responsive_stylesheet_templates' ] );
		add_action( 'madxartwork/document/save_version', [ $this, 'on_document_save_version' ] );
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->includes();

		new Connect\Manager();

		$this->setup_hooks();

		$this->editor = new Editor();

		if ( is_admin() ) {
			$this->admin = new Admin();
			$this->license_admin = new License\Admin();
		}
	}

	final public static function get_title() {
		return __( 'madxartwork Pro', 'madxartwork-pro' );
	}
}

if ( ! defined( 'madxartwork_PRO_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}
