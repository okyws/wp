<?php
/**
 * Displays the listing page.
 *
 * @uses ../../dialogs/migrate-dismiss-confirmation.php
 * @uses ../../notices/notice-non-exists.php
 * @uses ../../footer/footer.php
 * @uses ../dialogs/create-module.php
 * @uses ../dialogs/import-module.php
 * @uses ../dialogs/delete-module.php
 * @uses ../dialogs/manage-tracking.php
 * @uses ../dialogs/tracking-reset-data.php
 * @uses ../dialogs/pro-upgrade.php
 * @uses ../dialogs/preview-dialog.php
 * @uses ./summary.php
 * @uses ./pagination.php
 * @uses ./module.php
 * @uses ./empty-message.php
 *
 * @package Hustle
 * @since 4.0.0
 */

if ( isset( $page_title ) ) {
	$page_title = $page_title;
} else {
	$page_title = esc_html__( 'Module', 'hustle' );
}
$sql_month_start_date = date( 'Y-m-d H:i:s', strtotime( '-30 days midnight' ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
$tracking_model       = Hustle_Tracking_Model::get_instance();
$free_limit_reached   = ! Hustle_Module_Admin::can_create_new_module( $module_type );
?>

<div class="sui-header">

	<h1 class="sui-title"><?php echo esc_html( $page_title ); ?></h1>

	<?php if ( 0 < $total && $capability['hustle_create'] ) { ?>

		<div class="sui-actions-left">

			<button
				id="hustle-create-new-module"
				class="sui-button sui-button-blue hustle-create-module"
				<?php
				if ( $free_limit_reached ) {
					echo 'data-enabled="false"';}
				?>
			>
				<span class="sui-icon-plus" aria-hidden="true"></span> <?php esc_html_e( 'Create', 'hustle' ); ?>
			</button>

			<button
				class="sui-button hustle-import-module-button"
				<?php
				if ( $free_limit_reached ) {
					echo 'data-enabled="false"';}
				?>
			>
				<span class="sui-icon-upload-cloud" aria-hidden="true"></span> <?php esc_html_e( 'Import', 'hustle' ); ?>
			</button>

		</div>

	<?php } ?>

	<div class="sui-actions-right">

		<?php if ( false && 0 < count( $modules ) ) { ?>

			<div class="hui-reporting-period">

				<label><?php esc_html_e( 'Reporting Period', 'hustle' ); ?></label>

				<select>
					<option value="7"><?php esc_html_e( 'Last 7 days', 'hustle' ); ?></option>
					<option value="15"><?php esc_html_e( 'Last 15 days', 'hustle' ); ?></option>
					<option value="30" selected><?php esc_html_e( 'Last 30 days', 'hustle' ); ?></option>
				</select>

			</div>

		<?php } ?>

		<?php
		$hide = true; // apply_filters( 'wpmudev_branding_hide_doc_link', false ); Waiting for the docs to be completed.
		if ( ! $hide ) {
			?>
				<button class="sui-button sui-button-ghost">
					<span class="sui-icon-academy" aria-hidden="true"></span> <?php esc_html_e( 'View Documentation', 'hustle' ); ?>
				</button>
		<?php } ?>

	</div>

</div>

<div id="hustle-floating-notifications-wrapper" class="sui-floating-notices"></div>

<?php if ( 0 < count( $modules ) ) { ?>

	<?php
	// ELEMENT: Summary.
	$this->render(
		'admin/commons/sui-listing/elements/summary',
		array(
			'active_modules_count' => $active,
			'capitalize_singular'  => $capitalize_singular,
			'capitalize_plural'    => $capitalize_plural,
			'latest_entry_time'    => Opt_In_Utils::get_latest_conversion_time( $module_type ),
			'latest_entries_count' => $tracking_model->count_newer_conversions_by_module_type( $module_type, $sql_month_start_date ),
			'sui'                  => $sui,
		)
	);
	?>

	<?php
	// ELEMENT: Pagination.
	$this->render(
		'admin/commons/sui-listing/elements/pagination',
		array(
			'module_type'      => $module_type,
			'items'            => $modules,
			'total'            => $total,
			'entries_per_page' => $entries_per_page,
		)
	);
	?>

	<div class="hustle-list sui-accordion sui-accordion-block">

		<?php
		foreach ( $modules as $key => $module ) {
			?>

			<?php
			// ELEMENT: Modules.
			$this->render(
				'admin/commons/sui-listing/elements/module',
				array(
					'module'              => $module,
					'module_type'         => $module_type,
					'smallcaps_singular'  => $smallcaps_singular,
					'capitalize_singular' => $capitalize_singular,
					'capability'          => $capability,
					'tracking_types'      => $module->get_tracking_types(),
					'can_create'          => ! $free_limit_reached,
				)
			);
			?>

		<?php } ?>

	</div>

	<?php
	// ELEMENT: Pagination.
	echo '<div style="margin-top: 20px;">'; // Spacing correction.

		$this->render(
			'admin/commons/sui-listing/elements/pagination',
			array(
				'module_type'      => $module_type,
				'items'            => $modules,
				'total'            => $total,
				'entries_per_page' => $entries_per_page,
				'is_bottom'        => true,
			)
		);

	echo '</div>';
	?>

<?php } else { ?>

	<?php
	// ELEMENT: Empty Message.
	$this->render(
		'admin/commons/sui-listing/elements/empty-message',
		array(
			'count'      => $total,
			'is_free'    => $is_free,
			'capability' => $capability,
			'message'    => $page_message,
		)
	);
}

// ELEMENT: Footer.
$this->render( 'admin/footer/footer' );

// DIALOG: Create module.
$this->render(
	'admin/commons/sui-listing/dialogs/create-module',
	array(
		'module_type'         => $module_type,
		'capitalize_singular' => $capitalize_singular,
		'smallcaps_singular'  => $smallcaps_singular,
	)
);

// DIALOG: Import module.
$this->render(
	'admin/commons/sui-listing/dialogs/import-module',
	array(
		'capitalize_singular' => $capitalize_singular,
		'smallcaps_singular'  => $smallcaps_singular,
		'module_type'         => $module_type,
		'metas_optin'         => Hustle_Module_Model::instance()->get_module_meta_names( $module_type, Hustle_Module_Model::OPTIN_MODE, true ),
		'metas_info'          => Hustle_Module_Model::instance()->get_module_meta_names( $module_type, Hustle_Module_Model::INFORMATIONAL_MODE, true ),
	)
);

// DIALOG: Delete module.
$this->render(
	'admin/commons/sui-listing/dialogs/delete-module',
	array()
);

// DIALOG: Manage tracking.
if ( isset( $multiple_charts ) ) {

	$this->render(
		'admin/commons/sui-listing/dialogs/manage-tracking',
		array(
			'multiple_charts' => isset( $multiple_charts ) ? $multiple_charts : false,
		)
	);
}

/**
 * DIALOG: Reset Tracking Data Confirmation.
 */
$this->render( 'admin/commons/sui-listing/dialogs/tracking-reset-data' );

// DIALOG: Ugrade to pro.
if ( Opt_In_Utils::_is_free() ) {
	$this->render( 'admin/commons/sui-listing/dialogs/pro-upgrade' );
}

// DIALOG: Preview.
// If embedded, show the preview dialog to embed the module into.
if ( Hustle_Module_Model::EMBEDDED_MODULE === $module_type ) {
	$this->render( 'admin/dialogs/preview-dialog' );
}

// DIALOG: Dissmiss migrate tracking notice modal confirmation.
if ( Hustle_Notifications::is_show_migrate_tracking_notice() ) {
	$this->render( 'admin/dialogs/migrate-dismiss-confirmation' );
}
?>
