<?php

/**
 * Class for activation plugin
 */

class BLPW_Activator {

	/**
	 * Activate plugin
	 * @return None
	 */
	public static function activate()
	{
		self::activate_tables();
		self::activate_options();
		self::activate_default_template();
		self::activate_sitemap();
	}

	/**
	 * Create tables
	 * @return None
	 */
	private static function activate_tables()
	{
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'includes/class-blpw-tables.php' );
		$tables = new BLPW_Tables();
		$tables->build_tables();
	}


	/**
	 * Create basic options
	 * @return None
	 */
	private static function activate_options()
	{
		//Current country
		add_option( 'blpw_country', '0' );
	}


	/**
	 * Create basic template
	 * @return None
	 */
	private static function activate_default_template()
	{
		//Flag if we need to create new template
		$flag_create = true;
		//1. Try to untrash template
		$query_args = [
			'post_type' => 'blpw-template',
			'post_status' => 'trash',
			'posts_per_page' => -1
		];
		$posts = new WP_Query( $query_args );
		while( $posts->have_posts() ){
			$posts->the_post();
			wp_untrash_post( $posts->post->ID );
			$flag_create = false;
		}
		wp_reset_postdata();
		//2. Create template if not untrash
		if( $flag_create ){
			$attrs = [
				'post_title' => 'Basic Template',
				'post_name' => 'basic-template',
				'post_type' => 'blpw-template',
				'post_status' => 'publish'
			];
			wp_insert_post( $attrs, true );
		}
	}


	/**
	 * Create sitemap page if it doesn't exists
	 * @return None
	 */
	private static function activate_sitemap()
	{
		//Flag if we need to create new sitemap page
		$flag_create = false;
		//1. Try to find post
		$slug = 'sitemap';
		$query_args = [
			'post_type' => 'page',
			'name' => $slug,
			'post_status' => 'any',
			'posts_per_page' => 1
		];
		$posts = new WP_Query( $query_args );
		write_log( $posts );
		if( !$posts->have_posts() ){
			$flag_create = true;
			wp_reset_postdata();
		}
		if( $flag_create ){
			$attrs = [
				'post_type' => 'page',
				'post_name' => $slug,
				'post_title' => 'Sitemap',
				'post_content' => '[lpw_html_sitemap]',
				'post_status' => 'publish'
			];
			wp_insert_post( $attrs, true );
		}
	}

}