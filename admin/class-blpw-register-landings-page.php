<?php

class BLPW_Register_Landings_Page {

	function __construct()
	{
		$this->register_post_type();
	}


	private function register_post_type()
	{
		$slug = _x( 'blp', 'URL slug (no spaces or special characters)', 'blpw' );
		$labels = apply_filters( 'blpw_landing_page_labels', [
			'name' => _x( 'Basic Landing Pages', 'post type name', 'blpw' ),
			'singular_name' => _x( 'Landing Page', 'singular post type name', 'blpw' ),
			'add_new' => _x( 'Add New', 'landing page', 'blpw' ),
			'add_new_item' => __( 'Add New Landing Page', 'blpw' ),
			'edit_item' => __( 'Edit Landing Page', 'blpw' ),
			'new_item' => __( 'New Landing Page', 'blpw' ),
			'view_item' => __( 'View Landing Page', 'blpw' ),
			'search_items' => __( 'Search Landing Pages', 'blpw' ),
			'not_found' => __( 'No Landing Page found', 'blpw' ),
			'not_found_in_trash' => __( 'No Landing Pages found in trash', 'blpw' ),
			'parent_item_colon' => ''
		] );

		$args = apply_filters( 'blpw_landing_page_args', [
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => false,
			'exclude_from_search' => false,
			'capability_type' => 'page',
			'supports' => [
				'title'
			],
			'menu_position' => 7,
			'has_archive' => false,
			'rewrite' => [
				'slug' => $slug,
				'with_front' => false
			]
		] );

		register_post_type( 'blpw-landing-page', $args );
	}
}