<div class="sui-box">
    <div class="sui-box-header">
        <h3 class="sui-box-title">
			<?php _e( "Reporting", "defender-security" ) ?>
        </h3>
    </div>
    <form method="post" class="scan-frm scan-settings">
        <div class="sui-box-body">
            <p>
				<?php _e( "Defender can automatically run regular scans of your website and email you reports.", "defender-security" ) ?>
            </p>
            <div class="sui-box-settings-row">
                <div class="sui-box-settings-col-1">
                    <span class="sui-settings-label"><?php _e( "Enable reporting", "defender-security" ) ?></span>
                    <span class="sui-description">
                        <?php _e( "Enabling this option will ensure you’re always the first to know when something suspicious is detected on your site.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="sui-box-settings-col-2">
                    <div class="sui-side-tabs sui-tabs">
                        <input type="hidden" name="report" value="<?php echo $setting->report ?>"/>
                        <div data-tabs>
                            <div rel="input_value" data-target="report" data-value="1"
                                 class="<?php echo $setting->report == 1 ? 'active' : null ?>"><?php _e( "On", "defender-security" ) ?></div>
                            <div rel="input_value" data-target="report" data-value="0"
                                 class="<?php echo $setting->report == 0 ? 'active' : null ?>"><?php _e( "Off", "defender-security" ) ?></div>
                        </div>
                        <div data-panes>
                            <div class="sui-tab-boxed <?php echo $setting->report == 1 ? 'active' : null ?>">
                                <p class="sui-p-small">
									<?php _e( "By default, we will only notify the recipients below when there is an issue from your file scan. Enable this option to send emails even when no issues are detected. ", "defender-security" ) ?>
                                </p>
                                <input type="hidden" name="always_send" value="0"/>
                                <label class="sui-toggle">
                                    <input role="presentation" type="checkbox" name="always_send"
                                           class="toggle-checkbox"
                                           id="always_send" value="1"
										<?php checked( true, $setting->always_send ) ?>/>
                                    <span class="sui-toggle-slider"></span>
                                </label>
                                <label for="always_send" class="sui-toggle-label">
									<?php _e( "Also send notifications when no issues are detected.", "defender-security" ) ?>
                                </label>
                                <div class="margin-top-30">
									<?php $email->renderInput() ?>
                                </div>
                                <div class="margin-bottom-20">
                                    <h3 class="sui-field-list-title">
										<?php _e( "Reporting", "defender-security" ) ?>
                                    </h3>
                                </div>
                                <div class="sui-form-field">
                                    <label class="sui-label"><?php _e( "Frequency", "defender-security" ) ?></label>
                                    <select name="frequency">
                                        <option <?php selected( 1, $setting->frequency ) ?>
                                                value="1"><?php _e( "Daily", "defender-security" ) ?></option>
                                        <option <?php selected( 7, $setting->frequency ) ?>
                                                value="7"><?php _e( "Weekly", "defender-security" ) ?></option>
                                        <option <?php selected( 30, $setting->frequency ) ?>
                                                value="30"><?php _e( "Monthly", "defender-security" ) ?></option>
                                    </select>
                                </div>
                                <div class="sui-form-field">
                                    <label class="sui-label"><?php _e( "Day of the week", "defender-security" ) ?></label>
                                    <select name="day">
										<?php foreach ( \WP_Defender\Behavior\Utils::instance()->getDaysOfWeek() as $day ): ?>
                                            <option <?php selected( $day, $setting->day ) ?>
                                                    value="<?php echo $day ?>"><?php echo ucfirst( $day ) ?></option>
										<?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="sui-form-field">
                                    <label class="sui-label"><?php _e( "Time of day", "defender-security" ) ?></label>
                                    <select name="time">
										<?php foreach ( \WP_Defender\Behavior\Utils::instance()->getTimes() as $time ): ?>
                                            <option <?php selected( $time, $setting->time ) ?>
                                                    value="<?php echo $time ?>"><?php echo strftime( '%I:%M %p', strtotime( $time ) ) ?></option>
										<?php endforeach;; ?>
                                    </select>
                                </div>
                            </div>
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