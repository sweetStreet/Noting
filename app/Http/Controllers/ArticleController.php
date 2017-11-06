<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/10/31
 * Time: 下午8:49
 */

namespace App\Http\Controllers;

use Request;

class ArticleController
{
    public function index()
    {
        return view('article');
    }

    public function imgUpload(){
        //从请求中拿出wangEditorH5File 储存到public空间 并且用时间戳重命名
        if(Request::hasfile('myFileName')) {
            $path = Request::file('myFileName')->store('md5(time())');
            //保存到storage目录下
            return ['error' => 0, 'data' => asset('storage/' . $path)];
        }
    }

}