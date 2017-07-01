<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 17/5/27
 * Time: 下午1:14
 */
namespace App\Http\Middleware;

use Closure;

class AdminRole
{
    /**
     * 运行请求过滤
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!$request->user()->hasRole($role)) {
            // 重定向...
        }
        return $next($request);
    }

}