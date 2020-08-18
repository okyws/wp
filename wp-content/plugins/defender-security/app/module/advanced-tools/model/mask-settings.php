<?php
/**
 * Author: Hoang Ngo
 */

namespace WP_Defender\Module\Advanced_Tools\Model;

use Hammer\Helper\WP_Helper;

class Mask_Settings extends \Hammer\WP\Settings {
	public $mask_url = '';
	public $redirect_traffic = false;
	public $redirect_traffic_url = '';
	public $enabled = false;
	public $otps = [];
	private static $_instance;

	public function __construct( $id, $is_multi ) {
		parent::__construct( $id, $is_multi );
		$this->enabled          = ! ! $this->enabled;
		$this->redirect_traffic = ! ! $this->redirect_traffic;
	}

	/**
	 * @return Mask_Settings
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			$class           = new Mask_Settings( 'wd_masking_login_settings', WP_Helper::is_network_activate( wp_defender()->plugin_slug ) );
			self::$_instance = $class;
		}

		return self::$_instance;
	}

	/**
	 * @return bool
	 */
	public function isEnabled() {
		return $this->enabled && ( strlen( trim( $this->mask_url ) ) > 0 );
	}

	public function isRedirect() {
		return $this->redirect_traffic && ( strlen( trim( $this->redirect_traffic_url ) ) > 0 );
	}

	/**
	 * Return the attributes we will run an xss filters
	 * @return array
	 */
	public function filters() {
		return [
			'mask_url',
			'redirect_traffic_url'
		];
	}

	public function events() {
		$that = $this;

		return array(
			self::EVENT_AFTER_VALIDATE => array(
				array(
					function () use ( $that ) {
						if ( empty( $this->mask_url ) ) {
							return;
						}
						$forbidden = [
							'login',
							'wp-admin',
							'admin',
							'dashboard'
						];

						if ( in_array( $this->mask_url, $forbidden, true ) ) {
							$this->errors[] = __( 'A page already exists at this URL, please pick a unique page for your new login area.', 'wpdef' );

							return false;
						}
						$exits = get_page_by_path( $this->mask_url, OBJECT, [ 'post', 'page' ] );
						if ( is_object( $exits ) ) {
							$this->errors[] = __( 'A page already exists at this URL, please pick a unique page for your new login area.', 'wpdef' );

							return false;
						}

						if ( $this->mask_url === $this->redirect_traffic_url ) {
							$this->errors[] = __( 'Redirect URL must different from Login URL', 'wpdef' );

							return false;
						}
					}
				)
			)
		);
	}

	/**
	 * Define labels for settings key, we will use it for HUB
	 *
	 * @param null $key
	 *
	 * @return array|mixed
	 */
	public function labels( $key = null ) {
		$labels = [
			'enabled'              => __( 'Mask Login Area', "defender-security" ),
			'mask_url'             => __( "Masking URL", "defender-security" ),
			'redirect_traffic'     => __( 'Redirect traffic', "defender-security" ),
			'redirect_traffic_url' => __( "Redirection URL", "defender-security" ),
		];

		if ( $key != null ) {
			return isset( $labels[ $key ] ) ? $labels[ $key ] : null;
		}

		return $labels;
	}

	public function beforeValidate() {
		if ( $this->mask_url === $this->redirect_traffic_url && strlen( $this->redirect_traffic_url ) > 0 ) {
			$this->addError( 'redirect_traffic_url', __( "Redirect URL must different from Login URL", "defender-security" ) );
		}
	}
}