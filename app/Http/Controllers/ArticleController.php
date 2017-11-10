<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/10/31
 * Time: 下午8:49
 */

namespace App\Http\Controllers;

use Request;
use App\Article;
use DB;
use Illuminate\Pagination\Paginator;

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


    public function insertArticle(){
        $user_id = Request::get('user_id');
        $notebook_id = Request::get('notebook_id');
        $content = Request::get('content');
        $article = new Article();
        $article->user_id = $user_id;
        $article->notebook_id = $notebook_id;
        $article->content = $content;
        $result = $article->save();
        if($result){
            return ['status'=>1, 'msg'=>'创建成功'];
        }else{
            return ['status'=>1, 'msg'=>'创建失败'];
        }
    }

    public function updateArticle(){
        $user_id = Request::get('user_id');
        $notebook_id = Request::get('notebook_id');
        $content = Request::get('content');
        $article = new Article();
        $article->user_id = $user_id;
        $article->notebook_id = $notebook_id;
        $article->content = $content;
        $result = $article->save();
        if($result){
            return ['status'=>1, 'msg'=>'创建成功'];
        }else{
            return ['status'=>1, 'msg'=>'创建失败'];
        }

    }

    public function getArticlesByUserID(){
        $user_id = Request::get('user_id');


    }


    public function getArticlesByNotebookID(){
        $user_id = Request::get('user_id');
        $notebook_id = Request::get('notebook_id');
        //每次默认取5篇文章
        $articles = DB::table('articles')->where([
            ['user_id', '=', $user_id],
            ['notebook_id', '=', $notebook_id]
        ])->orderBy('created_at','desc')
            ->paginate(5);

        if($articles){
            return ['status'=>1, 'data'=>$articles];
        }else{
            return ['status'=>0, 'msg'=>'查询失败'];
        }
    }
}