<?php
/**
 * Wizard status bar.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box sui-box-sticky">

	<div class="sui-box-status">

		<div class="sui-status">

			<div class="sui-status-module">

				<?php esc_html_e( 'Status', 'hustle' ); ?>

				<?php if ( $is_active ) : ?>
					<span class="sui-tag sui-tag-blue"><?php esc_html_e( 'Published', 'hustle' ); ?></span>
				<?php else : ?>
					<span class="sui-tag"><?php esc_html_e( 'Draft', 'hustle' ); ?></span>
				<?php endif; ?>

			</div>

			<div id="hustle-unsaved-changes-status" class="sui-status-changes sui-hidden">
				<span class="sui-icon-update" aria-hidden="true"></span>
				<?php esc_html_e( 'Unsaved changes', 'hustle' ); ?>
			</div>

			<div id="hustle-saved-changes-status" class="sui-status-changes">
				<span class="sui-icon-check-tick" aria-hidden="true"></span>
				<?php esc_html_e( 'Saved', 'hustle' ); ?>
			</div>

		</div>

		<div class="sui-actions">

			<button id="hustle-draft-button"
				class="sui-button hustle-action-save"
				data-active="0">
				<span class="sui-loading-text">
					<span class="sui-icon-save" aria-hidden="true"></span>
					<span class="button-text"><?php $is_active ? esc_html_e( 'Unpublish', 'hustle' ) : esc_html_e( 'Save draft', 'hustle' ); ?></span>
				</span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

			<button
				class="hustle-publish-button sui-button sui-button-blue hustle-action-save"
				data-active="1">
				<span class="sui-loading-text">
					<span class="sui-icon-web-globe-world" aria-hidden="true"></span>
					<span class="button-text"><?php $is_active ? esc_html_e( 'Save changes', 'hustle' ) : esc_html_e( 'Publish', 'hustle' ); ?></span>
				</span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>
