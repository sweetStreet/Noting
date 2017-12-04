<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Eloquent
{
    use SoftDeletes;
    const TABLE_NAME="users";
    protected $table = self::TABLE_NAME;
    protected $dates = ['deleted_at'];
}
