<?php

/**
 * Plugin for CMB2 to
 * render Button element
 */

if( !defined( 'ABSPATH' ) ) exit;
if( !class_exists( 'CMB2_Button' ) ){

	class CMB2_Button {

		public function __construct()
		{
			add_action( 'cmb2_render_button', [$this, 'render_button'], 10, 5 );
		}

		public function render_button( $field, $escaped_value, $object_id, $object_type, $field_type_object )
		{
			$name = isset( $field->args['name'] )?esc_attr( $field->args['name'] ):'';
			$id = isset( $field->args['id'] )?esc_attr( $field->args['id'] ):$name;
			$title = isset( $field->args['title'] )?esc_attr( $field->args['title'] ):$name;
			$class = isset( $field->args['attributes']['class'] )?esc_attr( $field->args['attributes']['class'] ):null;
			$render = '<button name="'.$name.'" id="'.$id.'" type="button"';
			if( $class != null ){
				$render .= ' class="'.$class.'"';
			}
			$render .= '>'.$title.'</button>';
			echo $render;
		}

	}

	$cmb2_button = new CMB2_Button();
}