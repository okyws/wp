<?php
/**
 * Author: Hoang Ngo
 */

namespace WP_Defender\Controller;

use WP_Defender\Controller;

class GDPR extends Controller {
	public function __construct() {
		$this->addFilter( 'wp_get_default_privacy_policy_content', 'addPolicy' );
	}

	public function addPolicy( $content ) {
		$pluginName = wp_defender()->isFree ? __( "Defender", "defender-security" ) : __( "Defender Pro", "defender-security" );
		$content    .= '<h3>' . sprintf( __( 'Plugin: %s', "defender-security" ), $pluginName ) . '</h3>';
		$content    .= '<p><strong>' . __( "Third parties", "defender-security" ) . '</strong></p>';
		$content    .= '<p>' . __( "This site may be using WPMU DEV third-party cloud storage to store backups of its audit logs where personal information is collected.", "defender-security" ) . '</p>';
		$content    .= '<p><strong>' . __( "Additional data", "defender-security" ) . '</strong></p>';
		$content    .= '<p>' . __( "This site creates and stores an activity log that capture the IP address, username, email address and tracks user activity (like when a user makes a comment). Information will be stored locally for 30 days and remotely for 1 year. Information on remote logs cannot be cleared for security purposes.", "defender-security" ) . '</p>';
		return $content;
	}
}