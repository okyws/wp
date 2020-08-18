<?php

/**
 * Class Hustle_Module_Model
 *
 * @property Hustle_Module_Decorator $decorated
 */

class Hustle_Module_Model extends Hustle_Model {

	/**
	 * @var $_provider_details object
	 */
	private $_provider_details;

	public static function instance() {
		return new self();
	}

	/**
	 * Get the sub-types for embedded modules.
	 *
	 * @since the beggining of time
	 * @since 4.0 "after_content" changed to "inline"
	 *
	 * @return array
	 */
	public static function get_embedded_types( $with_titles = false ) {
		if ( ! $with_titles ) {
			return array( 'inline', 'widget', 'shortcode' );
		} else {
			return array(
				'inline'    => __( 'Inline', 'hustle' ),
				'widget'    => __( 'Widget', 'hustle' ),
				'shortcode' => __( 'Shortcode', 'hustle' ),
			);
		}
	}

	/**
	 * Get the sub-types for this module.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public function get_sub_types( $with_titles = false ) {
		if ( self::EMBEDDED_MODULE === $this->module_type ) {
			return self::get_embedded_types( $with_titles );
		} elseif ( self::SOCIAL_SHARING_MODULE === $this->module_type ) {
			return Hustle_SShare_Model::get_sshare_types( $with_titles );
		}

		return array();
	}

	/**
	 * Get the possible module types.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public static function get_module_types() {
		return array( self::POPUP_MODULE, self::SLIDEIN_MODULE, self::EMBEDDED_MODULE, self::SOCIAL_SHARING_MODULE );
	}

	/**
	 * Decorates current model
	 *
	 * @return Hustle_Module_Decorator
	 */
	public function get_decorated() {

		if ( ! $this->_decorator ) {
			$this->_decorator = new Hustle_Module_Decorator( $this ); }

		return $this->_decorator;
	}

	/**
	 * Content Model based upon module type.
	 *
	 * @return Class
	 */
	public function get_content() {
		$data = $this->get_settings_meta( self::KEY_CONTENT, '{}', true );
		// If redirect url is set then esc it.
		if ( isset( $data['redirect_url'] ) ) {
			$data['redirect_url'] = esc_url( $data['redirect_url'] );
		}

		return new Hustle_Meta_Base_Content( $data, $this );
	}

	/**
	 * Get the content of the data stored under 'emails' meta.
	 *
	 * @since 4.0
	 *
	 * @return Hustle_Popup_Emails
	 */
	public function get_emails() {
		$data = $this->get_settings_meta( self::KEY_EMAILS, '{}', true );

		return new Hustle_Meta_Base_Emails( $data, $this );
	}

	/**
	 * Get the module's settings for the given provider.
	 *
	 * @since 4.0
	 *
	 * @param string $slug
	 * @param bool   $get_cached
	 * @return array
	 */
	public function get_provider_settings( $slug, $get_cached = true ) {
		return $this->get_settings_meta( $slug . self::KEY_PROVIDER, '{}', true, $get_cached );
	}

	/**
	 * Save the module's settings for the given provider.
	 *
	 * @since 4.0
	 *
	 * @param string $slug
	 * @param array  $data
	 * @return array
	 */
	public function set_provider_settings( $slug, $data ) {
		return $this->update_meta( $slug . self::KEY_PROVIDER, $data );
	}

	/**
	 * Get the all-integrations module's settings.
	 * This is not each provider's settings. Instead, these are per module settings
	 * that are applied to all the active providers of this module.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public function get_integrations_settings() {
		$stored = $this->get_settings_meta( self::KEY_INTEGRATIONS_SETTINGS, '{}', true );
		return new Hustle_Meta_Base_Integrations( $stored, $this );
	}

	public function get_design() {
		$stored = $this->get_settings_meta( self::KEY_DESIGN, '{}', true );
		return new Hustle_Meta_Base_Design( $stored, $this );
	}

	/**
	 * Get the stored settings for the "Display" tab.
	 * Used for Embedded.
	 *
	 * @since 4.0
	 *
	 * @return Hustle_Embedded_Display
	 */
	public function get_display() {
		return new Hustle_Meta_Base_Display( $this->get_settings_meta( self::KEY_DISPLAY_OPTIONS, '{}', true ), $this );
	}

	/**
	 * Get the stored settings for the "Visibility" tab.
	 *
	 * @since 4.0
	 *
	 * @return Hustle_Popup_Visibility
	 */
	public function get_visibility() {
		return new Hustle_Meta_Base_Visibility( $this->get_settings_meta( self::KEY_VISIBILITY, '{}', true ), $this );
	}

