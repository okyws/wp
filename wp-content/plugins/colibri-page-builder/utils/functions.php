<?php

use ColibriWP\PageBuilder\ThemeHooks;

add_action( 'colibri_page_builder/activated', function ($builder ) {

	/** @var  \ColibriWP\PageBuilder\PageBuilder $builder */
	$builder->__createFrontPage();
} );


function colibri_builder_customize_reorganize( $wp_customize ) {
	$generalSettingsSections = array(
		'title_tagline',
		'colors',
		'general_site_style',
		'background_image',
		'static_front_page',
		'custom_css',
		'user_custom_widgets_areas',
		'header_image',
		'nav_menus'
	);


	/** @var \WP_Customize_Manager $wp_customize */
	$wp_customize->add_panel( 'general_settings', array(
		'title'    => esc_html__( 'General Settings', 'colibri-page-builder' ),
		'priority' => 5,
	) );


	$priority = 1;
	foreach ( $generalSettingsSections as $section_id ) {
		$section = $wp_customize->get_section( $section_id );

		if ( $section ) {
			$section->panel    = 'general_settings';
			$section->priority = $priority;
			$priority ++;
		}

	}
}

add_filter( 'colibri_page_builder/license_data', function ( $data ) {

	$data = array_merge( $data, array(
		'license_active_endpoint' => 'https://app.colibriwp.com/api/license/activate',
		'license_check_endpoint'  => 'https://app.colibriwp.com/api/license/check',
		'product_update_endpoint' => 'https://app.colibriwp.com/api/product/update',
		'dashboard_url'           => 'https://app.colibriwp.com/',
	) );

	return $data;
} );


add_action( 'customize_register', 'colibri_builder_customize_reorganize', PHP_INT_MAX, 1 );

ThemeHooks::prefixed_add_filter( 'customizer_skip_boot', '__return_true' );


function colibri_upgrade_url()  {
	$activate_theme_name = get_option( 'colibriwp_activate_theme_name', '' );

	return apply_filters( 
		'colibri_page_builder/upgrade_url', 
		'https://colibriwp.com/?utm_source=install&utm_medium=wp&utm_campaign=' . $activate_theme_name
	);
}

function colibri_try_demo_url()  {
	$activate_theme_name = get_option( 'colibriwp_activate_theme_name', '' );
	
	return apply_filters( 
		'colibri_page_builder/try_demo_url', 
		'https://app.colibriwp.com/#/get-started/?utm_source=trydemolink&utm_medium=wp&source=try-template&utm_campaign=' . $activate_theme_name
	);
}

function colibri_try_url()  {
	$activate_theme_name = get_option( 'colibriwp_activate_theme_name', '' );
		
	return apply_filters( 
		'colibri_page_builder/try_url', 
		'https://app.colibriwp.com/#/get-started/?utm_source=trylink&utm_medium=wp&utm_campaign=' . $activate_theme_name
	);
}
