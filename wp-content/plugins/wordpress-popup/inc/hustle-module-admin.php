<?php
if ( ! class_exists( 'Hustle_Module_Admin' ) ) :

	/**
	 * Class Hustle_Module_Admin
	 */
	class Hustle_Module_Admin {

		const ADMIN_PAGE                  = 'hustle';
		const DASHBOARD_PAGE              = 'hustle_dashboard';
		const POPUP_LISTING_PAGE          = 'hustle_popup_listing';
		const POPUP_WIZARD_PAGE           = 'hustle_popup';
		const SLIDEIN_LISTING_PAGE        = 'hustle_slidein_listing';
		const SLIDEIN_WIZARD_PAGE         = 'hustle_slidein';
		const EMBEDDED_LISTING_PAGE       = 'hustle_embedded_listing';
		const EMBEDDED_WIZARD_PAGE        = 'hustle_embedded';
		const SOCIAL_SHARING_LISTING_PAGE = 'hustle_sshare_listing';
		const SOCIAL_SHARING_WIZARD_PAGE  = 'hustle_sshare';
		const INTEGRATIONS_PAGE           = 'hustle_integrations';
		const ENTRIES_PAGE                = 'hustle_entries';
		const SETTINGS_PAGE               = 'hustle_settings';
		const UPGRADE_MODAL_PARAM         = 'requires-pro';

		/**
		 * Hustle_Module_Admin constructor
		 */
		public function __construct() {

			add_action( 'admin_init', array( $this, 'init' ) );
			add_action( 'current_screen', array( $this, 'set_proper_current_screen' ) );

			$admin_notices = Hustle_Notifications::get_instance();

			if ( $this->_is_admin_module() ) {

				add_action( 'admin_enqueue_scripts', array( $this, 'sui_scripts' ), 99 );
				add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ), 99 );
				add_action( 'admin_print_styles', array( $this, 'register_styles' ) );
				add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 99 );

				// geodirectory plugin compatibility.
				add_action( 'wp_super_duper_widget_init', array( $this, 'geo_directory_compat' ), 10, 2 );

				/**
				 * Add SUI classes to the pages' wrappers.
				 */
				add_filter( 'hustle_sui_wrap_class', array( $this, 'sui_wrap_class' ) );

				// remove Get params for notices.
				add_filter( 'removable_query_args', array( $this, 'remove_notice_params' ) );

				/**
				 * Renders the recommended plugins notice when the conditions are correct.
				 * This is shown when:
				 * -The current version is Free
				 * -The admin isn't logged to WPMU Dev dashboard
				 * -The notice hasn't been dismissed
				 * -The majority of our plugins aren't installed
				 * -A month has passed since the plugin was installed
				 * There are filters to force the display.
				 *
				 * @see https://bitbucket.org/incsub/recommended-plugins-notice/src/master/
				 *
				 * @since 4.2.0
				 */
				if ( Opt_In_Utils::_is_free() ) {
					require_once Opt_In::$plugin_path . 'lib/plugin-notice/notice.php';
					do_action(
						'wpmudev-recommended-plugins-register-notice', // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
						Opt_In::$plugin_base_file,
						__( 'Hustle', 'hustle' ),
						self::get_hustle_admin_pages(),
						array( 'after', '.sui-wrap .sui-header' )
					);
				}
				$admin_notices->add_in_hustle_notices();
			} else {

				$this->handle_non_hustle_pages();
			}

			if ( $this->_is_admin_module() || wp_doing_ajax() ) {
				Hustle_Provider_Autoload::initiate_providers();
			}

			Hustle_Provider_Autoload::load_block_editor();

			if ( wp_doing_ajax() ) {
				$admin_notices->add_ajax_actions();
			}

			add_filter( 'w3tc_save_options', array( $this, 'filter_w3tc_save_options' ), 10, 1 );
			add_filter( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 4 );
			add_filter( 'network_admin_plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 4 );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );

			add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 10, 2 );
		}

		/**
		 * Flags the previous version on upgrade so we can handle notices and modals.
		 * This action runs in the old version of the plugin, not the new one.
		 *
		 * @since 4.2.0
		 *
		 * @param WP_Upgrader $upgrader_object Instance of the WP_Upgrader class.
		 * @param array       $data Upgrade data.
		 */
		public function upgrader_process_complete( $upgrader_object, $data ) {

			if ( 'update' === $data['action'] && 'plugin' === $data['type'] && ! empty( $data['plugins'] ) ) {

				foreach ( $data['plugins'] as $plugin ) {

					// Make sure our plugin is among the ones being updated and set the flag for the previous version.
					if ( Opt_In::$plugin_base_file === $plugin ) {
						update_site_option( 'hustle_previous_version', Opt_In::VERSION );
					}
				}
			}
		}

		/**
		 * Handles the scripts for non-hustle pages.
		 *
		 * @since 4.2.0
		 */
		public function handle_non_hustle_pages() {

			global $pagenow;

			if ( 'index.php' === $pagenow || wp_doing_ajax() ) {

				$analytic_settings = Hustle_Settings_Admin::get_hustle_settings( 'analytics' );
				$analytics_enabled = ! empty( $analytic_settings['enabled'] ) && ! empty( $analytic_settings['modules'] );

				// Only initialize if the analytics are enabled.
				// That's the only use for this class for now.
				if ( $analytics_enabled && current_user_can( 'hustle_analytics' ) ) {
					new Hustle_Wp_Dashboard_Page( $analytic_settings );
				}
			}
		}

		/**
		 * Remove Get parameters for Hustle notices
		 *
		 * @param array $vars
		 * @return array
		 */
		public function remove_notice_params( $vars ) {
			$vars[] = 'show-notice';
			$vars[] = 'notice';
			$vars[] = 'notice-close';

			return $vars;
		}

		/**
		 * Handle SUI wrapper container classes.
		 *
		 * @since 4.0.0
		 * @since 4.1.2 Moved from Hustle_Settings_Page to this class.
		 */
		public function sui_wrap_class( $classes ) {
			if ( is_string( $classes ) ) {
				$classes = array( $classes );
			}
			if ( ! is_array( $classes ) ) {
				$classes = array();
			}
			$classes[] = 'sui-wrap';
			$classes[] = 'sui-wrap-hustle';
			/**
			 * Add high contrast mode.
			 */
			$accessibility = Hustle_Settings_Admin::get_hustle_settings( 'accessibility' );
			$is_high_contrast_mode = !empty( $accessibility['accessibility_color'] );
			if ( $is_high_contrast_mode ) {
				$classes[] = 'sui-color-accessible';
			}
			/**
			 * Set hide branding
			 *
			 * @since 4.0.0
			 */
			$hide_branding = apply_filters( 'wpmudev_branding_hide_branding', false );
			if ( $hide_branding ) {
				$classes[] = 'no-hustle';
			}
			/**
			 * hero image
			 *
			 * @since 4.0.0
			 */
			$image = apply_filters( 'wpmudev_branding_hero_image', 'hustle-default' );
			if ( empty( $image ) ) {
				$classes[] = 'no-hustle-hero';
			}
			return $classes;
		}

		// force reject minify for hustle js and css
		public function filter_w3tc_save_options( $config ) {

			// reject js
			$defined_rejected_js = $config['new_config']->get( 'minify.reject.files.js' );
			$reject_js           = array(
				Opt_In::$plugin_url . 'assets/js/admin.min.js',
				Opt_In::$plugin_url . 'assets/js/ad.js',
				Opt_In::$plugin_url . 'assets/js/front.min.js',
			);
			foreach ( $reject_js as $r_js ) {
				if ( ! in_array( $r_js, $defined_rejected_js, true ) ) {
					array_push( $defined_rejected_js, $r_js );
				}
			}
			$config['new_config']->set( 'minify.reject.files.js', $defined_rejected_js );

			// reject css
			$defined_rejected_css = $config['new_config']->get( 'minify.reject.files.css' );
			$reject_css           = array(
				Opt_In::$plugin_url . 'assets/css/front.min.css',
			);
			foreach ( $reject_css as $r_css ) {
				if ( ! in_array( $r_css, $defined_rejected_css, true ) ) {
					array_push( $defined_rejected_css, $r_css );
				}
			}
			$config['new_config']->set( 'minify.reject.files.css', $defined_rejected_css );

			return $config;
		}

		/**
		 * Inits admin
		 *
		 * @since 3.0
		 */
		public function init() {
			$this->add_privacy_message();
		}

		/**
		 * Register scripts for the admin page
		 *
		 * @since 1.0
		 */
		public function register_scripts( $page_slug ) {

			// add_filter( 'script_loader_tag', array( $this, 'handle_specific_script' ), 10, 2 );
			// add_filter( 'style_loader_tag', array( $this, 'handle_specific_style' ), 10, 2 );

			$optin_vars = array(
				'module_page' => array(
					'popup'          => self::POPUP_LISTING_PAGE,
					'slidein'        => self::SLIDEIN_LISTING_PAGE,
					'embedded'       => self::EMBEDDED_LISTING_PAGE,
					'social_sharing' => self::SOCIAL_SHARING_LISTING_PAGE,
				),
				'messages'    => array(
					'dont_navigate_away'          => __( 'Changes are not saved, are you sure you want to navigate away?', 'hustle' ), // loaded everywhere but only used in wizards maybe?
					'something_went_wrong'        => __( 'Something went wrong. Please try again', 'hustle' ), // everywhere.
					'something_went_wrong_reload' => '<label class="wpmudev-label--notice"><span>' . __( 'Something went wrong. Please reload this page and try again.', 'hustle' ) . '</span></label>', // everywhere.
					'aweber_migration_success'    => sprintf( esc_html__( '%s integration successfully migrated to the oAuth 2.0.', 'hustle' ), '<strong>' . esc_html__( 'Aweber', 'hustle' ) . '</strong>' ), // everywhere. views.js.
					'integraiton_required'        => '<label class="wpmudev-label--notice"><span>' . __( 'An integration is required on opt-in module.', 'hustle' ) . '</span></label>', // wizard and integrations.
					'module_deleted'              => __( 'Module successfully deleted.', 'hustle' ), // listing and dashboard.
					'shortcode_copied'            => __( 'Shortcode copied successfully.', 'hustle' ), // listing and dashboard.
					'commons'                     => array(
						'published' => __( 'Published', 'hustle' ), // dashboard and wizard.
						'draft'     => __( 'Draft', 'hustle' ), // dashboard and wizard.
						'dismiss'   => __( 'Dismiss', 'hustle' ), // everywhere, views.js.
					),
				),
			);

			$optin_vars['urlParams'] = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification

			/**
			 * The variables specific to each page are added via this hook.
			 */
			$optin_vars = apply_filters( 'hustle_optin_vars', $optin_vars );

			$url_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'debug' : 'min';
			wp_register_script(
				'optin_admin_scripts',
				Opt_In::$plugin_url . 'assets/js/admin.' . $url_suffix . '.js',
				array( 'jquery', 'backbone', 'jquery-effects-core' ),
				Opt_In::VERSION,
				true
			);
			wp_localize_script( 'optin_admin_scripts', 'optinVars', $optin_vars );
			wp_enqueue_script( 'optin_admin_scripts' );
		}

		/**
		 * Register shared-ui scripts
		 *
		 * @since 4.0.0
		 */
		public function sui_scripts() {

			$sanitize_version = str_replace( '.', '-', HUSTLE_SUI_VERSION );
			$sui_body_class   = "sui-$sanitize_version";

			wp_enqueue_script(
				'shared-ui',
				Opt_In::$plugin_url . 'assets/js/shared-ui.min.js',
				array( 'jquery' ),
				$sui_body_class,
				true
			);
		}

		/**
		 * Determine what admin section for Pop-up module
		 *
		 * @since 3.0.0.
		 *
		 * @param boolean/string $default Default value.
		 *
		 * @return mixed, string or boolean
		 */
		public static function get_current_section( $default = false ) {
			$section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_STRING );
			return ( is_null( $section ) || empty( $section ) )
			? $default
			: $section;
		}

		/**
		 * Handling specific scripts for each scenario
		 */
		// public function handle_specific_script( $tag, $handle ) {
		// if ( 'optin_admin_fitie' === $handle ) {
		// $tag = "<!--[if IE]>$tag<![endif]-->";
		// }
		// return $tag;
		// }

		/**
		 * Handling specific style for each scenario
		 */
		// public function handle_specific_style( $tag, $handle ) {
		// if ( 'hustle_admin_ie' === $handle ) {
		// $tag = '<!--[if IE]>'. $tag .'<![endif]-->';
		// }
		// return $tag;
		// }

		public function set_proper_current_screen( $current ) {
			global $current_screen;
			if ( ! Opt_In_Utils::_is_free() ) {
				$current_screen->id = Opt_In_Utils::clean_current_screen( $current_screen->id );
			}
		}

		/**
		 * Registers styles for the admin
		 */
		public function register_styles( $page_slug ) {

			$sanitize_version = str_replace( '.', '-', HUSTLE_SUI_VERSION );
			$sui_body_class   = "sui-$sanitize_version";

			wp_enqueue_style( 'thickbox' );

			wp_register_style(
				'hstl-roboto',
				'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i',
				array(),
				Opt_In::VERSION
			);
			wp_register_style(
				'hstl-opensans',
				'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i',
				array(),
				Opt_In::VERSION
			);
			wp_register_style(
				'hstl-source',
				'https://fonts.googleapis.com/css?family=Source+Code+Pro',
				array(),
				Opt_In::VERSION
			);

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'wdev_ui' );
			wp_enqueue_style( 'wdev_notice' );
			wp_enqueue_style( 'hstl-roboto' );
			wp_enqueue_style( 'hstl-opensans' );
			wp_enqueue_style( 'hstl-source' );

			wp_enqueue_style(
				'sui_styles',
				Opt_In::$plugin_url . 'assets/css/shared-ui.min.css',
				array(),
				$sui_body_class
			);
		}


		/**
		 * Checks if it's module admin page
		 *
		 * @return bool
		 */
		private function _is_admin_module() {
			$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
			return in_array(
				$page,
				array(
					self::ADMIN_PAGE,
					self::DASHBOARD_PAGE,
					self::POPUP_LISTING_PAGE,
					self::POPUP_WIZARD_PAGE,
					self::SLIDEIN_LISTING_PAGE,
					self::SLIDEIN_WIZARD_PAGE,
					self::EMBEDDED_LISTING_PAGE,
					self::EMBEDDED_WIZARD_PAGE,
					self::SOCIAL_SHARING_LISTING_PAGE,
					self::SOCIAL_SHARING_WIZARD_PAGE,
					self::INTEGRATIONS_PAGE,
					self::ENTRIES_PAGE,
					self::SETTINGS_PAGE,
				),
				true
			);

		}

		/**
		 * Return an array with the slugs of the admin pages
		 *
		 * @since 4.1.1
		 * @return array
		 */
		public static function get_hustle_admin_pages() {
			return array(
				'toplevel_page_hustle',
				'hustle_page_' . self::ADMIN_PAGE,
				'hustle_page_' . self::DASHBOARD_PAGE,
				'hustle_page_' . self::POPUP_LISTING_PAGE,
				'hustle_page_' . self::POPUP_WIZARD_PAGE,
				'hustle_page_' . self::SLIDEIN_LISTING_PAGE,
				'hustle_page_' . self::SLIDEIN_WIZARD_PAGE,
				'hustle_page_' . self::EMBEDDED_LISTING_PAGE,
				'hustle_page_' . self::EMBEDDED_WIZARD_PAGE,
				'hustle_page_' . self::SOCIAL_SHARING_LISTING_PAGE,
				'hustle_page_' . self::SOCIAL_SHARING_WIZARD_PAGE,
				'hustle_page_' . self::INTEGRATIONS_PAGE,
				'hustle_page_' . self::ENTRIES_PAGE,
				'hustle_page_' . self::SETTINGS_PAGE,
			);
		}

		/**
		 * Modify admin body class to our own advantage!
		 *
		 * @param $classes
		 * @return mixed
		 */
		public function admin_body_class( $classes ) {

			$sanitize_version = str_replace( '.', '-', HUSTLE_SUI_VERSION );
			$sui_body_class   = "sui-$sanitize_version";

			$screen = get_current_screen();

			$classes = '';

			// Do nothing if not a hustle page
			if ( strpos( $screen->base, '_page_hustle' ) === false ) {
				return $classes;
			}

			$classes .= $sui_body_class;

			return $classes;

		}

		/**
		 * Add Privacy Messages
		 *
		 * @since 3.0.6
		 */
		public function add_privacy_message() {
			if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
				$external_integrations_list             = '';
				$external_integrations_privacy_url_list = '';
				$params                                 = array(
					'external_integrations_list' => apply_filters( 'hustle_privacy_external_integrations_list', $external_integrations_list ),
					'external_integrations_privacy_url_list' => apply_filters( 'hustle_privacy_url_external_integrations_list', $external_integrations_privacy_url_list ),
				);
				// TODO: get the name from a variable instead.
				$renderer = new Hustle_Layout_Helper();
				$content  = $renderer->render( 'general/policy-text', $params, true );
				wp_add_privacy_policy_content( 'Hustle', wp_kses_post( $content ) );
			}
		}

		/**
		 * Adds custom links on plugin page
		 */
		public function add_plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
			static $plugin;

			if ( ! isset( $plugin ) ) {
				$plugin = Opt_In::$plugin_base_file; }

			if ( $plugin === $plugin_file ) {
				if ( is_network_admin() ) {
					$admin_url = network_admin_url( 'admin.php' );
				} else {
					$admin_url = admin_url( 'admin.php' );
				}
				$settings_url = add_query_arg( 'page', 'hustle_settings', $admin_url );
				$links        = array(
					'settings' => '<a href="' . $settings_url . '">' . esc_html__( 'Settings', 'hustle' ) . '</a>',
					'docs'     => '<a href="https://premium.wpmudev.org/project/hustle/#wpmud-hg-project-documentation?utm_source=hustle&utm_medium=plugin&utm_campaign=hustle_pluginlist_docs" target="_blank">' . esc_html__( 'Docs', 'hustle' ) . '</a>',
				);

				// Upgrade link.
				if ( Opt_In_Utils::_is_free() ) {
					if ( ! lib3()->is_member() ) {
						$url = 'https://premium.wpmudev.org/?utm_source=hustle&utm_medium=plugin&utm_campaign=hustle_pluginlist_upgrade';
					} else {
						$url = lib3()->get_link( 'hustle', 'install_plugin', '' );
					}
					if ( is_network_admin() || ! is_multisite() ) {
						$links['upgrade'] = '<a href="' . esc_url( $url ) . '" aria-label="' . esc_attr( __( 'Upgrade to Hustle Pro', 'hustle' ) ) . '" target="_blank" style="color: #8D00B1;">' . esc_html__( 'Upgrade', 'hustle' ) . '</a>';
					}
				} else {
					if ( ! lib3()->is_member() ) {
						$links['renew'] = '<a href="https://premium.wpmudev.org/?utm_source=hustle&utm_medium=plugin&utm_campaign=hustle_pluginlist_renew" target="_blank" style="color: #8D00B1;">' . esc_html__( 'Renew Membership', 'hustle' ) . '</a>';
					}
				}

				// Display only on site's plugins page, not network's.
				if ( current_user_can( 'activate_plugins' ) && ( ! is_network_admin() || ! is_multisite() ) ) {

					$migration      = Hustle_Migration::get_instance();
					$has_404_backup = $migration->migration_410->is_backup_created();

					// Add a "Rollback to 404" link if we have its backup.
					if ( $has_404_backup ) {
						$args    = array(
							'page'                => self::SETTINGS_PAGE,
							'404-downgrade-modal' => 'true',
						);
						$url     = add_query_arg( $args, 'admin.php' );
						$version = Opt_In_Utils::_is_free() ? 'v7.0.4' : 'v4.0.4';

						$links['rollback_404'] = '<a href="' . esc_url_raw( $url ) . '">' . sprintf( esc_html( 'Rollback to %s', 'hustle' ), $version ) . '</a>';
					}
					$actions = array_merge( $links, $actions );
				}
			}

			return $actions;
		}

		/**
		 * Links next to version number
		 *
		 * @param array  $plugin_meta
		 * @param string $plugin_file
		 * @return array
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( Opt_In::$plugin_base_file === $plugin_file ) {
				if ( Opt_In_Utils::_is_free() ) {
					$row_meta = array(
						'rate'    => '<a href="https://wordpress.org/support/plugin/wordpress-popup/reviews/#new-post" target="_blank">' . esc_html__( 'Rate Hustle', 'hustle' ) . '</a>',
						'support' => '<a href="https://wordpress.org/support/plugin/wordpress-popup/" target="_blank">' . esc_html__( 'Support', 'hustle' ) . '</a>',
					);
				} else {
					$row_meta = array(
						'support' => '<a href="https://premium.wpmudev.org/hub/support/#wpmud-chat-pre-survey-modal" target="_blank">' . esc_html__( 'Premium Support', 'hustle' ) . '</a>',
					);
				}

				$row_meta['roadmap'] = '<a href="https://premium.wpmudev.org/roadmap/" target="_blank">' . esc_html__( 'Roadmap', 'hustle' ) . '</a>';

				$plugin_meta = array_merge( $plugin_meta, $row_meta );
			}

			return $plugin_meta;
		}

		/**
		 * Get the listing page by the module type.
		 *
		 * @since 4.0
		 *
		 * @param string $module_type
		 * @return string
		 */
		public static function get_listing_page_by_module_type( $module_type ) {

			switch ( $module_type ) {
				case Hustle_Module_Model::POPUP_MODULE:
					return self::POPUP_LISTING_PAGE;

				case Hustle_Module_Model::SLIDEIN_MODULE:
					return self::SLIDEIN_LISTING_PAGE;

				case Hustle_Module_Model::EMBEDDED_MODULE:
					return self::EMBEDDED_LISTING_PAGE;

				case Hustle_Module_Model::SOCIAL_SHARING_MODULE:
					return self::SOCIAL_SHARING_LISTING_PAGE;

				default:
					return self::POPUP_LISTING_PAGE;
			}
		}

		/**
		 * Get the wizard page by the module type.
		 *
		 * @since 4.0
		 *
		 * @param string $module_type
		 * @return string
		 */
		public static function get_wizard_page_by_module_type( $module_type ) {

			switch ( $module_type ) {
				case Hustle_Module_Model::POPUP_MODULE:
					return self::POPUP_WIZARD_PAGE;

				case Hustle_Module_Model::SLIDEIN_MODULE:
					return self::SLIDEIN_WIZARD_PAGE;

				case Hustle_Module_Model::EMBEDDED_MODULE:
					return self::EMBEDDED_WIZARD_PAGE;

				case Hustle_Module_Model::SOCIAL_SHARING_MODULE:
					return self::SOCIAL_SHARING_WIZARD_PAGE;

				default:
					return self::POPUP_WIZARD_PAGE;
			}
		}

		/**
		 * Check whether a new module of this type can be created.
		 * If it's free and there's already 3 modules of this type, then it's a nope.
		 *
		 * @since 4.0
		 *
		 * @param string $module_type
		 * @return boolean
		 */
		public static function can_create_new_module( $module_type ) {

			// If it's Pro, the sky's the limit.
			if ( ! Opt_In_Utils::_is_free() ) {
				return true;
			}

			// Check the Module's type is valid.
			if ( ! in_array( $module_type, Hustle_Module_Model::get_module_types(), true ) ) {
				return false;
			}

			$collection_args = array(
				'module_type' => $module_type,
				'count_only'  => true,
			);
			$total_modules   = Hustle_Module_Collection::instance()->get_all( null, $collection_args );

			// If we have less than 3 modules of this type, can create another one.
			if ( $total_modules >= 3 ) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * Geodirectory compatibility issues.
		 *
		 * @since 4.0.1
		 *
		 * @param array  $options
		 * @param object $class WP_Super_Duper class instance
		 */
		public function geo_directory_compat( $options, $class ) {
			remove_action( 'media_buttons', array( $class, 'shortcode_insert_button' ) );
		}

	}

endif;
