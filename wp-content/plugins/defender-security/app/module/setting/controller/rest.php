<?php
/**
 * Author: Hoang Ngo
 */

namespace WP_Defender\Module\Setting\Controller;


use Hammer\Helper\HTTP_Helper;
use WP_Defender\Behavior\Utils;
use WP_Defender\Controller;
use WP_Defender\Module\Setting;
use WP_Defender\Module\Two_Factor\Model\Auth_Settings;

class Rest extends Controller {
	public function __construct() {
		$namespace = 'wp-defender/v1';
		$namespace .= '/settings';
		$routes    = [
			$namespace . '/updateSettings' => 'updateSettings',
			$namespace . '/resetSettings'  => 'resetSettings'

		];
		$this->registerEndpoints( $routes, Setting::getClassName() );
	}

	public function resetSettings() {
		if ( ! $this->checkPermission() ) {
			return;
		}

		if ( ! wp_verify_nonce( HTTP_Helper::retrieveGet( '_wpnonce' ), 'resetSettings' ) ) {
			return;
		}

		$hardener_settings = \WP_Defender\Module\Hardener\Model\Settings::instance();
		foreach ( $hardener_settings->getFixed() as $rule ) {
			$rule->getService()->revert();
		}

		$cache = \Hammer\Helper\WP_Helper::getCache();
		$cache->delete( 'isActivated' );
		$cache->delete( 'wdf_isActivated' );
		$cache->delete( 'wdfchecksum' );
		$cache->delete( 'cleanchecksum' );

		\WP_Defender\Module\Scan\Model\Settings::instance()->delete();
		if ( class_exists( '\WP_Defender\Module\Audit\Model\Settings' ) ) {
			\WP_Defender\Module\Audit\Model\Settings::instance()->delete();
		}
		$hardener_settings->delete();
		\WP_Defender\Module\IP_Lockout\Model\Settings::instance()->delete();
		Auth_Settings::instance()->delete();
		\WP_Defender\Module\Advanced_Tools\Model\Mask_Settings::instance()->delete();
		\WP_Defender\Module\Advanced_Tools\Model\Security_Headers_Settings::instance()->delete();
		\WP_Defender\Module\Setting\Model\Settings::instance()->delete();

		//Disabled  Blacklist Monitor
		if ( ! wp_defender()->isFree && $this->hasMethod( 'toggleStatus' ) ) {
			$this->toggleStatus( null, false );
			delete_site_transient( \WP_Defender\Behavior\Blacklist::CACHE_KEY );
		}
		//clear old stuff
		delete_site_option( 'wp_defender' );
		delete_option( 'wp_defender' );
		delete_option( 'wd_db_version' );
		delete_site_option( 'wd_db_version' );
		
		delete_site_transient( 'wp_defender_free_is_activated' );
		delete_site_transient( 'wp_defender_is_activated' );
		delete_transient( 'wp_defender_free_is_activated' );
		delete_transient( 'wp_defender_is_activated' );
		
		delete_site_option( 'wp_defender_free_is_activated' );
		delete_site_option( 'wp_defender_is_activated' );
		delete_option( 'wp_defender_free_is_activated' );
		delete_option( 'wp_defender_is_activated' );

		$res = array(
			'message' => __( "Your settings have been reset.", "defender-security" )
		);

		Utils::instance()->submitStatsToDev();
		wp_send_json_success( $res );
	}

	public function updateSettings() {
		if ( ! $this->checkPermission() ) {
			return;
		}

		if ( ! wp_verify_nonce( HTTP_Helper::retrieveGet( '_wpnonce' ), 'updateSettings' ) ) {
			return;
		}
		$settings = Setting\Model\Settings::instance();
		$data     = stripslashes( $_POST['data'] );
		$data     = json_decode( $data, true );
		$settings->import( $data );
		$settings->save();
		$res = array(
			'message' => __( "Your settings have been updated.", "defender-security" )
		);

		$this->submitStatsToDev();
		wp_send_json_success( $res );
	}


	/**
	 * @return array
	 */
	public function behaviors() {
		$behaviors = array(
			'utils'     => '\WP_Defender\Behavior\Utils',
			'endpoints' => '\WP_Defender\Behavior\Endpoint',
			'wpmudev'   => '\WP_Defender\Behavior\WPMUDEV',
			'blacklist' => wp_defender()->isFree ? '\WP_Defender\Behavior\Blacklist_Free' : '\WP_Defender\Behavior\Blacklist',
		);

		return $behaviors;
	}
}