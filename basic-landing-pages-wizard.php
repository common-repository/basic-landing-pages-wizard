<?php
/**
 * @link https://www.proffibit.com/products/basic-landing-pages-wizard.html
 *
 * Plugin Name: Basic Landing Pages Wizard
 * Description: The Offical Proffibit SEO Landing Pages Wizard plugin
 * Version: 1.0.0
 * Plugin URI: https://www.proffibit.com/products/basic-landing-pages-wizard.html
 * Author: proffibit
 * Author URI: https://www.proffibit.com
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


/**
 * Activate Plugin
 * @return None
 */
function activate_blpw()
{
	require_once( plugin_dir_path( __FILE__ ).'includes/class-blpw-activator.php' );
	$Activator = new BLPW_Activator();
	$Activator->activate();
}
register_activation_hook( __FILE__, 'activate_blpw' );


/**
 * Deactivate plugin
 * @return None
 */
function deactivate_blpw()
{
	require_once( plugin_dir_path( __FILE__ ).'includes/class-blpw-deactivator.php' );
	$Deactivator = new BLPW_Deactivator();
	$Deactivator->deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_blpw' );


//Start plugin
require_once( plugin_dir_path( __FILE__ ).'includes/class-blpw.php' );
new BLPW();


//-----------------------------------------------------------------------------
//For logging
if( !function_exists( 'write_log' ) ){
	function write_log( $log )
	{
		if( WP_DEBUG == true ){
			if( is_array( $log ) || is_object( $log ) ){
				error_log( print_r( $log, true ) );
			}else{
				error_log( $log );
			}
		}
	}
}