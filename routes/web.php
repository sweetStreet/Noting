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
//默认界面
Route::any('/api/user','UserController@index');
//登录
Route::post('/api/user/login','UserController@login');
//注册
Route::any('/api/user/register', 'UserController@register');


//笔记本模块
//默认界面
Route::any('/api/notebook','NotebookController@index');
//获得某一用户的所有笔记本
Route::get('/api/notebook/getAll','NotebookController@getAllByUserId');

//笔记编辑模块
Route::any('/api/article','ArticleController@index');

//管理员模块
//默认界面
Route::any('/api/admin','AdminController@login');
//管理员登录
Route::post('/api/admin/login','AdminController@login');
//管理员主界面
Route::any('/api/admin/index','AdminController@index');
//用户列表
Route::any('/api/admin/adminUserList','AdminController@userList');
//增加用户
Route::any('/api/admin/addUser','AdminController@addUser');
//删除用户
Route::any('/api/admin/adminDeleteUser','AdminController@deleteUser');
//后台服务器信息
Route::any('/api/admin/adminWelcome','AdminController@welcome');
//修改密码
Route::any('/api/admin/adminChangePassword','AdminController@changePassword');