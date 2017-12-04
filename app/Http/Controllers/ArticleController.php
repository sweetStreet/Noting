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
            return ['error' => 0, 'data' => asset('/storage/' . $path)];
        }
    }


    public function insertArticle(){
        $user_id = Request::get('user_id');
        $notebook_id = Request::get('notebook_id');
        $content = Request::get('content');
        $id=DB::table('articles')->insertGetId(['user_id'=>$user_id,'notebook_id'=>$notebook_id,'content'=>$content,'content_text'=>$this->html2text($content)]);
        $article = DB::table('articles')->where('id',$id)->first();
        if($article){
            return ['status'=>1, 'data'=>$article, 'msg'=>'创建成功'];
        }else{
            return ['status'=>0, 'msg'=>'创建失败'];
        }
    }

    public function updateArticle(){
        $id = Request::get('article_id');
        $user_id = Request::get('user_id');
        $notebook_id = Request::get('notebook_id');
        $content = Request::get('content');
        $result = DB::update('update articles set content=?,content_text=? where id=? and user_id=? and notebook_id=?',[$content,$this->html2text($content),$id,$user_id,$notebook_id]);
        if($result){
            return ['status'=>1, 'msg'=>'创建成功'];
        }else{
            return ['status'=>0, 'msg'=>'创建失败'];
        }

    }

    public function getArticlesByUserID(){
        $user_id = Request::get('user_id');
        $articles = DB::table('articles')->where([
            ['user_id', '=', $user_id],
            ['deleted_at','=',null]
        ])->orderBy('created_at', 'desc')
            ->get();
        if ($articles) {
            return ['status' => 1, 'data' => $articles,'msg'=>$user_id];
        } else {
            return ['status' => 0, 'msg' => '查询失败'];
        }
    }


    public function getArticlesByNotebookID()
    {
        $user_id = Request::get('user_id');
        $notebook_id = Request::get('notebook_id');
//        //每次默认取5篇文章
//        $articles = DB::table('articles')->where([
//            ['user_id', '=', $user_id],
//            ['notebook_id', '=', $notebook_id],
//            ['deleted_at','=',null]
//        ])->orderBy('created_at', 'asc')
//            ->paginate(5);
//
//        if ($articles) {
//            return ['status' => 1, 'data' => $articles];
//        } else {
//            return ['status' => 0, 'msg' => '查询失败'];
//        }
        $articles = DB::table('articles')->where([
            ['user_id', '=', $user_id],
            ['notebook_id', '=', $notebook_id],
            ['deleted_at','=',null]
        ])->orderBy('created_at', 'desc')
            ->get();
        if ($articles) {
            return ['status' => 1, 'data' => $articles];
        } else {
            return ['status' => 0, 'msg' => '查询失败'];
        }
    }

        public function deleteArticle(){
            $id=Request::get('article_id');
            $article = Article::find($id);
            $article->delete();
            if($article->trashed()){
                return ['status'=>1];
            }else{
                return ['status'=>0];
            }
        }


        public function searchContent(){
            $user_id = Request::get('user_id');
            $keyword = Request::get('keyword');
            $articles = DB::table('articles')->where([
                ['user_id', '=', $user_id],
                ['content_text', 'like', "%"+$keyword+"%"],
                ['deleted_at','=',null]
            ])->orderBy('created_at', 'desc')
                ->get();
            if ($articles) {
                return ['status' => 1, 'data' => $articles];
            } else {
                return ['status' => 0, 'msg' => '查询失败'];
            }
        }

    function html2text($str){
        $str = preg_replace("/<style .*?<\\/style>/is", "", $str);
        $str = preg_replace("/<script .*?<\\/script>/is", "", $str);
        $str = preg_replace("/<br \\s*\\/>/i", "", $str);
        $str = preg_replace("/<\\/?p>/i", "", $str);
        $str = preg_replace("/<\\/?td>/i", "", $str);
        $str = preg_replace("/<\\/?div>/i", "", $str);
        $str = preg_replace("/<\\/?blockquote>/i", "", $str);
        $str = preg_replace("/<\\/?li>/i", "", $str);
        $str = preg_replace("/ /i", "", $str);
        $str = preg_replace("/ /i", "", $str);
        $str = preg_replace("/&/i", "", $str);
        $str = preg_replace("/&/i", "", $str);
        $str = preg_replace("/</i", "", $str);
        $str = preg_replace("/</i", "", $str);
        $str = preg_replace("/“/i", '"', $str);
        $str = preg_replace("/&ldquo/i", '"', $str);
        $str = preg_replace("/‘/i", "'", $str);
        $str = preg_replace("/&lsquo/i", "'", $str);
        $str = preg_replace("/'/i", "'", $str);
        $str = preg_replace("/&rsquo/i", "'", $str);
        $str = preg_replace("/>/i", "", $str);
        $str = preg_replace("/>/i", "", $str);
        $str = preg_replace("/”/i", '"', $str);
        $str = preg_replace("/&rdquo/i", '"', $str);
        $str = strip_tags($str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/&#.*?;/i", "", $str);
        return $str;
    }


}