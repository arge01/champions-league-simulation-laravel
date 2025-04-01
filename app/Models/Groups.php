<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
  protected $table = 'groups';

	protected $fillable = [
		'name',
		'matches',
	];

	protected $hidden = [];

  public function match()
	{
		return $this->hasMany('App\Models\Matches', 'id', 'matches');
	}
}
