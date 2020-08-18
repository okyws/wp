<?php
/**
 * Meta box trait.
 *
 * To keep the abstract Page class clean, all the meta box functionality is moved out to a trait.
 *
 * @since 2.5.0
 * @package Hummingbird\Admin
 */

namespace Hummingbird\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait MetaBox
 */
trait MetaBox {

	/**
	 * Register a new meta box.
	 *
	 * @since 2.5.0
	 *
	 * @param string $box_id   Meta box ID.
	 * @param string $title    Meta box title.
	 * @param string $context  Meta box context.
	 */
	protected function register_meta_box( $box_id, $title, $context = 'main' ) {
		if ( ! isset( $this->meta_boxes[ $this->slug ] ) ) {
			$this->meta_boxes[ $this->slug ] = array();
		}

		if ( ! isset( $this->meta_boxes[ $this->slug ][ $context ] ) ) {
			$this->meta_boxes[ $this->slug ][ $context ] = array();
		}

		$meta_box = array(
			'id'              => $box_id,
			'title'           => $title,
			'callback'        => null,
			'callback_header' => null,
			'callback_footer' => null,
			'args'            => array(
				'box_class'         => 'sui-box',
				'box_header_class'  => 'sui-box-header',
				'box_content_class' => 'sui-box-body',
				'box_footer_class'  => 'sui-box-footer',
			),
		);

		/**
		 * Allow to filter a WP Hummingbird meta box.
		 *
		 * @param array  $meta_box  Meta box attributes.
		 * @param string $slug      Admin page slug.
		 * @param string $page_id   Admin page ID.
		 */
		$meta_box = apply_filters( 'wphb_add_meta_box', $meta_box, $this->slug, $this->page_id );
		$meta_box = apply_filters( 'wphb_add_meta_box_' . $meta_box['id'], $meta_box, $this->slug, $this->page_id );

		if ( $meta_box ) {
			$this->meta_boxes[ $this->slug ][ $context ][ $box_id ] = $meta_box;
		}
	}

	/**
	 * Add meta box callback.
	 *
	 * @since 2.5.0
	 *
	 * @param string   $box_id    Meta box ID.
	 * @param callable $callback  Callback for meta box content.
	 * @param string   $context   Meta box context.
	 */
	protected function register_meta_box_callback( $box_id, $callback = null, $context = 'main' ) {
		if ( ! $this->is_valid_meta_box( $box_id, $context ) ) {
			return;
		}

		$this->meta_boxes[ $this->slug ][ $context ][ $box_id ]['callback'] = $callback;
	}

	/**
	 * Add meta box header callback.
	 *
	 * @since 2.5.0
	 *
	 * @param string   $box_id    Meta box ID.
	 * @param callable $callback  Callback for meta box header.
	 * @param string   $context   Meta box context.
	 */
	protected function register_meta_box_header( $box_id, $callback = null, $context = 'main' ) {
		if ( ! $this->is_valid_meta_box( $box_id, $context ) ) {
			return;
		}

		$this->meta_boxes[ $this->slug ][ $context ][ $box_id ]['callback_header'] = $callback;
	}

	/**
	 * Add meta box footer callback.
	 *
	 * @since 2.5.0
	 *
	 * @param string   $box_id    Meta box ID.
	 * @param callable $callback  Callback for meta box footer.
	 * @param string   $context   Meta box context.
	 */
	protected function register_meta_box_footer( $box_id, $callback = null, $context = 'main' ) {
		if ( ! $this->is_valid_meta_box( $box_id, $context ) ) {
			return;
		}

		$this->meta_boxes[ $this->slug ][ $context ][ $box_id ]['callback_footer'] = $callback;
	}

	/**
	 * Add meta box footer callback.
	 *
	 * @since 2.5.0
	 *
	 * @param string $box_id   Meta box ID.
	 * @param array  $args     Callback for meta box content.
	 * @param string $context  Meta box context.
	 */
	protected function add_meta_box_arguments( $box_id, $args = array(), $context = 'main' ) {
		if ( ! $this->is_valid_meta_box( $box_id, $context ) ) {
			return;
		}

		$args = wp_parse_args( $args, $this->meta_boxes[ $this->slug ][ $context ][ $box_id ]['args'] );

		$this->meta_boxes[ $this->slug ][ $context ][ $box_id ]['args'] = $args;
	}

	/**
	 * Check if the meta box is already registered.
	 *
	 * @since 2.5.0
	 *
	 * @param string $box_id   Meta box ID.
	 * @param string $context  Meta box context.
	 *
	 * @return bool
	 */
	private function is_valid_meta_box( $box_id, $context ) {
		if ( ! isset( $this->meta_boxes[ $this->slug ] ) ) {
			return false;
		}

		if ( ! isset( $this->meta_boxes[ $this->slug ][ $context ] ) ) {
			return false;
		}

		if ( ! isset( $this->meta_boxes[ $this->slug ][ $context ][ $box_id ] ) ) {
			return false;
		}

		return true;
	}

}
