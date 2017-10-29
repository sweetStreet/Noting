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
//首页
Route::get('/', function () {
    return view('welcome');
});

//用户模块
//注册页面
Route::post('/api/user/login','UserController@login');
Route::any('/api/user/register', 'UserController@register');
Route::any('/api/user','UserController@index');

//笔记本界面
Route::any('/api/notebook','NotebookController@index');