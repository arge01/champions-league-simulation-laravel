<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('cors')->group(function () {
	Route::get('/test/all', function () {
		return response()->json([
			['id' => 1, 'title' => 'Title 1', 'desc' => 'Description 1'],
			['id' => 2, 'title' => 'Title 2', 'desc' => 'Description 2'],
		]);
	});

	// GET request for fetching a single item
	Route::get('/test', function () {
		return response()->json([
			'id' => 1,
			'title' => 'Title',
			'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ornare, risus a dictum fringilla, lectus nibh pulvinar elit, quis vestibulum turpis neque id erat.'
		]);
	});

	// POST request for creating a new item
	Route::post('/test', function (Request $request) {
		$data = $request->json()->all();
		return response()->json([
			'id' => 2,
			'title' => $data['title'],
			'desc' => $data['desc'],
			'req' => $data,
		]);
	});

	// PUT request for updating an item
	Route::put('/test', function (Request $request) {
		$data = $request->json()->all();
		return response()->json([
			'id' => $data['id'],
			'title' => $data['title'],
			'desc' => $data['desc'],
			'req' => $data,
		]);
	});

	// PATCH request for partially updating an item
	Route::patch('/test', function (Request $request) {
		$data = $request->json()->all();
		return response()->json([
			'id' => $data['id'],
			'title' => $data['title'] ?? 'Default Title',
			'desc' => $data['desc'] ?? 'Default Description',
			'req' => $data,
		]);
	});

	Route::delete('/test', function (Request $request) {
		return response()->json([
			'deleted' => true,
		]);
	});

	Route::options('/{any}', function () {
		return response()->json();
	})->where('any', '.*');
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });