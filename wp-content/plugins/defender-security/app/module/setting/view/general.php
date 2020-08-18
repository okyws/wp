<div class="sui-box">
    <div class="sui-box-header">
        <h3 class="sui-box-title">
			<?php _e( "General", "defender-security" ) ?>
        </h3>
    </div>
    <form method="post" id="settings" class="settings-frm">
        <div class="sui-box-body">
            <p>
				<?php _e( "Configure general settings for this plugin.", "defender-security" ) ?>
            </p>
            <div class="sui-box-settings-row">
                <div class="sui-box-settings-col-1">
                    <span class="sui-settings-label">
                        <?php esc_html_e( "Translations", "defender-security" ) ?>
                    </span>
                    <span class="sui-description">
                        <?php printf( __( "By default, Defender will use the language you’d set in your <a href=\"%s\">WordPress Admin Settings</a> if a matching translation is available.", "defender-security" ),
	                        network_admin_url( 'options-general.php' ) ) ?>
                    </span>
                </div>

                <div class="sui-box-settings-col-2">
                    <div class="sui-form-field">
                        <label class="sui-label"><?php _e( "Active translation", "defender-security" ) ?></label>
                        <input type="text" value="<?php echo $settings->translate ?>" disabled
                               class="sui-form-control">
                        <p class="sui-description">
							<?php _e( "Not using your language, or have improvements? Help us improve translations by providing your own improvements here.", "defender-security" ) ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="sui-box-settings-row">
                <div class="sui-box-settings-col-1">
                    <span class="sui-settings-label">
                        <?php esc_html_e( "Usage Tracking", "defender-security" ) ?>
                    </span>
                    <span class="sui-description">
                        <?php esc_html_e( "Help make Defender better by letting our designers learn how you’re using the plugin.", "defender-security" ) ?>
                    </span>
                </div>

                <div class="sui-box-settings-col-2">
                    <div class="sui-form-field">
                        <input type="hidden" name="usage_tracking" value="0"/>
                        <label class="sui-toggle">
                            <input role="presentation" type="checkbox" name="usage_tracking" class="toggle-checkbox"
                                   id="usage_tracking" <?php checked( 1, $settings->usage_tracking ) ?> value="1"
                            />
                            <span class="sui-toggle-slider"></span>
                        </label>
                        <label for="usage_tracking" class="sui-toggle-label">
							<?php _e( "Allow usage tracking", "defender-security" ) ?>
                        </label>
                        <p class="sui-description sui-toggle-content">
							<?php _e( "Note: Usage tracking is completely anonymous. We are only tracking what features you are/aren’t using to make our feature decisions more informed.", "defender-security" ) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="sui-box-footer">
            <input type="hidden" name="action" value="saveSettings"/>
			<?php wp_nonce_field( 'saveSettings' ) ?>
            <div class="sui-actions-right">
                <button type="submit" class="sui-button sui-button-blue">
                    <i class="sui-icon-save" aria-hidden="true"></i>
					<?php _e( "Save Changes", "defender-security" ) ?></button>
            </div>
        </div>
    </form>
</div>