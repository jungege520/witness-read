<?php

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['type'=>0,'parent_id'=>'0','name'=>'系统设置','icon'=>'android-settings','url'=>'#','model'=>'admin.setting','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>0,'parent_id'=>'1','name'=>'菜单列表','icon'=>'android-menu','url'=>'/home/admin/menu','model'=>'admin.menuList','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>0,'parent_id'=>'1','name'=>'权限列表','icon'=>'wrench','url'=>'/home/admin/role','model'=>'admin.roleList','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>0,'parent_id'=>'1','name'=>'管理员列表','icon'=>'person-stalker','url'=>'/home/admin/user','model'=>'admin.adminList','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>1,'parent_id'=>'3','name'=>'权限列表-添加','icon'=>'#','url'=>'#','model'=>'roleList.add','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>1,'parent_id'=>'3','name'=>'权限列表-编辑','icon'=>'#','url'=>'#','model'=>'roleList.edit','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>1,'parent_id'=>'3','name'=>'权限列表-删除','icon'=>'#','url'=>'#','model'=>'roleList.delete','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>1,'parent_id'=>'2','name'=>'菜单列表-添加','icon'=>'#','url'=>'#','model'=>'menuList.add','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>1,'parent_id'=>'2','name'=>'菜单列表-编辑','icon'=>'#','url'=>'#','model'=>'menuList.edit','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>1,'parent_id'=>'2','name'=>'菜单列表-删除','icon'=>'#','url'=>'#','model'=>'menuList.delete','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>1,'parent_id'=>'4','name'=>'管理员列表-添加','icon'=>'#','url'=>'#','model'=>'menuList.add','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>1,'parent_id'=>'4','name'=>'管理员列表-编辑','icon'=>'#','url'=>'#','model'=>'menuList.eit','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
            ['type'=>1,'parent_id'=>'4','name'=>'管理员列表-删除','icon'=>'#','url'=>'#','model'=>'menuList.delete','desc'=>'','status'=>1,'create_time'=>time(),'update_time'=>time()],
        ];
        Menu::insert($data);
    }
}
