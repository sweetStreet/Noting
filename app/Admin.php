<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Eloquent
{
    protected $table = "admins";
}
