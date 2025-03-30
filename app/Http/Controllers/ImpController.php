<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;

abstract class ImpController
{
  protected $user;

  protected $model;

  protected $request;
  protected $data;

  protected $with = [];

  public function __construct(Request $request, Model $model)
  {
    $this->request = $request;
    $this->model = $model;
    $this->data = $request->json()->all();
  }

  protected function fillter(?string $column = null, ?string $filter = null)
  {
    if ($column && $filter) {
      $this->model = $this->model->where($column, $filter);
    }
    return $this->model;
  }

  protected function request(?string $key = null, ?string $value = null)
  {
    if ($key && $value) {
      $this->data[$key] = $value;
      return $this->data;
    }

    return $this->data;
  }

  protected function error($message)
  {
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
      return $this->fillter()->with($this->with)->get($columns);
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
      return $this->fillter()->with($this->with)->findOrFail($id, $columns);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  /**
   * Get records by criteria
   */
  public function criteria(array $columns = ['*'])
  {
    try {
      $query = $this->fillter()->with($this->with);

      foreach ($this->request() as $criterion) {
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
  public function post()
  {
    DB::beginTransaction();
    try {
      $record = $this->fillter()->create($this->request());
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
  public function put($id)
  {
    DB::beginTransaction();
    try {
      $record = $this->fillter()->findOrFail($id);
      $record->update($this->request());
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
      $record = $this->fillter()->findOrFail($id);
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
  public function patch($id)
  {
    return $this->put($id, $this->request());
  }
}
