<?php

class BLPW_Landing_Page {

	static public function is_landing_page( $page_id = null )
	{
		$p_id = ($page_id != null)?$page_id:get_the_ID();
		return ( get_post_type( $p_id ) == 'blpw-landing-page' );
	}


	static public function get_page_title( string $type = 'heading', $template_page_id = null, array $attrs = [] )
	{
		$template_page_title = '';
		if( $template_page_id != null ){
			$template_page_title = get_the_title( $template_page_id );
		}
		$title_opt = '';
		if( $type == 'heading' ){
			$title_opt = '[title] in [city] [state] [phone]';
		}else{
			$title_opt = '[title] in [city] [state] [phone]';
		}

		//Get placeholders
		preg_match_all( '/\[([^#]+?)\]/', $title_opt, $placeholders );
		if( in_array( 'title', $placeholders[1] ) ){
			$title_opt = str_replace( '[title]', $template_page_title, $title_opt );
		}
		if( in_array( 'city', $placeholders[1] ) ){
			$title_opt = str_replace( '[city]', $attrs['city'], $title_opt );
		}
		if( in_array( 'state', $placeholders[1] ) ){
			$title_opt = str_replace( '[state]', $attrs['state'], $title_opt );
		}
		if( in_array( 'county', $placeholders[1] ) ){
			$title_opt = str_replace( '[county]', $attrs['county'], $title_opt );
		}
		if( in_array( 'phone', $placeholders[1] ) ){
			$title_opt = str_replace( '[phone]', $attrs['phone'], $title_opt );
		}
		//For future
		if( in_array( 'zip_code', $placeholders[1] ) ){
			$title_opt = str_replace( '[zip_code]', $attrs['zip_code'], $title_opt );
		}

		return $title_opt;
	}


	static public function get_city_page_id( $city_id, $template_id )
	{
		$page_id = null;

		$query_args = [
			'post_type' => 'blpw-landing-page',
			'post_status' => ['publish', 'trash'],
			'meta_query' => [
				'relation' => 'AND',
				[
					'key' => '_blpw_template_page_id',
					'value' => (string)$template_id,
					'compare' => '='
				],[
					'key' => '_blpw_city_id',
					'value' => (string)$city_id,
					'compare' => '='
				]
			]
		];

		$posts = new WP_Query( $query_args );
		while( $posts->have_posts() ){
			$posts->the_post();
			$page_id = get_the_ID();
		}
		wp_reset_postdata();

		return $page_id;
	}


	static public function create_city_pages( $state = null, $county = null, $city = null, $template_page_id = null )
	{
		if( $city == null ){
			return false;
		}

		//global $wpdb; //Future

		$query_args = [
			'post_type' => 'blpw-template',
			'post_status' => 'publish',
			'posts_per_page' => -1
		];
		if( $template_page_id != null ){
			$query_args['p'] = $template_page_id;
		}

		$landing_pages = [];

		$templates = new WP_Query( $query_args );
		while( $templates->have_posts() ){
			$templates->the_post();

			$template_title = get_the_title();
			$template_id = get_the_ID();

			$title_attrs = [];
			$title_attrs['city'] = $city->name;
			$title_attrs['state'] = $state->name;
			$title_attrs['county'] = $county->county;
			$title_attrs['phone'] = $city->phone;
			//For future
			$title_attrs['zip_code'] = '';

			$title = self::get_page_title( 'heading', $template_id, $title_attrs );
			$page_title = self::get_page_title( 'page-title', $template_id, $title_attrs );
			$slug = sanitize_title( "{$template_title} in {$city->name}, {$state->name}" );

			$landing_pages[$template_id]['title'] = $title;
			$landing_pages[$template_id]['page_title'] = $page_title;
			$landing_pages[$template_id]['template_title'] = $template_title;
			$landing_pages[$template_id]['slug'] = $slug;
			$landing_pages[$template_id]['template_id'] = $template_id;
			$landing_pages[$template_id]['city_id'] = $city->id;
		}
		wp_reset_postdata();

		foreach( $landing_pages as $landing_page ) {
			$attrs = [
				'post_title' => $landing_page['title'],
				'post_name' => $landing_page['slug'],
				'post_type' => 'blpw-landing-page',
				'meta_input' => [
					'_blpw_template_page_id' => $landing_page['template_id'],
					'_blpw_city_id' => $landing_page['city_id'],
					'_blpw_page_title' => $landing_page['page_title']
				]
			];

			$page_id = self::get_city_page_id( $landing_page['city_id'], $landing_page['template_id'] );
			if( $page_id != null ){
				$attrs['ID'] = $page_id;
				$attrs['post_status'] = get_post_status( $page_id );
				if( !wp_is_post_revision( $page_id ) ){
					wp_update_post( $attrs, true );
				}
			}else{
				$attrs['post_status'] = 'publish';
				wp_insert_post( $attrs, true );
			}
		}
	}


	static public function remove_city_pages( $city_id )
	{
		$query_args = [
			'post_type' => 'blpw-landing-page',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => [
				[
					'key' => '_blpw_city_id',
					'value' => (string)$city_id,
					'compare' => '='
				]
			]
		];
		$posts = new WP_Query( $query_args );
		while( $posts->have_posts() ){
			$posts->the_post();
			wp_trash_post( get_the_ID() );
		}
		wp_reset_postdata();
	}


	static public function restore_city_pages( $city_id )
	{
		$query_args = [
			'post_type' => 'blpw-landing-page',
			'post_status' => 'trash',
			'posts_per_page' => -1,
			'meta_query' => [
				[
					'key' => '_blpw_city_id',
					'value' => (string)$city_id,
					'compare' => '='
				]
			]
		];
		$posts = new WP_Query( $query_args );
		while( $posts->have_posts() ){
			$posts->the_post();
			wp_untrash_post( get_the_ID() );
		}
		wp_reset_postdata();
	}
}