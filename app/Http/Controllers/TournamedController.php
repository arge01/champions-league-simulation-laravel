<?php

namespace App\Http\Controllers;

use App\Models\Tournamed;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

class TournamedController extends ImpController
{
  public function __construct(Request $request, Tournamed $model) {
    $this->user = auth()->user();
    parent::__construct($request, $model);

    if (!auth('api')->user()) {
      return response()->json(["auth" => "Error"], 403);
    }

    if (request()->isMethod('POST')) {
      $this->data["key"] = substr(md5($this->data["name"]), 0, 8);
    }

    parent::filter("user", auth('api')->user()->id);
    parent::orderBy('updated_at', 'DESC');
    parent::request("user", auth('api')->user()->id);
  }
}
