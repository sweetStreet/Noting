<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/12/8
 * Time: 下午11:18
 */

namespace App;

use Eloquent;

class Tag extends Eloquent
{
    protected $table = "tags";
    protected $fillable = ['name'];
}