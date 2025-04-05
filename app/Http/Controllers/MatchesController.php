<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use Illuminate\Http\Request;

class MatchesController extends ImpController
{
  public function __construct(Request $request, Matches $model)
  {
    parent::__construct($request, $model);

    parent::withColumn('field');
    parent::withColumn('field.power');
    parent::withColumn('outfield');
    parent::withColumn('outfield.power');
    parent::withColumn('groups');
    parent::withColumn('tournamed');
  }

  public function multi()
  {
    ini_set('max_execution_time', 3600);

    $response = [];
    $data = $this->request();

    foreach ($data as $key => $value) {
      $match = [
        "groups" => $value["groups"]["id"],
        "field" => $value["field"]["id"],
        "goal_field" => $value["goalField"],
        "goal_outfield" => $value["goalOutField"],
        "outfield" => $value["outfield"]["id"],
        "tournamed" => $value["tournamed"]["id"],
      ];

      try {
        $match_create = Matches::create($match);
        $response[$key] = $match_create;
      } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
      }
    }

    return response()->json($response);
  }
}
