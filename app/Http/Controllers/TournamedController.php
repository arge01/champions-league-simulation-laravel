<?php

namespace App\Http\Controllers;

use App\Models\Tournamed;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

class TournamedController extends ImpController
{
  public function __construct(Request $request, Tournamed $model) {
    $this->user = auth()->user();
    parent::__construct($request, $model);

    if (!auth('api')->user()) {
      return response()->json(["auth" => "Error"], 403);
    }

    if (request()->isMethod('POST')) {
      $this->data["key"] = Hash::make($this->data["name"]);
    }

    parent::fillter("user", auth('api')->user()->id);
    parent::request("user", auth('api')->user()->id);
  }
}
