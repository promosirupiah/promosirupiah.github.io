<?php
namespace madxartworkPro\Modules\ThemeBuilder\ThemeSupport;

use madxartworkPro\Modules\ThemeBuilder\Classes\Locations_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Safe_Mode_Theme_Support {

	/**
	 * @param Locations_Manager $manager
	 */
	public function register_locations( $manager ) {
		$manager->register_core_location( 'header' );
		$manager->register_core_location( 'footer' );
	}

	public function do_header() {
		madxartwork_theme_do_location( 'header' );
	}

	public function do_footer() {
		madxartwork_theme_do_location( 'footer' );
	}

	public function __construct() {
		add_action( 'madxartwork/theme/register_locations', [ $this, 'register_locations' ] );

		add_action( 'madxartwork/page_templates/canvas/before_content', [ $this, 'do_header' ], 0 );
		add_action( 'madxartwork/page_templates/canvas/after_content', [ $this, 'do_footer' ], 0 );
	}
}
