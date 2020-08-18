<?php
/**
 * Redis caching class.
 *
 * @since 2.5.0
 * @package Hummingbird\Core\Modules
 */

namespace Hummingbird\Core\Modules;

use Hummingbird\Core\Module;
use Hummingbird\Core\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Redis
 */
class Redis extends Module {

	use \Hummingbird\Core\Traits\WPConfig;

	/**
	 * Initializes the module. Always executed even if the module is deactivated.
	 *
	 * @since 2.5.0
	 */
	public function init() {
		add_filter( 'wp_hummingbird_is_active_module_redis', array( $this, 'module_status' ) );

		if ( $this->module_status( null ) ) {
			add_action( 'wphb_deactivate', array( $this, 'disable' ) );
		}
	}

	/**
	 * Execute the module actions. Executed when module is active.
	 *
	 * @since 2.5.0
	 */
	public function run() {}

	/**
	 * Clear the module cache.
	 *
	 * @since 2.5.0
	 */
	public function clear_cache() {
		wp_cache_flush();
	}

	/**
	 * Clear APC and Opcache.
	 *
	 * @since 2.5.0
	 */
	public function clear_apc_and_opcache() {
		if ( function_exists( 'opcache_reset' ) ) {
			opcache_reset();
		}

		if ( function_exists( 'apc_clear_cache' ) ) {
			apc_clear_cache();
		}
	}

	/**
	 * Set enabled option value.
	 *
	 * @since 2.5.0
	 *
	 * @param bool $value
	 */
	public function set_enabled_option( $value ) {
		$options 			= $this->get_options();
		$options['enabled'] = $value;
		$this->update_options( $options );
		return $options;
	}

	/**
	 * Enable cache.
	 *
	 * @since 2.5.0
	 *
	 * @param string $host      Redis host.
	 * @param int    $port      Redis port.
	 * @param string $password  Password.
	 */
	public function enable( $host, $port, $password ) {
		$this->set_enabled_option( true );

		$this->wpconfig_add( 'WPHB_REDIS_HOST', $host );
		$this->wpconfig_add( 'WPHB_REDIS_PORT', $port );

		if ( $password ) {
			$this->wpconfig_add( 'WPHB_REDIS_PASSWORD', $password );
		}

		$this->toggle_object_cache( true );

		//Clear redis cache
		$this->test_redis_connection( $host, $port, $password, true );
	}

	/**
	 * Disable cache.
	 *
	 * @since 2.5.0
	 */
	public function disable() {
		$this->set_enabled_option( false );

		$this->wpconfig_remove( 'WPHB_REDIS_HOST' );
		$this->wpconfig_remove( 'WPHB_REDIS_PORT' );
		$this->wpconfig_remove( 'WPHB_REDIS_PASSWORD' );

		$this->toggle_object_cache( false );
	}

	/**
	 * Get module status.
	 *
	 * @since 2.5.0
	 *
	 * @param bool $current  Current status.
	 *
	 * @return bool
	 */
	public function module_status( $current ) {
		$options = $this->get_options();
		return $options['enabled'];
	}

	/**
	 * Toggle object cache.
	 *
	 * Will copy object-cache.php file to wp-content folder.
	 *
	 * @since 2.5.0
	 *
	 * @param bool $enable  Enable or disable object cache.
	 */
	public function toggle_object_cache( $enable ) {
		// See if there's already an advanced-cache.php file in place.
		$object_cache = dirname( get_theme_root() ) . '/object-cache.php';

		// Unlink file.
		if ( ! $enable && file_exists( $object_cache ) && false !== strpos( file_get_contents( $object_cache ), 'Hummingbird Redis Object Cache' ) ) {
			wp_cache_flush();
			$this->set_enabled_option( false );
			unlink( $object_cache );
		}

		if ( $enable && ! file_exists( $object_cache ) ) {
			// Try to add advanced-cache.php file.
			$hb_object_cache = WPHB_DIR_PATH . 'core/object-cache.php';
			if ( ! file_exists( $hb_object_cache ) ) {
				$this->set_enabled_option( false );
				return;
			}

			copy( $hb_object_cache, $object_cache );
			wp_cache_flush();
		}

		$this->clear_apc_and_opcache();
	}

