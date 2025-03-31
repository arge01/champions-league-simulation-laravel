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

    if (!auth('api')->user()) {
      return response()->json(["auth" => "Error"], 403);
    }

    parent::filter("user", auth('api')->user()->id);
    parent::request("user", auth('api')->user()->id);
  }
}
