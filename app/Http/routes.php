<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/10/23
 * Time: 下午8:52
 */
Route::get('/',function(){
    return view('welcome');
});

Route::any('api/user',function(){
   $user = new App\User;
   return $user->signup();
});

