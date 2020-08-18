/* global mixpanel */

import Fetcher from '../utils/fetcher';

( function( $ ) {
	WPHB_Admin.dashboard = {
		module: 'dashboard',

		init() {
			if ( wphbDashboardStrings ) this.strings = wphbDashboardStrings;

			$( '.wphb-performance-report-item' ).click( function() {
				const url = $( this ).data( 'performance-url' );
				if ( url ) {
					location.href = url;
				}
			} );

			$( '#dismiss-cf-notice' ).click( function( e ) {
				e.preventDefault();
				Fetcher.common.call( 'wphb_cf_notice_dismiss' );
				const cloudFlareDashNotice = $( '.cf-dash-notice' );
				cloudFlareDashNotice.slideUp();
				cloudFlareDashNotice.parent().addClass( 'no-background-image' );
			} );

			return this;
		},

		/**
		 * Skip quick setup.
		 */
		skipSetup() {
			Fetcher.common.call( 'wphb_dash_skip_setup' ).then( () => {
				window.location.reload();
			} );
		},

		/**
		 * Run performance test after quick setup.
		 */
		runPerformanceTest() {
			window.SUI.closeModal(); // Hide tracking-modal.
			// Show performance test modal
			window.SUI.openModal(
				'run-performance-onboard-modal',
				'wpbody-content',
				undefined,
				false
			);

			window.WPHB_Admin.Tracking.track( 'plugin_scan_started', {
				score_mobile_previous: wphbPerformanceStrings.previousScoreMobile,
				score_desktop_previous: wphbPerformanceStrings.previousScoreDesktop,
			} );

			this.skipSetup();

			// Run performance test
			window.WPHB_Admin.getModule( 'performance' ).performanceTest(
				this.strings.finishedTestURLsLink
			);
		},
	};
} )( jQuery );
