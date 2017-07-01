<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */
$api = app('Dingo\Api\Routing\Router');

// v2 version API
// add in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\Admin',
    // each route have a limit of 100 of 1 minutes
    //'middleware' => 'api.throttle', 'limit' => 100, 'expires' => 1
], function ($api) {

    /** 上传图片 */
    $api->post('public/upload', [
        'as' => 'public.upload',
        'uses' => 'UploadController@image',
    ]);

    $api->group(['middleware' => ['appSign']], function ($api) {
        /** 注册管理员 */
        $api->post('admin/register', [
            'as' => 'admin.register',
            'uses' => 'AdminAuthController@register',
        ]);
        /** 管理员登录 */
        $api->post('admin/login', [
            'as' => 'admin.login',
            'uses' => 'AdminAuthController@login',
        ]);
        /** 管理员刷新登录状态 返回TOKEN */
        $api->post('admin/tokenRefresh', [
            'as' => 'admin.tokenRefresh',
            'uses' => 'AdminAuthController@tokenRefresh',
        ]);

        //需要API认证  设置header {"Authorization":"Bearer $token}routerRole
        $api->group(['middleware' => ['api.auth']], function ($api) {

            /** 获取管理员菜单权限 */
            $api->post('admin/authCheck', [
                'as' => 'admin.authCheck',
                'uses' => 'AdminAuthController@check',
            ]);
            /** 更新自己的个人信息 */
            $api->post('admin/updateMySelfInfo', [
                'as' => 'admin.updateMySelfInfo',
                'uses' => 'AdminAuthController@updateMySelfInfo',
            ]);
            /** 获取管理员菜单权限 */
            $api->post('admin/info', [
                'as' => 'admin.info',
                'uses' => 'AdminAuthController@info',
            ]);
            /** 管理员退出登录 */
            $api->post('admin/loginOut', [
                'as' => 'admin.loginOut',
                'uses' => 'AdminAuthController@loginOut',
            ]);
            /** 管理员列表 */
            $api->post('admin/adminList', [
                'as' => 'admin.adminList',
                'uses' => 'AdminController@adminList',
            ]);
            /** 管理员基础信息 */
            $api->post('admin/adminInfo', [
                'as' => 'admin.adminInfo',
                'uses' => 'AdminController@adminInfo',
            ]);
            /** 更新管理员信息 */
            $api->post('admin/updateAdminInfo', [
                'as' => 'admin.updateAdminInfo',
                'uses' => 'AdminController@updateAdminInfo',
            ]);
            /** 修改密码 */
            $api->post('admin/updatePassword', [
                'as' => 'admin.updatePassword',
                'uses' => 'AdminController@updatePassword',
            ]);
            /** 删除管理员 */
            $api->post('admin/deleteAdmin', [
                'as' => 'admin.deleteAdmin',
                'uses' => 'AdminController@destroy',
            ]);

            /***********************  菜单管理路由  ********************/
            /** 添加管理菜单 */
            $api->post('admin/addMenu', [
                'as' => 'admin.addMenu',
                'uses' => 'MenuController@store',
            ]);
            /** 更新管理菜单 */
            $api->post('admin/updateMenu', [
                'as' => 'admin.updateMenu',
                'uses' => 'MenuController@update',
            ]);
            /** 删除管理菜单 */
            $api->post('admin/deleteMenu', [
                'as' => 'admin.deleteMenu',
                'uses' => 'MenuController@destroy',
            ]);
            /** 管理菜单列表 */
            $api->post('admin/menuList', [
                'as' => 'admin.menuList',
                'uses' => 'MenuController@menuList',
            ]);
            /** 管理权限菜单列表 */
            $api->post('admin/menuRoleList', [
                'as' => 'admin.menuRoleList',
                'uses' => 'MenuController@menuRoleList',
            ]);
            /** 管理菜单顺序更新 */
            $api->post('admin/updateMenuSort', [
                'as' => 'admin.updateMenuSort',
                'uses' => 'MenuController@updateMenuSort',
            ]);

            /***********************  角色管理路由  ********************/
            /** 角色菜单列表 */
            $api->post('admin/roleList', [
                'as' => 'admin.roleList',
                'uses' => 'RoleController@roleList',
            ]);
            /** 添加角色 */
            $api->post('admin/addRole', [
                'as' => 'admin.addRole',
                'uses' => 'RoleController@store',
            ]);
            /** 更新角色 */
            $api->post('admin/updateRole', [
                'as' => 'admin.updateRole',
                'uses' => 'RoleController@update',
            ]);
            /** 删除角色 */
            $api->post('admin/deleteRole', [
                'as' => 'admin.deleteRole',
                'uses' => 'RoleController@destroy',
            ]);

        });
    });
});
