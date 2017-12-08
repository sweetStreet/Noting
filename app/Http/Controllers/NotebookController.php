<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/10/29
 * Time: 下午10:43
 */

namespace App\Http\Controllers;


use App\Article;
use Illuminate\Http\RedirectResponse;
use DB;
use Request;
use App\Notebook;
use Session;

class NotebookController
{
    public function index()
    {
        if(Session::has('id')) {
            return view('notebook');
        }else{
            return view('welcome');
        }
    }

    public function getAllByUserId(){
        $userid = Request::get('userid');
        $notebooks = DB::table('notebooks')->where([['uid',$userid],['deleted_at',null]])->orderBy('created_at', 'desc')->get();
        if(!$notebooks->isEmpty()){//笔记本存在
            return ['status' => 1, 'notebooks' => $notebooks];
        }else{
            return ['status' => 0, 'msg' => '还未创建笔记本'];
        }
    }

    public function create(){
        $userid = Request::get('userid');
        $title = Request::get('title');
        //检查笔记本名是否存在
        $notebook_exists = DB::table('notebooks')->where([
            ['uid', '=', $userid],
            ['title','=',$title],
            ['deleted_at','=',null]
        ])->exists();

        if($notebook_exists){
            return ['status' => 0, 'msg'=>"该笔记本已存在"];
        }

        //存入数据库
//        $result = DB::table('notebooks')->insert([
//            'uid' => $userid,
//            'title' => $title,
//        ]);

        $notebook = new Notebook();
        $notebook->uid = $userid;
        $notebook->title = $title;
        $result = $notebook->save();

        if($result){
            return ['status' => 1, 'msg' => '成功','notebookid'=>$notebook->id];
        }else{
            return ['status' => 0, 'msg' => '数据库插入失败'];
        }
    }


    /**
     * 删除笔记本以及笔记本中的文章
     * @return array
     */
    public function deleteNotebook(){
        $notebook_id = Request::get('notebook_id');
        $notebook = Notebook::find($notebook_id);
        if($notebook){
            $result = $notebook->delete();
            $articles = Article::where('notebook_id', '=', $notebook_id)->get();
            if($articles){
                foreach($articles as $article){
                    $result = $article->delete();
                }
            }
            if($result){
                return ['status' => 1, 'msg'=>"删除成功"];
            }else{
                return ['status' => 0, 'msg'=>"删除失败，请重新删除"];
            }
        }else{
            return ['status' => 0, 'msg'=>"笔记本不存在"];
        }
    }
}