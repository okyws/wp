<?php
/**
 * Opt_In_Condition_Visitor_Country.
 *
 * @package Hustle
 * @since unkwnown
 */

/**
 * Opt_In_Condition_Visitor_Country.
 * Condition based on the visitor's country.
 *
 * @since unkwnown
 */
class Opt_In_Condition_Visitor_Country extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unkwnown
	 */
	public function is_allowed() {

		if ( isset( $this->args->countries ) ) {

			if ( 'except' === $this->args->filter_type ) {
				return ! ( $this->utils()->test_country( $this->args->countries ) );
			} elseif ( 'only' === $this->args->filter_type ) {
				return $this->utils()->test_country( $this->args->countries );
			}
		}

		return false;
	}
}
