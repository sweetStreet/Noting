<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Eloquent
{
    use SoftDeletes;
    use Notifiable;
    protected $table = "users";
    protected $dates = ['deleted_at'];
}
