<?php

/**
 * Class for deactivate plugin
 */

class BLPW_Deactivator {

	/**
	 * Deactivate plugin
	 * @return None
	 */
	public static function deactivate()
	{
		self::deactivate_tables();
		self::deactivate_options();
		self::deactivate_pages();
		self::deactivate_templates();
	}


	/**
	 * Drop tables
	 * @return None
	 */
	private static function deactivate_tables()
	{
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'includes/class-blpw-tables.php' );
		$tables = new BLPW_Tables();
		$tables->drop_tables();
	}


	/**
	 * Drop options
	 * @return None
	 */
	private static function deactivate_options()
	{
		//Current country
		delete_option( 'blpw_country' );
	}


	private static function deactivate_templates()
	{
		$query_args = [
			'post_type' => 'blpw-template',
			'posts_per_page' => -1
		];
		$posts = new WP_Query( $query_args );
		while( $posts->have_posts() ){
			$posts->the_post();
			wp_trash_post( get_the_ID() );
		}
		wp_reset_postdata();
	}


	private static function deactivate_pages()
	{
		$query_args = [
			'post_type' => 'blpw-landing-page',
			'posts_per_page' => -1
		];
		$posts = new WP_Query( $query_args );
		while( $posts->have_posts() ){
			$posts->the_post();
			wp_trash_post( get_the_ID() );
		}
		wp_reset_postdata();
	}
}