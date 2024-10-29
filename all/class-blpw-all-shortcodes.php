<?php

use WeDevs\ORM\WP\City as Cities;

class BLPW_All_Shortcodes {
	private $options_data;


	function __construct()
	{
		$this->options_data = [
			'company_info' => get_option( 'blpw_company_info_block', false ),
			'slider' => get_option( 'blpw_block_0', false ),
			'block_1' => get_option( 'blpw_block_1', false ),
			'block_2' => get_option( 'blpw_block_2', false ),
			'trusted_symbols' => get_option( 'blpw_trusted_symbols', false ),
			'last_projects' => get_option( 'blpw_last_projects', false ),
			'w2e' => get_option( 'blpw_w2e', false ),
			'testimonials' => get_option( 'blpw_testimonials', false )
		];
	}

	//---------------------- Company Info shortcodes ---------------------------------
	public function company_name( $atts = [], string $content = '', string $tag = '' )
	{
		$ret = ( $this->options_data['company_info'] != false )?$this->options_data['company_info']['company_name']:'';
		return $ret;
	}


	public function company_phone( $atts = [], string $content = '', string $tag = '' )
	{
		$ret = ( $this->options_data['company_info'] != false )?$this->options_data['company_info']['company_phone']:'';
		return $ret;
	}


	public function company_address( $atts = [], string $content = '', string $tag = '' )
	{
		$ret = ( $this->options_data['company_info'] != false )?$this->options_data['company_info']['company_address']:'';
		$ret = nl2br( $ret );
		return $ret;
	}


	public function company_logo( $atts = [], string $content = '', string $tag = '' )
	{
		$img_atts = [
			'size' => 'large',
			'class' => ''
		];
		if( is_array( $atts ) ){
			if( array_key_exists( 'size', $atts ) ){
				$img_atts['size'] = $atts['size'];
			}
			if( array_key_exists( 'class', $atts ) ){
				$img_atts['class'] = $atts['class'];
			}
			if( array_key_exists( 'alt', $atts ) ){
				$img_atts['alt'] = esc_html__( $atts['alt'], 'blpw' );
			}
			if( array_key_exists( 'title', $atts ) ){
				$img_atts['title'] = esc_html__( $atts['title'], 'blpw' );
			}
		}
		$ret = '';
		$img_url = ( $this->options_data['company_info'] != false )?$this->options_data['company_info']['company_logo']:'';
		if( !empty( $img_url ) ){
			$img_id = attachment_url_to_postid( $img_url );
			$ret = wp_get_attachment_image( $img_id, $img_atts['size'], false, ['class' => $img_atts['class']] );
		}
		return $ret;
	}


	public function company_mobile_logo( $atts = [], string $content = '', string $tag = '' )
	{
		$img_atts = [
			'size' => 'large',
			'class' => ''
		];
		if( is_array( $atts ) ){
			if( array_key_exists( 'size', $atts ) ){
				$img_atts['size'] = $atts['size'];
			}
			if( array_key_exists( 'class', $atts ) ){
				$img_atts['class'] = $atts['class'];
			}
		}
		$ret = '';
		$img_url = ( $this->options_data['company_info'] != false )?$this->options_data['company_info']['company_mobile_logo']:'';
		if( !empty( $img_url ) ){
			$img_id = attachment_url_to_postid( $img_url );
			$ret = wp_get_attachment_image( $img_id, $img_atts['size'], false, ['class' => $img_atts['class']] );
		}
		return $ret;
	}


	public function company_social( $atts = [], string $content = '', string $tag = '' )
	{
		if( $this->options_data['company_info'] == false ){
			return '';
		}

		//Select social net by variable "social" in attributes
		$soc = 'facebook';
		if( is_array( $atts ) && isset( $atts['social'] ) ){
			$soc = $atts['social'];
		}
		$social = 'company_social_'.$soc;
		$ret = '';
		if( isset( $this->options_data['company_info'][$social] ) ){
			$ret = $this->options_data['company_info'][$social];
		}
		return $ret;
	}


	public function html_landing_sitemap( $atts = [], string $content = '', string $tag = '' )
	{
		$ret = '';
		$is_html_sitemap = BLPW_Options::get_option( 'blpw_company_info_block', 'html_sitemap', 'off', 'wp' );
		if( $is_html_sitemap != 'off' && $is_html_sitemap != false ){
			$HTMLS = new BLPW_All_HTML_Sitemap();
			$ret = $HTMLS->get_html_sitemap();
		}
		return $ret;
	}
	//-------------------------------------------------------------------------------------------
	//----------------------------------- Slider block ------------------------------------------
	
	public function slider_headline( $atts = [], string $content = '', string $tag = '' )
	{
		if( $this->options_data['slider'] == false ){
			return '';
		}
		$num = rand( 1, 2 );
		$tag = "headline_{$num}";

		return $this->options_data['slider'][$tag];
	}

