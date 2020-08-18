<?php
/**
 * Abstract Hustle_Meta class.
 * Extended by each handler of the modules' metas.
 *
 * When creating a new meta property:
 * -Booleans properties must be '0' and '1', as strings. Make sure they're also stored in this way when saving.
 */
abstract class Hustle_Meta {

	protected $data;
	protected $model;

	protected $defaults = array();

	public function __construct( array $data, Hustle_Model $model ) {
		$this->data     = $data;
		$this->model    = $model;
		$this->defaults = apply_filters( 'hustle_meta_get_defaults', $this->get_defaults(), $this, $model, $data );
	}

	/**
	 * Implements getter magic method
	 *
	 * @since 1.0.0
	 *
	 * @param $field
	 * @return mixed
	 */
	public function __get( $field ) {

		if ( method_exists( $this, 'get_' . $field ) ) {
			return $this->{ 'get_' . $field }();
		}

		if ( ! empty( $this->data ) && isset( $this->data[ $field ] ) ) {
			$val = $this->data[ $field ];
			if ( 'true' === $val ) {
				return true;
			} elseif ( 'false' === $val ) {
				return false;
			} elseif ( 'null' === $val ) {
				return null;
			}
			return $val;
		}

	}

	public function to_array() {

		$defaults = $this->get_defaults();
		if ( $defaults ) {
			if ( isset( $defaults['form_elements'] ) && ! empty( $this->data['form_elements'] ) ) {
				unset( $defaults['form_elements'] );
			}

			return array_replace_recursive( $defaults, $this->data );
		}

		return $this->data;
	}

	/**
	 * Return an array with the default values.
	 * Can be overridden to return an array of default values
	 * without restricting to static values.
	 *
	 * @since 4.0
	 *
	 * @return array|false
	 */
	public function get_defaults() {

		if ( isset( $this->defaults ) && is_array( $this->defaults ) ) {
			return $this->defaults;
		}

		return false;
	}

}
