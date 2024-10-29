<?php

namespace WeDevs\ORM\WP;

use WeDevs\ORM\Eloquent\Model;

class Country extends Model {
	protected $table;
	protected $table_prefix;

	protected $fillable = [];
	protected $primaryKey = 'id';
	protected $guarded = ['id'];

	public $timestamps = false;

	public function __construct( array $attrs = array() )
	{
		parent::__construct( $attrs );
		global $wpdb;
		$this->table_prefix = $wpdb->prefix;
		$this->table = "{$this->table_prefix}lpw_countries";
	}

	public function get_table_name()
	{
		return $this->table;
	}
}