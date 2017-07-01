<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name'=>'超级管理员',
                'desc'=>'所有权限',
                'role'=>serialize(['master_menu'=>[1],'sub_menu'=>[2,3,4,5,6,7,8,9,10,11,12,13]]),
                'status'=>1,
                'create_time'=>time(),
                'update_time'=>time(),
            ]
        ];
        Role::insert($data);
    }
}
