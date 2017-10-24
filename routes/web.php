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

Route::any('/api/user',function (){

//    //获得localhost/api/user?username=***的***内容
//    return Request::get('username');
//    //get post 都可以打印出来
//    return Request::all();
});
