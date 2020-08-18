<?php
/**
 * Opt_In_Condition_Visitor_Commented.
 *
 * @package Hustle
 * @since unkwnown
 */

/**
 * Opt_In_Condition_Visitor_Commented.
 * Condition based on whether a user has commented.
 *
 * @since unkwnown
 */
class Opt_In_Condition_Visitor_Commented extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unkwnown
	 */
	public function is_allowed() {

		if ( 'true' === $this->args->filter_type ) {
			return $this->utils()->has_user_commented();
		}

		return ! ( $this->utils()->has_user_commented() );
	}
}
