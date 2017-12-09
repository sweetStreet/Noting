<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use DB;
use App\Notifications\Invitation;
use App\User;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
//    public function testBasicTest()
//    {
//        $user_id = 1;
//        $keyword = "我们";
//        $articles = DB::table('articles')->where([
//            ['user_id', '=', $user_id],
//            ['content', 'like', "%".$keyword."%"],
//        ])->orderBy('created_at', 'desc')
//            ->get();
//        echo $articles;
//        if ($articles) {
//            return ['status' => 1, 'data' => $articles];
//        } else {
//            return ['status' => 0, 'msg' => '查询失败'];
//        }
//    }

//    public function testBasicTest(){
//        //获取当前的url
//        echo(storage_path()."/app/public/1/");
//        $path = storage_path() . "/app/public/1/";
//        echo("\n");
//        if(!file_exists($path)){
//            echo('no path');
//            exit;
//        }
//        //PHP遍历文件夹下所有文件
//        $handle=opendir($path.".");
//        //定义用于存储文件名的数组
//        $array_file = array();
//        while (false !== ($file = readdir($handle)))
//        {
//            if ($file != "." && $file != "..") {
//                $array_file[] = $file; //输出文件名
//            }
//        }
//        closedir($handle);
//        print_r($array_file);
//
//        exit;
//
//    }

    public function testBasicTest(){
        $user = User::find('1');
        echo 'hi';
        echo $user->notify(new Invitation('2@2','yuki','hi'));
//        foreach ($user->notifications as $notification) {
//            echo 'hi';
//            echo implode('||',$notification->data);
//        }
    }
}
