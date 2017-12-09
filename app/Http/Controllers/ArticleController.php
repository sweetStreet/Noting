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
use App\Tag;
use App\TagMap;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;

class ArticleController
{
    public function index()
    {
        if(Session::has('id')) {
            return view('article');
        }else{
            return view('welcome');
        }
    }

    /**
     * 将图片保存在某个文件夹中
     * 同一个用户的图片放在同一个文件夹下
     * @return array
     */
    public function imgUpload(){
        //从请求中拿出wangEditorH5File 储存到public空间 并且用时间戳重命名
        if(Request::hasfile('myFileName')) {
//            $path = Request::file('myFileName')->store(md5(date("Y/m/d")));
            $userid=str_replace('"','', Request::get('userid'));
            $path = Request::file('myFileName')->store($userid);
            //保存到storage目录下
            return ['error' => 0, 'data' => asset('/storage/' . $path)];
        }
    }


    /**
     * 新增一篇笔记
     * @return array
     */
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

    /**
     * 更新笔记
     * @return array
     */
    public function updateArticle(){
        $id = Request::get('article_id');
        $user_id = Request::get('user_id');
        $content = Request::get('content');
        //暂时没有实现笔记可以换笔记本的打算
//        $notebook_id = Request::get('notebook_id');
        $tag = Request::get('tag');

        $article = Article::find($id);
        if($article) {
            $article->user_id = $user_id;
            $article->content = $content;
            $article->content_text = $this->html2text($content);
//            $article->notebook_id = $notebook_id;
            $article->tag = $tag;
        }

        if($tag!=null){
            $tag_array = explode(',',$tag);

            //每次修改需要前需要将tagmaps中的标签对应关系删除
            DB::table('tagmaps')->where('a_id','=',$id)->delete();

            foreach($tag_array as $t){
                //如果没有则新建相应的标签
                $tagDB = Tag::firstOrCreate(['name'=>$t]);
                TagMap::create(['tag_id'=>$tagDB->id,'user_id'=>$user_id,'a_id'=>$id]);
            }
        }

        $result = $article->save();
        if($result){
            return ['status'=>1, 'msg'=>'保存成功'];
        }else{
            return ['status'=>0, 'msg'=>'保存失败'];
        }
    }

    /**
     * 获得某个用户的所有文章
     * @return array
     */
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


    /**
     * 获得某个用户某本笔记本内的所有文章
     * @return array
     */
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

    /**
     * 根据关键字搜索文章
     * @return array
     */
        public function searchContent(){
            $user_id = Request::get('user_id');
            $keyword = Request::get('keyword');

            $articles = DB::table('articles')->where([
                ['user_id', '=', $user_id],
                ['content_text', 'like', "%".$keyword."%"]
            ])->orderBy('created_at', 'desc')
                ->get();

            if ($articles) {
                return ['status' => 1, 'data' => $articles,'msg'=>'查询成功'];
            } else {
                return ['status' => 0, 'msg' => '查询失败'];
            }
        }

    /**
     * 根据标签搜索文章
     * @return array
     */
        public function searchByTag(){
            $user_id = Request::get('user_id');
            $tag = Request::get('tag');

            $articles = Article::withAnyTag($tag)->where('user_id',$user_id)->get();

            if ($articles) {
                return ['status' => 1, 'data' => $articles,'msg'=>'查询成功'];
            } else {
                return ['status' => 0, 'msg' => '查询失败'];
            }
        }

    /**
     * 获得某个用户的所有文件
     * @return array
     */
        public function getFile(){
            $userid=str_replace('"','', Request::get('userid')); $userid = Request::get('user_id');
            $path = storage_path() . "/app/public/".$userid."/";
            $basicpath = "http://127.0.0.1:8000/storage/".$userid."/";

            //用户没有上传过文件
            if(!file_exists($path)){
                return ['status'=>1,'data'=>[],'msg'=>"没有上传过文件"];
            }

            //PHP遍历文件夹下所有文件
            $handle=opendir($path.".");
            //定义用于存储文件名的数组
            $array_file = array();
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != "..") {
                    $array_file[]= $basicpath.$file; //将文件名加入到输出文件名
                }
            }
            closedir($handle);
            return ['status'=>1,'data'=>$array_file,'msg'=>'查询成功'];
        }

    function html2text($str){
        return strip_tags($str);
    }
}