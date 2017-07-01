<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Menu;
use App\Models\Role;
use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminAuthController extends BaseController
{

    /**
     * 添加管理员、返回token信息 POST
     * @username 管理员账号
     * @password 管理员密码
     * @nickname 管理员昵称
     * @icon     管理员头像
     * @role     管理员权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function register(Request $request)
    {
        $messages = [
            'username.required' => '账号不能为空',
            'nickname.required' => '昵称不能为空',
            'password.required' => '密码不能为空',
            'icon.required' => '用户头像不能为空',
            'role.required' => '请选择管理权限',
        ];

        $validator = \Validator::make($request->input(), [
            'username' => 'required|string',
            'nickname' => 'required|string',
            'password' => 'required',
            'icon' => 'required',
            'role' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['message' => '请先完善管理员信息再提交', 'status_code' => '-1']);
        }
        $flag = Admin::where('username', $request->username)->count();
        if ($flag) {
            return response()->json(['message' => '该账号已被其他管理员注册，请使用其他账号注册', 'status_code' => '-1']);
        }
        $admin = $request->only('username','nickname','password','icon','role');
        //unset($admin['once_pass']);
        $admin['password'] = app('hash')->make($admin['password']);
        $admin['create_time'] = time();
        $admin['update_time'] = time();
        $result = [
            'token' => \Auth::fromUser(Admin::create($admin)),
            'expired' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
            'refresh' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString(),
        ];
        return $this->response->array(['message' => 'success', 'status_code' => '200', 'data' => $result]);
    }

    /**
     * 管理员登录账号，返回token POST
     * @username 管理员账号
     * @password 管理员密码
     * @param Request $request
     */
    public function login(Request $request)
    {
        $messages = [
            'username.required' => '账号不能为空',
            'password.required' => '密码不能为空',
        ];
        $validator = \Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['message' => '账号或密码不能为空', 'status_code' => '-1']);
        }
        $credentials = $request->only('username', 'password');
        // 验证失败返回403
        if (!$token = \Auth::attempt($credentials)) {
            return response()->json(['message' => '账号或者密码错误', 'status_code' => '-1']);
        }
        //成功返回token信息以及状态码
        $result = [
            'token' => $token,
            'expired' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
            'refresh' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString(),
        ];

        //登录时间更新
        Admin::where('username', $credentials['username'])->update(['login_time' => time()]);
        $info = Admin::select('icon')->where('username', $credentials['username'])->first()->toArray();
        $result['icon'] = $info['icon'];
        return $this->response->array(['message' => 'success', 'status_code' => '200', 'data' => $result]);
    }

    /**
     * 刷新token，返回最新的token信息 PUT
     * @header Authorization: Bearer $token
     * @return mixed
     */
    public function tokenRefresh()
    {
        $result['data'] = [
            'token' => \Auth::refresh(),
            'expired' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
            'refresh' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString(),
        ];
        return $this->response->array(['message' => 'success', 'status_code' => '200', 'data' => $result]);
    }

    /**
     * 删除当前的token，退出登录 DELETE
     * @apiDescription 删除当前token (delete current token)
     * @apiSuccessExample {json} Success-Response:
     */
    public function loginOut()
    {
        \Auth::logout();
        return $this->response->noContent();
    }

    /**
     * 返回成功状态值
     * @return mixed
     */
    public function check()
    {
        return $this->response->array(['message' => 'success', 'status_code' => '200']);
    }

    public function updateMySelfInfo(Request $request)
    {
        $info = Admin::find($this->user()->id);
        $info->nickname = $request->nickname;
        $info->icon = $request->icon;
        $flag = $info->save();
        if ($flag) {
            return ['message' => 'success', 'status_code' => '200'];
        } else {
            return ['message' => '信息修改失败', 'status_code' => '-1'];
        }
    }

    /**
     * 获取管理员菜单权限信息
     * @return mixed
     */
    public function info()
    {
        $role_info = Role::find($this->user()->role);
        if($role_info){
            $menuArr = unserialize($role_info->role);
            $menuList = Menu::whereIn('id',$menuArr['master_menu'])->where('status',1)->orderBy('sort','desc')->select(['id','name','url','icon'])->get();
            $roleList = [];
            foreach ($menuList as &$value){
                $subMenu = Menu::whereIn('id',$menuArr['sub_menu'])->where('parent_id',$value['id'])->where('status',1)->orderBy('sort','desc')->select(['id','name','url','icon'])->get();
                $value['sub_menu'] = $subMenu;
                $roleList[$value['url']] = $this->getRoleList($value['id'],$menuArr['sub_menu']);
                foreach ($subMenu as $val){
                    $roleList[$val['url']] = $this->getRoleList($val['id'],$menuArr['sub_menu']);
                }
            }
        }else{
            $menuList = $roleList = [];
        }
        $this->user()->menuList = $menuList;
        $this->user()->roleList = $roleList;
        return $this->response->array(['message' => 'success', 'status_code' => '200', 'data' => $this->user()]);
    }

    private function getRoleList($parent_id,$sub_menu)
    {
        $roleArr = [];
        $data = Menu::where('parent_id',$parent_id)->where('type',1)->select(['id','model'])->get()->toArray();
        foreach ($data as $value){
            if(in_array($value['id'],$sub_menu)){
                $roleModel = explode('.',$value['model']);
                if(!empty($roleModel[1])){
                    $roleArr[] = $roleModel[1];
                }
            }
        }
        return $roleArr;
    }
}
