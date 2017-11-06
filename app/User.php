<?php

namespace App;

use Eloquent;

class User extends Eloquent
{
    const TABLE_NAME="users";
    protected $table = self::TABLE_NAME;
    public $timestamps = false;
    protected $softDelete = true;
}
