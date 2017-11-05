<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            'name' => 'yuki',
            'email' => '1@1',
            'password' => bcrypt('1'),
        ]);

        DB::table('notebooks')->insert([
            'uid' => '1',
            'title' => '时光已逝秋已凉',
        ]);

        DB::table('notebooks')->insert([
            'uid' => '1',
            'title' => 'web编程指南',
        ]);

        DB::table('notebooks')->insert([
            'uid' => '1',
            'title' => 'javascript编程指南',
        ]);

        DB::table('notebooks')->insert([
            'uid' => '1',
            'title' => '颈椎病康复笔记',
        ]);
    }
}
