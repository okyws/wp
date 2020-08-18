<?php

add_filter( 'colibri_page_builder/remote_import_slug', function ( $remote_default_path, $front_page_design ) {

    if ( $front_page_design === null ) {
        return $remote_default_path;
    }

    if ( intval( $front_page_design ) === 3 || intval( $front_page_design ) === 0 ) {
        $remote_default_path = get_stylesheet();
    }

    return $remote_default_path;
}, 10, 2 );

add_filter( 'mesmerize_notifications_template_slug', 'get_stylesheet' );

add_filter( 'mesmerize_notifications_stylesheet_slug', 'get_stylesheet' );


add_filter( 'colibri_page_builder/upgrade_url', function ( $url ) {
	return add_query_arg( 'utm_source', 'install_' . get_stylesheet(), $url);
} );

add_filter( 'colibri_page_builder/try_url', function ( $url ) {
	return add_query_arg( 'utm_source', get_stylesheet() . '_trylink', $url);
} );