	/**
	 * Test redis connection with given auth info.
	 *
	 * @since 2.5.0
	 *
	 * @param string $host      Redis host.
	 * @param int    $port      Redis port.
	 * @param string $password  Password.
	 *
	 * @return array
	 */
	public function test_redis_connection( $host, $port, $password, $clear_cache = false ) {
		$parameters = array(
			'host'           => $host,
			'port'           => $port,
			'timeout'        => 5,
			'read_timeout'   => 5,
			'retry_interval' => 0,
		);

		$redis  = null;
		$error  = '';
		$client = 'predis';

		if ( class_exists( 'Redis' ) && 0 !== strcasecmp( 'predis', $client ) ) {
			$client = defined( 'HHVM_VERSION' ) ? 'hhvm' : 'pecl';
		}

		try {
			if ( 'hhvm' === $client ) {
				$redis = new \Redis();
				$redis->connect( $parameters['host'], $parameters['port'], $parameters['timeout'], null, $parameters['retry_interval'] );

				if ( $parameters['read_timeout'] ) {
					$redis->setOption( \Redis::OPT_READ_TIMEOUT, $parameters['read_timeout'] );
				}
			}

			if ( 'pecl' === $client ) {
				$phpredis_version = phpversion( 'redis' );

				$redis = new \Redis();
				if ( version_compare( $phpredis_version, '3.1.3', '>=' ) ) {
					$redis->connect( $parameters['host'], $parameters['port'], $parameters['timeout'], null, $parameters['retry_interval'], $parameters['read_timeout'] );
				} else {
					$redis->connect( $parameters['host'], $parameters['port'], $parameters['timeout'], null, $parameters['retry_interval'] );
				}
			}

			if ( ( 'hhvm' === $client || 'pecl' === $client ) ) {
				$redis->auth( $password );
			}

			if ( 'predis' === $client ) {
				// Load bundled Predis library.
				if ( ! class_exists( '\\Predis\\Client' ) ) {
					$predis_autoload_file = WPHB_DIR_PATH . 'vendor/predis/predis/autoload.php';
					if ( file_exists( $predis_autoload_file ) ) {
						include_once $predis_autoload_file;
					} else {
						throw new \Exception( 'Predis library not found. Re-install Hummingbird plugin or delete object-cache.php.' );
					}
				}

				$options = array();

				if ( $parameters['read_timeout'] ) {
					$parameters['read_write_timeout'] = $parameters['read_timeout'];
				}

				if ( $password ) {
					$options['parameters']['password'] = $password;
				}

				$redis = new \Predis\Client( $parameters, $options );
				$redis->connect();
			}

			$redis->ping();

			$redis_connected = true;
			
			if( $clear_cache ) {
				$redis->flushdb();
			}

		} catch ( \Exception $exception ) {
			$redis_connected = false;

			$error = $exception->getMessage();
		}

		$result = array();
		if ( $redis_connected ) {
			$result['status'] = 'success';
		} else {
			$result['status'] = 'fail';
			$result['error']  = $error;
		}

		return $result;
	}

	/**
	 * Generate admin notice for Redis settings update.
	 *
	 * @since 2.5.0
	 *
	 * @param string $updated  Type.
	 *
	 * @return string
	 */
	public function get_update_notice( $updated ) {
		$vars   = $this->get_status_related_vars();
		$notice = '';

		switch ( $updated ) {
			case 'redis-auth':
				if ( $vars['redis_connected'] ) {
					$notice = __( 'Redis is connected successfully.', 'wphb' );
				} else {
					$notice = __( 'Redis is not connected.', 'wphb' );
				}
				break;
			case 'redis-auth-2':
				$notice = __( 'Settings updated successfully.', 'wphb' );
				break;				
			case 'redis-disconnect':
				if ( ! $vars['redis_connected'] ) {
					$notice = __( 'Redis was disconnected successfully.', 'wphb' );
				}
				break;
			case 'redis-object-cache':
				if ( $vars['is_redis_object_cache'] ) {
					$notice = __( 'Object caching is enabled successfully.', 'wphb' );
				} else {
					$notice = __( 'Object caching is disabled successfully.', 'wphb' );
				}
				break;
			default:
				$notice = __( 'Settings updated successfully.', 'wphb' );
				break;
		}

		return $notice;
	}

	/**
	 * Get redis status related vars and options
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */
	public function get_status_related_vars() {
		static $vars = null;
		if ( $vars ) {
			return $vars;
		}

		global $wp_object_cache;

		$redis_connected  = false;
		$connection_error = '';

		if ( is_object( $wp_object_cache ) && method_exists( $wp_object_cache, 'redis_status' ) ) {
			$redis_connected = $wp_object_cache->redis_status();
			if ( ! $redis_connected ) {
				$connection_error = ! empty( $wp_object_cache->redis_error ) ? $wp_object_cache->redis_error : '';
			}
			$redis_connected = $redis_connected && defined( 'WPHB_REDIS_HOST' ) ? $redis_connected : false;
		}

		if ( method_exists( $this, 'can_continue' ) ) {
			if ( ! defined( 'WPHB_REDIS_HOST' ) && ! $this->can_continue() ) {
				$redis_connected  = false;
				$connection_error = 'wp.config.php is not editable.';
			}
		}

		$options = Settings::get_settings( 'redis' );

		$object_cache = file_exists( WP_CONTENT_DIR . '/object-cache.php' );

		// Flag to disable redis cache if hosted on DEV.
		$disable_redis = false;
		if ( isset( $_SERVER['WPMUDEV_HOSTED'] ) && $_SERVER['WPMUDEV_HOSTED'] ) {
			$disable_redis = true;
		}

		$vars = array(
			'options'               => $options,
			'redis_connected'       => $redis_connected,
			'redis_enabled'         => $options['enabled'],
			'is_redis_object_cache' => $object_cache,
			'disable_redis'         => $disable_redis,
			'connection_error'      => $connection_error,
		);

		return $vars;
	}

}
