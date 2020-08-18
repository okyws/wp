<?php
$table = new \WP_Defender\Module\Scan\Component\Result_Table();
$table->prepare_items();
?>
<div class="sui-box">
    <div class="sui-box-header">
        <!-- Box title with icon -->
        <h3 class="sui-box-title">
			<?php _e( "Issues", "defender-security" ) ?>
        </h3>

		<?php if ( $table->get_pagination_arg( 'total_items' ) > 0 ): ?>
            <div class="sui-actions-right">
                <div class="box-filter">
                <span>
                    <?php _e( "Type", "defender-security" ) ?>
                </span>
                    <select id="type-filter" class="sui-select-sm">
                        <option
							<?php selected( false, \Hammer\Helper\HTTP_Helper::retrieve_get( 'type' ) ) ?>
                                value=""><?php _e( "All", "defender-security" ) ?></option>
                        <option <?php selected( 'core', \Hammer\Helper\HTTP_Helper::retrieve_get( 'type' ) ) ?>
                                value="core">
							<?php _e( "Core", "defender-security" ) ?></option>
                        <option
							<?php selected( 'vuln', \Hammer\Helper\HTTP_Helper::retrieve_get( 'type' ) ) ?>
                                value="vuln"><?php _e( "Plugins/Themes Vulnerability", "defender-security" ) ?></option>
                        <option
							<?php selected( 'content', \Hammer\Helper\HTTP_Helper::retrieve_get( 'type' ) ) ?>
                                value="content"><?php _e( "Suspicious code", "defender-security" ) ?></option>
                    </select>
                </div>
            </div>
		<?php endif; ?>
    </div>
    <div class="sui-box-body">
        <p>
			<?php _e( "Here’s a list of potentially harmful files Defender thinks could be suspicious. In a lot of cases the scan will pick up harmless files, but in some cases you may wish to remove files that look suspicious.", "defender-security" ) ?>
        </p>
		<?php
		if ( $table->get_pagination_arg( 'total_items' ) > 0 ) {
			$table->display();
		} else {
			?>
            <div class="sui-notice sui-notice-success">
                <p>
					<?php _e( "Your code is currently clean! There were no issues found during the last scan, though you can always perform a new scan anytime.", "defender-security" ) ?>
                </p>
            </div>
			<?php
		}
		?>
    </div>
</div>