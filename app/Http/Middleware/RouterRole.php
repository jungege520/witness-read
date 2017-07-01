<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 17/5/27
 * Time: 下午1:14
 */
namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RouterRole
{
    /**
     * 运行请求过滤
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeInfo = app('request')->route();
        $routeInfo = $routeInfo[1];
        $role_info = Role::find($request->user()->role);
        $menu = unserialize($role_info->role);
        $menu_id_arr = Menu::where('model',$routeInfo['as'])->pluck('id');
        if(empty($menu_id_arr[0])){
            return ['message'=>'你无权此权限','code'=>'-1','data'=>[]];
        }
        if(!in_array($menu_id_arr[0],$menu['master_menu']) && !in_array($menu_id_arr[0],$menu['sub_menu'])){
            return ['message'=>'你无权此权限','code'=>'-1','data'=>[]];
        }
        return $next($request);
    }

}