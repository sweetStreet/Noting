<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notebook extends Model
{
    const TABLE_NAME="notebooks";
    protected $table = self::TABLE_NAME;
    public $timestamps = false;
    protected $softDelete = true;
}
