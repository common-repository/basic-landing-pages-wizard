<?php

class BLPW_All {

	function __construct()
	{
		//Dependences
		$this->load_dependences();
		//Load hooks
		$this->load_hooks();
		//Start sitemaps
		//$this->start_xml_sitemap();
		//Filters
		add_filter( 'template_include', array( $this, 'locale_landing_page_theme_template' ), 99, 1 );
		//Actions
		add_action( 'init', [$this, 'register_routing'], 10, 0 );
		//Shortcodes
		$this->register_shortcodes();
	}


	private function load_dependences()
	{
		//Shortcodes
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'all/class-blpw-all-shortcodes.php' );
		//Routing
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'all/class-blpw-all-routing.php' );
		//XML Sitemap
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'all/class-blpw-all-xml-sitemap.php' );
		//HTML Sitemap
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'all/class-blpw-all-html-sitemap.php' );
	}


	public function register_shortcodes()
	{
		$shortcodes = new BLPW_All_Shortcodes();
		//Add global shortcodes
		add_shortcode( 'lpw_company_name', [$shortcodes, 'company_name'] );
		add_shortcode( 'lpw_company_phone', [$shortcodes, 'company_phone'] );
		add_shortcode( 'lpw_company_phone_number', [$shortcodes, 'company_phone'] );
		add_shortcode( 'lpw_company_address', [$shortcodes, 'company_address'] );
		add_shortcode( 'lpw_company_logo', [$shortcodes, 'company_logo'] );
		add_shortcode( 'lpw_company_mobile_logo', [$shortcodes, 'company_mobile_logo'] );
		add_shortcode( 'lpw_company_social', [$shortcodes, 'company_social'] );
		add_shortcode( 'lpw_city', [$shortcodes, 'city'] );
		add_shortcode( 'lpw_city_phone', [$shortcodes, 'city_phone'] );
		add_shortcode( 'lpw_state', [$shortcodes, 'state'] );
		add_shortcode( 'lpw_county', [$shortcodes, 'county'] );
		add_shortcode( 'lpw_site_name', [$shortcodes, 'site_name'] );
		add_shortcode( 'lpw_page_title', [$shortcodes, 'page_title'] );
		add_shortcode( 'lpw_html_sitemap', [$shortcodes, 'html_landing_sitemap'] );
		//Blocks
		//Slider
		add_shortcode( 'lpw_slider_headline', [$shortcodes, 'slider_headline'] );
		add_shortcode( 'lpw_slider_background', [$shortcodes, 'slider_background'] );
		add_shortcode( 'lpw_slider_bullet_point', [$shortcodes, 'slider_bullet_point'] );
		//Info block 1 and 2
		add_shortcode( 'lpw_block_headline', [$shortcodes, 'block_headline'] );
		add_shortcode( 'lpw_block_text', [$shortcodes, 'block_text'] );
		//Trusted symbols
		add_shortcode( 'lpw_trusted_symbol', [$shortcodes, 'trusted_symbol'] );
		//Last projects
		add_shortcode( 'lpw_last_project_headline', [$shortcodes, 'last_projects_headline'] );
		add_shortcode( 'lpw_last_project_picture', [$shortcodes, 'last_projects_picture'] );
		//w2e
		add_shortcode( 'lpw_w2e_photo', [$shortcodes, 'w2e_photo'] );
		add_shortcode( 'lpw_w2e_bullet_point', [$shortcodes, 'w2e_bullet_point'] );
		//Testimonials
		add_shortcode( 'lpw_testimonial', [$shortcodes, 'testimonial'] );
	}


	public function locale_landing_page_theme_template( $template )
	{
		if( BLPW_Landing_Page::is_landing_page() ){
			$page_template = plugin_dir_path( dirname( __FILE__ ) ).'all/template/blpw_page.php';
			if( file_exists( $page_template ) ){
				return $page_template;
			}
		}

		return $template;
	}


	public function register_routing()
	{
		$routing = new BLPW_All_Routing();
		$routing->register_rewrite_tags();
		$routing->register_location_rewrite();
		$routing->register_sitemap_rewrite();
		$routing->flush_rewrite();
	}


	public function load_hooks()
	{
		add_action( 'wp_enqueue_scripts', function(){
			//JS library
			wp_enqueue_script( 'blpw_ant_lib', plugin_dir_url( __FILE__ ).'template/js/ant.carousel.js' );
			//Styles
			wp_enqueue_style( 'blpw_template_css', plugin_dir_url( __FILE__ ).'template/css/style.css' );
			wp_enqueue_style( 'blpw_ant_css', plugin_dir_url( __FILE__ ).'template/css/ant.css' );
		} );
	}
}