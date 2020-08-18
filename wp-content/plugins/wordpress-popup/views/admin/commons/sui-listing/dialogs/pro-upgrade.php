<?php
/**
 * Modal for promoting the upgrade from Hustle Free to Hustle Pro.
 *
 * @package Hustle
 * @since 4.0.0
 */

ob_start();
?>

<a
	id="hustle-upgrade-to-pro-link"
	target="_blank"
	href="https://premium.wpmudev.org/project/hustle/?utm_source=hustle&utm_medium=plugin&utm_campaign=hustle_modal_upsell_notice"
	class="sui-button sui-button-green"
>
	<?php esc_html_e( 'Learn more', 'hustle' ); ?>
</a>

<?php
$body_content = ob_get_clean();
ob_start();
?>

<img
	src="<?php echo esc_url( self::$plugin_url . 'assets/images/hustle-upsell.png' ); ?>"
	srcset="<?php echo esc_url( self::$plugin_url . 'assets/images/hustle-upsell.png' ); ?> 1x, <?php echo esc_url( self::$plugin_url . 'assets/images/hustle-upsell@2x.png' ); ?> 2x"
	alt="<?php esc_html_e( 'Upgrade to Hustle Pro!', 'hustle' ); ?>"
	class="sui-image sui-image-center"
	style="max-width: 128px;"
	aria-hidden="true"
/>

<?php
$after_body_content = ob_get_clean();

$attributes = array(
	'modal_id'           => 'upgrade-to-pro',
	'has_description'    => true,
	'modal_size'         => 'sm',

	'header'             => array(
		'classes'       => 'sui-flatten sui-content-center sui-spacing-top--60',
		'title'         => __( 'Upgrade to Pro', 'hustle' ),
		'title_classes' => 'sui-lg',
	),
	'body'               => array(
		'classes'     => 'sui-content-center sui-spacing-top--20',
		'description' => __( 'Get unlimited Popups, Slide-ins, Embeds and social sharing widgets with the Pro version of Hustle. Get it as part of a WPMU DEV membership including Smush Pro, Hummingbird Pro and other popular professional plugins.', 'hustle' ),
		'content'     => $body_content,
	),
	'after_body_content' => $after_body_content,
);

$this->render_modal( $attributes );
