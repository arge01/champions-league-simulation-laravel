<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
  protected $table = 'test';

	protected $fillable = [
    'title', 'desc', 'user'
  ];

  protected $hidden = ['user'];
}