	public function slider_background( $atts = [], string $content = '', string $tag = '' )
	{
		$img_atts = [
			'size' => 'large',
			'class' => ''
		];
		$image = true;
		if( is_array( $atts ) ){
			if( array_key_exists( 'size', $atts ) ){
				$img_atts['size'] = $atts['size'];
			}
			if( array_key_exists( 'class', $atts ) ){
				$img_atts['class'] = $atts['class'];
			}
			if( array_key_exists( 'type', $atts ) ){
				if( $atts['type'] == 'src' || $atts['type'] == 'url' ){
					$image = false;
				}
			}
		}
		$img_url = ( $this->options_data['slider'] != false )?$this->options_data['slider']['background']:'';
		if( !$image ){
			return $img_url;
		}
		$ret = '';
		if( !empty( $img_url ) ){
			$img_id = attachment_url_to_postid( $img_url );
			$ret = wp_get_attachment_image( $img_id, $img_atts['size'], false, ['class' => $img_atts['class']] );
		}
		return $ret;
	}

	public function slider_bullet_point( $atts = [], string $content = '', string $tag = '' )
	{
		if( $this->options_data['slider'] == false ){
			return '';
		}

		//Select number of bullet point by variable "bullet_point" in attributes
		$num = '1';
		if( is_array( $atts ) && isset( $atts['bullet_point'] ) ){
			$num = $atts['bullet_point'];
		}
		$bullet_point = 'bp'.strval( $num );
		$ret = '';
		if( isset( $this->options_data['slider'][$bullet_point] ) ){
			$ret = $this->options_data['slider'][$bullet_point];
		}
		return $ret;
	}	
	//-------------------------------------------------------------------------------------------
	//------------------------------- Info Block 1 and 2 ----------------------------------------
	public function block_headline( $atts = [], string $content = '', string $tag = '' )
	{
		if( !is_array( $atts ) ){
			return '';
		}
		if( !isset( $atts['infoblock'] ) ){
			return '';
		}
		$block = 'block_'.strval( $atts['infoblock'] );
		if( $this->options_data[$block] == false ){
			return '';
		}
		$num = rand( 1, 2 );
		$tag = "headline_{$num}";

		return $this->options_data[$block][$tag];
	}

	public function block_text( $atts = [], string $content = '', string $tag = '' )
	{
		if( !is_array( $atts ) ){
			return '';
		}
		if( !isset( $atts['infoblock'] ) ){
			return '';
		}
		$block = 'block_'.strval( $atts['infoblock'] );
		if( $this->options_data[$block] == false ){
			return '';
		}
		$num = rand( 1, 2 );
		$tag = "text_{$num}";

		//Replace \n to <br/>
		$text = nl2br( $this->options_data[$block][$tag] );

		return $text;
	}
	//-------------------------------------------------------------------------------------------
	//------------------------------------- Trusted symbols -------------------------------------
	public function trusted_symbol( $atts = [], string $content = '', string $tag = '' )
	{
		if( $this->options_data['trusted_symbols'] == false ){
			return '';
		}
		$img_atts = [
			'size' => 'large',
			'class' => ''
		];
		//Select number of trusted symbol by variable "trusted_symbol" in attributes
		$num = '1';
		if( is_array( $atts ) ){
			if( isset( $atts['trusted_symbol'] ) ){
				$num = $atts['trusted_symbol'];
			}
			if( array_key_exists( 'size', $atts ) ){
				$img_atts['size'] = $atts['size'];
			}
			if( array_key_exists( 'class', $atts ) ){
				$img_atts['class'] = $atts['class'];
			}
		}
		$trusted_symbol = 'ts'.strval( $num );
		$ret = '';
		if( isset( $this->options_data['trusted_symbols'][$trusted_symbol] ) ){
			$img_url = $this->options_data['trusted_symbols'][$trusted_symbol];
			if( !empty( $img_url ) ){
				$img_id = attachment_url_to_postid( $img_url );
				$ret = wp_get_attachment_image( $img_id, $img_atts['size'], false, ['class' => $img_atts['class']] );
			}
		}
		return $ret;
	}
	//-------------------------------------------------------------------------------------------
	//----------------------------------- Last Projects -----------------------------------------
	public function last_projects_headline( $atts = [], string $content = '', string $tag = '' )
	{
		$ret = ( $this->options_data['last_projects'] != false )?$this->options_data['last_projects']['headline']:'';
		return $ret;
	}

