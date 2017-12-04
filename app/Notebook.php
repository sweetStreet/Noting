<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notebook extends Eloquent
{
    use SoftDeletes;
    const TABLE_NAME="notebooks";
    protected $table = self::TABLE_NAME;
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
