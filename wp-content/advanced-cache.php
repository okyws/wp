<?php
/**
 * Hummingbird Advanced Tools module
 *
 * @package Hummingbird
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Load necessary modules for caching.
 */

if ( ! class_exists( 'Hummingbird\\Core\\Modules\\Page_Cache' ) ) {
	if ( defined( 'WP_ADMIN' ) && WP_ADMIN ) {
		return;
	}

	if ( is_dir( WP_CONTENT_DIR . '/plugins/wp-hummingbird/' ) ) {
		$plugin_path = WP_CONTENT_DIR . '/plugins/wp-hummingbird/';
	} elseif ( is_dir( WP_CONTENT_DIR . '/plugins/hummingbird-performance/' ) ) {
		$plugin_path = WP_CONTENT_DIR . '/plugins/hummingbird-performance/';
	} else {
		return;
	}

	include_once $plugin_path . 'core/class-utils.php';
	include_once $plugin_path . 'core/class-module.php';
	include_once $plugin_path . 'core/traits/trait-wpconfig.php';
	include_once $plugin_path . 'core/modules/class-page-cache.php';

	if ( ! method_exists( 'Hummingbird\\Core\\Modules\\Page_Cache', 'serve_cache' ) ) {
		return;
	}

	define( 'WPHB_ADVANCED_CACHE', true );
	\Hummingbird\Core\Modules\Page_Cache::serve_cache();
}
