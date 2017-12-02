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

class NotebookController
{
    public function index()
    {
        return view('notebook');
    }

    public function getAllByUserId(){
        $userid = Request::get('userid');
        $notebooks = DB::table('notebooks')->where('uid',$userid)->orderBy('created_at', 'desc')->get();
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


}