<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Session;
use Request;
use Hash;
use DB;
use App\Notifications\Invitation;

class UserController extends Controller
{

    //注册
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
            return ['status' => 1, 'msg' => '/'];
        }else{
            return ['status' => 0, 'msg' => 'db inset failed'];
        }
    }

    //登录
    public function login(){
        $email = Request::get('email');
        $password = Request::get('password');
        $user = DB::table('users')->where('email',$email)->first();
        if($user){//邮箱存在
            if(Hash::check($password,$user->password)){//密码正确
                Session::put('id',$user->id);
                return ['status' => 1, 'msg' => '/api/notebook','userid'=>$user->id];
            }else{
                return ['status' => 0, 'msg' => '密码错误'];
            }
        }else{
                return ['status' => 0, 'msg' => '该邮箱未注册'];
        }
    }

    //登出
    public function logout(){
//        if(session('USERID','null')){//如果有userid
//            session()->flush();//清空session
//        }
        return ['status' => 1];
    }

    //根据id获得用户信息
    public function info(){
        $id = Request::get('id');
        $user = User::find($id);
        if($user){
            return ['status'=>1, 'data'=>$user, 'msg'=>'查询成功'];
        }else{
            return ['status'=>0, 'msg'=>'查询失败，用户不存在'];
        }
    }

    //修改用户信息
    public function revise(){
        $id = Request::get('id');
        $name = Request::get('name');
        $password = Request::get('password');
        $user = User::find($id);
        if(!empty($name)){
            $user->name = $name;
        }
        if(!empty($password)){
            $user->password = bcrypt($password);
        }
        $result = $user->save();
        if($result){
            return ['status'=>1, 'msg'=>'修改成功'];
        }else{
            return ['status'=>0, 'msg'=>'修改失败'];
        }
    }

    //用户发送邀请
    public function invite(){
        $from_user_id = Request::get('from_user_id');
        $to_user_id = Request::get('to_user_id');
        $content = Request::get('content');
        $to_user = User::find($to_user_id);
        $from_user = User::find($from_user_id);
        $to_user->notify(new Invitation($from_user->email,$from_user->name,$content));
    }

    //用户查看邀请
    public function getInvitation(){
        $user_id = Request::get('user_id');
        $user = User::find($user_id);
        $result = [];
        //所有邀请，不区分已读和未读
        if($user) {
            foreach ($user->notifications as $notification) {
                $result[] = $notification->data;
            }
        }
        return ['status'=>1,'data'=>$result];
    }
}
