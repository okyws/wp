<?php
/**
 * @author: Hoang Ngo
 */
// If uninstall is not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}


if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( is_plugin_active( 'wp-defender/wp-defender.php' ) ) {
	return;
}

$path = dirname( __FILE__ );
include_once $path . DIRECTORY_SEPARATOR . 'wp-defender.php';

$settings = \WP_Defender\Module\Setting\Model\Settings::instance();
if ( is_multisite() ) {
	$data = get_site_option( 'wd_main_settings', array(), false );
	$settings->import( $data );
}

if ( $settings->uninstall_data == 'remove' ) {
	$scan = \WP_Defender\Module\Scan\Model\Scan::findAll();
	foreach ( $scan as $model ) {
		$model->delete();
	}
	delete_site_option( \WP_Defender\Module\Scan\Component\Scan_Api::IGNORE_LIST );
	delete_option( \WP_Defender\Module\Scan\Component\Scan_Api::IGNORE_LIST );

	//wipe table
	global $wpdb;
	$tableName1 = $wpdb->base_prefix . 'defender_lockout';
	$tableName2 = $wpdb->base_prefix . 'defender_lockout_log';

	$sql = "DROP TABLE IF EXISTS $tableName1, $tableName2;";
	$wpdb->query( $sql );
	
	\WP_Defender\Behavior\Utils::instance()->removeDir( \WP_Defender\Behavior\Utils::instance()->getDefUploadDir() );
}

if ( $settings->uninstall_settings == 'reset' ) {
	$tweakFixed = \WP_Defender\Module\Hardener\Model\Settings::instance()->getFixed();

	foreach ( $tweakFixed as $rule ) {
		$rule->getService()->revert();
	}
	
	( new \WP_Defender\Module\Scan\Component\Scanning() )->flushCache();

	$cache = \Hammer\Helper\WP_Helper::getCache();
	$cache->delete( 'isActivated' );
	$cache->delete( 'wdf_isActivated' );
	$cache->delete( 'wdfchecksum' );
	$cache->delete( 'cleanchecksum' );

	\WP_Defender\Module\Scan\Model\Settings::instance()->delete();
	\WP_Defender\Module\Hardener\Model\Settings::instance()->delete();
	\WP_Defender\Module\IP_Lockout\Model\Settings::instance()->delete();
	\WP_Defender\Module\Two_Factor\Model\Auth_Settings::instance()->delete();
	\WP_Defender\Module\Advanced_Tools\Model\Mask_Settings::instance()->delete();
	\WP_Defender\Module\Two_Factor\Model\Auth_Settings::instance()->delete();
	\WP_Defender\Module\Setting\Model\Settings::instance()->delete();
	//clear old stuff
	delete_site_option( 'wp_defender' );
	delete_option( 'wp_defender' );
	delete_option( 'wd_db_version' );
	delete_site_option( 'wd_db_version' );
}