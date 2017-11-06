<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuyu
 * Date: 2017/11/5
 * Time: 下午8:49
 */

namespace App;

use Eloquent;

class Article extends Eloquent
{
    const TABLE_NAME="articles";
    protected $table = self::TABLE_NAME;
    public $timestamps = false;
    protected $softDelete = true;
}