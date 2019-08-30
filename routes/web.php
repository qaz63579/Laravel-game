<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/index',"GameController@index");
Route::post('/index/login',"GameController@login");
Route::get('/index/main',"GameController@main");

Route::get('/index/pay',"GameController@pay");
Route::post('/index/pay',"GameController@postpay");

Route::get('/index/result',"GameController@result");
Route::get('/info',"GameController@info");

Route::get('/redis',"RedisController@index");
Route::get('/issue',"RedisController@InsertIssue");
Route::get('/code',"RedisController@RenewCode");
Route::get('/end',"RedisController@result");
Route::get('/close',"RedisController@IsClosed");



// Route::get('/server',"GameController@server");
// Route::get('/content',"ContentController@index");