<?php
$banner_1x = forminator_plugin_url() . 'assets/images/e-sign.png';
$banner_2x = forminator_plugin_url() . 'assets/images/e-sign@2x.png';

if ( ! FORMINATOR_PRO ) {
	$banner_1x = forminator_plugin_url() . 'assets/images/graphic-upgradetour-feature.png';
	$banner_2x = forminator_plugin_url() . 'assets/images/graphic-upgradetour-feature@2x.png';
}
?>

<div
	id="forminator-new-feature"
	class="sui-dialog sui-dialog-onboard"
	aria-hidden="true"
>

	<div class="sui-dialog-overlay sui-fade-out" data-a11y-dialog-hide="forminator-new-feature" aria-hidden="true"></div>

	<div
		class="sui-dialog-content sui-fade-out"
		role="dialog"
	>

		<div class="sui-slider forminator-feature-modal" data-prop="forminator_dismiss_feature_113" data-nonce="<?php echo esc_attr( wp_create_nonce( 'forminator_dismiss_notification' ) ); ?>">

			<ul role="document" class="sui-slider-content">

				<li class="sui-current sui-loaded" data-slide="1">

					<div class="sui-box">

						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img
								src="<?php echo esc_url( $banner_1x ); ?>"
								srcset="<?php echo esc_url( $banner_1x ); ?> 1x, <?php echo esc_url( $banner_2x ); ?> 2x"
								class="sui-image"
								alt="Forminator"
							/>
						</div>

						<div class="sui-box-header sui-block-content-center">

							<button data-a11y-dialog-hide="forminator-new-feature" class="sui-dialog-close forminator-dismiss-new-feature" aria-label="<?php esc_html_e( 'Close this dialog window', Forminator::DOMAIN ); ?>"></button>

							<?php if ( FORMINATOR_PRO ) { ?>

								<h2 class="sui-box-title"><?php esc_html_e( 'E-Signatures added!', Forminator::DOMAIN ); ?></h2>

								<p class="sui-description"><?php printf( esc_html__( 'That\'s right, we\'ve just added the ability to %sreceive signatures%s in your forms!', Forminator::DOMAIN ), '<strong>', '</strong>' ); ?></p>

								<p class="sui-description"><?php printf( esc_html__( 'Have an online application that requires a signature or a contract you need your customers to sign? Insert the %sE-Signature%s field into your form so that your customers can use their mouse, trackpad or finger (on touch devices) to leave a signature before submitting the form.', Forminator::DOMAIN ), '<strong>', '</strong>' ); ?></p>

							<?php } else { ?>

								<h2 class="sui-box-title" sui-content-size="380"><?php esc_html_e( 'Multi-file Uploader & Datepicker Limits', Forminator::DOMAIN ); ?></h2>

								<p class="sui-description" sui-content-size="380"><?php esc_html_e( 'Introducing some excellent new features and improvements with Forminator 1.13, including the multi-file upload option, advanced date field limits, and much more.', Forminator::DOMAIN ); ?></p>

							<?php } ?>

						</div>

						<?php if ( FORMINATOR_PRO ) { ?>

							<div class="sui-box-footer sui-block-content-center" sui-space-bottom="60">

								<button class="sui-button forminator-dismiss-new-feature" type="button" data-a11y-dialog-hide="forminator-new-feature"><?php esc_html_e( 'Got It', Forminator::DOMAIN ); ?></button>

							</div>

						<?php } else { ?>

							<div class="sui-box-body" sui-spacing-bottom="0">

								<ul class="sui-list" sui-type="bullets">

									<li>
										<p class="sui-description"><strong sui-color="darkgray"><?php esc_html_e( 'Multi-file Uploader', Forminator::DOMAIN ); ?></strong></p>
										<p class="sui-description">
											<?php printf(
												esc_html__( 'Allow your users to upload more than just one file in the File Upload field with a drag & drop interface. All you have to do is, set the type as "%1$sMultiple%2$s" under the Labels tab of the File Upload field.', Forminator::DOMAIN ),
												'<strong>',
												'</strong>'
											); ?>
										</p>
									</li>

									<li>
										<p class="sui-description"><strong sui-color="darkgray"><?php esc_html_e( 'Advanced Datepicker Limits', Forminator::DOMAIN ); ?></strong></p>
										<p class="sui-description"><?php esc_html_e( 'Datepicker field has now got a new Limits tab where you can restrict the selectable dates in your form date picker field. E.g., future dates only, N days from today, dates between a specific date range, specific days of the week, and a lot more.', Forminator::DOMAIN ); ?></p>
									</li>

								</ul>

							</div>

							<div class="sui-box-footer sui-block-content-center">

								<button class="sui-button forminator-dismiss-new-feature" type="button" data-a11y-dialog-hide="forminator-new-feature"><?php esc_html_e( 'Got It', Forminator::DOMAIN ); ?></button>

							</div>

						<?php } ?>

					</div>

				</li>

			</ul>

		</div>

	</div>

</div>

<script type="text/javascript">
	jQuery( '#forminator-new-feature .forminator-dismiss-new-feature' ).on( 'click', function( e ) {
		e.preventDefault();

		var $notice = jQuery( e.currentTarget ).closest( '.forminator-feature-modal' );
		var ajaxUrl = '<?php echo forminator_ajax_url();// phpcs:ignore ?>';

		jQuery.post(
			ajaxUrl,
			{
				action: 'forminator_dismiss_notification',
				prop: $notice.data('prop'),
				_ajax_nonce: $notice.data('nonce')
			}
		).always( function() {
			$notice.hide();
		});
	});
</script>
