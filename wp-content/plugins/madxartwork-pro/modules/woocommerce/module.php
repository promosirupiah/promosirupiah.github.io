<?php
namespace madxartworkPro\Modules\Woocommerce;

use madxartwork\Core\Documents_Manager;
use madxartworkPro\Base\Module_Base;
use madxartworkPro\Modules\ThemeBuilder\Classes\Conditions_Manager;
use madxartworkPro\Modules\Woocommerce\Conditions\Woocommerce;
use madxartworkPro\Modules\Woocommerce\Documents\Product;
use madxartworkPro\Modules\Woocommerce\Documents\Product_Post;
use madxartworkPro\Modules\Woocommerce\Documents\Product_Archive;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends Module_Base {

	const WOOCOMMERCE_GROUP = 'woocommerce';

	protected $docs_types = [];

	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public static function is_product_search() {
		return is_search() && 'product' === get_query_var( 'post_type' );
	}

	private static function render_cart_item( $cart_item_key, $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$is_product_visible = ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) );

		if ( ! $is_product_visible ) {
			return;
		}

		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
		$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
		?>
		<div class="madxartwork-menu-cart__product woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

			<div class="madxartwork-menu-cart__product-image product-thumbnail">
				<?php
				$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

				if ( ! $product_permalink ) :
					echo wp_kses_post( $thumbnail );
				else :
					printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
				endif;
				?>
			</div>

			<div class="madxartwork-menu-cart__product-name product-name" data-title="<?php esc_attr_e( 'Product', 'madxartwork-pro' ); ?>">
				<?php
				if ( ! $product_permalink ) :
					echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
				else :
					echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
				endif;

				do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

				// Meta data.
				echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
				?>
			</div>

			<div class="madxartwork-menu-cart__product-price product-price" data-title="<?php esc_attr_e( 'Price', 'madxartwork-pro' ); ?>">
				<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
			</div>

			<div class="madxartwork-menu-cart__product-remove product-remove">
				<?php
				echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
					'<a href="%s" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"></a>',
					esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
					__( 'Remove this item', 'madxartwork-pro' ),
					esc_attr( $product_id ),
					esc_attr( $cart_item_key ),
					esc_attr( $_product->get_sku() )
				), $cart_item_key );
				?>
			</div>
		</div>
		<?php
	}

	private static function render_cart_empty() {
		?>
		<div class="woocommerce-mini-cart__empty-message"><?php esc_attr_e( 'No products in the cart.', 'madxartwork-pro' ); ?></div>
		<?php
	}

	private static function render_cart_content( $cart_items, $sub_total ) {
		if ( empty( $cart_items ) ) {
			self::render_cart_empty();
			return;
		}
		?>
		<div class="madxartwork-menu-cart__products woocommerce-mini-cart cart woocommerce-cart-form__contents">
			<?php
			do_action( 'woocommerce_before_mini_cart_contents' );

			foreach ( $cart_items as $cart_item_key => $cart_item ) {
				self::render_cart_item( $cart_item_key, $cart_item );
			}

			do_action( 'woocommerce_mini_cart_contents' );
			?>
		</div>

		<div class="madxartwork-menu-cart__subtotal">
			<strong><?php echo translate( 'Subtotal', 'woocommerce' ); ?>:</strong> <?php echo $sub_total; ?>
		</div>
		<div class="madxartwork-menu-cart__footer-buttons">
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="madxartwork-button madxartwork-button--view-cart madxartwork-size-md">
				<span class="madxartwork-button-text"><?php echo translate( 'View cart', 'woocommerce' ); ?></span>
			</a>
			<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="madxartwork-button madxartwork-button--checkout madxartwork-size-md">
				<span class="madxartwork-button-text"><?php echo translate( 'Checkout', 'woocommerce' ); ?></span>
			</a>
		</div>
		<?php
	}

	public function get_name() {
		return 'woocommerce';
	}

	public function get_widgets() {
		return [
			'Archive_Products',
			'Archive_Products_Deprecated',
			'Archive_Description',
			'Products',
			'Products_Deprecated',

			'Breadcrumb',
			'Add_To_Cart',
			'Elements',
			'Single_Elements',
			'Categories',
			'Menu_Cart',

			'Product_Title',
			'Product_Images',
			'Product_Price',
			'Product_Add_To_Cart',
			'Product_Rating',
			'Product_Stock',
			'Product_Meta',
			'Product_Short_Description',
			'Product_Content',
			'Product_Data_Tabs',
			'Product_Additional_Information',
			'Product_Related',
			'Product_Upsell',
		];
	}

	public function add_product_post_class( $classes ) {
		$classes[] = 'product';

		return $classes;
	}

	public function add_products_post_class_filter() {
		add_filter( 'post_class', [ $this, 'add_product_post_class' ] );
	}

	public function remove_products_post_class_filter() {
		remove_filter( 'post_class', [ $this, 'add_product_post_class' ] );
	}

	public function register_tags() {
		$tags = [
			'Product_Gallery',
			'Product_Image',
			'Product_Price',
			'Product_Rating',
			'Product_Sale',
			'Product_Short_Description',
			'Product_SKU',
			'Product_Stock',
			'Product_Terms',
			'Product_Title',
			'Category_Image',
		];

		/** @var \madxartwork\Core\DynamicTags\Manager $module */
		$module = Plugin::madxartwork()->dynamic_tags;

		$module->register_group( self::WOOCOMMERCE_GROUP, [
			'title' => __( 'WooCommerce', 'madxartwork-pro' ),
		] );

		foreach ( $tags as $tag ) {
			$module->register_tag( 'madxartworkPro\\Modules\\Woocommerce\\tags\\' . $tag );
		}
	}

	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	/**
	 * @param Conditions_Manager $conditions_manager
	 */
	public function register_conditions( $conditions_manager ) {
		$woocommerce_condition = new Woocommerce();

		$conditions_manager->get_condition( 'general' )->register_sub_condition( $woocommerce_condition );
	}

	/**
	 * @param Documents_Manager $documents_manager
	 */
	public function register_documents( $documents_manager ) {
		$this->docs_types = [
			'product-post' => Product_Post::get_class_full_name(),
			'product' => Product::get_class_full_name(),
			'product-archive' => Product_Archive::get_class_full_name(),
		];

		foreach ( $this->docs_types as $type => $class_name ) {
			$documents_manager->register_document_type( $type, $class_name );
		}
	}

	public static function render_menu_cart() {
		if ( null === WC()->cart ) {
			return;
		}

		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', is_cart() || is_checkout() );
		$product_count = WC()->cart->get_cart_contents_count();
		$sub_total = WC()->cart->get_cart_subtotal();
		$cart_items = WC()->cart->get_cart();

		$toggle_button_link = $widget_cart_is_hidden ? wc_get_cart_url() : '#';
		/** workaround WooCommerce Subscriptions issue that changes the behavior of is_cart() */
		$toggle_button_classes = 'madxartwork-button madxartwork-size-sm';
		$toggle_button_classes .= $widget_cart_is_hidden ? ' madxartwork-menu-cart-hidden' : '';
		$counter_attr = 'data-counter="' . $product_count . '"';

		?>
		<div class="madxartwork-menu-cart__wrapper">
			<?php if ( ! $widget_cart_is_hidden ) : ?>
			<div class="madxartwork-menu-cart__container madxartwork-lightbox">
				<form class="madxartwork-menu-cart__main woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
					<div class="madxartwork-menu-cart__close-button"></div>
					<?php self::render_cart_content( $cart_items, $sub_total ); ?>
				</form>
			</div>
			<?php endif; ?>

			<div class="madxartwork-menu-cart__toggle madxartwork-button-wrapper">
				<a href="<?php echo esc_attr( $toggle_button_link ); ?>" class="<?php echo $toggle_button_classes; ?>">
					<span class="madxartwork-button-text"><?php echo $sub_total; ?></span>
					<span class="madxartwork-button-icon" <?php echo $counter_attr; ?>>
						<i class="eicon" aria-hidden="true"></i>
						<span class="madxartwork-screen-only"><?php esc_html_e( 'Cart', 'madxartwork-pro' ); ?></span>
					</span>
				</a>
			</div>
		</div>
		<?php
	}

	public function menu_cart_fragments( $fragments ) {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );
		if ( ! $has_cart ) {
			return $fragments;
		}

		ob_start();
		self::render_menu_cart();
		$menu_cart_html = ob_get_clean();

		if ( ! empty( $menu_cart_html ) ) {
			$fragments['body:not(.madxartwork-editor-active) div.madxartwork-element.madxartwork-widget.madxartwork-widget-woocommerce-menu-cart div.madxartwork-menu-cart__wrapper'] = $menu_cart_html;
		}

		return $fragments;
	}

	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session = new $session_class();
			WC()->session->init();
			WC()->cart = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	public function localized_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'widgets' => [
				'theme-archive-title' => [
					'categories' => [
						'woocommerce-elements-archive',
					],
				],
			],
		] );

		return $settings;
	}

	public function theme_template_include( $need_override_location, $location ) {
		if ( is_product() && 'single' === $location ) {
			$need_override_location = true;
		}

		return $need_override_location;
	}

	public function __construct() {
		parent::__construct();

		add_action( 'madxartwork/editor/before_enqueue_scripts', [ $this, 'maybe_init_cart' ] );
		add_action( 'madxartwork/dynamic_tags/register_tags', [ $this, 'register_tags' ] );
		add_action( 'madxartwork/documents/register', [ $this, 'register_documents' ] );
		add_action( 'madxartwork/theme/register_conditions', [ $this, 'register_conditions' ] );

		add_filter( 'madxartwork/theme/need_override_location', [ $this, 'theme_template_include' ], 10, 2 );

		add_filter( 'madxartwork/editor/localize_settings', [ $this, 'localized_settings' ] );

		// On Editor - Register WooCommerce frontend hooks before the Editor init.
		// Priority = 5, in order to allow plugins remove/add their wc hooks on init.
		if ( ! empty( $_REQUEST['action'] ) && 'madxartwork' === $_REQUEST['action'] && is_admin() ) {
			add_action( 'init', [ $this, 'register_wc_hooks' ], 5 );
		}

		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'menu_cart_fragments' ] );
	}
}
