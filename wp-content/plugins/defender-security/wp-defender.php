<?php

/**
 * Plugin Name: Defender
 * Plugin URI: https://premium.wpmudev.org/project/wp-defender/
 * Version:     2.3
 * Description: Get regular security scans, vulnerability reports, safety recommendations and customized hardening for your site in just a few clicks. Defender is the analyst and enforcer who never sleeps.
 * Author:      WPMU DEV
 * Author URI:  http://premium.wpmudev.org/
 * License:     GNU General Public License (Version 2 - GPLv2)
 * Text Domain: defender-security
 * Network: true
 */
class WP_Defender_Free {

	/**
	 * Store the WP_Defender object for singleton implement
	 *
	 * @var WP_Defender_Free
	 */
	private static $_instance;
	/**
	 * @var string
	 */
	private $plugin_path;

	/**
	 * @return string
	 */
	public function getPluginPath() {
		return $this->plugin_path;
	}

	/**
	 * @return string
	 */
	public function getPluginUrl() {
		return $this->plugin_url;
	}

	/**
	 * @var string
	 */
	private $plugin_url;
	/**
	 * @var string
	 */
	public $domain = 'defender-security';

	/**
	 * @var string
	 */
	public $version = "1.5";

	/**
	 * @var string
	 */
	public $isFree = false;

	/**
	 * @var bool
	 */
	public $is_membership = false;
	/**
	 * @var array
	 */
	public $global = array();
	/**
	 * @var string
	 */
	public $plugin_slug = 'defender-security/wp-defender.php';

	public $db_version = "2.2.9";

	public $whiteLabel = 0;

	/**
	 * @var int
	 */
	public $hideHeroImage = 0;
	/**
	 * @var null
	 */
	public $heroImage = null;
	/**
	 * @var null
	 */
	public $footerText = null;
	/**
	 * @var bool
	 */
	public $hideDocLinks = false;
	/**
	 * @var bool
	 */
	public $changeFooter = false;

	/**
	 * @return WP_Defender_Free
	 */
	public static function instance() {
		if ( ! is_object( self::$_instance ) ) {
			self::$_instance = new WP_Defender_Free();
		}

		return self::$_instance;
	}

	/**
	 * WP_Defender constructor.
	 */
	private function __construct() {
		$this->initVars();
		$this->includeVendors();
		$this->autoload();
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_styles' ) );
		add_action( 'plugins_loaded', array( &$this, 'loadTextDomain' ) );
		include_once $this->getPluginPath() . 'main-activator.php';
		$this->global['bootstrap'] = new WD_Main_Activator( $this );
		//for the new SUI
		add_filter( 'admin_body_class', array( $this, 'adminBodyClasses' ), 11 );
		do_action(
			'wpmudev-recommended-plugins-register-notice',
			plugin_basename( __FILE__ ), // Plugin basename
			'Defender', // Plugin Name
			array(
				'toplevel_page_wp-defender',
				'toplevel_page_wp-defender-network'
			),
			array( 'after', '.sui-wrap .sui-header' )
		);

//		add_filter(
//			'wpmudev-recommended-plugins-notice-display-seconds-after-registered',
//			function ( $time_trigger ) {
//				// 1 minute trigger
//				$time_trigger = 30;
//
//				return $time_trigger;
//			}
//		);
	}

	public function loadTextDomain() {
		load_plugin_textdomain( $this->domain, false, basename( __DIR__ ) . '/languages' );
	}

	/**
	 * Init values
	 */
	private function initVars() {
		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->plugin_url  = plugin_dir_url( __FILE__ );
	}

	/**
	 * Including vendors
	 */
	private function includeVendors() {
		$phpVersion = phpversion();
		if ( version_compare( $phpVersion, '5.3', '>=' ) ) {
			if ( function_exists( 'Avada' ) ) {
				define( 'WD_NO_OBJECT_CACHE', 1 );
			}
			include_once $this->plugin_path . 'vendor' . DIRECTORY_SEPARATOR . 'hammer' . DIRECTORY_SEPARATOR . 'bootstrap.php';
		}
		//load gettext helper
		include_once $this->plugin_path . 'vendor' . DIRECTORY_SEPARATOR . 'gettext' . DIRECTORY_SEPARATOR . 'gettext' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'autoloader.php';
		include_once $this->plugin_path . 'vendor' . DIRECTORY_SEPARATOR . 'gettext' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'autoloader.php';

		require_once __DIR__ . '/recommended-plugins-notice/notice.php';
	}

