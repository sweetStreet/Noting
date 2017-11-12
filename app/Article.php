<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/11/5
 * Time: 下午8:49
 */

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;
    public $table='articles';
    protected $dates = ['deleted_at'];
}
