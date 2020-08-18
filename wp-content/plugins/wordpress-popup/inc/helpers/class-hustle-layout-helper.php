<?php
/**
 * Hustle_Layout_Helper class.
 *
 * @package Hustle
 * @since 4.2.0
 */

/**
 * Helper class for rendering markup on admin side.
 * This is used along admin pages to standardize certain elements markup.
 *
 * @since 4.2.0
 */
class Hustle_Layout_Helper {

	/**
	 * Instance of the class that controls the template.
	 *
	 * @since 4.2.0
	 * @var Object
	 */
	private $admin;

	/**
	 * White labeling based on Dash Plugin Settings.
	 *
	 * @since 4.2.0
	 * @var boolean
	 */
	private $is_branding_hidden = false;

	/**
	 * To be removed.
	 *
	 * @var string something.
	 */
	public static $plugin_url;

	/**
	 * Hustle_Layout_Helper class constructor.
	 *
	 * @since 4.2.0
	 * @param object $referer The class that has the properties to access from within templates.
	 */
	public function __construct( $referer = null ) {

		self::$plugin_url = Opt_In::$plugin_url;

		$this->is_branding_hidden = apply_filters( 'wpmudev_branding_hide_branding', $this->is_branding_hidden );

		/**
		 * Sets the referer class as a property.
		 * This allows us to access the referer class' properties if needed
		 * from the template files.
		 */
		// TODO maybe check if the referer has the two allowed classes.
		if ( $referer ) {
			$this->admin = $referer;
		}
	}

	/**
	 * Gets the previously set referer.
	 *
	 * @since 4.2.0
	 * @return object
	 */
	public function get_referer() {
		if ( ! $this->admin ) {
			return false;
		}
		return $this->admin;
	}

	/**
	 * Returns or echoes markup from the given $options array.
	 * Uses the file 'admin/commons/options' as the markup template.
	 *
	 * @since 4.2.0
	 *
	 * @param  array   $options Array with the options that define the markup to be returned.
	 * @param  boolean $return Whether to echo or return the markup.
	 * @return string
	 */
	public function get_html_for_options( $options, $return = false ) {
		$html = '';
		foreach ( $options as $key => $option ) {
			$html .= $this->render( 'admin/commons/options', $option, $return );
		}
		return $html;
	}

	/**
	 * Renders a view file with static call.
	 *
	 * @since 1.0
	 * @since 4.2.0 Moved from Opt_In to this class.
	 *
	 * @param string     $file Path to the view file.
	 * @param array      $params Array whose keys will be variable names when within the view file.
	 * @param bool|false $return Whether to echo or return the contents.
	 * @return string
	 */
	public function render( $file, $params = array(), $return = false ) {

		// Assign $file to a variable which is unlikely to be used by users of the method.
		$opt_in_to_be_file_name = $file;
		extract( $params, EXTR_OVERWRITE ); // phpcs:ignore

		if ( $return ) {
			ob_start();
		}

		$template_file = trailingslashit( Opt_In::$plugin_path ) . Opt_In::VIEWS_FOLDER . '/' . $opt_in_to_be_file_name . '.php';
		if ( file_exists( $template_file ) ) {
			include $template_file;

		} else {
			$template_path = Opt_In::$template_path . $opt_in_to_be_file_name . '.php';

			// Render file located outside the plugin's folder. Useful when adding third party integrations.
			$external_path = $opt_in_to_be_file_name . '.php';

			if ( file_exists( $template_path ) ) {
				include $template_path;
			} elseif ( file_exists( $external_path ) ) {
				include $external_path;
			} elseif ( file_exists( $opt_in_to_be_file_name ) ) {
				include $opt_in_to_be_file_name;
			}
		}

		if ( $return ) {
			return ob_get_clean();
		}

		if ( ! empty( $params ) ) {
			foreach ( $params as $param ) {
				unset( $param );
			}
		}
	}

	/**
	 * Renders custom attributes within views templates.
	 *
	 * @since 1.0.0
	 * @since 4.2.0 Moved from Opt_In to this class.
	 *
	 * @param array   $html_options Attributes as an array to be renderd.
	 * @param boolean $echo Whether to return or echo the attributes.
	 * @return string
	 */
	public function render_attributes( $html_options, $echo = true ) {

		$special_attributes = array(
			'async'          => 1,
			'autofocus'      => 1,
			'autoplay'       => 1,
			'checked'        => 1,
			'controls'       => 1,
			'declare'        => 1,
			'default'        => 1,
			'defer'          => 1,
			'disabled'       => 1,
			'formnovalidate' => 1,
			'hidden'         => 1,
			'ismap'          => 1,
			'loop'           => 1,
			'multiple'       => 1,
			'muted'          => 1,
			'nohref'         => 1,
			'noresize'       => 1,
			'novalidate'     => 1,
			'open'           => 1,
			'readonly'       => 1,
			'required'       => 1,
			'reversed'       => 1,
			'scoped'         => 1,
			'seamless'       => 1,
			'selected'       => 1,
			'typemustmatch'  => 1,
		);
		if ( array() === $html_options ) {
			return '';
		}

		$html = '';
		if ( isset( $html_options['encode'] ) ) {
			$raw = ! $html_options['encode'];
			unset( $html_options['encode'] );
		} else {
			$raw = false;
		}
		foreach ( $html_options as $name => $value ) {
			if ( isset( $special_attributes[ $name ] ) ) {
				if ( $value ) {
					$html .= ' ' . $name;
					$html .= '="' . $name . '"';
				}
			} elseif ( null !== $value ) {
				$html .= ' ' . esc_attr( $name ) . '="' . ( $raw ? $value : esc_attr( $value ) ) . '"'; }
		}

		if ( $echo ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $html;
		}
	}

	/**
	 * Renders a basic modal with the passed attributes.
	 *
	 * @since 4.2.0
	 * @param array $arguments Arguments for the modal. Documented in the template file.
	 */
	private function render_modal( $arguments ) {
		$this->render( '/admin/commons/modal-template', $arguments );
	}
}
