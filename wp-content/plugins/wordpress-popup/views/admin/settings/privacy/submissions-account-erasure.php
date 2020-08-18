<?php
/**
 * Account erasure row under the "privacy" tab.
 *
 * @package Hustle
 * @since 4.0.3
 */

$submission_erasure_enabled = '1' === $settings['retain_sub_on_erasure'];
$remove_data_url            = admin_url( 'tools.php?page=remove_personal_data' );
?>

<fieldset class="sui-form-field">

	<label class="sui-settings-label"><?php esc_html_e( 'Account Erasure Requests', 'hustle' ); ?></label>

	<span class="sui-description">
		<?php
		printf(
			/* translators: 1. opening 'a' tag to the 'remove personal data' tool, 2. closing 'a' tag */
			esc_html__( 'When handling an %1$saccount erasure request%2$s that contains an email associated with a submission, what do you want to do?', 'hustle' ),
			'<a href="' . esc_url( $remove_data_url ) . '">',
			'</a>'
		);
		?>
	</span>

	<div class="sui-side-tabs" style="margin-top: 10px;">

		<div class="sui-tabs-menu">

			<label for="retain_sub_on_erasure-true" class="sui-tab-item">
				<input type="radio"
					name="retain_sub_on_erasure"
					value="1"
					id="retain_sub_on_erasure-true"
					<?php checked( $submission_erasure_enabled, true ); ?> />
				<?php esc_html_e( 'Retain Submission', 'hustle' ); ?>
			</label>

			<label for="retain_sub_on_erasure-false" class="sui-tab-item">
				<input type="radio"
					name="retain_sub_on_erasure"
					value="0"
					id="retain_sub_on_erasure-false"
					<?php checked( $submission_erasure_enabled, false ); ?> />
				<?php esc_html_e( 'Remove Submission', 'hustle' ); ?>
			</label>

		</div>

	</div>

</fieldset>
