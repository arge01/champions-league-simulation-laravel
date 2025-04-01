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

/**
 * Exemaple route
	Route::group(['prefix' => '/url'], function () {
		Route::get('/all', 'Controller@all');
		Route::post('/criteria', 'Controller@criteria');
		Route::post('/criteria/pagination/{page}/{size}', 'MatchesController@pagination');
		Route::get('/get-criteria/{key}/{column}', 'Controller@get_criteria');
		Route::delete('/{id}', 'Controller@delete');
		Route::put('/{id}', 'Controller@put');
		Route::patch('/{id}', 'Controller@patch');
		Route::get('/{id}', 'Controller@get');
		Route::post('', 'Controller@post');
	});
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
				Route::get('/get-criteria/{key}/{column}', 'TournamedController@get_criteria');
				Route::delete('/{id}', 'TournamedController@delete');
				Route::get('/{id}', 'TournamedController@get');
				Route::post('', 'TournamedController@post');
			});

			Route::group(['prefix' => '/groups'], function () {
				Route::get('/all', 'GroupsController@all');
				Route::post('/all-create/{tournamed}', 'GroupsController@multi');
				Route::post('/criteria', 'GroupsController@criteria');
				Route::get('/get-criteria/{key}/{column}', 'GroupsController@get_criteria');
				Route::delete('/{id}', 'GroupsController@delete');
				Route::put('/{id}', 'GroupsController@put');
				Route::patch('/{id}', 'GroupsController@patch');
				Route::get('/{id}', 'GroupsController@get');
				Route::post('', 'GroupsController@post');
			});

			Route::group(['prefix' => '/matches'], function () {
				Route::get('/all', 'MatchesController@all');
				Route::post('/all-create', 'MatchesController@multi');
				Route::post('/criteria', 'MatchesController@criteria');
				Route::post('/criteria/pagination/{page}/{size}', 'MatchesController@pagination');
				Route::get('/get-criteria/{key}/{column}', 'MatchesController@get_criteria');
				Route::delete('/{id}', 'MatchesController@delete');
				Route::put('/{id}', 'MatchesController@put');
				Route::patch('/{id}', 'MatchesController@patch');
				Route::get('/{id}', 'MatchesController@get');
				Route::post('', 'MatchesController@post');
			});

			Route::group(['prefix' => '/soccer'], function () {
				Route::group(['prefix' => '/power'], function () {
					Route::get('/all', 'PowerController@all');
					Route::post('/criteria', 'PowerController@criteria');
					Route::get('/get-criteria/{key}/{column}', 'PowerController@get_criteria');
					Route::delete('/{id}', 'PowerController@delete');
					Route::put('/{id}', 'PowerController@put');
					Route::patch('/{id}', 'PowerController@patch');
					Route::get('/{id}', 'PowerController@get');
					Route::post('', 'PowerController@post');
				});
				Route::get('/all', 'SoccerController@all');
				Route::post('/all-create', 'SoccerController@multi');
				Route::post('/criteria', 'SoccerController@criteria');
				Route::get('/get-criteria/{key}/{column}', 'SoccerController@get_criteria');
				Route::delete('/{id}', 'SoccerController@delete');
				Route::put('/{id}', 'SoccerController@put');
				Route::patch('/{id}', 'SoccerController@patch');
				Route::get('/{id}', 'SoccerController@get');
				Route::post('', 'SoccerController@post');
			});
		});
	});

	Route::options('/{any}', function () {
		return response()->json();
	})->where('any', '.*');
});
