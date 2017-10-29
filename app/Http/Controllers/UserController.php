<?php

namespace App\Http\Controllers;

use Request;
use Hash;
use DB;
use Illuminate\Http\RedirectResponse;

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

        $user = DB::table('users')->where('email',$email)->get();
        if(!$user->isEmpty()){//邮箱存在
            if(Hash::check($password,$user->first()->password)){//密码正确
                //return redirect('/api/notebook');
//                return redirect()->action('NotebookController@index');
                return ['status' => 1, 'msg' => 'success'];
            }else{
                return ['status' => 0, 'msg' => '密码错误'];
            }
        }else{
                return ['status' => 0, 'msg' => '该邮箱未注册'];
        }
    }

}
