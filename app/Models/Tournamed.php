<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournamed extends Model
{
	protected $table = 'tournamed';

	protected $fillable = [
		'id',
		'name',
		'key',
		'desc',
	];

	protected $hidden = [
    'user',
  ];
}
