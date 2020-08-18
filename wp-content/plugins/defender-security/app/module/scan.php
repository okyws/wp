<?php
/**
 * Author: Hoang Ngo
 */

namespace WP_Defender\Module;

use Hammer\Base\Module;
use WP_Defender\Module\Scan\Controller\Main;
use WP_Defender\Module\Scan\Controller\Rest;

class Scan extends Module {
	public function __construct() {
		$this->_registerPostTpe();
		$main = new Main();
		$rest = new Rest();
	}

	private function _registerPostTpe() {
		register_post_type( 'wdf_scan', array(
			'labels'              => array(
				'name'          => __( "Scans", "defender-security" ),
				'singular_name' => __( "Scan", "defender-security" )
			),
			'capability_type'     => array( 'wdf_scan', 'wdf_scans' ),
			'supports'            => array( '' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => false,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'rewrite'             => false,
		) );
		register_post_type( 'wdf_scan_item', array(
			'labels'              => array(
				'name'          => __( "Scan Items", "defender-security" ),
				'singular_name' => __( "Scan Item", "defender-security" )
			),
			'capability_type'     => array( 'wdf_scan_item', 'wdf_scan_items' ),
			'supports'            => array( '' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'rewrite'             => false,
		) );
	}
}