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
            'email' => '151250101@smail.nju.edu.cn',
            'password' => bcrypt('3961180'),
        ]);

        DB::table('users')->insert([
            'name' => 'sweetstreet',
            'email' => '942290857@qq.com',
            'password' => bcrypt('3961180'),
        ]);

        DB::table('users')->insert([
            'name' => 'Cloudy',
            'email' => '957487183@qq.com',
            'password' => bcrypt('3961180'),
        ]);

        DB::table('notebooks')->insert([
            'uid' => '1',
            'title' => '大三第一学期',
        ]);

        DB::table('notebooks')->insert([
            'uid' => '1',
            'title' => 'web编程指南',
        ]);
    }
}
