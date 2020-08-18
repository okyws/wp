<?php
/**
 * Integration module for various page builders.
 *
 * @since 2.4.0
 * @package Hummingbird\Core\Integration
 */

namespace Hummingbird\Core\Integration;

use Hummingbird\WP_Hummingbird;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Builders
 */
class Builders {

	/**
	 * Builders constructor.
	 */
	public function __construct() {
		// Clear cache after layout save.
		add_action( 'fl_builder_after_save_layout', array( $this, 'beaver_builder_clear_cache' ) );
	}

	/**
	 * Clear cache on beaver builder save layout action.
	 *
	 * @since 2.4.0
	 */
	public function beaver_builder_clear_cache() {
		if ( ! isset( $_POST['fl_builder_data']['post_id'] ) ) {
			return;
		}

		WP_Hummingbird::flush_cache( false, false, false );
	}

}
