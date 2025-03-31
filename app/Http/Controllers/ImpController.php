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

  protected function orderBy(string $column, string $direction = 'ASC')
  {
    $this->model = $this->model->orderBy($column, $direction);
    return $this->model;
  }

  protected function filter(?string $column = null, ?string $filter = null)
  {
    if ($column && $filter) {
      $this->model = $this->model->where($column, $filter);
    }
    return $this->model;
  }

  protected function withColumn(?string $column = null)
  {
    if ($column) {
      $this->model = $this->model->with($column);
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
      return $this->filter()->with($this->with)->get($columns);
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
      return $this->filter()->with($this->with)->findOrFail($id, $columns);
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
      $query = $this->filter()->with($this->with);

      foreach ($this->request() as $criterion) {
        if (is_array($criterion)) {
          foreach ($criterion as $key => $value) {
            // array is object
            if (is_array($value) && isset($value['id'])) {
              $query->where($key, $value['id']);
            } else {
              $query->where($key, $value);
            }
          }
        }
      }

      return $query->get($columns);
    } catch (Exception $e) {
      return [];
    }
  }

  /**
   * Create new record
   */
  public function post()
  {
    DB::beginTransaction();
    try {
      $record = $this->filter()->create($this->request());
      DB::commit();
      return $record;
    } catch (Exception $e) {
      DB::rollBack();
      return $this->error($e->getMessage());
    }
  }

  /**
   * Get criteria
   */
  public function get_criteria($key, $column)
  {
    try {
      return $this->filter()->with($this->with)->where($key, $column)->first();
    } catch (Exception $e) {
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
      $record = $this->filter()->findOrFail($id);
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
      $record = $this->filter()->findOrFail($id);
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