	public function last_projects_picture( $atts = [], string $content = '', string $tag = '' )
	{
		if( $this->options_data['last_projects'] == false ){
			return '';
		}
		$img_atts = [
			'size' => 'large',
			'class' => ''
		];
		//Select number of last project by variable "last_project" in attributes
		$num = '1';
		if( is_array( $atts ) ){
			if( isset( $atts['last_project'] ) ){
				$num = $atts['last_project'];
			}
			if( array_key_exists( 'size', $atts ) ){
				$img_atts['size'] = $atts['size'];
			}
			if( array_key_exists( 'class', $atts ) ){
				$img_atts['class'] = $atts['class'];
			}
		}
		$pic = 'pic'.strval( $num );
		$ret = '';
		if( isset( $this->options_data['last_projects'][$pic] ) ){
			$img_url = $this->options_data['last_projects'][$pic];
			if( !empty( $img_url ) ){
				$img_id = attachment_url_to_postid( $img_url );
				$ret = wp_get_attachment_image( $img_id, $img_atts['size'], false, ['class' => $img_atts['class']] );
			}
		}
		return $ret;
	}
	//-------------------------------------------------------------------------------------------
	//------------------------------------------- W2E -------------------------------------------
	public function w2e_photo( $atts = [], string $content = '', string $tag = '' )
	{
		$img_atts = [
			'size' => 'large',
			'class' => ''
		];
		if( is_array( $atts ) ){
			if( array_key_exists( 'size', $atts ) ){
				$img_atts['size'] = $atts['size'];
			}
			if( array_key_exists( 'class', $atts ) ){
				$img_atts['class'] = $atts['class'];
			}
		}
		$ret = '';
		$img_url = ( $this->options_data['w2e'] != false )?$this->options_data['w2e']['photo']:'';
		if( !empty( $img_url ) ){
			$img_id = attachment_url_to_postid( $img_url );
			$ret = wp_get_attachment_image( $img_id, $img_atts['size'], false, ['class' => $img_atts['class']] );
		}
		return $ret;
	}

	public function w2e_bullet_point( $atts = [], string $content = '', string $tag = '' )
	{
		if( $this->options_data['w2e'] == false ){
			return '';
		}

		//Select number of bullet point by variable "bullet_point" in attributes
		$num = '1';
		if( is_array( $atts ) && isset( $atts['bullet_point'] ) ){
			$num = $atts['bullet_point'];
		}
		$bullet_point = 'bp'.strval( $num );
		$ret = '';
		if( isset( $this->options_data['w2e'][$bullet_point] ) ){
			$ret = $this->options_data['w2e'][$bullet_point];
		}
		return $ret;
	}
	//-------------------------------------------------------------------------------------------
	//------------------------------------- Testimonials ----------------------------------------
	public function testimonial( $atts = [], string $content = '', string $tag = '' )
	{
		if( $this->options_data['testimonials'] == false ){
			return '';
		}

		//Select number of testimonial by variable "testimonial" in attributes
		$num = '1';
		if( is_array( $atts ) && isset( $atts['testimonial'] ) ){
			$num = $atts['testimonial'];
		}
		$testimonial = 'testimonial'.strval( $num );
		$ret = '';
		if( isset( $this->options_data['testimonials'][$testimonial] ) ){
			$ret = $this->options_data['testimonials'][$testimonial];
		}
		return $ret;
	}
	//-------------------------------------------------------------------------------------------
	//----------------------------------- Locations ---------------------------------------------
	public function city( $atts = [], string $content = '', string $tag = '' )
	{
		$ret = '';
		$city_id = get_post_meta( get_the_ID(), '_blpw_city_id', true );
		if( empty( $city_id ) ){
			return $ret;
		}
		$city = Cities::where( ['id' => intval( $city_id )] )->first();
		if( $city != null ){
			$ret = $city->name;
		}
		return $ret;
	}


	public function city_phone( $atts = [], string $content = '', string $tag = '' )
	{
		$ret = '';
		$city_id = get_post_meta( get_the_ID(), '_blpw_city_id', true );
		if( empty( $city_id ) ){
			return $ret;
		}
		$city = Cities::where( ['id' => intval( $city_id )] )->first();
		if( $city != null ){
			$ret = $city->phone;
		}
		return $ret;
	}


	public function state( $atts = [], string $content = '', string $tag = '' )
	{
		$ret = '';
		$state_id = 0;
		//Find by city
		$city_id = get_post_meta( get_the_ID(), '_blpw_city_id', true );
		if( empty( $city_id ) ){
			return $ret;
		}
		$city = Cities::where( ['id' => intval( $city_id )] )->first();
		if( $city != null ){
			$ret = $city->state->name;
		}
		return $ret;
	}


	public function county( $atts = [], string $content = '', string $tag = '' )
	{
		$ret = '';
		$state_id = 0;
		//Find by city
		$city_id = get_post_meta( get_the_ID(), '_blpw_city_id', true );
		if( empty( $city_id ) ){
			return $ret;
		}
		$city = Cities::where( ['id' => intval( $city_id )] )->first();
		if( $city != null ){
			$ret = $city->county->name;
		}
		return $ret;
	}

	public function site_name( $atts = [], string $content = '', string $tag = '' )
	{
		$name = get_bloginfo( 'name' );
		return $name;
	}


	public function page_title( $atts = [], string $content = '', string $tag = '' )
	{
		$template_id = get_post_meta( get_the_ID(), '_blpw_template_page_id', true );
		$title = get_the_title( $template_id );
		return $title;
	}
	//-------------------------------------------------------------------------------------------
}