<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const TABLE_NAME="users";
    protected $table = self::TABLE_NAME;
    public $timestamps = false;
}