	/**
	 * Used when populating data with "get".
	 */
	public function get_settings() {
		$saved = $this->get_settings_meta( self::KEY_SETTINGS, '{}', true );

		// The default value for 'triggers' was an empty string in old versions.
		// This will bring php errors if it persists.
		// Let's remove that troubling value and let the module grab the new defaults.
		if ( isset( $saved['triggers'] ) && empty( $saved['triggers'] ) ) {
			unset( $saved['triggers'] );
		}

		if ( self::POPUP_MODULE === $this->module_type ) {
			return new Hustle_Popup_Settings( $saved, $this );

		} elseif ( self::EMBEDDED_MODULE === $this->module_type ) {
			return new Hustle_Meta_Base_Settings( $saved, $this );

		} elseif ( self::SLIDEIN_MODULE === $this->module_type ) {
			return new Hustle_Slidein_Settings( $saved, $this );
		}

		return false;
	}

	/**
	 * Get the stored schedule flags
	 *
	 * @since 4.2.0
	 * @return array
	 */
	public function get_schedule_flags() {
		$default = array(
			'is_currently_scheduled' => '1',
			'check_schedule_at'      => 1,
		);

		return $this->get_settings_meta( 'schedule_flags', $default, true );
	}

	/**
	 * Set the schedule flags.
	 *
	 * @since 4.2.0
	 * @param array $flags
	 * @return void
	 */
	public function set_schedule_flags( $flags ) {
		$this->update_meta( 'schedule_flags', $flags );
	}

	public function get_shortcode_id() {
		return $this->id;
	}

	public function get_custom_field( $key, $value ) {
		$custom_fields = $this->get_content()->__get( 'form_elements' );

		if ( is_array( $custom_fields ) ) {
			foreach ( $custom_fields as $field ) {
				if ( isset( $field[ $key ] ) && $value === $field[ $key ] ) {
					return $field;
				}
			}
		}
	}

	/**
	 * Get wizard page for this module type.
	 *
	 * @since 4.0
	 * @return string
	 */
	public function get_wizard_page() {
		return Hustle_Module_Admin::get_wizard_page_by_module_type( $this->module_type );
	}

	/**
	 * Get the listing page for this module type.
	 *
	 * @since 4.0
	 * @return string
	 */
	public function get_listing_page() {
		return Hustle_Module_Admin::get_listing_page_by_module_type( $this->module_type );
	}

	/**
	 * Get the module's data. Used to display it.
	 *
	 * @since 3.0.7
	 *
	 * @param bool is_preview
	 * @return array
	 */
	public function get_module_data_to_display() {

		if ( 'social_sharing' === $this->module_type ) {
			$data = $this->get_data();

		} else {
			$settings = array( 'settings' => $this->get_settings()->to_array() );
			$data     = array_merge( $settings, $this->get_data() );

		}

		return $data;
	}

	/**
	 * Get the form fields of this module, if any.
	 *
	 * @since 4.0
	 *
	 * @return null|array
	 */
	public function get_form_fields() {

		if ( 'social_sharing' === $this->module_type || 'informational' === $this->module_mode ) {
			return null;
		}

		$emails_data = $this->get_emails()->to_array();
		/**
		 * Edit module fields
		 *
		 * @since 4.1.1
		 * @param string $form_elements Current module fields.
		 */
		$form_fields = apply_filters( 'hustle_form_elements', $emails_data['form_elements'] );

		return $form_fields;

	}

	/**
	 * Create a new module of the provided mode and type.
	 *
	 * @since 4.0
	 *
	 * @param array $data Must contain the Module's 'mode', 'name' and 'type.
	 * @return int|false Module ID if successfully saved. False otherwise.
	 */
	public function create_new( $data ) {

		// Verify it's a valid module type.
		if ( ! in_array( $data['module_type'], array( self::POPUP_MODULE, self::SLIDEIN_MODULE, self::EMBEDDED_MODULE, self::SOCIAL_SHARING_MODULE ), true ) ) {
			return false;
		}

		$is_social_share = ( self::SOCIAL_SHARING_MODULE === $data['module_type'] );

		// Abort if it's not a Social Share module and the mode isn't set.
		if ( ! $is_social_share && ! in_array( $data['module_mode'], array( 'optin', 'informational' ), true ) ) {
			return false;
		}

		if ( ! $is_social_share ) {
			$module = $this;
		} else {
			$module = Hustle_SShare_Model::instance();
		}

		// save to modules table
		$module->module_name = sanitize_text_field( $data['module_name'] );
		$module->module_type = $data['module_type'];
		$module->active      = 0;
		$module->module_mode = ! $is_social_share ? $data['module_mode'] : '';
		$module->save();

		// Save the new module's meta.
		$this->store_new_module_meta( $module, $data );

		// Activate providers
		$module->activate_providers( $data );

		return $module->id;
	}

