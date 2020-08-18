<?php
/**
 * Asset optimization summary meta box.
 *
 * @package Hummingbird
 *
 * @var string $compressed_size  Compressed size string.
 * @var int    $enqueued_files   Number of enqueued files.
 * @var bool   $is_member        Is WPMU DEV member.
 * @var string $percentage       Percentage string.
 * @var bool   $use_cdn          CDN status.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="sui-summary-image-space" aria-hidden="true"></div>
<div class="sui-summary-segment">
	<div class="sui-summary-details">
		<span class="sui-summary-large">
			<?php echo ! $percentage || '0.0' === $percentage ? '-' : esc_html( $percentage ) . '%'; ?>
		</span>
		<span class="sui-summary-sub"><?php esc_html_e( 'Compression savings', 'wphb' ); ?></span>
	</div>
</div>
<div class="sui-summary-segment">
	<ul class="sui-list">
		<li>
			<span class="sui-list-label"><?php esc_html_e( 'Total files', 'wphb' ); ?></span>
			<span class="sui-list-detail"><?php echo intval( $enqueued_files ); ?></span>
		</li>
		<li>
			<span class="sui-list-label"><?php esc_html_e( 'Filesize reductions', 'wphb' ); ?></span>
			<span class="sui-list-detail"><?php echo intval( $compressed_size ); ?>kb</span>
		</li>
		<li>
			<span class="sui-list-label"><?php esc_html_e( 'WPMU DEV CDN', 'wphb' ); ?></span>
			<span class="sui-list-detail">
				<?php if ( ! is_multisite() && $is_member ) : ?>
					<label class="sui-toggle sui-tooltip sui-tooltip-top-right" data-tooltip="<?php esc_html_e( 'Enable WPMU DEV CDN', 'wphb' ); ?>">
						<input type="checkbox" name="use_cdn" id="use_cdn" <?php checked( $use_cdn && $is_member ); ?> <?php disabled( ! $is_member ); ?>>
						<span class="sui-toggle-slider"></span>
					</label>
				<?php elseif ( ! is_multisite() && ! $is_member ) : ?>
					<span class="sui-tag sui-tag-upsell sui-tooltip sui-tooltip-top-right" data-tooltip="<?php esc_html_e( 'Host your files on WPMU DEV’s blazing fast CDN', 'wphb' ); ?>" data-modal-open="wphb-upgrade-membership-modal" data-modal-open-focus="upgrade-to-pro-button" data-modal-mask="true">
						<?php esc_html_e( 'Pro Feature', 'wphb' ); ?>
					</span>
				<?php elseif ( $use_cdn && $is_member ) : ?>
					<span class="sui-tooltip sui-tooltip-top-right" data-tooltip="<?php esc_html_e( 'The Network Admin has the WPMU DEV CDN turned on', 'wphb' ); ?>">
						<i class="sui-icon-check-tick sui-md sui-info" aria-hidden="true"></i>
					</span>
				<?php else : ?>
					<span class="sui-tag sui-tag-disabled sui-tooltip sui-tooltip-top-right" data-tooltip="<?php esc_html_e( 'The Network Admin has the WPMU DEV CDN turned off', 'wphb' ); ?>">
						<?php esc_html_e( 'Disabled', 'wphb' ); ?>
					</span>
				<?php endif; ?>
			</span>
		</li>
	</ul>
</div>
