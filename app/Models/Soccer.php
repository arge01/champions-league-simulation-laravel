<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soccer extends Model
{
	protected $table = 'soccer';

	protected $fillable = [
		'tournamed',
		'groups',
		'power',
		'name',
		'colors',
		'country',
	];

	protected $hidden = [];

	public function power()
	{
		return $this->belongsTo('App\Models\Power', 'power', 'id');
	}

	public function groups()
	{
		return $this->belongsTo('App\Models\Groups', 'groups', 'id');
	}

	public function tournamed()
	{
		return $this->belongsTo('App\Models\Tournamed', 'tournamed', 'id');
	}
}
