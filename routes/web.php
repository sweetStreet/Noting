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
    Route::any('/api/user', 'UserController@index');
//注册
    Route::any('/api/user/register', 'UserController@register');
//登录
    Route::post('/api/user/login', 'UserController@login');
//登出
    Route::any('/api/user/logout', 'UserController@logout');
//获得个人信息
    Route::any('/api/user/info','UserController@info');
//修改个人信息
    Route::any('/api/user/revise','UserController@revise');



//笔记本模块
//默认界面
    Route::any('/api/notebook', 'NotebookController@index');
//获得某一用户的所有笔记本
Route::get('/api/notebook/getAll', 'NotebookController@getAllByUserId');
//新建笔记本
Route::any('/api/notebook/create','NotebookController@create');
//删除笔记本
Route::get('/api/notebook/delete','NotebookController@deleteNotebook');
    //笔记编辑模块
    Route::any('/api/article', 'ArticleController@index');

    //图片上传
    Route::any('/api/article/img/upload', 'ArticleController@imgUpload');
    //新增文章
    Route::post('/api/article/addArticle','ArticleController@insertArticle');
    //保存文章
    Route::post('/api/article/saveArticle','ArticleController@updateArticle');
    //查看文章
    Route::any('/api/article/getArticlesByNotebookID','ArticleController@getArticlesByNotebookID');
    //获得所有文章
    Route::any('/api/article/getArticlesByUserID','ArticleController@getArticlesByUserID');
    //删除文章
    Route::any('/api/article/deleteArticle','ArticleController@deleteArticle');
    //搜索关键字文章
    Route::any('/api/article/searchContent','ArticleController@searchContent');
    //获得文件
    Route::any('/api/article/getFile','ArticleController@getFile');
    //获得所有标签
    Route::any('/api/tag','ArticleController@getTags');
    //根据标签显示笔记列表
    Route::any('/api/searchByTag','ArticleController@searchByTag');
    //日志模块
    Route::get('/api/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


//管理员模块
//默认登录界面
    Route::any('/api/admin', 'AdminController@loginIndex');
//管理员登录
    Route::any('/api/admin/login', 'AdminController@login');
//管理员主界面
    Route::any('/api/admin/index', 'AdminController@index');
//修改邮箱
    Route::any('/api/admin/reviseEmail','AdminController@reviseEmail');
//用户列表
    Route::any('/api/admin/adminUserList', 'AdminController@userList');
//恢复用户
    Route::any('/api/admin/recoverUser', 'AdminController@recoverUser');
//删除用户
    Route::any('/api/admin/adminDeleteUser', 'AdminController@deleteUser');