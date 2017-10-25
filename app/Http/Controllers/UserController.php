<?php

namespace App\Http\Controllers;

use Request;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    //表名
    //public $table = 'user_table'
    public function register(){
//        if($email=Request::get('email')){
//            return ['status'=>0,'msg'=>'邮箱不能为空'];
//        }
//        if($username=Request::get('username')){
//            return ['status'=>0,'msg'=>'用户名不能为空'];
//        }
//        if($password=Request::get('password')){
//            return ['status'=>0,'msg'=>'密码不能为空'];
//        }
        //检查用户名是否存在
        //this指当前这个user Model，对应的是这个user表
//        $user_exisis = $this -> where('username',Request::get('username'))->exists();
//
//        if($user_exisis){
//            return ['status' => 0, 'msg'=>"用户名已存在"];
//        }
//
//        //加密密码
//        $hashed_password = Hash::make($password);
//
//        //存入数据库
//        $this->password = $hashed_password;
//        $this->username = $username;
//
//        if(
//            return ['status' => 1, 'msg' => $this->id];
//        }else{
//            return ['status' => 0, 'msg' => 'db inset failed'];
//        }
        return 'valid';
    }

    public function login(){
        return 'login';
    }
}