	/**
	 * Register the autoload
	 */
	private function autoload() {
		spl_autoload_register( array( &$this, '_autoload' ) );
	}

	public function adminBodyClasses( $classes ) {
		$pages = [
			'wp-defender',
			'wdf-hardener',
			'wdf-scan',
			'wdf-logging',
			'wdf-ip-lockout',
			'wdf-advanced-tools',
			'wdf-setting',
			'wdf-debug',
			'wdf-2fa',
			'wdf-waf'
		];
		$page  = isset( $_GET['page'] ) ? $_GET['page'] : null;
		if ( ! in_array( $page, $pages, true ) ) {
			return $classes;
		}
		$classes .= ' sui-2-9-2 ';

		return $classes;
	}

	/**
	 * Register globally css, js will be load on each module
	 */
	public function register_styles() {
		wp_enqueue_style( 'defender-menu', $this->getPluginUrl() . 'assets/css/defender-icon.css' );

		$css_files = array(
			'defender' => $this->plugin_url . 'assets/css/styles.css'
		);

		foreach ( $css_files as $slug => $file ) {
			wp_register_style( $slug, $file, array(), $this->version );
		}

		$is_min   = ! defined( 'SCRIPT_DEBUG' ) ? '.min' : '';
		$js_files = array(
			'wpmudev-sui' => $this->plugin_url . 'assets/js/shared-ui.js',
			'defender'    => $this->plugin_url . 'assets/js/scripts.js',
			'def-vue'     => $this->plugin_url . 'assets/js/vendor/vue.runtime' . $is_min . '.js',
		);

		foreach ( $js_files as $slug => $file ) {
			wp_register_script( $slug, $file, array( 'jquery', 'clipboard' ), $this->version, true );
		}


		wp_localize_script( 'def-vue', 'defender', array(
			'whitelabel'   => \WP_Defender\Behavior\WPMUDEV::instance()->whiteLabelStatus(),
			'misc'         => [
				'high_contrast' => \WP_Defender\Behavior\WPMUDEV::instance()->maybeHighContrast(),
			],
			'site_url'     => network_site_url(),
			'admin_url'    => network_admin_url(),
			'defender_url' => $this->getPluginUrl(),
			'is_free'      => $this->isFree,
			'days_of_week' => \WP_Defender\Behavior\Utils::instance()->getDaysOfWeek(),
			'times_of_day' => \WP_Defender\Behavior\Utils::instance()->getTimes()
		) );
		do_action( 'defender_enqueue_assets' );
	}

	/**
	 * @param $class
	 */
	public function _autoload( $class ) {
		$base_path = __DIR__ . DIRECTORY_SEPARATOR;
		$pools     = explode( '\\', $class );

		if ( $pools[0] != 'WP_Defender' ) {
			return;
		}
		if ( $pools[1] == 'Vendor' ) {
			unset( $pools[0] );
		} else {
			$pools[0] = 'App';
		}

		//build the path
		$path = implode( DIRECTORY_SEPARATOR, $pools );
		$path = $base_path . strtolower( str_replace( '_', '-', $path ) ) . '.php';
		if ( file_exists( $path ) ) {
			include_once $path;
		}
	}
}

if ( ! function_exists( 'wp_defender' ) ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	if ( is_plugin_active( 'wp-defender/wp-defender.php' ) ) {
		return;
	}
	/**
	 * Shorthand to get the instance
	 * @return WP_Defender_Free
	 */
	function wp_defender() {
		return WP_Defender_Free::instance();
	}

	//init
	wp_defender();

	function wp_defender_deactivate() {
		//we disable any cron running
		wp_clear_scheduled_hook( 'processScanCron' );
		wp_clear_scheduled_hook( 'lockoutReportCron' );
		wp_clear_scheduled_hook( 'auditReportCron' );
		wp_clear_scheduled_hook( 'cleanUpOldLog' );
		wp_clear_scheduled_hook( 'scanReportCron' );
		wp_clear_scheduled_hook( 'tweaksSendNotification' );
	}

	function wp_defender_activate() {
		$phpVersion = phpversion();
		if ( version_compare( $phpVersion, '5.3', '>=' ) ) {
			wp_defender()->global['bootstrap']->activationHook();
		}

		$hs            = \WP_Defender\Module\Hardener\Model\Settings::instance();
		$hs->last_seen = time();
		$hs->save();
	}

	register_deactivation_hook( __FILE__, 'wp_defender_deactivate' );
	register_activation_hook( __FILE__, 'wp_defender_activate' );
}