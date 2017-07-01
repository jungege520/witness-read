<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Admin extends BaseModel implements AuthenticatableContract, JWTSubject
{
    // 用户验证attempt
    use Authenticatable;

    public $timestamps = false;

    protected $table = 'vue_admin';
    // 查询用户的时候，不暴露密码
    protected $hidden = ['password'];

    // jwt 需要实现的方法
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // jwt 需要实现的方法
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function menuList()
    {
        return $this->hasOne(Role::class,'id','role_id');
    }
}
