<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Power extends Model
{
	protected $table = 'power';

	protected $fillable = [
		'field',
		'outfield',
		'power',
		'playing',
		'fortunate',
	];

	protected $hidden = [];
}
