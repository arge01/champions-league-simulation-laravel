<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use Illuminate\Http\Request;

class GroupsController extends ImpController
{
  public function __construct(Request $request, Groups $model) {
    parent::__construct($request, $model);

    parent::withColumn('match');
  }

  public function multi($tournamed) {
    ini_set('max_execution_time', 3600);

    $response = [];
    $data = $this->request();

    return response()->json($data);
  }
}
