<div class="sui-box">
    <div class="sui-box-header">
        <h3 class="sui-box-title">
			<?php _e( "Accessibility", "defender-security" ) ?>
        </h3>
    </div>
    <form method="post" id="settings" class="settings-frm">
        <div class="sui-box-body">
            <p>
				<?php _e( "Enable support for any accessibility enhancements available in the plugin interface.", "defender-security" ) ?>
            </p>
            <div class="sui-box-settings-row">
                <div class="sui-box-settings-col-1">
                    <span class="sui-settings-label">
                        <?php esc_html_e( "High Contrast Mode", "defender-security" ) ?>
                    </span>
                    <span class="sui-description">
                        <?php _e( "Increase the visibility and accessibility of elements and components of this plugin’s interface to meet WCAG AAA requirements.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="sui-box-settings-col-2">
                    <input type="hidden" name="high_contrast_mode" value="0"/>
                    <div class="sui-form-field">
                        <label class="sui-toggle">
                            <input role="presentation" type="checkbox" name="high_contrast_mode" class="toggle-checkbox"
                                   id="high_contrast_mode" <?php checked( 1, $settings->high_contrast_mode ) ?> value="1"
                            />
                            <span class="sui-toggle-slider"></span>
                        </label>
                        <label for="high_contrast_mode" class="sui-toggle-label">
				            <?php _e( "Enable high contrast mode", "defender-security" ) ?>
                        </label>
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