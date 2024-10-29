<?php

class BLPW_Register_Templates_Page {

	function __construct()
	{
		$this->register_post_type();
	}

	private function register_post_type()
	{
		$slug = _x( 'btp', 'URL slug (no spaces or special characters)', 'blpw' );
		$labels = apply_filters( 'blpw_template_page_labels', [
			'name' => _x( 'Basic Template Pages', 'post type name', 'blpw' ),
			'singular_name' => _x( 'Template Page', 'singular post type name', 'blpw' ),
			'add_new' => _x( 'Add New', 'template page', 'blpw' ),
			'add_new_item' => __( 'Add New Template Page', 'blpw' ),
			'edit_item' => __( 'Edit Template Page', 'blpw' ),
			'new_item' => __( 'New Template Page', 'blpw' ),
			'view_item' => __( 'View Template Page', 'blpw' ),
			'search_items' => __( 'Search Template Pages', 'blpw' ),
			'not_found' => __( 'No Template Page found', 'blpw' ),
			'not_found_in_trash' => __( 'No Template Pages found in trash', 'blpw' ),
			'parent_item_colon' => ''
		] );

		$args = apply_filters( 'blpw_template_page_args', [
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => false,
			'exclude_from_search' => false,
			'capability_type' => 'page',
			'supports' => [
				'title',
				'editor',
				'thumbnail',
				'excerpt'
			],
			'menu_position' => 7,
			'has_archive' => false,
			'rewrite' => [
				'slug' => $slug,
				'with_front' => false
			]
		] );

		register_post_type( 'blpw-template', $args );
	}
}