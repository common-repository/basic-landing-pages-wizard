<?php

/**
 * Class container for plugin
 */

class BLPW {

	function __construct()
	{
		$this->load_dependences();

		$this->start_admin();

		$this->start_all();
	}


	/**
	 * Load plugin dependences
	 * @return None
	 */
	private function load_dependences()
	{
		//Options
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'includes/class-blpw-options.php' );
		//Utilites
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'includes/class-blpw-utils.php' );
		//Vendor ORM and models
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'vendor/autoload.php' );
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'includes/models/class-countries.php' );
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'includes/models/class-states.php' );
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'includes/models/class-counties.php' );
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'includes/models/class-cities.php' );
		//Admin
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'admin/class-blpw-admin.php' );
		//All
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'all/class-blpw-all.php' );
		//Landing pages
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'includes/class-blpw-landing-page.php' );
	}


	private function start_admin()
	{
		$Admin = new BLPW_Admin();
		add_action( 'cmb2_admin_init', [$Admin, 'init_wizard'] );

		add_action( 'init', [$Admin, 'register_page_post_types'], 1, 1 );
	}


	private function start_all()
	{
		new BLPW_All();
	}
}