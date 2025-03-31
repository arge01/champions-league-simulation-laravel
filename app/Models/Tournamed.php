<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournamed extends Model
{
	protected $table = 'tournamed';

	protected $fillable = [
		'name',
		'key',
		'desc',
		'user'
	];

	protected $hidden = [
    'user',
  ];
}
