<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Request;
use Hash;
use DB;

class UserController extends Controller
{

    public function index()
    {
        return view('welcome');
    }

    //表名
    //public $table = 'user_table'
    public function register(){
        $email = Request::get('email');
        $username = Request::get('username');
        $password = Request::get('password');

        if(!$email){
            return ['status'=>0,'msg'=>'邮箱不能为空'];
        }
        if(!$username){
            return ['status'=>0,'msg'=>'用户名不能为空'];
        }
        if(!$password){
            return ['status'=>0,'msg'=>'密码不能为空'];
        }

        //检查用户名是否存在
        $user_exisis = DB::table('users')-> where('email',$email)->exists();

        if($user_exisis){
            return ['status' => 0, 'msg'=>"邮箱已注册"];
        }

        //加密密码 bcrypt()
        //存入数据库
        $result = DB::table('users')->insert([
            'name' => $username,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        if($result){
            return ['status' => 1, 'msg' => 'success'];
        }else{
            return ['status' => 0, 'msg' => 'db inset failed'];
        }
    }

    public function login(){
        $email = Request::get('email');
        $password = Request::get('password');
        $user = DB::table('users')->where('email',$email)->first();
        if($user){//邮箱存在
            if(Hash::check($password,$user->password)){//密码正确
                //将username和userid保存到session
//                Session::put('USERID', $user->id);
//                Session::put('EMAIL',$email);
//                session(['username' => $user->name]);
//                session(['userid' => $user->id]);
//                session()->put('USERNAME',$user->name);
//                session()->put('USERID',$user->id);
                return ['status' => 1, 'msg' => '/api/notebook','userid'=>$user->id];
            }else{
                return ['status' => 0, 'msg' => '密码错误'];
            }
        }else{
                return ['status' => 0, 'msg' => '该邮箱未注册'];
        }
    }

    public function logout(){
//        if(session('USERID','null')){//如果有userid
//            session()->flush();//清空session
//        }
        return ['status' => 1];
    }
}
