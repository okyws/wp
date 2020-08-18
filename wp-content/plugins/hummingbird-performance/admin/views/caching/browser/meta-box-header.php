<?php
/**
 * Browser caching meta box header.
 *
 * @package Hummingbird
 *
 * @var string $title   Title of the module.
 * @var int    $issues  Number of caching issues.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h3  class="sui-box-title"><?php echo esc_html( $title ); ?></h3>
<?php if ( $issues ) : ?>
	<div class="sui-actions-left">
		<span class="sui-tag sui-tag-warning"><?php echo intval( $issues ); ?></span>
	</div>
<?php endif; ?>
<div class="sui-actions-right">
	<span class="wphb-label-notice-inline sui-hidden-xs sui-hidden-sm"><?php esc_html_e( 'Made changes?', 'wphb' ); ?></span>
	<button type="submit" class="sui-button sui-button-ghost" aria-live="polite">
		<!-- Default State Content -->
		<span class="sui-button-text-default">
			<i class="sui-icon-update" aria-hidden="true"></i>
			<?php esc_html_e( 'Re-Check Status', 'wphb' ); ?>
		</span>

		<!-- Loading State Content -->
		<span class="sui-button-text-onload">
			<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
			<?php esc_html_e( 'Updating Status', 'wphb' ); ?>
		</span>
	</button>
</div>
