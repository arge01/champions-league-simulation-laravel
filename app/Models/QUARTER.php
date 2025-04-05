<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QUARTER extends Model
{
	protected $table = 'quarter';

	protected $fillable = [
		"field",
		"goal_field",
		"goal_outfield",
		"outfield",
		"tournamed",

		"goal_outfield_penalty",
		"goal_field_penalty",
		"is_penalty",
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
