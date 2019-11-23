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

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix'     => 'auth'
  ], function () {
    Route::post('login', 'Users\LoginController@login');
    Route::post('register', 'Users\LoginController@register');
});


/*
|--------------------------------------------------------------------------
| Log Out Routes
|--------------------------------------------------------------------------
*/
Route::group([
  'prefix'     => 'auth',
  'middleware' => 'jwt.verify'
], function () {
  Route::get('logout', 'Users\LoginController@logout');
});

/*
|--------------------------------------------------------------------------
| Category Header Routes
|--------------------------------------------------------------------------
*/
Route::group([
  'prefix'     => 'category',
  'middleware' => 'jwt.verify'
], function () {
  Route::post('insert', 'Category\CategoryController@insert');
  Route::get('get', 'Category\CategoryController@get');
  Route::get('get/{id}', 'Category\CategoryController@getId');
  Route::delete('delete/{id}', 'Category\CategoryController@delete');
  Route::put('put', 'Category\CategoryController@put');
});

/*
|--------------------------------------------------------------------------
| Menu Routes
|--------------------------------------------------------------------------
*/
Route::group([
  'prefix'     => 'menu'
], function () {
  Route::get('all', 'Menu\MenuController@getMenu');
});

/*
|--------------------------------------------------------------------------
| Category Header Routes
|--------------------------------------------------------------------------
*/
Route::group([
  'prefix'     => 'categoryheader',
  'middleware' => 'jwt.verify'
], function () {
  Route::post('insert', 'Category\CategoryHeaderController@insert');
  Route::get('get', 'Category\CategoryHeaderController@get');
  Route::get('get/{id}', 'Category\CategoryHeaderController@getId');
  Route::delete('delete/{id}', 'Category\CategoryHeaderController@delete');
  Route::put('put', 'Category\CategoryHeaderController@put');
});

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/
Route::group([
  'prefix'      => 'product',
  'middleware'  => 'jwt.verify' 
], function() {
  Route::post('insert', 'Product\ProductController@insert');
});

Route::group([
  'prefix'      => 'product',
], function() {
  Route::get('get/{offset}', 'Product\ProductController@get');
});