	/**
	 * Store the defaults meta when creating a new module.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module
	 */
	public function store_new_module_meta( Hustle_Module_Model $module, $data ) {

		// All modules types except Social sharing modules. //
		if ( self::SOCIAL_SHARING_MODULE !== $module->module_type ) {

			$def_content  = apply_filters( 'hustle_module_get_' . self::KEY_CONTENT . '_defaults', $module->get_content()->to_array(), $module, $data );
			$content_data = empty( $data['content'] ) ? $def_content : array_merge( $def_content, $data['content'] );

			$def_emails  = apply_filters( 'hustle_module_get_' . self::KEY_EMAILS . '_defaults', $module->get_emails()->to_array(), $module, $data );
			$emails_data = empty( $data['emails'] ) ? $def_emails : array_merge( $def_emails, $data['emails'] );

			$def_design  = apply_filters( 'hustle_module_get_' . self::KEY_DESIGN . '_defaults', $module->get_design()->to_array(), $module, $data );
			$design_data = empty( $data['design'] ) ? $def_design : array_merge( $def_design, $data['design'] );

			$def_integrations_settings  = apply_filters( 'hustle_module_get_' . self::KEY_INTEGRATIONS_SETTINGS . '_defaults', $module->get_integrations_settings()->to_array(), $module, $data );
			$integrations_settings_data = empty( $data['integrations_settings'] ) ? $def_integrations_settings : array_merge( $def_integrations_settings, $data['integrations_settings'] );

			$def_settings  = apply_filters( 'hustle_module_get_' . self::KEY_SETTINGS . '_defaults', $module->get_settings()->to_array(), $module, $data );
			$settings_data = empty( $data['settings'] ) ? $def_settings : array_merge( $def_settings, $data['settings'] );

			// save to meta table
			$module->update_meta( self::KEY_CONTENT, $content_data );
			$module->update_meta( self::KEY_EMAILS, $emails_data );
			$module->update_meta( self::KEY_INTEGRATIONS_SETTINGS, $integrations_settings_data );
			$module->update_meta( self::KEY_DESIGN, $design_data );
			$module->update_meta( self::KEY_SETTINGS, $settings_data );

		} else {

			// Social sharing only. //
			$def_content  = apply_filters( 'hustle_module_get_' . self::KEY_CONTENT . '_defaults', $module->get_content()->to_array(), $module, $data );
			$content_data = empty( $data['content'] ) ? $def_content : array_merge( $def_content, $data['content'] );

			$def_design  = apply_filters( 'hustle_module_get_' . self::KEY_DESIGN . '_defaults', $module->get_design()->to_array(), $module, $data );
			$design_data = empty( $data['design'] ) ? $def_design : array_merge( $def_design, $data['design'] );

			// save to meta table
			$module->update_meta( self::KEY_CONTENT, $content_data );
			$module->update_meta( self::KEY_DESIGN, $design_data );
		}

		// Embedded and Social sharing only. //
		if ( self::EMBEDDED_MODULE === $module->module_type || self::SOCIAL_SHARING_MODULE === $module->module_type ) {

			// Display options.
			$def_display  = apply_filters( 'hustle_module_get_' . self::KEY_DISPLAY_OPTIONS . '_defaults', $module->get_display()->to_array(), $module, $data );
			$display_data = empty( $data['display'] ) ? $def_display : array_merge( $def_display, $data['display'] );

			// Save Display to meta table.
			$module->update_meta( self::KEY_DISPLAY_OPTIONS, $display_data );
		}

		// For all module types. //

		// Visibility settings.
		$def_visibility  = apply_filters( 'hustle_module_get_' . self::KEY_VISIBILITY . '_defaults', $module->get_visibility()->to_array(), $module, $data );
		$visibility_data = empty( $data['visibility'] ) ? $def_visibility : array_merge( $def_visibility, $data['visibility'] );
		$module->update_meta( self::KEY_VISIBILITY, $visibility_data );
	}

