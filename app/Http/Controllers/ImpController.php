<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;

abstract class ImpController
{
  protected $model;
  protected $with = [];

  public function __construct(Model $model)
  {
    // ->where("user", auth()->user()->id)
    $this->model = $model;
  }

  private function fill() {
    return $this->model->where("user", auth()->user()->id);
  } 

  private function data($request) {
    $data = $request->json()->all();
    $data["user"] = auth()->user()->id;

    return $data;
  }

  protected function error($message) {
    Log::error("Error updating record: " . $message);
    return response()->json(["message" => "Error, the error you received has been logged"], 500);
  } 

  /**
   * Set relationships to eager load
   */
  public function with(array $relations): self
  {
    $this->with = $relations;
    return $this;
  }

  /**
   * Get all records
   */
  public function all(array $columns = ['*'])
  {
    try {
      return $this->fill()->with($this->with)->get($columns);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  /**
   * Get single record by ID
   */
  public function get($id, array $columns = ['*'])
  {
    try {
      return $this->fill()->with($this->with)->findOrFail($id, $columns);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  /**
   * Get records by criteria
   */
  public function criteria(Request $request, array $columns = ['*'])
  {
    $data = $this->data($request);
    try {
      $query = $this->fill()->with($this->with);

      foreach ($data as $criterion) {
        $query->where($criterion[0], $criterion[1], $criterion[2] ?? null);
      }

      return $query->get($columns);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  /**
   * Create new record
   */
  public function post(Request $request)
  {
    $data = $this->data($request);

    DB::beginTransaction();
    try {
      $record = $this->fill()->create($data);
      DB::commit();
      return $record;
    } catch (Exception $e) {
      DB::rollBack();
      return $this->error($e->getMessage());
    }
  }

  /**
   * Update record
   */
  public function put($id, Request $request)
  {
    $data = $this->data($request);

    DB::beginTransaction();
    try {
      $record = $this->fill()->findOrFail($id);
      $record->update($data);
      DB::commit();
      return $record;
    } catch (Exception $e) {
      DB::rollBack();
      Log::error("Error updating record {$id}: " . $e->getMessage());
      throw $e;
    }
  }

  /**
   * Delete record
   */
  public function delete($id)
  {
    DB::beginTransaction();
    try {
      $record = $this->fill()->findOrFail($id);
      $record->delete();
      DB::commit();
      return response()->json(true);
    } catch (Exception $e) {
      DB::rollBack();
      return response()->json(false);
    }
  }

  /**
   * Partial update
   */
  public function patch($id, Request $request)
  {
    return $this->put($id, $request);
  }
}
