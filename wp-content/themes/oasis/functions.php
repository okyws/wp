<?php

use ColibriWP\Theme\Core\Hooks;
use ColibriWP\Theme\Core\Utils;
use ColibriWP\Theme\Defaults;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function oasis_override_defaults( $defaults, $overrides ) {
    $overrides = Utils::pathDelete( $overrides, array(
        'header_front_page.hero',
        'header_post.hero'
    ) );

    if ( file_exists( get_stylesheet_directory() . "/inc/defaults.php" ) ) {
        $defaults  = require_once get_stylesheet_directory() . "/inc/defaults.php";
        $overrides = array_replace_recursive( $overrides, $defaults );
    }

    if ( file_exists( get_stylesheet_directory() . "/inc/template-defaults.php" ) ) {
        $template_defaults = require_once get_stylesheet_directory() . "/inc/template-defaults.php";
        $defaults          = array_replace_recursive( $template_defaults, $overrides );
    }

    return $defaults;
}

function oasis_set_google_fonts() {
    colibriwp_assets()
        ->clearGoogleFonts()
        ->addGoogleFont( 'Open Sans', [ 400, 600 ] )
        ->addGoogleFont( 'Raleway', [ 400, 500 ] )
        ->addGoogleFont( 'Montserrat', [ 300, 400, 500, 600 ] )
        ->registerScript(
            'oasis-admin-js',
            get_stylesheet_directory_uri() . "/resources/admin/admin.js",
            array( 'jquery' ),
            false
        );
}


function oasis_override_translations( $texts ) {

    $name  = colibriwp_theme()->getThemeHeaderData( 'Name' );
    $texts = array_merge( $texts, array(
        'theme_page_name'                         => sprintf( __( '%s Settings', 'oasis' ), $name ),
        "page_builder_plugin_description"         => sprintf(
            __(
                '%1$s plugin adds drag and drop functionality and many other features to the %2$s theme.',
                'oasis'
            ),
            'Colibri Page Builder',
            $name
        ),
        "admin_sidebar_documentation_description" =>
            sprintf(
                __(
                    '%s is easy to learn and master, but you can always check out our documentation to learn about some features you might have missed.',
                    'oasis'
                ),
                $name
            ),
    ) );


    return $texts;
}

function oasis_theme_loaded() {
    oasis_set_google_fonts();

    Hooks::colibri_add_filter(
        'info_page_support_link',
        Hooks::identity( 'https://colibriwp.com/go/oasis-support' )
    );

    Hooks::colibri_add_filter(
        'info_page_review_link',
        Hooks::identity( 'https://wordpress.org/support/theme/' . get_stylesheet() . '/reviews/' )
    );


    Hooks::colibri_add_filter(
        'info_page_docs_link',
        Hooks::identity( 'https://colibriwp.com/go/oasis-docs' )
    );


    add_action( 'admin_enqueue_scripts', function () {

        $notice_disble_slug = get_template() . "-page-info";
        $is_notice_disabled = get_option( "{$notice_disble_slug}-theme-notice-dismissed", false );

        if ( ! $is_notice_disabled ) {
            wp_enqueue_script( 'oasis-admin-js' );
        }

    }, 20 );

}

function oasis_override_main_row_class( $classes ) {

    return Defaults::get( 'templates.blog.row.layout-classes', $classes );
}

function oasis_front_page_design_screenshot_url( $url, $design ) {
    $slug = Utils::pathGet( $design, 'meta.slug', '' );

    if ( $slug === 'modern' ) {
        return get_stylesheet_directory_uri() . "/resources/images/modern-screenshot.jpg";
    }

    return $url;
}

function oasis_overrides_colibriwp_components( $components ) {
    if ( ! apply_filters( 'colibri_page_builder/installed', false ) ) {
        require_once get_stylesheet_directory() . "/inc/FrontPageHero.php";
        require_once get_stylesheet_directory() . "/inc/InnerHero.php";
        $components['front-hero'] = "\\ExtendThemes\\Oasis\\FrontPageHero";
        $components['inner-hero'] = "\\ExtendThemes\\Oasis\\InnerHero";
    }

    return $components;
}

function oasis_set_white_texts_for_header_presets( $js_data ) {

    $headers = Utils::pathGet( $js_data, 'headers', array() );

    foreach ( $headers as $index => $header ) {
        $headers[ $index ]['data'] = array_merge(
            Utils::pathGet( $header, 'data', array() ),
            array(
                'header_front_page.hero.style.white-texts' => true
            )
        );
    }

    Utils::pathSet( $js_data, 'headers', $headers );

    return $js_data;
}

function oasis_remove_posts_per_row_option( $options ) {

    if ( Utils::pathGet( $options, 'settings.blog_posts_per_row' ) ) {
        unset( $options['settings']['blog_posts_per_row'] );
    }

    return $options;
}


function oasis_footer_parallax_is_enabled() {
        $parallax = (bool) get_theme_mod( "footer_post.footer.props.useFooterParallax", false );
        return ($parallax == true) ? 'true': 'false';
        
}

function oasis_theme_boot() {
    Hooks::colibri_add_filter( 'use_child_theme_header_data', '__return_true' );
    Hooks::colibri_add_filter( 'defaults', 'oasis_override_defaults', 10, 2 );
    Hooks::colibri_add_filter( 'translations', 'oasis_override_translations', 10, 2 );
    Hooks::colibri_add_filter( 'main_row_class', 'oasis_override_main_row_class', 10, 1 );
    Hooks::colibri_add_filter( 'front_page_design_screenshot_url', 'oasis_front_page_design_screenshot_url', 10, 2 );
    Hooks::colibri_add_filter( 'components', 'oasis_overrides_colibriwp_components' );
    Hooks::colibri_add_filter( 'customizer_js_data', 'oasis_set_white_texts_for_header_presets', 100 );
    Hooks::colibri_add_filter( 'customizer_options', 'oasis_remove_posts_per_row_option', 100 );

    require_once __DIR__ . "/inc/integration/colibri-page-builder/colibri-page-builder-integration.php";
}


add_action( 'colibriwp_theme_boot', 'oasis_theme_boot' );
add_action( 'after_setup_theme', 'oasis_theme_loaded', 40 );
