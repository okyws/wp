<?php
/**
 * WPConfig trait.
 *
 * Allows read/write of wp-config.php file.
 *
 * @since 1.7.0
 * @since 2.5.0  Improved functionality and moved to a trait from Page_Cache module.
 * @package Hummingbird\Core
 */

namespace Hummingbird\Core\Traits;

use Hummingbird\WP_Hummingbird;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait WPConfig
 */
trait WPConfig {

	/**
	 * Config file path.
	 *
	 * @var string $wp_config_file
	 */
	public $wp_config_file = ABSPATH . 'wp-config.php';

	/**
	 * File pointer.
	 *
	 * @var null|resource $fp
	 */
	private $fp = null;

	/**
	 * Add a define to wp-config.php file.
	 *
	 * @since 2.5.0
	 *
	 * @param string $name   Define name.
	 * @param string $value  Define value.
	 *
	 * @return bool
	 */
	public function wpconfig_add( $name, $value ) {
		if ( ! $this->can_continue() ) {
			return false;
		}

		$value = $this->prepare_value( $value );
		$lines = $this->get_lines();

		// Generate the new file data.
		$new_file = array();
		$added    = false;
		foreach ( $lines as $line ) {
			// Maybe there's already a define?
			if ( preg_match( "/define\(\s*'{$name}'/i", $line ) ) {
				$added = true;
				WP_Hummingbird::get_instance()->core->logger->log( "Added define( {$name}, {$value} ) to wp-config.php file.", $this->get_slug() );
				$new_file[] = "define( '{$name}', {$value} ); // Added by Hummingbird";
				continue;
			}

			// If we reach the end and no define - add it.
			if ( ! $added && preg_match( "/\/\* That's all, stop editing!.*/i", $line ) ) {
				WP_Hummingbird::get_instance()->core->logger->log( "Added define( {$name}, {$value} ) to wp-config.php file.", $this->get_slug() );
				$new_file[] = "define( '{$name}', {$value} ); // Added by Hummingbird";
			}

			$new_file[] = $line;
		}

		return $this->write( implode( "\n", $new_file ) );
	}

	/**
	 * Remove a define from wp-config.php file.
	 *
	 * @since 2.5.0
	 *
	 * @param string $name  Define name.
	 *
	 * @return bool
	 */
	public function wpconfig_remove( $name ) {
		if ( ! $this->can_continue() ) {
			return false;
		}

		$lines = $this->get_lines();

		// Generate the new file data.
		$new_file = array();
		foreach ( $lines as $line ) {
			if ( preg_match( "/define\(\s*'{$name}'/i", $line ) ) {
				WP_Hummingbird::get_instance()->core->logger->log( "Removed define( '{$name}', ... ) from wp-config.php file.", $this->get_slug() );
				continue;
			}

			$new_file[] = $line;
		}

		return $this->write( implode( "\n", $new_file ) );
	}

	/**
	 * Check if we can access the file.
	 *
	 * @since 2.5.0
	 *
	 * @return bool
	 */
	private function can_continue() {
		if ( ! file_exists( $this->wp_config_file ) ) {
			WP_Hummingbird::get_instance()->core->logger->log( 'Failed to locate wp-config.php file.', $this->get_slug() );
			return false;
		}

		if ( ! $this->fp = fopen( $this->wp_config_file, 'r+' ) ) {
			WP_Hummingbird::get_instance()->core->logger->log( 'Failed to open wp-config.php for writing.', $this->get_slug() );
			return false;
		}

		return true;
	}

	/**
	 * Try to convert the value to a proper string, so that it is properly written to wp-config.php file.
	 *
	 * @since 2.5.0
	 *
	 * @param mixed $value  Value.
	 *
	 * @return string
	 */
	private function prepare_value( $value ) {
		// Make sure to enclose in single quotes if this is a string value.
		if ( is_string( $value ) ) {
			return "'{$value}'";
		}

		if ( is_bool( $value ) ) {
			return $value ? 'true' : 'false';
		}

		return $value;
	}

	/**
	 * Get lines from file.
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */
	private function get_lines() {
		// Attempt to get a lock. If the filesystem supports locking, this will block until the lock is acquired.
		flock( $this->fp, LOCK_EX );

		$lines = array();
		while ( ! feof( $this->fp ) ) {
			$lines[] = rtrim( fgets( $this->fp ), "\r\n" );
		}

		return $lines;
	}

	/**
	 * Write to the start of the file, and truncate it to that length.
	 *
	 * @since 2.5.0
	 *
	 * @param string $data  File data.
	 *
	 * @return bool
	 */
	private function write( $data ) {
		fseek( $this->fp, 0 );
		$bytes = fwrite( $this->fp, $data );

		if ( $bytes ) {
			ftruncate( $this->fp, ftell( $this->fp ) );
		}

		fflush( $this->fp );
		flock( $this->fp, LOCK_UN );
		fclose( $this->fp );

		return (bool) $bytes;
	}

}
