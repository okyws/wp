<?php
/**
 * Footer links.
 *
 * @package Hustle
 * @since 4.0.0
 */

if ( Opt_In_Utils::_is_free() ) { ?>

	<ul class="sui-footer-nav">
		<li><a href="https://profiles.wordpress.org/wpmudev#content-plugins" target="_blank"><?php esc_html_e( 'Free Plugins', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/features/" target="_blank"><?php esc_html_e( 'Membership', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/roadmap/" target="_blank"><?php esc_html_e( 'Roadmap', 'hustle' ); ?></a></li>
		<li><a href="https://wordpress.org/support/plugin/wordpress-popup" target="_blank"><?php esc_html_e( 'Support', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/docs/" target="_blank"><?php esc_html_e( 'Docs', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/hub-welcome/" target="_blank"><?php esc_html_e( 'The Hub', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/terms-of-service/" target="_blank"><?php esc_html_e( 'Terms of Service', 'hustle' ); ?></a></li>
		<li><a href="https://incsub.com/privacy-policy/" target="_blank"><?php esc_html_e( 'Privacy Policy', 'hustle' ); ?></a></li>
	</ul>

<?php } else { ?>

	<ul class="sui-footer-nav">
		<li><a href="https://premium.wpmudev.org/hub/" target="_blank"><?php esc_html_e( 'The Hub', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/projects/category/plugins/" target="_blank"><?php esc_html_e( 'Plugins', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/roadmap/" target="_blank"><?php esc_html_e( 'Roadmap', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/hub/support/" target="_blank"><?php esc_html_e( 'Support', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/docs/" target="_blank"><?php esc_html_e( 'Docs', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/hub/community/" target="_blank"><?php esc_html_e( 'Community', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/academy/" target="_blank"><?php esc_html_e( 'Academy', 'hustle' ); ?></a></li>
		<li><a href="https://premium.wpmudev.org/terms-of-service/" target="_blank"><?php esc_html_e( 'Terms of Service', 'hustle' ); ?></a></li>
		<li><a href="https://incsub.com/privacy-policy/" target="_blank"><?php esc_html_e( 'Privacy Policy', 'hustle' ); ?></a></li>
	</ul>

<?php } ?>
