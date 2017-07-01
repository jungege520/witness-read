<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 17/5/26
 * Time: 下午9:52
 */

namespace App\Http\Controllers\Api\Admin;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends BaseController
{
    /**
     * 获取所有菜单列表 POST
     * @param Request $request
     * @return mixed
     */
    public function roleList(Request $request)
    {
        $sort = $request->sort;
        $list = Role::where('status', '!=', '-1')
            ->orderBy(!empty($sort['key']) ? $sort['key'] : 'id', !empty($sort['orderBy']) ? $sort['orderBy'] : 'desc')
            ->paginate(!empty($request->page_size) ? $request->page_size : 15)
            ->toArray();
        foreach ($list['data'] as &$value) {
            $role = unserialize($value['role']);
            $master_arr = $sub_arr = [];
            foreach ($role['master_menu'] as $master) {
                $master_arr[] = intval($master);
            }
            foreach ($role['sub_menu'] as $sub) {
                $sub_arr[] = intval($sub);
            }
            $value['master_menu'] = $master_arr;
            $value['sub_menu'] = $sub_arr;
            unset($value['role']);
        }
        return ['message' => 'success', 'status_code' => '200', 'data' => $list];
    }

    /**
     * 添加菜单接口 POST
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => '角色名称不能为空',
            'master_menu.required' => '权限不能为空',
            'desc.required' => '角色描述不能为空',
        ];

        $validator = \Validator::make($request->input(), [
            'name' => 'required|string',
            'master_menu' => 'required|array',
            'desc' => 'required|string',
        ], $messages);

        if ($validator->fails()) {
            return ['message' => '请先完善角色信息', 'status_code' => '-1'];
        }
        $data = $request->only('name', 'desc');
        $data['role'] = serialize(['master_menu' => $request->input('master_menu'), 'sub_menu' => !empty($request->input('sub_menu')) ? $request->input('sub_menu') : []]);
        $data['create_time'] = time();
        $data['status'] = 1;
        $flag = Role::create($data);
        if ($flag) {
            return ['message' => 'success', 'status_code' => '200', 'data' => []];
        } else {
            return ['message' => '角色创建失败', 'status_code' => '-1', 'data' => []];
        }
    }

    /**
     * 更新菜单 PUT
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $messages = [
            'name.required' => '角色名称不能为空',
            'master_menu.required' => '权限不能为空',
            'desc.required' => '角色描述不能为空',
        ];

        $validator = \Validator::make($request->input(), [
            'name' => 'required|string',
            'master_menu' => 'required|array',
            'desc' => 'required|string',
        ], $messages);

        if ($validator->fails()) {
            return ['message' => '请先完善角色信息', 'status_code' => '-1'];
        }
        $data = $request->only('name', 'desc');
        $data['role'] = serialize(['master_menu' => $request->input('master_menu'), 'sub_menu' => !empty($request->input('sub_menu')) ? $request->input('sub_menu') : []]);
        $data['update_time'] = time();
        $flag = Role::where('id', $request->id)->update($data);
        if ($flag) {
            return ['message' => 'success', 'status_code' => '200', 'data' => []];
        } else {
            return ['message' => '角色更新失败', 'status_code' => '-1', 'data' => []];
        }
    }

    /**
     * 删除菜单 DELETE
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $role = Role::find($request->id);
        if ($role->status == 1) {
            $role->status = -1;
        } else {
            return ['message' => '角色不存在', 'status_code' => '-1', 'data' => []];
        }

        if ($role->save()) {
            return ['message' => 'success', 'status_code' => '200', 'data' => []];
        } else {
            return ['message' => '角色删除失败', 'status_code' => '-1', 'data' => []];
        }
    }

}