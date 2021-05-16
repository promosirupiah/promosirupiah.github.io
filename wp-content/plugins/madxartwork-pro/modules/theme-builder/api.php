<?php

use madxartworkPro\Modules\ThemeBuilder\Module as Theme_Builder_Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function madxartwork_theme_do_location( $location ) {
	/** @var Theme_Builder_Module $theme_builder_module */
	$theme_builder_module = Theme_Builder_Module::instance();

	return $theme_builder_module->get_locations_manager()->do_location( $location );
}

function madxartwork_location_exits( $location, $check_match = false ) {
	/** @var Theme_Builder_Module $theme_builder_module */
	$theme_builder_module = Theme_Builder_Module::instance();

	return $theme_builder_module->get_locations_manager()->location_exits( $location, $check_match );
}
