<?php
namespace madxartworkPro\Modules\DynamicTags\Tags;

use madxartwork\Core\DynamicTags\Tag;
use madxartworkPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_Title extends Tag {
	public function get_name() {
		return 'site-title';
	}

	public function get_title() {
		return __( 'Site Title', 'madxartwork-pro' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_bloginfo() );
	}
}