	/**
	 * Creates and store the nonce used to validate email unsubscriptions.
	 *
	 * @since 3.0.5
	 * @param string $email Email to be unsubscribed.
	 * @param array  $lists_id IDs of the modules to which it will be unsubscribed.
	 * @return boolean
	 */
	public function create_unsubscribe_nonce( $email, array $lists_id ) {
		// Since we're supporting php 5.2, random_bytes or other strong rng are not available. So using this instead.
		$nonce = hash_hmac( 'md5', $email, wp_rand() . time() );

		$data = get_option( self::KEY_UNSUBSCRIBE_NONCES, array() );

		// If the email already created a nonce and didn't use it, replace its data.
		$data[ $email ] = array(
			'nonce'        => $nonce,
			'lists_id'     => $lists_id,
			'date_created' => time(),
		);

		$updated = update_option( self::KEY_UNSUBSCRIBE_NONCES, $data );
		if ( $updated ) {
			return $nonce;
		} else {
			return false;
		}
	}

	/**
	 * Does the actual email unsubscription.
	 *
	 * @since 3.0.5
	 * @param string $email Email to be unsubscribed.
	 * @param string $nonce Nonce associated with the email for the unsubscription.
	 * @return boolean
	 */
	public function unsubscribe_email( $email, $nonce ) {
		$data = get_option( self::KEY_UNSUBSCRIBE_NONCES, false );
		if ( ! $data ) {
			return false;
		}
		if ( ! isset( $data[ $email ] ) || ! isset( $data[ $email ]['nonce'] ) || ! isset( $data[ $email ]['lists_id'] ) ) {
			return false;
		}
		$email_data = $data[ $email ];
		if ( ! hash_equals( (string) $email_data['nonce'], $nonce ) ) {
			return false;
		}
		// Nonce expired. Remove it. Currently giving 1 day of life span.
		if ( ( time() - (int) $email_data['date_created'] ) > DAY_IN_SECONDS ) {
			unset( $data[ $email ] );
			update_option( self::KEY_UNSUBSCRIBE_NONCES, $data );
			return false;
		}

		// Proceed to unsubscribe
		foreach ( $email_data['lists_id'] as $id ) {
			$unsubscribed = $this->remove_local_subscription_by_email_and_module_id( $email, $id );
		}

		// The email was unsubscribed and the nonce was used. Remove it from the saved list.
		unset( $data[ $email ] );
		update_option( self::KEY_UNSUBSCRIBE_NONCES, $data );

		return true;

	}

	/**
	 * Duplicate a module.
	 *
	 * @since 3.0.5
	 * @since 4.0 moved from Hustle_Popup_Admin_Ajax to here. New settings added.
	 *
	 * @return bool
	 */
	public function duplicate_module() {

		if ( ! $this->id ) {
			return false;
		}

		// TODO: make use of the sshare model to extend this instead.
		if ( self::SOCIAL_SHARING_MODULE !== $this->module_type ) {

			$data = array(
				'content'                       => $this->get_content()->to_array(),
				'emails'                        => $this->get_emails()->to_array(),
				'design'                        => $this->get_design()->to_array(),
				'settings'                      => $this->get_settings()->to_array(),
				'visibility'                    => $this->get_visibility()->to_array(),
				self::KEY_INTEGRATIONS_SETTINGS => $this->get_integrations_settings()->to_array(),
			);

			if ( self::EMBEDDED_MODULE === $this->module_type ) {
				$data['display'] = $this->get_display()->to_array();
			}

			// Pass integrations.
			if ( 'optin' === $this->module_mode ) {
				$integrations = array();
				$providers    = Hustle_Providers::get_instance()->get_providers();
				foreach ( $providers as $slug => $provider ) {
					$provider_data = $this->get_provider_settings( $slug, false );
					// if ( 'local_list' !== $slug && $provider_data && $provider->is_connected()
					if ( $provider_data && $provider->is_connected()
							&& $provider->is_form_connected( $this->module_id ) ) {
						$integrations[ $slug ] = $provider_data;
					}
				}

				$data['integrations'] = $integrations;
			}
		} else {
			$data = array(
				'content'    => $this->get_content()->to_array(),
				'display'    => $this->get_display()->to_array(),
				'design'     => $this->get_design()->to_array(),
				'visibility' => $this->get_visibility()->to_array(),
			);
		}

		unset( $this->id );

		// rename
		$this->module_name .= __( ' (copy)', 'hustle' );

		// Turn status off.
		$this->active = 0;

		// Save.
		$result = $this->save();

		if ( $result && ! is_wp_error( $result ) ) {

			$this->update_module( $data );

			return true;
		}

		return false;
	}

