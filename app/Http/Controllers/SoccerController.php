<?php

namespace App\Http\Controllers;

use App\Models\Power;
use App\Models\Soccer;
use Illuminate\Http\Request;

class SoccerController extends ImpController
{
  public function __construct(Request $request, Soccer $model)
  {
    parent::__construct($request, $model);

    parent::orderBy('updated_at', 'DESC');
  }

  public function multi()
  {
    ini_set('max_execution_time', 3600);

    $response = [];
    $data = $this->request();

    foreach ($data as $key => $value) {
      try {
        $power_create = Power::create($value["power"]);

        if ($power_create->id) {
          $soccer = [
            "tournamed" => $value["tournamed"],
            "power" => $power_create->id,
            "name" => $value["name"],
            "colors" => $value["colors"],
            "country" => $value["country"],
          ];

          try {
            $soccer_create = Soccer::create($soccer);
            $response[$key] = $soccer_create;
          } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
          }
        }
      } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
      }
    }

    return response()->json($response);
  }
}
