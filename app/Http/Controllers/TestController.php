<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Test;

class TestController extends ImpController
{
  public function __construct(Request $request, Test $model)
  {
    $this->user = auth()->user();
    parent::__construct($request, $model);
  }
}
