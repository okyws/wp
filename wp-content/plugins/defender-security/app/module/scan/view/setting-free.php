<div class="sui-box">
    <div class="sui-box-header">
        <h3 class="sui-box-title">
			<?php _e( "Settings", "defender-security" ) ?>
        </h3>
    </div>
    <form method="post" class="scan-frm scan-settings">
        <div class="sui-box-body sui-upsell-items">
            <div class="sui-box-settings-row no-border no-padding-bottom no-margin-bottom">
                <div class="sui-box-settings-col-1">
                    <span class="sui-settings-label"><?php _e( "Scan Types", "defender-security" ) ?></span>
                    <span class="sui-description">
                        <?php _e( "Choose the scan types you would like to include in your default scan. It's recommended you enable all types.", "defender-security" ) ?>
                    </span>
                </div>

                <div class="sui-box-settings-col-2">
                    <div class="sui-form-field">
                        <input type="hidden" name="scan_core" value="0"/>
                        <label class="sui-toggle">
                            <input role="presentation" type="checkbox" name="scan_core" class="toggle-checkbox"
                                   id="core-scan" value="1"
								<?php checked( true, $setting->scan_core ) ?>/>
                            <span class="sui-toggle-slider"></span>
                        </label>
                        <label for="core-scan" class="sui-toggle-label">
							<?php _e( "WordPress Core", "defender-security" ) ?>
                        </label>
                        <p class="sui-description sui-toggle-content">
							<?php _e( "Defender checks for any modifications or additions to WordPress core files.", "defender-security" ) ?>
                        </p>
                    </div>
                    <div class="sui-form-field">
                        <div class="relative">
                            <label class="sui-toggle">
                                <input role="presentation" disabled type="checkbox" class="toggle-checkbox" value="0"/>
                                <span class="sui-toggle-slider"></span>
                            </label>
                            <label for="scan_vuln" class="sui-toggle-label">
								<?php _e( "Plugins & Themes", "defender-security" ) ?>
                            </label>
                            <span class="sui-tag sui-tag-pro"><?php _e( "Pro", "defender-security" ) ?></span>
                        </div>
                        <p class="sui-description sui-toggle-content">
							<?php _e( "Defender looks for publicly reported vulnerabilities in your installed plugins and themes.", "defender-security" ) ?>
                        </p>
                    </div>
                    <div class="sui-form-field">
                        <div class="relative">
                            <label class="sui-toggle">
                                <input role="presentation" disabled type="checkbox" class="toggle-checkbox" value="0"/>
                                <span class="sui-toggle-slider"></span>
                            </label>
                            <label for="scan_content" class="sui-toggle-label">
								<?php _e( "Suspicious Code", "defender-security" ) ?>
                            </label>
                            <span class="sui-tag sui-tag-pro"><?php _e( "Pro", "defender-security" ) ?></span>
                        </div>
                        <p class="sui-description sui-toggle-content">
							<?php _e( "Defender looks inside all of your files for suspicious and potentially harmful code.", "defender-security" ) ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="sui-box-settings-row sui-upsell-row">
                <img class="sui-image sui-upsell-image"
                     src="<?php echo wp_defender()->getPluginUrl() . 'assets/img/scanning-free-man.svg' ?>">
                <div class="sui-upsell-notice">
                    <p>
						<?php printf( __( "Defender Pro allows you to scan your entire file structure for malicious code, including non-WordPress files. This feature is included in a WPMU DEV monthly membership along with 24/7 support and lots of handy site management tools  – <a target='_blank' href='%s'>Try it all FREE today!</a>", "defender-security" ),
							\WP_Defender\Behavior\Utils::instance()->campaignURL( 'defender_filescanning_settings_upsell_link' ) ) ?>
                    </p>
                </div>
            </div>
            <div class="sui-box-settings-row">
                <div class="sui-box-settings-col-1">
                    <span class="sui-settings-label"><?php _e( "Maximum included file size", "defender-security" ) ?></span>
                    <span class="sui-description">
                    <?php _e( "Defender will skip any files larger than this size. The smaller the number, the faster Defender will scan your website.", "defender-security" ) ?>
                </span>
                </div>

                <div class="sui-box-settings-col-2">
                    <div class="sui-form-field">
                        <div class="sui-form-field">
                            <input type="number" size="4" class="sui-form-control sui-input-sm sui-field-has-suffix"
                                   value="<?php echo esc_attr( $setting->max_filesize ) ?>"
                                   name="max_filesize">
                            <span class="sui-field-suffix">Mb</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="action" value="saveScanSettings"/>
		<?php wp_nonce_field( 'saveScanSettings' ) ?>
        <div class="sui-box-footer">
            <div class="sui-actions-right">
                <button type="submit" class="sui-button sui-button-blue">
                    <i class="sui-icon-save" aria-hidden="true"></i>
					<?php _e( "Save Changes", "defender-security" ) ?></button>
            </div>
        </div>
    </form>
</div>
<dialog id="all-ok" title="<?php esc_attr_e( 'All OK', "defender-security" ) ?>">
    <div class="wp-defender">
        <form method="post" class="scan-frm scan-settings">
            <input type="hidden" name="action" value="saveScanSettings"/>
			<?php wp_nonce_field( 'saveScanSettings' ) ?>
            <textarea rows="12" name="email_all_ok"><?php echo $setting->email_all_ok ?></textarea>
            <strong class="small">
				<?php _e( "Available variables", "defender-security" ) ?>
            </strong>
            <div class="clearfix"></div>
            <span class="def-tag tag-generic">{USER_NAME}</span>
            <span class="def-tag tag-generic">{SITE_URL}</span>
            <div class="clearfix mline"></div>
            <hr class="mline"/>
            <button type="button"
                    class="button button-light close"><?php _e( "Cancel", "defender-security" ) ?></button>
            <button class="button float-r"><?php _e( "Save Template", "defender-security" ) ?></button>
        </form>
    </div>
</dialog>