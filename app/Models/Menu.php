<?php

namespace App\Models;

class Menu extends BaseModel
{
    public $timestamps = false;
    protected $table = 'vue_menu';
    //public $primaryKey = 'admin_id';

    public function sub_menu()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }

    public function sub_operate_menu()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }
}
