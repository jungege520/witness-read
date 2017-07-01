<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 17/5/26
 * Time: 下午9:52
 */

namespace App\Http\Controllers\Api\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends BaseController
{
    /**
     * 获取管理员列表 POST
     * @param Request $request
     * @return mixed
     */
    public function adminList(Request $request)
    {
        $sort = $request->sort;
        $list = Admin::where('status', '!=', '-1')
            ->orderBy(!empty($sort['key']) ? $sort['key'] : 'id', !empty($sort['orderBy']) ? $sort['orderBy'] : 'desc')
            ->paginate(!empty($request->page_size) ? $request->page_size : 15)
            ->toArray();
        foreach ($list['data'] as &$value) {
            $roleInfo = Role::find($value['role']);
            $value['login_time'] = $value['login_time'] ? date('Y-m-d H:i:s', $value['login_time']) : '#';
            $value['role_name'] = !empty($roleInfo->name) ? $roleInfo->name : '#';
        }
        return ['message' => 'success', 'status_code' => '200', 'data' => $list];
    }

    /**
     * 管理员基础信息接口 POST
     * @param Request $request
     * @return mixed
     */
    public function adminInfo(Request $request)
    {
        $admin_info = Admin::find($request->only('admin_id'))->toArray();
        return ['message' => 'success', 'status_code' => '200', 'data' => $admin_info];
    }

    /**
     * 更新管理员信息 PUT
     * @param Request $request
     * @return mixed
     */
    public function updateAdminInfo(Request $request)
    {

        $admin_model = Admin::find($request->id);
        $admin_model->nickname = $request->nickname;
        $admin_model->icon = $request->icon;
        if (!empty($request->input('password'))) {
            $admin_model->password = app('hash')->make($request->password);
        }
        $admin_model->role = $request->role;
        $flag = $admin_model->save();
        if ($flag) {
            return ['message' => 'success', 'status_code' => '200'];
        } else {
            return ['message' => '添加管理员失败', 'status_code' => '-1'];
        }
    }

    /**
     * 管理员修改密码 PUT
     * @param Request $request
     * @return mixed
     */
    public function updatePassword(Request $request)
    {
        $password_arr = $request->only('password', 'new_password');
        $credentials = array('username' => $this->user()->username, 'password' => $password_arr['password']);
        if (\Auth::attempt($credentials)) {
            $flag = Admin::where('id', $this->user()->id)->update(['password' => app('hash')->make($password_arr['new_password'])]);
            if ($flag) {
                return ['status_code' => '200', 'message' => '更新成功'];
            } else {
                return ['status_code' => '-1', 'message' => '更新失败'];
            }
        }
        return ['status_code' => '-1', 'message' => '原密码不正确，请重试'];
    }

    /**
     * 删管理员吧 DELETE
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $admin = Admin::find($request->id);
        if ($admin->status == 1) {
            $admin->status = -1;
        } else {
            return ['message' => '管理员不存在', 'status_code' => '-1', 'data' => []];
        }

        if ($admin->save()) {
            return ['message' => 'success', 'status_code' => '200', 'data' => []];
        } else {
            return ['message' => '管理员删除失败', 'status_code' => '-1', 'data' => []];
        }
    }

}