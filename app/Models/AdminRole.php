<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class AdminRole extends BaseModel
{
    public $timestamps = false;
    protected $table = 'vue_admin_role';

    public function role_info()
    {
        return $this->hasOne(Role::class,'id','admin_id');
    }


}
