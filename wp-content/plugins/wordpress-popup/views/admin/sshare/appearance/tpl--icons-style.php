<?php
/**
 * Icons style section.
 *
 * @var bool $is_empty True if neither inline nor floating display types are enabled.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div id="hustle-appearance-icons-style" class="sui-box-settings-row"<?php echo $is_empty ? ' style="display: none;"' : ''; ?>>

	<div class="sui-box-settings-col-1">

		<span class="sui-settings-label"><?php esc_html_e( 'Icons Style', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Choose the style for your social icons as per your need.', 'hustle' ); ?></span>

	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-side-tabs">

			<div class="sui-tabs-menu">

				<label for="hustle-social-icon--default" class="sui-tab-item" >
					<input
						type="radio"
						name="icon_style"
						data-attribute="icon_style"
						value="flat"
						id="hustle-social-icon--default"
						<?php checked( $icon_style, 'flat' ); ?>
					/>
					<span class="hui-icon-social-facebook hui-sm" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Default', 'hustle' ); ?></span>
				</label>

				<label for="hustle-social-icon--outlined" class="sui-tab-item" >
					<input
						type="radio"
						name="icon_style"
						data-attribute="icon_style"
						value="outline"
						id="hustle-social-icon--outlined"
						<?php checked( $icon_style, 'outline' ); ?>
					/>
					<span class="hui-icon-social-facebook hui-icon-outlined hui-sm" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Outlined', 'hustle' ); ?></span>
				</label>

				<label for="hustle-social-icon--circle" class="sui-tab-item">
					<input
						type="radio"
						name="icon_style"
						data-attribute="icon_style"
						value="rounded"
						id="hustle-social-icon--circle"
						<?php checked( $icon_style, 'rounded' ); ?>
					/>
					<span class="hui-icon-social-facebook hui-icon-circle hui-sm" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Circle', 'hustle' ); ?></span>
				</label>

				<label for="hustle-social-icon--square" class="sui-tab-item" >
					<input
						type="radio"
						name="icon_style"
						data-attribute="icon_style"
						value="squared"
						id="hustle-social-icon--square"
						<?php checked( $icon_style, 'squared' ); ?>
					/>
					<span class="hui-icon-social-facebook hui-icon-square hui-sm" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Square', 'hustle' ); ?></span>
				</label>

			</div>

		</div>

	</div>

</div>
