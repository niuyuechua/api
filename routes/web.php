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
Route::get('/test','Test\testController@index');
//服务端路由
Route::post('/test/post','Test\testController@post');
Route::post('/test/post2','Test\testController@post2');
Route::post('/test/post4','Test\testController@post4');
Route::post('/test/dec','Test\testController@dec');
Route::get('/test/upfile','Test\testController@upfile');
Route::post('/test/rsadec','Test\testController@rsaDec');
//客户端路由
Route::get('/get','Test\CurlController@get');
Route::get('/get2','Test\CurlController@get2');
Route::get('/get3','Test\CurlController@get3');
Route::get('/post','Test\CurlController@post');
Route::get('/post2','Test\CurlController@post2');
Route::get('/post3','Test\CurlController@post3');
Route::get('/post4','Test\CurlController@post4');
Route::get('/enc','Test\CurlController@enc');
Route::get('/enc2','Test\CurlController@enc2');
Route::get('/upfile','Test\CurlController@upfile');
Route::get('/rsa','Test\CurlController@rsa');
Route::get('/aes','Test\CurlController@aes');
Route::post('/aesRes','Test\CurlController@aesRes');
//aliPay
Route::get('/aliPay','aliPay\PayController@index');
