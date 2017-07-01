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
use App\Models\Menu;

class MenuController extends BaseController
{
    /**
     * 获取所有菜单列表 POST
     * @param Request $request
     * @return mixed
     */
    public function menuList(Request $request)
    {
        $list = Menu::with(array('sub_menu' => function ($query) {
            $query->where('type', 0);
            $query->orderBy('sort', 'desc');
            $query->with(array('sub_operate_menu' => function ($query2) {
                $query2->where('type', 1);
                $query2->orderBy('sort', 'desc');
            }));
        }))->with(array('sub_operate_menu' => function ($query) {
            $query->where('type', 1);
            $query->orderBy('sort', 'desc');
        }))->where('status', '!=', '-1')->where('parent_id', '=', '0')->orderBy('sort', 'desc')->get();
        return ['message' => 'success', 'status_code' => '200', 'data' => $list];
    }

    /**
     * 获取所有的权限菜单列表 POST
     * @param Request $request
     * @return mixed
     */
    public function menuRoleList()
    {
        $list = Menu::with(array('sub_menu' => function ($query) {
            $query->where('type', 0);
            $query->orderBy('sort', 'desc');
            $query->with(array('sub_operate_menu' => function ($query2) {
                $query2->where('type', 1);
                $query2->orderBy('sort', 'desc');
            }));
        }))->with(array('sub_operate_menu' => function ($query) {
            $query->where('type', 1);
            $query->orderBy('sort', 'desc');
        }))->where('status', '!=', '-1')->where('parent_id', '=', '0')->orderBy('sort', 'desc')->get()->toArray();
        $menuArr = [];
        foreach ($list as $value) {
            $subMenuArr = [];
            foreach ($value['sub_operate_menu'] as $v1) {
                $subMenuArr[] = $v1;
            }
            foreach ($value['sub_menu'] as $v2) {
                $a = $v2;
                unset($a['sub_operate_menu']);
                $subMenuArr[] = $a;
                foreach ($v2['sub_operate_menu'] as $v3) {
                    $subMenuArr[] = $v3;
                }
            }
            $value['sub_menu'] = $subMenuArr;
            unset($value['sub_operate_menu']);
            $menuArr[] = $value;
        }
        return ['message' => 'success', 'status_code' => '200', 'data' => $menuArr];
    }

    /**
     * 添加菜单接口 POST
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => '菜单名称不能为空',
            'parent_id.required' => '权限等级不能为空',
            'icon.required' => '图标不能为空',
            'model.required' => '权限不能为空',
            'url.required' => 'URL链接不能为空',
            'desc.required' => '菜单描述不能为空',
        ];

        $validator = \Validator::make($request->input(), [
            'name' => 'required|string',
            'parent_id' => 'required|int',
            'icon' => 'required|string',
            'model' => 'required|string',
            'url' => 'required|string',
            'desc' => 'required|string',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['message' => '请先完善菜单信息信息', 'status_code' => '-1']);
        }
        $flag = Menu::create($request->only('name', 'parent_id', 'icon', 'model', 'url', 'desc', 'status', 'type'));
        if ($flag) {
            return ['message' => 'success', 'status_code' => '200', 'data' => []];
        } else {
            return ['message' => '菜单创建失败', 'status_code' => '-1', 'data' => []];
        }
    }

    public function updateMenuSort(Request $request)
    {
        $sort = 99;
        $flag = [];
        if (!empty($request->menu_sort) && is_array($request->menu_sort)) {
            foreach ($request->menu_sort as $ke => $value) {
                $flag[] = Menu::where('id', $value)->update(['sort' => $sort, 'update_time' => time()]);
                $sort -= 1;
            }
        }
        if (in_array(false, $flag) || in_array(0, $flag)) {
            return ['message' => '菜单顺序排列失败', 'status_code' => '-1', 'data' => []];
        } else {
            return ['message' => 'success', 'status_code' => '200', 'data' => []];
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
            'name.required' => '菜单名称不能为空',
            'parent_id.required' => '权限等级不能为空',
            'icon.required' => '图标不能为空',
            'model.required' => '权限不能为空',
            'url.required' => 'URL链接不能为空',
            'desc.required' => '菜单描述不能为空',
        ];

        $validator = \Validator::make($request->input(), [
            'name' => 'required|string',
            'parent_id' => 'required|int',
            'icon' => 'required|string',
            'model' => 'required|string',
            'url' => 'required|string',
            'desc' => 'required|string',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['message' => '请先完善菜单信息信息', 'status_code' => '-1']);
        }
        $menu = Menu::find($request->id);
        if ($menu->update($request->only('name', 'parent_id', 'icon', 'model', 'url', 'desc', 'status'))) {
            return ['message' => 'success', 'status_code' => '200', 'data' => []];
        } else {
            return ['message' => '菜单创建失败', 'status_code' => '-1', 'data' => []];
        }
    }

    /**
     * 删除菜单 DELETE
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $menu = Menu::find($request->id);
        if ($menu->status != -1) {
            $menu->status = -1;
        } else {
            return ['message' => '菜单删除失败', 'status_code' => '-1', 'data' => []];
        }

        if ($menu->save()) {
            return ['message' => 'success', 'status_code' => '200', 'data' => []];
        } else {
            return ['message' => '菜单删除失败', 'status_code' => '-1', 'data' => []];
        }
    }

}