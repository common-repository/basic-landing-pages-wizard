<?php

class BLPW_All_HTML_Sitemap {

	function __construct()
	{

	}


	/**
	 * Get cities data from landing pages
	 * @return array 	Data from generated pages
	 */
	private function get_cities_data() : array
	{
		$ret = [];
		$query_args = [
			'post_type' => 'blpw-landing-page',
			'post_status' => 'publish',
			'posts_per_page' => -1
		];
		$posts = get_posts( $query_args );
		foreach( $posts as $post ){
			$ret[] = [
				'title' => esc_html__( $post->post_title ),
				'link' => get_the_permalink( $post->ID )
			];
		}

		return $ret;
	}


	/**
	 * Get string with sitemap of landing pages
	 * @return string 	Sitemap data
	 */
	public function get_html_sitemap() : string
	{
		$output = '<ul>';
		$cities_data = $this->get_cities_data();

		foreach( $cities_data as $c_data ){
			$link = "<li><a href='{$c_data['link']}'>{$c_data['title']}</a></li>";
			$output .= $link;
		}

		$output .= '</ul>';
		return $output;
	}
}