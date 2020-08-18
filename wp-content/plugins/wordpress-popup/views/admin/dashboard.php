<?php
/**
 * Main wrapper for Hustle's dashboard.
 *
 * @package Hustle
 * @since 4.0.0
 */

$capability = array(
	'hustle_create'        => current_user_can( 'hustle_create' ),
	'hustle_access_emails' => current_user_can( 'hustle_access_emails' ),
);

$has_modules = ( count( $popups ) + count( $slideins ) + count( $embeds ) ) > 0;

?>

<div class="sui-header">
	<h1 class="sui-header-title"><?php esc_html_e( 'Dashboard', 'hustle' ); ?></h1>
	<?php $this->render( 'admin/commons/view-documentation' ); ?>
</div>

<div id="hustle-floating-notifications-wrapper" class="sui-floating-notices"></div>

<div class="<?php echo esc_attr( implode( ' ', $sui['summary']['classes'] ) ); ?>">
	<div class="sui-summary-image-space" aria-hidden="true" style="<?php echo esc_attr( $sui['summary']['style'] ); ?>"></div>
	<div class="sui-summary-segment">
		<div class="sui-summary-details">
			<span class="sui-summary-large"><?php echo esc_html( $active_modules ); ?></span>
			<span class="sui-summary-sub"><?php esc_html_e( 'Active Modules', 'hustle' ); ?></span>
			<span class="sui-summary-detail"><?php echo esc_html( $last_conversion ); ?></span>
			<span class="sui-summary-sub"><?php esc_html_e( 'Last Conversion', 'hustle' ); ?></span>

		</div>

	</div>

	<div class="sui-summary-segment">
		<?php if ( is_array( $metrics ) && ! empty( $metrics ) ) : ?>
			<ul class="sui-list">
				<?php foreach ( $metrics as $key => $data ) : ?>
					<li class="hustle-<?php echo esc_attr( $key ); ?>">
						<span class="sui-list-label"><?php echo esc_html( $data['label'] ); ?></span>
						<span class="sui-list-detail"><?php echo $data['value']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<?php esc_html_e( 'No data to display.', 'hustle' ); ?>
		<?php endif; ?>
	</div>

</div>

<div class="sui-row">

	<div class="sui-col-md-6">

		<?php
		// WIDGET: Pop-ups.
		$this->render(
			'admin/popup/dashboard',
			array(
				'capability' => $capability,
				'popups'     => $popups,
			)
		);
		?>

		<?php
		// WIDGET: Embeds.
		$this->render(
			'admin/embedded/dashboard',
			array(
				'capability' => $capability,
				'embeds'     => $embeds,
			)
		);
		?>

	</div>

	<div class="sui-col-md-6">

		<?php
		// WIDGET: Slide-ins.
		$this->render(
			'admin/slidein/dashboard',
			array(
				'capability' => $capability,
				'slideins'   => $slideins,
			)
		);
		?>

		<?php
		// WIDGET: Social Shares.
		$this->render(
			'admin/sshare/dashboard',
			array(
				'capability'      => $capability,
				'social_sharings' => $social_sharings,
			)
		);
		?>

	</div>

</div>

<?php
// Global Footer.
$this->render( 'admin/footer/footer', array( 'is_large' => true ) );
?>

<?php
// This is false if the flag isn't set. Which means it either was previous to 4.2, or this is a fresh install.
$previous_installed_version = Hustle_Migration::get_previous_installed_version();

// DIALOG: On Upgrade (release highlights).
$is_force_highlight = filter_input( INPUT_GET, 'show-release-highlight', FILTER_VALIDATE_BOOLEAN );
if (
		! Hustle_Notifications::was_notification_dismissed( Hustle_Dashboard_Admin::HIGHLIGHT_MODAL_NAME ) ||
		$is_force_highlight
	) {

	// Only display when it's not a fresh install and no 3.x to 4.x migration is needed.
	// Check for $previous_installed_version in 4.2.1. For now, we assume that if there are modules it's not a fresh install.
	if ( $is_force_highlight || ( $has_modules && ! $need_migrate ) ) {
		$this->render( 'admin/dashboard/dialogs/release-highlight' );

	} else {
		// Fresh install or focus on migration. Dismiss the notification.
		Hustle_Notifications::add_dismissed_notification( Hustle_Dashboard_Admin::HIGHLIGHT_MODAL_NAME );
	}
}

// DIALOG: On Boarding (Welcome).
if (
	( ! $previous_installed_version && ! Hustle_Notifications::was_notification_dismissed( Hustle_Dashboard_Admin::WELCOME_MODAL_NAME ) && ! $has_modules )
	|| filter_input( INPUT_GET, 'show-welcome', FILTER_VALIDATE_BOOLEAN )
) {
	$user     = wp_get_current_user();
	$username = ! empty( $user->user_firstname ) ? $user->user_firstname : $user->user_login;
	$this->render( 'admin/dashboard/dialogs/fresh-install', array( 'username' => $username ) );
}

// DIALOG: Visibility behavior updated.
$review_conditions = filter_input( INPUT_GET, 'review-conditions', FILTER_VALIDATE_BOOLEAN );
if ( $review_conditions && Hustle_Migration::is_migrated( 'hustle_40_migrated' ) && ! Hustle_Notifications::was_notification_dismissed( '41_visibility_behavior_update' ) ) {
	$version     = Opt_In_Utils::_is_free() ? '7.1' : '4.1';
	$support_url = Opt_In_Utils::_is_free() ? 'https://wordpress.org/support/plugin/wordpress-popup/' : 'https://premium.wpmudev.org/hub/support/#wpmud-chat-pre-survey-modal';
	$this->render(
		'admin/dashboard/dialogs/review-conditions',
		array(
			'version'     => $version,
			'support_url' => $support_url,
		)
	);
}

// DIALOG: On Boarding (Migrate).
if ( ( $need_migrate && ! Hustle_Notifications::was_notification_dismissed( Hustle_Dashboard_Admin::MIGRATE_MODAL_NAME ) )
	|| filter_input( INPUT_GET, 'show-migrate', FILTER_VALIDATE_BOOLEAN )
) {
	$user     = wp_get_current_user();
	$username = ! empty( $user->user_firstname ) ? $user->user_firstname : $user->user_login;
	$this->render( 'admin/dashboard/dialogs/migrate-data', array( 'username' => $username ) );
}

// DIALOG: Delete.
$this->render(
	'admin/commons/sui-listing/dialogs/delete-module',
	array()
);

// DIALOG: Preview.
$this->render( 'admin/dialogs/preview-dialog' );

// DIALOG: Dissmiss migrate tracking notice modal confirmation.
if ( Hustle_Notifications::is_show_migrate_tracking_notice() ) {
	$this->render( 'admin/dialogs/migrate-dismiss-confirmation' );
}
?>
