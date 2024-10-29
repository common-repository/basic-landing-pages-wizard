<?php

/**
 * Container for admin classes
 */

class BLPW_Admin {

	function __construct()
	{
		$this->load_dependences();

		$this->load_hooks();
	}


	private function load_dependences()
	{
		//CMB2 Plugin
		require_once( plugin_dir_path( __FILE__ ).'plugins/cmb2/init.php' );
		require_once( plugin_dir_path( __FILE__ ).'plugins/cmb2-switch-button/cmb2-switch-button.php' );
		require_once( plugin_dir_path( __FILE__ ).'plugins/cmb2-button/class-cmb2-button.php' );
		require_once( plugin_dir_path( __FILE__ ).'plugins/cmb2-grid-master/Cmb2GridPlugin.php' );
		//Wizard
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'admin/class-blpw-wizard.php' );
		//Landing Pages
		require_once( plugin_dir_path( dirname( __FILE__ ) ).'admin/class-blpw-register-landings-page.php' );
	}


	public function init_wizard()
	{
		$Wizard = new BLPW_Wizard();
		$Wizard->register_menu();
	}


	public function load_hooks()
	{
		add_action( 'admin_enqueue_scripts', function(){
			//Admin styles
			wp_enqueue_style( 'blpw_admin_css', plugin_dir_url( __FILE__ ).'css/blpw-admin.css' );
			//Autocomplete jQuery plugin
			wp_enqueue_script( 'blpw_autocomplete', plugin_dir_url( __FILE__ ).'js/wp-jquery.easy-autocomplete.js' );
			wp_enqueue_style( 'blpw_autocomplete_css', plugin_dir_url( __FILE__ ).'css/easy-autocomplete.min.css' );
			//Utilites
			wp_enqueue_script( 'blpw_wizard_utils', plugin_dir_url( __FILE__ ).'js/blpw-wizard-utils.js' );
			//Wizard routines
			wp_enqueue_script( 'blpw_wizard', plugin_dir_url( __FILE__ ).'js/blpw-wizard.js' );
		} );
	}


	public function register_page_post_types()
	{
		new BLPW_Register_Landings_Page();
	}
}