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
use App\User;
use Hash;
use Session;

class AdminController
{
    /**
     * 主界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(Session::has('admin')) {
            return view('admin');
        }else{
            return view('adminLogin');
        }
    }

    /**
     * 管理员登录界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginIndex(){
        return view('adminLogin');
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
        $user = DB::table('admins')->where('username',$username)->get();
        if(!$user->isEmpty()){//账号存在
            if(Hash::check($password,$user->first()->password)){//密码正确
                Session::put('admin','admin');
                return ['status' => 1,'msg' => '/api/admin/index'];
            }else{
                return ['status' => 0, 'msg' => '账号或密码错误'];
            }
        }else{
            return ['status' => 0, 'msg' => '账号或密码错误'];
        }
    }

    public function reviseEmail(){
        $id = Request::get('id');
        $email = Request::get('email');
        $result = DB::update('update users set email=? where id = ?',[$email,$id]);
        if($result){
            return ['status'=>1,'msg'=>'修改成功'];
        }else{
            return ['status'=>0,'msg'=>'修改失败'];
        }
    }

    public function deleteUser(){
        $id = Request::get('id');
        $user = User::find($id);
        if($user){
            $result = $user->delete();
            if($result){
                return ['status' => 1, 'msg'=>"删除成功"];
            }else{
                return ['status' => 0, 'msg'=>"删除失败，请重新删除"];
            }
        }else{
            return ['status' => 0, 'msg'=>"用户不存在"];
        }
    }

    public function recoverUser(){
        $id = Request::get('id');
        $result = User::withTrashed()->where('id', $id)->restore();
        if($result){
            return ['status' => 1, 'msg'=>"恢复成功"];
        }else{
            return ['status' => 0, 'msg'=>"恢复失败"];
        }
    }


    public function userList(){
        $users = DB::table('users')->orderBy('created_at', 'desc')
            ->get();
        if($users){
            return ['status' => 1, 'data'=>$users, 'msg'=>"查找成功"];
        }else{
            return ['status' => 0, 'msg'=>"查找失败"];
        }
    }
}