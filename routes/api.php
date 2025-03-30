<?php

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
	Route::group(['prefix' => ''], function () {
		Route::post('register', 'AuthController@register');
		Route::post('control', 'AuthController@control');
		Route::post('login', 'AuthController@login');
		Route::middleware('auth:api')->group(function () {
			Route::post('logout', 'AuthController@logout');
			Route::post('refresh', 'AuthController@refresh');
			Route::get('user', 'AuthController@user');

			Route::group(['prefix' => '/tournamed'], function () {
				Route::get('/all', 'TournamedController@all');
				Route::get('/criteria', 'TournamedController@criteria');
				Route::delete('/{id}', 'TournamedController@delete');
				Route::put('/{id}', 'TournamedController@put');
				Route::patch('/{id}', 'TournamedController@patch');
				Route::get('/{id}', 'TournamedController@get');
				Route::post('', 'TournamedController@post');
			});
		});
	});

	Route::options('/{any}', function () {
		return response()->json();
	})->where('any', '.*');
});
