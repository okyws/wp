<div class="sui-box">
    <div class="sui-box-header">
        <h3 class="sui-box-title">
			<?php _e( "Reporting", "defender-security" ) ?>
        </h3>
    </div>
    <div class="sui-box-body sui-upsell-items">
        <div class="sui-box-settings-row sui-disabled no-margin-bottom">
            <p>
				<?php _e( "Defender can automatically run regular scans of your website and email you reports.", "defender-security" ) ?>
            </p>
        </div>
        <div class="sui-box-settings-row sui-disabled no-margin-bottom padding-bottom-30">
            <div class="sui-box-settings-col-1">
                <span class="sui-settings-label"><?php _e( "Enable reporting", "defender-security" ) ?></span>
                <span class="sui-description">
                        <?php _e( "Enabling this option will ensure you're always the first to know when something suspicious is detected on your site.", "defender-security" ) ?>
                    </span>
            </div>
            <div class="sui-box-settings-col-2">
                <div class="sui-side-tabs sui-tabs">
                    <div data-tabs>
                        <div><?php _e( "On", "defender-security" ) ?></div>
                        <div class="active"><?php _e( "Off", "defender-security" ) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sui-box-settings-row sui-upsell-row">
            <img class="sui-image sui-upsell-image"
                 src="<?php echo wp_defender()->getPluginUrl() . '/assets/img/scanning-free-man.svg' ?>">
            <div class="sui-upsell-notice">
                <p>
					<?php printf( __( "Schedule automated file scanning and email reporting for all your websites. This feature is included in a WPMU DEV membership along with 100+ plugins & themes, 24/7 support and lots of handy site management tools  – <a href='%s'>Try it all FREE today</a>!", "defender-security" ), \WP_Defender\Behavior\Utils::instance()->campaignURL( 'defender_filescanning_reports_upsell_link' ) ) ?>
                </p>
            </div>
        </div>
    </div>
</div>