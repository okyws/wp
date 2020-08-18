<?php
/**
 * Markup for the modal "Create Module" on listing pages.
 *
 * @package Hustle
 * @since 4.0.0
 */

$is_social_share = ( Hustle_Module_Model::SOCIAL_SHARING_MODULE === $module_type );

$hide_branding = apply_filters( 'wpmudev_branding_hide_branding', false );
?>

<div class="sui-modal sui-modal-lg">

	<div
		role="dialog"
		id="hustle-dialog--create-new-module"
		class="sui-modal-content"
		aria-modal="true"
		<?php /* translators: module type in smallcaps and singular. */ ?>
		aria-label="<?php printf( esc_html__( 'Create a new %s', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_create_new_module' ) ); ?>"
	>

		<?php if ( ! $is_social_share ) { ?>

			<div
				id="hustle-create-new-module-step-1"
				class="sui-modal-slide sui-active"
				data-modal-size="lg"
			>

				<div class="sui-box">

					<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">

						<button class="sui-button-icon sui-button-float--right hustle-modal-close" data-modal-close>
							<span class="sui-icon-close sui-md" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
						</button>

						<span class="sui-box-steps sui-md sui-steps-float" aria-hidden="true">
							<span class="sui-current"></span>
							<span></span>
						</span>

						<h3 class="sui-box-title sui-lg"><?php esc_html_e( 'Choose Content Type', 'hustle' ); ?></h3>

						<p class="sui-description"><?php esc_html_e( "Let's start by choosing an appropriate content type based on your goal.", 'hustle' ); ?></p>

					</div>

					<div class="sui-box-selectors sui-box-selectors-col-2">

						<ul role="radiogroup" id="hustle-module-types">

							<li><label for="optin" class="sui-box-selector">
								<input type="radio" name="mode" id="optin" value="optin" checked="checked" />
								<span><span class="sui-icon-mail" aria-hidden="true"></span> <?php esc_html_e( 'Email Opt-in', 'hustle' ); ?></span>
								<span><?php esc_html_e( 'Perfect for Newsletter signups, or collecting user data in general.', 'hustle' ); ?></span>
							</label></li>

							<li><label for="informational" class="sui-box-selector">
								<input type="radio" name="mode" id="informational" value="informational" />
								<span><span class="sui-icon-info" aria-hidden="true"></span> <?php esc_html_e( 'Informational', 'hustle' ); ?></span>
								<span><?php esc_html_e( 'Perfect for promotional offers with Call to Action.', 'hustle' ); ?></span>
							</label></li>

						</ul>

					</div>

					<div class="sui-box-footer sui-flatten sui-content-right">

						<button id="hustle-select-mode" class="sui-button"><?php esc_html_e( 'Next', 'hustle' ); ?></button>

					</div>

					<?php if ( ! $hide_branding ) { ?>
						<img
							src="<?php echo esc_url( self::$plugin_url . 'assets/images/hustle-create.png' ); ?>"
							srcset="<?php echo esc_url( self::$plugin_url . 'assets/images/hustle-create.png' ); ?> 1x, <?php echo esc_url( self::$plugin_url . 'assets/images/hustle-create@2x.png' ); ?> 2x"
							<?php /* translators: module's type capitalized and in singular. */ ?>
							alt="<?php printf( esc_html__( 'Create New %s', 'hustle' ), esc_html( $capitalize_singular ) ); ?>"
							class="sui-image sui-image-center"
							aria-hidden="true"
						/>
					<?php } ?>

				</div>

			</div>

		<?php } ?>

		<div
			id="hustle-create-new-module-step-2"
			class="sui-modal-slide sui-active"
			data-modal-size="sm"
		>

			<div class="sui-box">

				<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">

					<button class="sui-button-icon sui-button-float--right hustle-modal-close" data-modal-close>
						<span class="sui-icon-close sui-md" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
					</button>
					<?php if ( ! $is_social_share ) { ?>

						<span class="sui-box-steps sui-md sui-steps-float" aria-hidden="true">
							<span></span>
							<span class="sui-current"></span>
						</span>

						<button class="sui-button-icon sui-button-float--left" data-modal-slide="hustle-create-new-module-step-1" data-modal-slide-focus="hustle-select-mode" data-modal-slide-intro="back">
							<span class="sui-icon-chevron-left sui-md" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Go back to choose module content type', 'hustle' ); ?></span>
						</button>

					<?php } ?>

					<?php /* translators: module's type capitalized and in singular. */ ?>
					<h3 class="sui-box-title sui-lg"><?php printf( esc_html__( 'Create %s', 'hustle' ), esc_html( $capitalize_singular ) ); ?></h3>

					<?php /* translators: module's type in small caps and in singular. */ ?>
					<p class="sui-description"><?php printf( esc_html__( "Let's give your new %s module a name. What would you like to name it?", 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

				</div>

				<div class="sui-box-body">

					<div class="sui-form-field">

						<?php /* translators: module's type in small caps and in singular. */ ?>
						<label for="hustle-module-name" class="sui-screen-reader-text"><?php printf( esc_html__( '%s name', 'hustle' ), esc_html( $capitalize_singular ) ); ?></label>

						<div class="sui-with-button sui-inside">

							<input
								type="text"
								name="name"
								autocomplete="off"
								placeholder="<?php esc_html_e( 'E.g. Newsletter', 'hustle' ); ?>"
								id="hustle-module-name"
								class="sui-form-control sui-required"
								autofocus
							/>

							<button id="hustle-create-module" class="sui-button-icon sui-button-blue sui-button-filled sui-button-lg" disabled>
								<span class="sui-loading-text">
									<span class="sui-icon-arrow-right" aria-hidden="true"></span>
								</span>
								<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
								<span class="sui-screen-reader-text"><?php esc_html_e( 'Done', 'hustle' ); ?></span>
							</button>

						</div>

						<span id="error-empty-name" class="sui-error-message" style="display: none;"><?php esc_html_e( 'Please add a name for this module.', 'hustle' ); ?></span>

						<span id="error-saving-settings" class="sui-error-message" style="display: none;"><?php esc_html_e( 'Something went wrong saving the settings. Make sure everything is okay.', 'hustle' ); ?></span>

						<span class="sui-description"><?php esc_html_e( 'This will not be visible anywhere on your website', 'hustle' ); ?></span>

					</div>

				</div>

				<?php if ( ! $hide_branding ) { ?>
					<img
						src="<?php echo esc_url( self::$plugin_url . 'assets/images/hustle-create.png' ); ?>"
						srcset="<?php echo esc_url( self::$plugin_url . 'assets/images/hustle-create.png' ); ?> 1x, <?php echo esc_url( self::$plugin_url . 'assets/images/hustle-create@2x.png' ); ?> 2x"
						<?php /* translators: module's type capitalized and in singular. */ ?>
						alt="<?php printf( esc_html__( 'Create New %s', 'hustle' ), esc_html( $capitalize_singular ) ); ?>"
						class="sui-image sui-image-center"
						aria-hidden="true"
					/>
				<?php } ?>

			</div>

		</div>

	</div>

</div>
