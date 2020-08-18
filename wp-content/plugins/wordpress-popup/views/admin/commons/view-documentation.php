<?php
/**
 * Title section.
 *
 * @package Hustle
 * @since 4.0.0
 */

// Waiting for the docs to be completed.
// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
$hide = true; // apply_filters( 'wpmudev_branding_hide_doc_link', false );.
if ( ! $hide ) {
	?>
<div class="sui-actions-right">
	<button class="sui-button sui-button-ghost"><i class="sui-icon-academy"></i> <?php esc_html_e( 'View Documentation', 'hustle' ); ?></button>
</div>
<?php } ?>
