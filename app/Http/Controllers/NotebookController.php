<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/10/29
 * Time: 下午10:43
 */

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;


class NotebookController
{
    public function index()
    {
        return view('notebook');
    }


}