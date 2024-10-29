<?php

class BLPW_Options {

	static public function get_option( string $slug, string $option_name, $default = null, string $mode = 'cmb2' )
	{
		if( $mode == 'wp' ){
			$raw_option = get_option( $slug, $default );
			if( !empty( $option_name ) ){
				if( isset( $raw_option[$option_name] ) ){
					$raw_option = $raw_option[$option_name];
				}else{
					$raw_option = $default;
				}
			}
		}elseif( $mode == 'cmb2' ){
			$raw_option = cmb2_get_option( $slug, $option_name, $default );
		}
		return $raw_option;
	}
}