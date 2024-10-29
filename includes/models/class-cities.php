<?php

namespace WeDevs\ORM\WP;

use WeDevs\ORM\Eloquent\Model;

class City extends Model {
	protected $table;
	protected $table_prefix;

	protected $fillable = ['selected', 'phone'];
	protected $primaryKey = 'id';
	protected $foreignKey = ['county_id', 'state_id'];
	protected $guarded = ['id'];

	public $timestamps = false;

	public function __construct( array $attrs = array() )
	{
		parent::__construct( $attrs );
		global $wpdb;
		$this->table_prefix = $wpdb->prefix;
		$this->table = "{$this->table_prefix}lpw_cities";
	}

	public function get_table_name()
	{
		return $this->table;
	}

	public function county()
	{
		return $this->belongsTo( 'WeDevs\ORM\WP\County', 'county_id' );
	}

	public function state()
	{
		return $this->belongsTo( 'WeDevs\ORM\WP\State', 'state_id' );
	}
}