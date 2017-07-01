<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
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
                'nickname'=>'周先生',
                'username'=>'773729704@qq.com',
                'password'=>'$2y$10$70d12NtYUSJSonZHrU8qceirCDT.Br0SpYdhmlXXbAnOUEJK0KESC',
                'role'=>'1',
                'icon'=>'',
                'status'=>1,
                'login_time'=>0,
                'create_time'=>time(),
                'update_time'=>time(),
            ], [
                'nickname'=>'管理员',
                'username'=>'admin',
                'password'=>'$2y$10$70d12NtYUSJSonZHrU8qceirCDT.Br0SpYdhmlXXbAnOUEJK0KESC',
                'role'=>'1',
                'icon'=>'',
                'status'=>1,
                'login_time'=>0,
                'create_time'=>time(),
                'update_time'=>time(),
            ],[
                'nickname'=>'超级管理员',
                'username'=>'superadmin',
                'password'=>'$2y$10$70d12NtYUSJSonZHrU8qceirCDT.Br0SpYdhmlXXbAnOUEJK0KESC',
                'role'=>'1',
                'icon'=>'',
                'status'=>1,
                'login_time'=>0,
                'create_time'=>time(),
                'update_time'=>time(),
            ],
        ];
        Admin::insert($data);
    }
}
