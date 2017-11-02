<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/10/31
 * Time: 下午11:27
 */

namespace App\Http\Controllers;

use Request;
use DB;
use Zend\Diactoros\Response\RedirectResponse;

class AdminController
{

    public function index()
    {
        return view('admin');
    }

    public function userList(){
        return view('adminUserList');
    }

    public function addUser(){
        return view('adminAddUser');
    }

    public function welcome(){
        return view('adminWelcome');
    }

    public function changePassword(){
        return view('adminChangePassword');
    }

    public function login(){
        $username = Request::get('username');
        $password = Request::get('password');
        if(!$username){
            return ['status'=>0,'msg'=>'用户名不能为空'];
        }
        if(!$password){
            return ['status'=>0,'msg'=>'密码不能为空'];
        }

        $user = DB::table('users')->where('username',$username)->get();
        if(!$user->isEmpty()){//账号存在
            if(Hash::check($password,$user->first()->password)){//密码正确
                return ['status' => 1, 'msg' => 'api/admin/index'];
            }else{
                return ['status' => 0, 'msg' => '账号或密码错误'];
            }
        }else{
            return ['status' => 0, 'msg' => '账号或密码错误'];
        }
    }


    public function revisePassword(){
        $email = Request::get('email');
        $password = Request::get('password');


    }

    public function deleteUser(){
        $email = Request::get('email');
        $user_exisis = DB::table('users')-> where('email',$email)->exists();

        if($user_exisis==false){
            return ['status' => 0, 'msg'=>"用户不存在"];
        }else{
            $result = DB::table('users')->where('email',$email)->delete();
            if($result){
                return ['status' => 1, 'msg'=>"删除成功"];
            }else{
                return ['status' => 0, 'msg'=>"删除失败，请重新删除"];
            }
        }
    }
}