<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
	protected $fillable = [
		"groups",
		"field",
		"goal_field",
		"goal_outfield",
		"outfield",
		"tournamed",
	];

	public function field()
	{
		return $this->belongsTo('App\Models\Soccer', 'field', 'id');
	}

	public function tournamed()
	{
		return $this->belongsTo('App\Models\Tournamed', 'tournamed', 'id');
	}

	public function outfield()
	{
		return $this->belongsTo('App\Models\Soccer', 'outfield', 'id');
	}

	public function groups()
	{
		return $this->belongsTo('App\Models\Groups', 'groups', 'id');
	}

	protected $hidden = [];
}
