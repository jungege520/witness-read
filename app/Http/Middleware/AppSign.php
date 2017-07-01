<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 17/5/27
 * Time: 下午1:14
 */

namespace App\Http\Middleware;

use Closure;

class AppSign
{
    /**
     * 运行请求过滤
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $AppKey = env('API_KEY', 'vue-admin@#!');
        $flag = env('API_SIGN', true);
        $data = $request->all();
        $sign_origin = $data['sign'];
        $timestamp = $data['timestamp'];
        unset($data['sign'], $data['timestamp']);
        $keys = array_keys($data);
        sort($keys);
        $signStr = '';
        foreach ($keys as $value) {
            if (is_array($data[$value]) && $data[$value]) {
                foreach ($data[$value] as $k => $v) {
                    $signStr .= $value . '[' . $k . ']' . '=' . $v . '&';
                }
            } else {
                $signStr .= $value . '=' . $data[$value] . '&';
            }
        }
        $signStr = $keys ? $signStr . 'appKey=' . $AppKey . '&timestamp=' . $timestamp : 'appKey=' . $AppKey . '&timestamp=' . $timestamp;
        $sign = strtoupper(md5($signStr));
        if ($flag) {
            if ($sign != $sign_origin) {
                return ['message' => '签名信息错误', 'code' => '-1', 'data' => []];
            }
            if ($timestamp < time() - env('API_SIGN_EXPIRE', 2 * 60)) {
                return ['message' => '签名信息已过期', 'code' => '-1', 'data' => []];
            }
        }
        return $next($request);
    }
}