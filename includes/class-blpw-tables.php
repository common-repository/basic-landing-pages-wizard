<?php

/**
 * Class for DB tables
 * with create, and fill default
 * information
 */

class BLPW_Tables {

	private $table_prefix;
	private $charset;
	private $data_url_local;

	function __construct()
	{
		global $wpdb;
		$this->table_prefix = "{$wpdb->prefix}lpw_";
		$this->charset = $wpdb->get_charset_collate();
		$this->data_url_local = plugin_dir_path( dirname( __FILE__ ) ).'includes/json/';
	}


	/**
	 * Build all tables with default data
	 * @return None
	 */
	public function build_tables()
	{
		$this->build_countries();
		$this->build_states();
		$this->build_counties();
		$this->build_cities();
	}


	/**
	 * Build countries table
	 * @return None
	 */
	private function build_countries()
	{
		$table_name = $this->table_prefix.'countries';
		$sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
						  `id` mediumint(8) unsigned NOT NULL PRIMARY KEY,
						  `name` varchar(100) NOT NULL,
						  `iso3` char(3) DEFAULT NULL,
						  `iso2` char(2) DEFAULT NULL,
						  `phonecode` varchar(255) DEFAULT NULL,
						  `capital` varchar(255) DEFAULT NULL,
						  `currency` varchar(255) DEFAULT NULL
						){$this->charset};";
		$sql .= " INSERT INTO `{$table_name}` (`id`, `name`, `iso3`, `iso2`, `phonecode`, `capital`, `currency`) VALUES";
		//Read JSON with countries data
		$raw_data = file_get_contents( $this->data_url_local.'countries.json' );
		$json_data = json_decode( $raw_data, true );
		$raw_data = null; //Free content
		foreach( $json_data as $cn ){
			$sql .= "(";
			$sql .= $cn['id'].',';
			$sql .= "'".addslashes( $cn['name'] )."',";
			$sql .= "'".$cn['iso3']."',";
			$sql .= "'".$cn['iso2']."',";
			$sql .= "'".$cn['phone_code']."',";
			$sql .= "'".addslashes( $cn['capital'] )."',";
			$sql .= "'".$cn['currency']."'";
			$sql .= "),";
		}
		$sql = substr_replace( $sql, ';', -1 );
		require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		$sql = null;
	}


	/**
	 * Build states table
	 * @return None
	 */
	private function build_states()
	{
		$table_name = $this->table_prefix.'states';
		$sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
						  `id` mediumint(8) unsigned NOT NULL PRIMARY KEY,
						  `name` varchar(255) NOT NULL,
						  `country_id` mediumint(8) unsigned NOT NULL,
						  `state_code` char(2) NOT NULL,
						  `phone` varchar( 20 ) NOT NULL DEFAULT '',
						  `selected` tinyint(1) NOT NULL DEFAULT '0'
						) {$this->charset} ROW_FORMAT=COMPACT;";
		$sql .= " INSERT INTO `{$table_name}` (`id`, `name`, `country_id`, `state_code`, `phone`, `selected`) VALUES";
		//Read JSON with states data
		$raw_data = file_get_contents( $this->data_url_local.'states.json' );
		$json_data = json_decode( $raw_data, true );
		$raw_data = null; //Free content
		foreach( $json_data as $cn ){
			$sql .= "(";
			$sql .= $cn['id'].',';
			$sql .= "'".addslashes( $cn['name'] )."',";
			$sql .= strval( $cn['country_id'] ).",";
			$sql .= "'".$cn['state_code']."',";
			$sql .= "'',";
			$sql .= "0";
			$sql .= "),";
		}
		$sql = substr_replace( $sql, ';', -1 );
		require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		$sql = null;
	}


	/**
	 * Build counties table
	 * @return None
	 */
	private function build_counties()
	{
		$table_name = $this->table_prefix.'counties';
		$sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
						  `id` mediumint(8) unsigned NOT NULL PRIMARY KEY,
						  `name` varchar(100) NOT NULL,
						  `country_id` mediumint(8) unsigned NOT NULL,
						  `state_id` mediumint(8) unsigned NOT NULL,
						  `phone` varchar( 20 ) NOT NULL DEFAULT '',
						  `selected` tinyint(1) NOT NULL DEFAULT '0'
						) {$this->charset} ROW_FORMAT=COMPACT;";
		$sql .= " INSERT INTO `{$table_name}` (`id`, `name`, `country_id`, `state_id`, `phone`, `selected`) VALUES";
		//Read JSON with counties data
		$raw_data = file_get_contents( $this->data_url_local.'counties.json' );
		$json_data = json_decode( $raw_data, true );
		$raw_data = null; //Free content
		foreach( $json_data as $cn ){
			$sql .= "(";
			$sql .= $cn['id'].',';
			$sql .= "'".addslashes( $cn['name'] )."',";
			$sql .= strval( $cn['country_id'] ).",";
			$sql .= strval( $cn['state_id'] ).",";
			$sql .= "'',";
			$sql .= "0";
			$sql .= "),";
		}
		$sql = substr_replace( $sql, ';', -1 );
		require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		$sql = null;
	}


	/**
	 * Build cities table
	 * @return None
	 */
	private function build_cities()
	{
		$table_name = $this->table_prefix.'cities';
		$sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
						  `id` mediumint(8) unsigned NOT NULL PRIMARY KEY,
						  `name` varchar(255) NOT NULL,
						  `state_id` mediumint(8) unsigned NOT NULL,
						  `country_id` mediumint(8) unsigned NOT NULL,
						  `county_id` mediumint(8) NOT NULL,
						  `latitude` decimal(10,8) NOT NULL,
						  `longitude` decimal(11,8) NOT NULL,
						  `phone` varchar( 20 ) NOT NULL DEFAULT '', 
						  `selected` tinyint(1) NOT NULL DEFAULT '0'
						) {$this->charset} ROW_FORMAT=COMPACT;";
		require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
		$sql .= " INSERT INTO `{$table_name}` (`id`, `name`, `state_id`, `country_id`, `county_id`, `latitude`, `longitude`, `phone`, `selected`) VALUES";
		//Read JSON with sities data
		$raw_data = file_get_contents( $this->data_url_local.'cities.json' );
		$json_data = json_decode( $raw_data, true );
		$raw_data = null; //Free content
		foreach( $json_data as $cn ){
			$country_id = ( isset( $cn['country_id'] ) )?strval( $cn['country_id'] ):'2'; //"Bone" for USA data
			$sql .= "(";
			$sql .= $cn['id'].',';
			$sql .= "'".addslashes( $cn['name'] )."',";
			$sql .= strval( $cn['state_id'] ).",";
			$sql .= $country_id.",";
			$sql .= strval( $cn['county_id'] ).",";
			$sql .= strval( $cn['latitude'] ).",";
			$sql .= strval( $cn['longitude'] ).",";
			$sql .= "'',";
			$sql .= "0";
			$sql .= "),";
		}
		$sql = substr_replace( $sql, ';', -1 );
		dbDelta( $sql );
		$sql = null;
	}


	/**
	 * Drop tables with full data
	 * @return none
	 */
	public function drop_tables()
	{
		global $wpdb;
		$sql = "DROP TABLE IF EXISTS ";
		$sql .= $this->table_prefix.'countries,';
		$sql .= $this->table_prefix.'states,';
		$sql .= $this->table_prefix.'counties,';
		$sql .= $this->table_prefix.'cities;';
		$wpdb->query( $sql );
	}
}