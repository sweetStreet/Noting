<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Eloquent
{
    use SoftDeletes;
    protected $table = "users";
    protected $dates = ['deleted_at'];
}
