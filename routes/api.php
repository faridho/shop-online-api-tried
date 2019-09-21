<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
  'prefix' => 'try'
], function() {
  Route::post('registertetoken', 'Tries\TriesController@registerToken');
  Route::post('generatetoken', 'Tries\TriesController@generateToken');
  Route::post('uploadbucket', 'Tries\TriesController@uploadBucket');
});

Route::group([
  'prefix'     => 'try',
  'middleware' => ['jwt.verify']
], function() {
  Route::get('accesstoken', 'Tries\TriesController@accessToken');
  Route::get('accessusers', 'Tries\TriesController@accessUsers');
});
