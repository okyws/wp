<?php
/**
 * Vanilla theme section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Vanilla Theme', 'hustle' ); ?></span>
		<?php /* translators: module type in small caps and in singular */ ?>
		<span class="sui-description"><?php printf( esc_html__( 'Enable this option if you don’t want to use the styling Hustle adds to your %s.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle-module-use-vanilla" class="sui-toggle hustle-toggle-with-container" data-toggle-on="use-vanilla-on" data-toggle-off="use-vanilla">
				<input type="checkbox"
					name="use_vanilla"
					data-attribute="use_vanilla"
					id="hustle-module-use-vanilla"
					aria-labelledby="hustle-module-use-vanilla-label"
					<?php checked( $settings['use_vanilla'], '1' ); ?> />
				<span class="sui-toggle-slider" aria-hidden="true"></span>
				<?php /* translators: module type in small caps and in singular */ ?>
				<span id="hustle-module-use-vanilla-label" class="sui-toggle-label"><?php printf( esc_html__( 'Enable vanilla theme for this %s', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
			</label>

			<div class="sui-toggle-content" data-toggle-content="use-vanilla-on" style="margin-top: 4px;">

				<?php
				$this->get_html_for_options(
					array(
						array(
							'type'  => 'inline_notice',
							'icon'  => 'info',
							/* translators: %s module type display name */
							'value' => sprintf( esc_html__( "You have opted for no stylesheet to be enqueued. The %s will inherit styles from your theme's CSS.", 'hustle' ), esc_html( $smallcaps_singular ) ),
						),
					)
				);
				?>

			</div>

		</div>

	</div>

</div>
