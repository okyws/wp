<?php

/**
 * Author: Hoang Ngo
 */
class WD_Main_Activator {
	public $wp_defender;

	public function __construct( WP_Defender_Free $wp_defender ) {
		$this->wp_defender = $wp_defender;
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'wp_loaded', array( &$this, 'maybeShowNotice' ) );
		add_action( 'wp_ajax_hideDefenderNotice', array( &$this, 'hideNotice' ) );
		add_action( 'wp_loaded', array( &$this, 'upgradeHook' ), 5 );
		//add_action( 'activated_plugin', array( &$this, 'redirectToDefender' ) );
	}

	public function upgradeHook() {
		$db_ver = get_site_option( 'wd_db_version' );
		if ( false != $db_ver && version_compare( $db_ver, '2.2.9', '>=' ) ) {
			return;
		}
		if ( false != $db_ver && version_compare( $db_ver, '2.2.9', '<' ) ) {
			// Migrate security headers into Advanced Tools from Security Tweaks
			$this->migrateSecurityHeaders();
			//add a flag for showing new feature
			update_site_option( 'waf_show_new_feature', true );
		}

		\WP_Defender\Module\Setting\Component\Backup_Settings::backupData();
		if ( false != $db_ver && version_compare( $db_ver, '2.2', '<' ) ) {
			$scan_settings = get_site_option( 'wd_scan_settings' );
			$settings      = \WP_Defender\Module\Scan\Model\Settings::instance();
			if ( isset( $scan_settings['receiptsNotification'] ) ) {
				$settings->recipients_notification = $scan_settings['receiptsNotification'];
			}
			if ( isset( $scan_settings['receipts'] ) ) {
				$settings->recipients = $scan_settings['receipts'];
			}
			if ( isset( $scan_settings['alwaysSendNotification'] ) ) {
				$settings->always_send_notification = $scan_settings['alwaysSendNotification'];
			}
			$result    = $settings->save();
			$msettings = get_site_option( 'wd_main_settings' );
			if ( isset( $msettings['high_contrast_mode'] ) ) {
				$highcontast                  = filter_var( $msettings['high_contrast_mode'], FILTER_VALIDATE_BOOLEAN );
				$settings                     = \WP_Defender\Module\Setting\Model\Settings::instance();
				$settings->high_contrast_mode = $highcontast;
				$ret                          = $settings->save();
			}
		}
		if ( false != $db_ver && version_compare( $db_ver, '2.2.1', '<' ) ) {
			$mask_url_settings = get_site_option( 'wd_masking_login_settings' );
			$model             = \WP_Defender\Module\Advanced_Tools\Model\Mask_Settings::instance();

			if ( isset( $mask_url_settings['maskUrl'] ) ) {
				$model->mask_url = $mask_url_settings['maskUrl'];
			}
			if ( isset( $mask_url_settings['redirectTraffic'] ) ) {
				$model->redirect_traffic = $mask_url_settings['redirectTraffic'];
			}
			if ( isset( $mask_url_settings['redirectTrafficUrl'] ) ) {
				$model->redirect_traffic_url = $mask_url_settings['redirectTrafficUrl'];
			}
			//delete cache to force update
			$ret              = $model->save();
			$factors_settings = get_site_option( 'wd_2auth_settings' );
			$settings         = \WP_Defender\Module\Two_Factor\Model\Auth_Settings::instance();
			if ( isset( $factors_settings['lostPhone'] ) ) {
				$settings->lost_phone = $factors_settings['lostPhone'];
			}
			if ( isset( $factors_settings['forceAuth'] ) ) {
				$settings->force_auth = $factors_settings['forceAuth'];
			}
			if ( isset( $factors_settings['forceAuthMess'] ) ) {
				$settings->force_auth_mess = $factors_settings['forceAuthMess'];
			}
			if ( isset( $factors_settings['userRoles'] ) ) {
				$settings->user_roles = $factors_settings['userRoles'];
			}
			if ( isset( $factors_settings['forceAuthRoles'] ) ) {
				$settings->force_auth_roles = $factors_settings['forceAuthRoles'];
			}
			if ( isset( $factors_settings['customGraphicURL'] ) ) {
				$settings->custom_graphic_url = $factors_settings['customGraphicURL'];
			}
			if ( isset( $factors_settings['customGraphic'] ) ) {
				$settings->custom_graphic = $factors_settings['customGraphic'];
			}
			$ret = $settings->save();
		}
		update_site_option( 'wd_db_version', wp_defender()->db_version );
	}

	public function migrateSecurityHeaders() {
		$sh_settings = get_site_option( 'wd_security_headers_settings' );
		if ( empty( $sh_settings ) ) {
			$model = \WP_Defender\Module\Advanced_Tools\Model\Security_Headers_Settings::instance();
			//Part of Security tweaks data
			$old_settings = get_site_option( 'wd_hardener_settings' );
			if ( ! is_array( $old_settings ) ) {
				$old_settings = json_decode( $old_settings, true );
				if ( is_array( $old_settings ) && isset( $old_settings['data'] ) && ! empty( $old_settings['data'] ) ) {
					//Exists 'X-Frame-Options'
					if ( isset( $old_settings['data']['sh_xframe'] ) && ! empty( $old_settings['data']['sh_xframe'] ) ) {
						$header_data = $old_settings['data']['sh_xframe'];

						$mode = ( isset( $header_data['mode'] ) && ! empty( $header_data['mode'] ) )
							? strtolower( $header_data['mode'] )
							: false;
						if ( 'allow-from' === $mode ) {
							$model->sh_xframe_mode = 'allow-from';
							if ( isset( $header_data['values'] ) && ! empty( $header_data['values'] ) ) {
								$urls                  = explode( ' ', $header_data['values'] );
								$model->sh_xframe_urls = implode( PHP_EOL, $urls );
							}
						} elseif ( in_array( $mode, array( 'sameorigin', 'deny' ), true ) ) {
							$model->sh_xframe_mode = $mode;
						}
						$model->sh_xframe = true;
					}

					//Exists 'X-XSS-Protection'
					if ( isset( $old_settings['data']['sh_xss_protection'] ) && ! empty( $old_settings['data']['sh_xss_protection'] ) ) {
						$header_data = $old_settings['data']['sh_xss_protection'];

						if ( isset( $header_data['mode'] )
							&& ! empty( $header_data['mode'] )
							&& in_array( $header_data['mode'], array( 'sanitize', 'block' ), true )
						) {
							$model->sh_xss_protection_mode = $header_data['mode'];
							$model->sh_xss_protection      = true;
						}
					}

					//Exists 'X-Content-Type-Options'
					if ( isset( $old_settings['data']['sh_content_type_options'] ) && ! empty( $old_settings['data']['sh_content_type_options'] ) ) {
						$header_data = $old_settings['data']['sh_content_type_options'];

						if ( isset( $header_data['mode'] ) && ! empty( $header_data['mode'] ) ) {
							$model->sh_content_type_options_mode = $header_data['mode'];
							$model->sh_content_type_options      = true;
						}
					}

					//Exists 'Strict Transport'
					if ( isset( $old_settings['data']['sh_strict_transport'] ) && ! empty( $old_settings['data']['sh_strict_transport'] ) ) {
						$header_data = $old_settings['data']['sh_strict_transport'];

						if ( isset( $header_data['hsts_preload'] ) && ! empty( $header_data['hsts_preload'] ) ) {
							$model->hsts_preload = (int) $header_data['hsts_preload'];
						}
						if ( isset( $header_data['include_subdomain'] ) && ! empty( $header_data['include_subdomain'] ) ) {
							$model->include_subdomain = in_array( $header_data['include_subdomain'], array( 'true', '1', 1 ), true ) ? 1 : 0;
						}
						if ( isset( $header_data['hsts_cache_duration'] ) && ! empty( $header_data['hsts_cache_duration'] ) ) {
							$model->hsts_cache_duration = $header_data['hsts_cache_duration'];
						}
						$model->sh_strict_transport = true;
					}

					//Exists 'Referrer Policy'
					if ( isset( $old_settings['data']['sh_referrer_policy'] ) && ! empty( $old_settings['data']['sh_referrer_policy'] ) ) {
						$header_data = $old_settings['data']['sh_referrer_policy'];

						if ( isset( $header_data['mode'] ) && ! empty( $header_data['mode'] ) ) {
							$model->sh_referrer_policy_mode = $header_data['mode'];
							$model->sh_referrer_policy      = true;
						}
					}

					//Exists 'Feature-Policy'
					if ( isset( $old_settings['data']['sh_feature_policy'] ) && ! empty( $old_settings['data']['sh_feature_policy'] ) ) {
						$header_data = $old_settings['data']['sh_feature_policy'];

						if ( isset( $header_data['mode'] ) && ! empty( $header_data['mode'] ) ) {
							$mode                          = strtolower( $header_data['mode'] );
							$model->sh_feature_policy_mode = $mode;
							if ( 'origins' === $mode && isset( $header_data['values'] ) && ! empty( $header_data['values'] ) ) {
								//The values differ from the values of the 'X-Frame-Options' key, because they may be array.
								if ( is_array( $header_data['values'] ) ) {
									$model->sh_feature_policy_urls = implode( PHP_EOL, $header_data['values'] );
									//otherwise
								} elseif ( is_string( $header_data['values'] ) ) {
									$urls                          = explode( ' ', $header_data['values'] );
									$model->sh_feature_policy_urls = implode( PHP_EOL, $urls );
								}
							}
							$model->sh_feature_policy = true;
						}
					}
					//Save
					$model->save();
				}
			}
		}
	}

	/**
	 * redirect to defender dahsboard after plugin activated
	 */
	public function redirectToDefender( $plugin ) {
		if ( isset( $_POST['plugin_status'] ) && $_POST['plugin_status'] == 'all' ) {
			//seem like a bulk action, do nothing
			return;
		}
		if ( $plugin == wp_defender()->plugin_slug ) {
			exit( wp_redirect( network_admin_url( 'admin.php?page=wp-defender' ) ) );
		}
	}

	/**
	 * Initial
	 */
	public function init() {
		add_filter( 'plugin_action_links_' . plugin_basename( wp_defender()->plugin_slug ), array( &$this, 'addSettingsLink' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_styles' ) );
		if ( ! \WP_Defender\Behavior\Utils::instance()->checkRequirement() ) {
			return;
		} else {
			wp_defender()->isFree = true;
			//check if we do have API
			//start to init navigators
			\Hammer\Base\Container::instance()->set( 'dashboard', new \WP_Defender\Controller\Dashboard() );
			\Hammer\Base\Container::instance()->set( 'hardener', new \WP_Defender\Module\Hardener() );
			\Hammer\Base\Container::instance()->set( 'scan', new \WP_Defender\Module\Scan() );
			\Hammer\Base\Container::instance()->set( 'audit', new \WP_Defender\Module\Audit() );
			\Hammer\Base\Container::instance()->set( 'lockout', new \WP_Defender\Module\IP_Lockout() );
			\Hammer\Base\Container::instance()->set( 'waf', new \WP_Defender\Controller\Waf() );
			\Hammer\Base\Container::instance()->set( 'two_fa', new \WP_Defender\Module\Two_Factor() );
			\Hammer\Base\Container::instance()->set( 'advanced_tool', new \WP_Defender\Module\Advanced_Tools() );
			\Hammer\Base\Container::instance()->set( 'gdpr', new \WP_Defender\Controller\GDPR() );
			\Hammer\Base\Container::instance()->set( 'setting', new \WP_Defender\Module\Setting() );
			//no need to set debug
			require_once $this->wp_defender->getPluginPath() . 'free-dashboard/module.php';
			add_filter( 'wdev-email-message-' . plugin_basename( __FILE__ ), array( &$this, 'defenderAdsMessage' ) );
			do_action(
				'wdev-register-plugin',
				/* 1             Plugin ID */
				plugin_basename( __FILE__ ),
				'Defender',
				'/plugins/defender-security/',
				/* 4      Email Button CTA */
				__( 'Get Secure!', "defender-security" ),
				/* 5  getdrip Plugin param */
				'0cecf2890e'
			);
		}
	}

	private function convertOldToNewRecipients( $data ) {
		$tmp = array();
		foreach ( $data as $id ) {
			if ( filter_var( $id, FILTER_VALIDATE_INT ) ) {
				$user = get_user_by( 'id', $id );
				if ( is_object( $user ) ) {
					$temp[] = array(
						'first_name' => $user->display_name,
						'email'      => $user->user_email,
					);
				}
			}
		}

		return $tmp;
	}

	public function defenderAdsMessage( $message ) {
		$message = __( "You're awesome for installing Defender! Are you interested in how to make the most of this plugin? We've collected all the best security resources we know in a single email - just for users of Defender!", "defender-security" );

		return $message;
	}

	public function hideNotice() {
		$utils = \WP_Defender\Behavior\Utils::instance();
		if ( ! $utils->checkPermission() ) {
			return;
		}

		update_site_option( 'wdf_noNotice', 1 );
		wp_send_json_success();
	}

	public function maybeShowNotice() {
		$utils = \WP_Defender\Behavior\Utils::instance();
		if ( get_site_option( 'wdf_noNotice' ) == 1 ) {
			return;
		}

		if ( $utils->checkPermission()
			&& ( is_admin() || is_network_admin() )
			&& class_exists( 'WPMUDEV_Dashboard' )
			&& $utils->getAPIKey() != false
		) {
			if ( \WP_Defender\Behavior\Utils::instance()->isActivatedSingle() ) {
				add_action( 'admin_notices', array( &$this, 'showUpgradeNotification' ) );
			} else {
				add_action( 'network_admin_notices', array( &$this, 'showUpgradeNotification' ) );
			}
			add_action( 'wp_ajax_installDefenderPro', array( &$this, 'installDefenderPro' ) );
			add_action( 'defender_enqueue_assets', array( &$this, 'enqueueUpgradeJs' ) );
		}
	}

	public function enqueueUpgradeJs() {
		wp_enqueue_script( 'defender-upgrader', wp_defender()->getPluginUrl() . '/assets/js/upgrader.js', array( 'jquery' ), wp_defender()->version );
	}

	public function showUpgradeNotification() {
		$class   = 'notice notice-info is-dismissible wp-defender-notice';
		$message = sprintf(
			__( "%1\$s, you now have access to Defender's pro features but you still have the free version installed. Let's upgrade Defender and unlock all those juicy features! &nbsp; %2\$s", "defender-security" ),
			\WP_Defender\Behavior\Utils::instance()->getDisplayName(),
			'<button id="install-defender-pro" type="button" data-id="1081723" data-nonce="' . wp_create_nonce( 'installDefenderPro' ) . '" class="button button-small">' . __( 'Upgrade', "defender-security" ) . '</button>'
		);
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
	}

	public function installDefenderPro() {
		if ( \WP_Defender\Behavior\Utils::instance()->checkPermission() == false ) {
			return;
		}

		if ( ! wp_verify_nonce( \Hammer\Helper\HTTP_Helper::retrievePost( 'nonce' ), 'installDefenderPro' ) ) {
			return;
		}

		if ( ! class_exists( 'WPMUDEV_Dashboard' ) ) {
			//usually should not here
			return;
		}

		$upgrader = WPMUDEV_Dashboard::$upgrader;
		if ( file_exists( dirname( __DIR__ ) . '/wp-defender/wp-defender.php' ) || $upgrader->install( '1081723' ) ) {
			//activate this
			activate_plugin( 'wp-defender/wp-defender.php' );
			wp_send_json_success(
				array(
					'url' => network_admin_url( 'admin.php?page=wp-defender' ),
				)
			);
		} else {
			wp_send_json_error(
				array(
					'message' => __( '<br/>Something went wrong. Please try again later!', "defender-security" ),
				)
			);
		}
	}

	/**
	 * Add a setting link in plugins page
	 * @return array
	 */
	public function addSettingsLink( $links ) {
		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=wp-defender' ) . '">' . __( 'Settings', "defender-security" ) . '</a>',
		);

		$mylinks = array_merge( $mylinks, $links );
		$mylinks = array_merge(
			$mylinks,
			array(
				'<a target="_blank" href="https://premium.wpmudev.org/docs/wpmu-dev-plugins/defender/">' . __( 'Docs', "defender-security" ) . '</a>',
				'<a style="color: #1ABC9C" target="_blank" href="' . \WP_Defender\Behavior\Utils::instance()->campaignURL( 'defender_wppluginslist_upgrade' ) . '">' . __( 'Upgrade', "defender-security" ) . '</a>',
			)
		);
		return $mylinks;
	}

	/**
	 * Register globally css, js will be load on each module
	 */
	public function register_styles() {
		wp_enqueue_style( 'defender-menu', wp_defender()->getPluginUrl() . 'assets/css/defender-icon.css' );

		$css_files = array(
			'defender' => wp_defender()->getPluginUrl() . 'assets/css/styles.css',
		);

		foreach ( $css_files as $slug => $file ) {
			wp_register_style( $slug, $file, array(), wp_defender()->version );
		}

		$js_files = array(
			'defender' => wp_defender()->getPluginUrl() . 'assets/js/scripts.js',
		);

		foreach ( $js_files as $slug => $file ) {
			wp_register_script( $slug, $file, array(), wp_defender()->version );
		}

		do_action( 'defender_enqueue_assets' );
	}

	public function activationHook() {
		$db_ver = get_site_option( 'wd_db_version' );
		\WP_Defender\Module\Setting\Component\Backup_Settings::backupData();
		if ( ! \WP_Defender\Module\IP_Lockout\Component\Login_Protection_Api::checkIfTableExists() ) {
			\WP_Defender\Module\IP_Lockout\Component\Login_Protection_Api::createTables();
		} else {
			\WP_Defender\Module\IP_Lockout\Component\Login_Protection_Api::alterTableFor171();
		}
		if ( $db_ver != false && version_compare( $db_ver, '1.7.1', '<' ) ) {
			update_site_option( 'wd_db_version', "1.7.1" );
		}

		if ( $db_ver != false && version_compare( $db_ver, '2.1.1', '<' ) ) {
			//4 scan notification
			$settings                          = \WP_Defender\Module\Scan\Model\Settings::instance();
			$settings->recipients              = $this->convertOldToNewRecipients( $settings->recipients );
			$settings->recipients_notification = $this->convertOldToNewRecipients( $settings->recipients_notification );
			$settings->save();
			//audit
			$settings           = \WP_Defender\Module\Audit\Model\Settings::instance();
			$settings->receipts = $this->convertOldToNewRecipients( $settings->receipts );
			$settings->save();
			//lockout
			$settings                  = \WP_Defender\Module\IP_Lockout\Model\Settings::instance();
			$settings->receipts        = $this->convertOldToNewRecipients( $settings->receipts );
			$settings->report_receipts = $this->convertOldToNewRecipients( $settings->report_receipts );
			$settings->save();
		}
		$this->upgradeHook();
		//init report cron
		$settings = \WP_Defender\Module\Scan\Model\Settings::instance();
		if ( $settings->notification ) {
			$cronTime = \WP_Defender\Behavior\Utils::instance()->reportCronTimestamp( $settings->time, 'scanReportCron' );
			wp_schedule_event( $cronTime, 'daily', 'scanReportCron' );
		}

		$settings = \WP_Defender\Module\IP_Lockout\Model\Settings::instance();
		if ( $settings->report ) {
			$cronTime = \WP_Defender\Behavior\Utils::instance()->reportCronTimestamp( $settings->report_time, 'lockoutReportCron' );
			wp_schedule_event( $cronTime, 'daily', 'lockoutReportCron' );
		}
	}
}
