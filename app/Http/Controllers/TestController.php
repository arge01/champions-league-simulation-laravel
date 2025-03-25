<?php

namespace App\Http\Controllers;

use App\Models\Test;

class TestController extends ImpController
{
  public function __construct(Test $model)
  {
    parent::__construct($model);
  }
}
