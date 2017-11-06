<?php

namespace App;

use Eloquent;

class Notebook extends Eloquent
{
    const TABLE_NAME="notebooks";
    protected $table = self::TABLE_NAME;
    public $timestamps = false;
    protected $softDelete = true;
}
