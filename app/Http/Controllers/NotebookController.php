<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/10/29
 * Time: 下午10:43
 */

namespace App\Http\Controllers;


use Illuminate\Http\RedirectResponse;
use DB;
use Request;

class NotebookController
{
    public function index()
    {
        return view('notebook');
    }

    public function getAllByUserId(){
        $userid=1;
        $notebooks = DB::table('notebooks')->where('uid',$userid)->get();
        if(!$notebooks->isEmpty()){//笔记本存在
            return ['status' => 1, 'notebookList' => $notebooks];
        }else{
            return ['status' => 0, 'msg' => '还未创建笔记本'];
        }
    }

}