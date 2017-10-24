<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zend\Diactoros\Request;

class User extends Model
{
    //表名
    //public $table = 'user_table'
    public function signup(){
       if(Request::get('username')){
           return ['status'=>0,'msg'=>'用户名不能为空'];
       }
       if(Request::get('password')){
           return ['status'=>0,'msg'=>'密码不能为空'];
       }
       //检查用户名是否存在
        //this指当前这个user Model，对应的是这个user表
       $user = $this -> where('username',Request::get('username'))->exists();

        if($user_exisis){
            return ['status' => 0, 'msg'=>"用户名已存在"];
        }

       return 'valid';

    }

}
