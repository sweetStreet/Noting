<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use DB;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $user_id = 1;
        $keyword = "我们";
        $articles = DB::table('articles')->where([
            ['user_id', '=', $user_id],
            ['content', 'like', "%".$keyword."%"],
        ])->orderBy('created_at', 'desc')
            ->get();
        echo $articles;
        if ($articles) {
            return ['status' => 1, 'data' => $articles];
        } else {
            return ['status' => 0, 'msg' => '查询失败'];
        }
    }
}
