<?php
/**
 * Dialog for previewing modules.
 *
 * @package Hustle
 * @since 4.0.0
 */

$attributes = array(
	'modal_id'        => 'preview',
	'has_description' => false,
	'modal_size'      => 'xl',

	'header'          => array(
		'title' => __( 'Preview', 'hustle' ),
	),
	'body'            => array(
		'content' => ' ',
	),
);

$this->render_modal( $attributes );
