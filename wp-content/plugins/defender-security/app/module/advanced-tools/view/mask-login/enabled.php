<div class="sui-box">
    <div class="sui-box-header">
        <h3 class="sui-box-title">
			<?php _e( "Mask Login Area", "defender-security" ) ?>
        </h3>
    </div>
    <form method="post" id="ad-mask-settings-frm" class="advanced-settings-frm">
        <div class="sui-box-body">
            <p>
				<?php _e( "Change your default WordPress login URL to hide your login area from hackers and bots.", "defender-security" ) ?>
            </p>
			<?php if ( isset( wp_defender()->global['compatibility'] ) ): ?>
                <div class="sui-notice sui-notice-error">
                    <p>
						<?php echo implode( '<br/>', array_unique( wp_defender()->global['compatibility'] ) ); ?>
                    </p>
                </div>
			<?php else: ?>
				<?php if ( strlen( trim( $settings->maskUrl ) ) == 0 ): ?>
                    <div class="sui-notice sui-notice-warning">
                        <p>
							<?php _e( "Masking is currently inactive. Choose your URL and save your settings to finish setup. ", "defender-security" ) ?>
                        </p>
                    </div>
				<?php else: ?>
                    <div class="sui-notice sui-notice-info">
                        <p>
							<?php printf( __( "Masking is currently active at <strong>%s</strong>", "defender-security" ), \WP_Defender\Module\Advanced_Tools\Component\Mask_Api::getNewLoginUrl() ) ?>
                        </p>
                    </div>
				<?php endif; ?>
			<?php endif; ?>
            <div class="sui-box-settings-row">
                <div class="sui-box-settings-col-1">
                    <span class="sui-settings-label">
                        <?php _e( "Masking URL", "defender-security" ) ?>
                    </span>
                    <span class="sui-description">
                        <?php _e( 'Choose the new URL slug where users of your website will now navigate to log in or register.', "defender-security" ) ?>
                    </span>
                </div>

                <div class="sui-box-settings-col-2">
                    <span class="sui-description">
                        <?php _e( "You can specify any URLs. For security reasons, less obvious URLs are recommended as they are harder for bots to guess.", "defender-security" ) ?>
                    </span>
                    <div class="sui-form-field">
                        <label class="sui-label"><?php _e( 'New Login URL', "defender-security" ) ?></label>
                        <input type="text" class="sui-form-control" name="maskUrl"
                               value="<?php echo $settings->maskUrl ?>"
                               placeholder="<?php _e( 'I.e. dashboard', "defender-security" ); ?>"/>
                        <span class="sui-description">
                            <?php printf( __( "Users will login at <a href='%s'>%s</a>. Note: Registration and Password Reset emails have hardcoded URLs in them. We will update them automatically to match your new login URL.", "defender-security" ), get_site_url() . '/' . $settings->maskUrl,get_site_url() . '/' . $settings->maskUrl ) ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="sui-box-settings-row">
                <div class="sui-box-settings-col-1">
                    <span class="sui-settings-label">
                        <?php _e( "Redirect traffic", "defender-security" ) ?>
                    </span>
                    <span class="sui-description">
                        <?php _e( 'With this feature you can send visitors and bots who try to visit the default Wordpress login URLs to a separate URL to avoid 404s.', "defender-security" ) ?>
                    </span>
                </div>

                <div class="sui-box-settings-col-2">
                    <input type="hidden" name="redirectTraffic" value="0"/>
                    <label class="sui-toggle">
                        <input role="presentation" type="checkbox" name="redirectTraffic" class="toggle-checkbox"
                               id="redirectTraffic" value="1"
			                <?php checked( true, $settings->redirectTraffic ) ?>/>
                        <span class="sui-toggle-slider"></span>
                    </label>
                    <label for="redirectTraffic" class="sui-toggle-label">
		                <?php _e( "Enable 404 redirection", "defender-security" ) ?>
                    </label>
                    <div id="redirectTrafficContainer" class="sui-border-frame sui-toggle-content"
                         aria-hidden="<?php echo (bool)! $settings->redirectTraffic ?>">
                        <label class="sui-label"><?php _e( "Redirection URL", "defender-security" ) ?></label>
                        <input type="text" class="sui-form-control" name="redirectTrafficUrl"
                               value="<?php echo $settings->redirectTrafficUrl ?>"/>
                        <span class="sui-description">
                            <?php printf( __( "Visitors who visit the default login URLs will be redirected to <a href='%s'>%s</a>", "defender-security" ), get_site_url() . '/' . $settings->redirectTrafficUrl,get_site_url() . '/' . $settings->redirectTrafficUrl ) ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="sui-box-settings-row">
                <div class="sui-box-settings-col-1">
                    <span class="sui-settings-label">
                        <?php _e( "Deactivate", "defender-security" ) ?>
                    </span>
                    <span class="sui-description">
                        <?php _e( "Disable login area masking and return to the default wp-admin and wp-login URLS.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="sui-box-settings-col-2">
                    <button type="button" class="sui-button sui-button-ghost deactivate-atmasking">
				        <?php _e( "Deactivate", "defender-security" ) ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="sui-box-footer">
            <input type="hidden" name="action" value="saveATMaskLoginSettings"/>
			<?php wp_nonce_field( 'saveATMaskLoginSettings' ) ?>
            <div class="sui-actions-right">
                <button type="submit" class="sui-button sui-button-blue">
                    <i class="sui-icon-save" aria-hidden="true"></i>
			        <?php _e( "Save Changes", "defender-security" ) ?></button>
            </div>
        </div>
    </form>
</div>
