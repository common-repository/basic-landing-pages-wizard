<?php

class BLPW_All_XML_Sitemap {
	private $map_dir;
	private $map_url;

	function __construct()
	{
		$upload_dir = wp_upload_dir();
		$default_map_slug = 'sitemap';
		$this->map_dir = $upload_dir['basedir'].'/'.$default_map_slug;
		$this->map_url = $upload_dir['baseurl'].'/'.$default_map_slug;
		$this->create_map_path();
	}


	/**
	 * Create complete landing sitemap
	 * @return bool
	 */
	public function create_maps() : bool
	{
		$ret = true;
		$ret2 = true;
		$is_xml_sitemap = BLPW_Options::get_option( 'blpw_company_info_block', 'xml_sitemap', 'off', 'wp' );
		if( $is_xml_sitemap != 'off' && $is_xml_sitemap != false ){
			if( $this->clear_map_dir() ){
				$ret = $this->create_landing_map();
				$ret2 = $this->create_map_index();
			}
		}
		return ( $ret && $ret2 );
	}


	/**
	 * Create path where xml sitemap will be
	 * @return bool Status of operation
	 */
	private function create_map_path() : bool
	{
		if( file_exists( $this->map_dir ) ){
			return true;
		}
		if( mkdir( $this->map_dir ) ){
			return true;
		}
		return false;
	}


	/**
	 * Remove all sitemaps
	 * @return bool Status of operations
	 */
	private function clear_map_dir() : bool
	{
		$files = glob( $this->map_dir.'/*.xml' );
		$flag = true;
		foreach( $files as $file ){
			$flag = $this->remove_map_file( $file );
			if( !$flag ){
				break;
			}
		}
		return $flag;
	}


	/**
	 * Remove one map file
	 * @param  string $file Filename with path
	 * @return bool       	Status of operations
	 */
	private function remove_map_file( string $file = '' ) : bool
	{
		if( file_exists( $file ) ){
			return unlink( $file );
		}
		return true;
	}


	/**
	 * Get sitemap header
	 * @param  bool|boolean $index Is for sitemap index?
	 * @return string              Header block
	 */
	private function get_header( bool $index = true ) : string
	{
		$ret = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		if( $index ){
			$ret .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
		}else{
			$ret .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
		}

		return $ret;
	}


	/**
	 * Get sitemap footer
	 * @param  bool|boolean $index Is for sitemap index?
	 * @return string              Footer block
	 */
	private function get_footer( bool $index = true ) : string
	{
		$ret = '';
		if( $index ){
			$ret = '</sitemapindex>'."\n";
		}else{
			$ret = '</urlset>'."\n";
		}

		return $ret;
	}


	/**
	 * Create sitemap from landing pages
	 * @return bool Status of operation
	 */
	private function create_landing_map() : bool
	{
		$file_name = $this->map_dir.'/posts.xml';
		$query_args = [
			'post_type' => 'blpw-landing-page',
			'post_status' => 'publish',
			'posts_per_page' => -1
		];
		$posts = get_posts( $query_args );
		$xml = $this->get_header( false );
		foreach( $posts as $post ){
			$post_attrs = [
				'link' => get_the_permalink( $post->ID ),
				'lastmod' => get_the_modified_date( 'Y-m-d h:m:s', $post->ID ),
				'changefreq' => 'weekly',	//ToDo - from options get freq
				'priority' => 0.8			//ToDo - Think about priority
			];
			$xml .= $this->xml_block( $post_attrs );
		}
		wp_reset_postdata();
		$xml .= $this->get_footer( false );
		return $this->write_file( $file_name, $xml );
	}


	/**
	 * Create indexfilr of sitemap
	 * @return bool
	 */
	private function create_map_index() : bool
	{
		$xml = $this->get_header( true );
		$file_name = $this->map_dir.'/index.xml';
		$xml .= "\t".'<sitemap>'."\n";
		$xml .= "\t\t".'<loc>'.$this->map_url.'/posts.xml'.'</loc>'."\n";
		$xml .= "\t".'</sitemap>'."\n";
		$xml .= $this->get_footer( true );
		return $this->write_file( $file_name, $xml );
	}


	/**
	 * Make XML block for sitemap from attributes
	 * @param  array  $attrs Block Attributes
	 * @return string        XML Block
	 */
	private function xml_block( $attrs = array() ) : string
	{
		$ret = "\t".'<url>'."\n";
		$ret .= "\t\t".'<loc>'.$attrs['link'].'</loc>'."\n";
		$ret .= "\t\t".'<lastmod>'.$attrs['lastmod'].'</lastmod>'."\n";
		$ret .= "\t\t".'<changefreq>'.$attrs['changefreq'].'</changefreq>'."\n";
		if( isset( $attrs['priority'] ) ){
			$ret .= "\t\t".'<priority>'.$attrs['priority'].'</priority>'."\n";
		}
		$ret .= "\t".'</url>'."\n";
		return $ret;
	}


	/**
	 * Write map file
	 * @param  string $file    File with path
	 * @param  string $content Content of file
	 * @return bool
	 */
	private function write_file( string $file = '', string $content = '' ) : bool
	{
		$ret = $this->remove_map_file( $file );
		if( !$ret ){
			return $ret;
		}

		$fp = fopen( $file, 'w' ) or die( 'Cannot create file!' );
		$fw = fwrite( $fp, $content );
		fclose( $fp );
		if( !$fw ){
			$ret = false;
		}
		return $ret;
	}
}