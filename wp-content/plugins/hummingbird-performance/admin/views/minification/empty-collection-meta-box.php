<?php
/**
 * Asset optimization empty collection meta box.
 * Will be used when the scan completed but wphb_styles_collection and wphb_scripts_collection are empty.
 *
 * @since 2.5.0
 * @package Hummingbird
 */

use Hummingbird\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wphb-minification-files">
	<div class="wphb-minification-files-header">
		<p>
			<?php esc_html_e( 'Choose which files you wish to compress and then publish your changes.', 'wphb' ); ?>
		</p>
	</div>

	<div class="wphb-minification-files-table wphb-minification-files-basic">
		<div class="sui-notice sui-notice-info">
			<p>
				<?php
				printf(
					/* translators: %1$s - <a>, %2$s - </a> */
					esc_html__( "We've completed the file check but haven't been able to load the files. Please try clearing your object cache, refresh the page and wait a few seconds to load the files, or visit your homepage to trigger the file list to show. If you continue having problems, please %1\$sopen a ticket%2\$s with our support team.", 'wphb' ),
					'<a href="' . esc_url( Utils::get_link( 'support' ) ) . '" target="_blank">',
					'</a>'
				);
				?>
			</p>

			<div class="sui-notice-buttons">
				<a href="<?php echo esc_url( site_url() ); ?>" class="sui-button sui-button-blue" target="_blank">
					<?php esc_html_e( 'Visit homepage', 'wphb' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
