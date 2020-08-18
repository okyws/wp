<?php
/**
 * File for Hustle_Admin_Page_Abstract class.
 *
 * @package Hustle
 * @since 4.0.1
 */

if ( ! class_exists( 'Hustle_Admin_Page_Abstract' ) ) :
	/**
	 * Class Hustle_Admin_Page_Abstract.
	 * This is the base class for all Hustle's pages.
	 *
	 * @since 4.0.1
	 */
	abstract class Hustle_Admin_Page_Abstract {

		/**
		 * Page slug defined by us.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page;

		/**
		 * Template path for the page relative to the 'views' folder.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page_template_path;

		/**
		 * Page title.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page_title;

		/**
		 * Page title for the WordPress menu.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page_menu_title;

		/**
		 * Required capability for the page to be available.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page_capability;

		/**
		 * The current page that's being requested.
		 *
		 * @since 4.0.2
		 * @var string|bool
		 */
		protected $current_page;

		/**
		 * Page slug defined by WordPress when registering the page.
		 *
		 * @since 4.0.0
		 * @var string
		 */
		protected $page_slug;

		/**
		 * Instance of Hustle_Layout_Helper
		 *
		 * @since 4.2.0
		 * @var Hustle_Layout_Helper
		 */
		private $renderer;

		/**
		 * Class constructor.
		 *
		 * @since 4.0.1
		 */
		public function __construct() {

			$this->current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

			$this->init();

			add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		}

		/**
		 * Initiate the page's properties
		 * Should be overridden by each page.
		 *
		 * @since 4.0.1
		 */
		abstract protected function init();

		/**
		 * Register the js variables to be localized for this page.
		 * To be overridden.
		 *
		 * @since 4.0.4
		 *
		 * @param array $current_array The already registered js variables.
		 * @return array
		 */
		public function register_current_json( $current_array ) {
			return $current_array;
		}

		/**
		 * Register the admin menus.
		 *
		 * @since 4.0.1
		 */
		public function register_admin_menu() {

			$this->page_slug = add_submenu_page( 'hustle', $this->page_title, $this->page_menu_title, $this->page_capability, $this->page, array( $this, 'render_main_page' ) );

			add_action( 'load-' . $this->page_slug, array( $this, 'current_page_loaded' ) );
		}

		/**
		 * Gets an instance of the renderer class.
		 *
		 * @since 4.2.1
		 * @return Hustle_Layout_Helper
		 */
		protected function get_renderer() {
			if ( ! $this->renderer ) {
				$this->renderer = new Hustle_Layout_Helper( $this );
			}
			return $this->renderer;
		}

		/**
		 * Render the main page
		 *
		 * @since 4.0.1
		 */
		public function render_main_page() {

			$main_class = implode( ' ', apply_filters( 'hustle_sui_wrap_class', null ) );
			?>
			<main class="<?php echo esc_attr( $main_class ); ?>">

				<?php
				$template_args = $this->get_page_template_args();
				$renderer      = $this->get_renderer();
				$renderer->render( $this->page_template_path, $template_args );
				?>

			</main>
			<?php
		}

		/**
		 * Perform actions during the 'load-{page}' hook.
		 *
		 * @since 4.0.4
		 */
		public function current_page_loaded() {

			// Register variables for the js side only if this is the requested page.
			add_filter( 'hustle_optin_vars', array( $this, 'register_current_json' ) );

			$this->run_action_on_page_load();
		}

		/**
		 * Method called when the action 'load-' . $this->page_slug runs.
		 *
		 * @since 4.0.0
		 * @since 4.2.0 Visibility changed from public to protected
		 */
		protected function run_action_on_page_load() {}

		/**
		 * Print forminator scripts for preview.
		 * Used by Dashboard, Wizards, and Listings.
		 *
		 * @since 4.0.1
		 */
		public function maybe_print_forminator_scripts() {

			// Add Forminator's front styles and scripts for preview.
			if ( defined( 'FORMINATOR_VERSION' ) ) {
				forminator_print_front_styles( FORMINATOR_VERSION );
				forminator_print_front_scripts( FORMINATOR_VERSION );

			}
		}

		/**
		 * Loads the styles and scripts required for previewing modules.
		 * Used by Dashboard, Wizards, and Listings.
		 *
		 * @since 4.2.0
		 */
		protected function load_preview_scripts() {

			// TODO: We could load only the required front styles instead of all of them for listing and wizards.
			add_action( 'admin_print_styles', array( 'Hustle_Module_Front', 'print_front_styles' ) );
			add_action( 'admin_enqueue_scripts', array( 'Hustle_Module_Front', 'add_hui_scripts' ) );
			add_action( 'admin_footer', array( $this, 'maybe_print_forminator_scripts' ) );
		}

		/**
		 * Exports a single module.
		 * Used by Dashboard and Listing.
		 *
		 * @since 4.0.0
		 * @since 4.2.0 Moved from Hustle_Modules_Common_Admin to this class.
		 */
		protected function export_module() {

			$nonce = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $nonce, 'hustle_module_export' ) ) {
				return;
			}
			$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
			if ( ! $id ) {
				return;
			}
			// Plugin data.
			$plugin = get_plugin_data( WP_PLUGIN_DIR . '/' . Opt_In::$plugin_base_file );

			// Get module.
			$module = Hustle_Module_Model::instance()->get( $id );
			if ( is_wp_error( $module ) ) {
				return;
			}

			// Export data.
			$settings = array(
				'plugin'     => array(
					'name'    => $plugin['Name'],
					'version' => Opt_In::VERSION,
					'network' => $plugin['Network'],
				),
				'timestamp'  => time(),
				'attributes' => $module->get_attributes(),
				'data'       => $module->get_data(),
				'meta'       => array(),
			);

			if ( 'optin' === $module->module_mode ) {
				$integrations = array();
				$providers    = Hustle_Providers::get_instance()->get_providers();
				foreach ( $providers as $slug => $provider ) {
					$provider_data = $module->get_provider_settings( $slug, false );
					if ( $provider_data && $provider->is_connected()
							&& $provider->is_form_connected( $id ) ) {
						$integrations[ $slug ] = $provider_data;
					}
				}

				$settings['meta']['integrations'] = $integrations;
			}

			$meta_names = $module->get_module_meta_names();
			foreach ( $meta_names as $meta_key ) {
				$settings['meta'][ $meta_key ] = json_decode( $module->get_meta( $meta_key ) );
			}
			/**
			 * Filename
			 */
			$filename = sprintf(
				'hustle-%s-%s-%s-%s.json',
				$module->module_type,
				gmdate( 'Ymd-his' ),
				get_bloginfo( 'name' ),
				$module->module_name
			);
			$filename = strtolower( $filename );
			$filename = sanitize_file_name( $filename );
			/**
			 * Print HTTP headers
			 */
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: application/bin; charset=' . get_option( 'blog_charset' ), true );
			/**
			 * Check PHP version, for PHP < 3 do not add options
			 */
			$version = phpversion();
			$compare = version_compare( $version, '5.3', '<' );
			if ( $compare ) {
				echo wp_json_encode( $settings );
				exit;
			}
			$option = defined( 'JSON_PRETTY_PRINT' ) ? JSON_PRETTY_PRINT : null;
			echo wp_json_encode( $settings, $option );
			exit;
		}

		/**
		 * Filter related to TinyMCE
		 * Used by Settings and Wizard pages.
		 *
		 * @since 4.2.0 Moved from Hustle_Module_Admin to this class.
		 */
		protected function set_up_tinymce() {

			add_filter( 'tiny_mce_before_init', array( $this, 'set_tinymce_settings' ), 10, 2 );
			add_filter( 'wp_default_editor', array( $this, 'set_editor_to_tinymce' ) );
			add_filter( 'tiny_mce_plugins', array( $this, 'remove_despised_editor_plugins' ) );
		}

		/**
		 * Modify tinymce editor settings.
		 *
		 * @param array  $settings Registered settings.
		 * @param string $editor_id Current editor ID.
		 */
		public function set_tinymce_settings( $settings, $editor_id ) {
			$settings['paste_as_text'] = 'true';

			return $settings;
		}

		/**
		 * Sets default editor to tinymce for opt-in admin
		 *
		 * @param string $editor_type Current editor type.
		 * @return string
		 */
		public function set_editor_to_tinymce( $editor_type ) {
			return 'tinymce';
		}

		/**
		 * Removes unnecessary editor plugins
		 *
		 * @param array $plugins Registered plugins.
		 * @return mixed
		 */
		public function remove_despised_editor_plugins( $plugins ) {
			$k = array_search( 'fullscreen', $plugins, true );
			if ( false !== $k ) {
				unset( $plugins[ $k ] );
			}
			$plugins[] = 'paste';
			return $plugins;
		}

		/**
		 * SUI summary config.
		 *
		 * @since 4.0.0
		 * @since 4.2.0 Moved from Opt_In to Hustle_Admin_Page_Abstract. Scope changed from 'public static' to 'protected'.
		 *
		 * @param string|null $class Class to be added.
		 */
		protected function get_sui_summary_config( $class = null ) {
			$style     = '';
			$image_url = apply_filters( 'wpmudev_branding_hero_image', null );
			if ( ! empty( $image_url ) ) {
				$style = 'background-image:url(' . esc_url( $image_url ) . ')';
			}
			$sui = array(
				'summary' => array(
					'style'   => $style,
					'classes' => array(
						'sui-box',
						'sui-summary',
					),
				),
			);
			if ( ! empty( $class ) && is_string( $class ) ) {
				$sui['summary']['classes'][] = $class;
			}
			/**
			 * Dash integration
			 *
			 * @since 4.0.0
			 */
			$hide_branding  = apply_filters( 'wpmudev_branding_hide_branding', false );
			$branding_image = apply_filters( 'wpmudev_branding_hero_image', null );
			if ( $hide_branding && ! empty( $branding_image ) ) {
				$sui['summary']['classes'][] = 'sui-rebranded';
			} elseif ( $hide_branding && empty( $branding_image ) ) {
				$sui['summary']['classes'][] = 'sui-unbranded';
			}
			return $sui;
		}
	}

endif;