	/**
	 * Render the module.
	 *
	 * @since 4.0
	 *
	 * @param string $sub_type
	 * @param string $custom_classes
	 * @param bool   $is_preview
	 * @return string
	 */
	public function display( $sub_type = null, $custom_classes = '', $is_preview = false ) {
		if ( ! $this->id ) {
			return;
		}
		$renderer = $this->get_renderer();
		return $renderer->display( $this, $sub_type, $custom_classes, $is_preview );
	}

	public function get_renderer() {
		return new Hustle_Module_Renderer();
	}

	/**
	 * Return whether the module's sub_type is active.
	 *
	 * @since the beginning of time
	 * @since 4.0 method name changed.
	 *
	 * @param string $type
	 * @return boolean
	 */
	public function is_display_type_active( $type ) {
		$settings = $this->get_display()->to_array();

		if ( isset( $settings[ $type . '_enabled' ] ) && in_array( $settings[ $type . '_enabled' ], array( '1', 1, 'true' ), true ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Sanitize the form fields name replacing spaces by underscores.
	 * This way the data is handled properly along hustle.
	 *
	 * @since 4.0
	 * @param string $name
	 * @return string
	 */
	public static function sanitize_form_field_name( $name ) {
		$sanitized_name = apply_filters( 'hustle_sanitize_form_field_name', str_replace( ' ', '_', trim( $name ) ), $name );
		return $sanitized_name;
	}

	public static function sanitize_form_fields_names( $names_to_sanitize, $form_fields ) {

		// Replace the name without changing the array's order.
		$names_array = array_keys( $form_fields );
		foreach ( $names_to_sanitize as $name ) {
			$index                        = array_search( $name, $names_array, true );
			$sanitized_name               = self::sanitize_form_field_name( $name );
			$form_fields[ $name ]['name'] = $sanitized_name;

			$names_array[ $index ] = $sanitized_name;

		}
		$sanitized_fields = array_combine( $names_array, array_values( $form_fields ) );

		return $sanitized_fields;
	}

	public function sanitize_form_elements( $form_elements ) {
		// Sanitize GDPR message
		if ( isset( $form_elements['gdpr']['gdpr_message'] ) ) {
			$allowed_html                          = array(
				'a'      => array(
					'href'   => true,
					'title'  => true,
					'target' => true,
					'alt'    => true,
				),
				'b'      => array(),
				'strong' => array(),
				'i'      => array(),
				'em'     => array(),
				'del'    => array(),
			);
			$form_elements['gdpr']['gdpr_message'] = wp_kses( wp_unslash( $form_elements['gdpr']['gdpr_message'] ), $allowed_html );
		}

		$names_to_sanitize = array();
		foreach ( $form_elements as $name => $field_data ) {
			if ( false !== stripos( $name, ' ' ) ) {
				$names_to_sanitize[] = $name;
			}
		}

		// All good, return the data.
		if ( empty( $names_to_sanitize ) ) {
			return $form_elements;
		}

		$form_elements = self::sanitize_form_fields_names( $names_to_sanitize, $form_elements );

		return $form_elements;
	}

	/**
	 * Update Custom Fields for Sendgrid New Campaigns
	 */
	public function maybe_update_custom_fields() {
		$connected_addons = Hustle_Provider_Utils::get_addons_instance_connected_with_module( $this->module_id );

		foreach ( $connected_addons as $addon ) {

			// Change logic only for sendgrid for now.
			if ( 'sendgrid' !== $addon->get_slug() ) {
				continue;
			}
			$global_multi_id = $addon->selected_global_multi_id;
			$new_campaigns   = $addon->get_setting( 'new_campaigns', '', $global_multi_id );

			// only if it's the New Sendgrid Campaigns.
			if ( 'new_campaigns' !== $new_campaigns ) {
				continue;
			}
			$emails        = $this->get_emails()->to_array();
			$custom_fields = array();

			$api_key = $addon->get_setting( 'api_key', '', $global_multi_id );
			$api     = $addon::api( $api_key, $new_campaigns );

			foreach ( $emails['form_elements'] as $element ) {
				if ( empty( $element['type'] ) || in_array( $element['type'], array( 'submit', 'recaptcha' ), true ) ) {
					continue;
				}
				$custom_fields[] = array(
					'type' => 'text',
					'name' => $element['name'],
				);
			}

			if ( ! empty( $custom_fields ) ) {
				$api->add_custom_fields( $custom_fields );
			}
		}
	}

}
