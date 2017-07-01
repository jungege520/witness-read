<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $guarded = ['id'];

    protected $dates = ['create_time', 'update_time'];

    //protected $hidden = ['update_time'];
}
