<?php

namespace WeDevs\ORM\WP;

use WeDevs\ORM\Eloquent\Model;

class County extends Model {
	protected $table;
	protected $table_prefix;

	protected $fillable = ['selected', 'phone'];
	protected $primaryKey = 'id';
	protected $foreignKey = 'state_id';
	protected $guarded = ['id'];

	public $timestamps = false;

	public function __construct( array $attrs = array() )
	{
		parent::__construct( $attrs );
		global $wpdb;
		$this->table_prefix = $wpdb->prefix;
		$this->table = "{$this->table_prefix}lpw_counties";
	}

	public function get_table_name()
	{
		return $this->table;
	}

	public function state()
	{
		return $this->belongsTo( 'WeDevs\ORM\WP\State' );
	}
}