<?php

class BLPW_All_Routing {
	private $page_name_format = '(.?.+?)';
	private $location_format = '([^&]+)';
	private $id_format = '([0-9]+)';


	public function register_rewrite_tags()
	{
		add_rewrite_tag( '%xml_sitemap%', $this->page_name_format );
		add_rewrite_tag( '%city%', $this->location_format );
		add_rewrite_tag( '%city_id%', $this->location_format );
		add_rewrite_tag( '%county%', $this->location_format );
		add_rewrite_tag( '%county_id%', $this->location_format );
		add_rewrite_tag( '%state%', $this->location_format );
		add_rewrite_tag( '%state_id%', $this->location_format );
		add_rewrite_tag( '%show_pages%', $this->location_format );
		add_rewrite_tag( '%template_id%', $this->location_format ); //????
	}


	public function register_sitemap_rewrite()
	{
		$menu_slug = 'sitemap';

		add_rewrite_rule(
			"^{$menu_slug}/?$",
			'index.php?pagename='.$menu_slug,
			'top'
		);

		add_rewrite_rule(
			"^{$menu_slug}/county/{$this->id_format}/btp/{$this->id_format}/?$",
			'index.php?pagename='.$menu_slug.'&county_id=$matches[1]&template_id=$matches[2]',
			'top'
		);

		add_rewrite_rule(
			"^{$menu_slug}/state/{$this->id_format}/btp/{$this->id_format}/?$",
			'index.php?pagename='.$menu_slug.'&state_id=$matches[1]&template_id=$matches[2]',
			'top'
		);

		add_rewrite_rule(
			"^{$menu_slug}/state/{$this->id_format}/?$",
			'index.php?pagename='.$menu_slug.'&state_id=$matches[1]',
			'top'
		);

	}


	public function register_location_rewrite()
	{
		//City: City / State / ID
		add_rewrite_rule( 
			"^{$this->page_name_format}/{$this->location_format}/{$this->location_format}/{$this->id_format}/?$",
			'index.php?pagename=$matches[1]&city=$matches[2]&state=$matches[3]&city_id=$matches[4]',
			'top'
		 );

		//City: City / State
		add_rewrite_rule( 
			"^{$this->page_name_format}/{$this->location_format}/{$this->location_format}/?$",
			'index.php?pagename=$matches[1]&city=$matches[2]&state=$matches[3]',
			'top'
		 );

		//County: County / State
		add_rewrite_rule( 
			"^county/{$this->id_format}/{$this->page_name_format}/{$this->location_format}/{$this->location_format}/?$",
			'index.php?county_id=$matches[1]&pagename=$matches[2]&county=matches[3]&state=$matches[4]',
			'top'
		 );
	}


	public function flush_rewrite()
	{
		flush_rewrite_rules();
	}
}