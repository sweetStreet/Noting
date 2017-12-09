<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/12/8
 * Time: 下午11:18
 */

namespace App;

use Eloquent;

class TagMap extends Eloquent
{
    protected $table = "tagmaps";
    protected $fillable = ['tag_id','user_id','a_id'];
}