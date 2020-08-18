<section class="wpmudev-dashboard-section">

	<?php $this->template( 'dashboard/widgets/widget-resume' ); ?>

	<div class="fui-row fui-row-dynamic">

		<?php $this->template( 'dashboard/widgets/widget-cform' ); ?>

		<?php if ( ! FORMINATOR_PRO ) {
            $this->template( 'dashboard/widgets/widget-upgrade' );
		} ?>

		<?php $this->template( 'dashboard/widgets/widget-quiz' ); ?>

		<?php $this->template( 'dashboard/widgets/widget-poll' ); ?>

		<?php
		$notice_dismissed = get_option( 'forminator_dismiss_feature_113', false );
		$version_upgraded = get_option( 'forminator_version_upgraded', false );

		if ( ! $notice_dismissed && $version_upgraded ) { ?>

			<?php $this->template( 'dashboard/new-feature-notice' ); ?>

		<?php } ?>

	</div>

</section>
