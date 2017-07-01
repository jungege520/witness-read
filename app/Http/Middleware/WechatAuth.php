<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 17/5/27
 * Time: 下午1:14
 */

namespace App\Http\Middleware;

use Closure;
use App\Libraries\WechatAuth as wechat_auth;

class WechatAuth
{
    /**
     * 运行请求过滤
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->start_session();
        $user_info = !empty($_SESSION['wechat.user']) ? $_SESSION['wechat.user'] : '';
        if (!$user_info) {
            $wechat = new wechat_auth();
            $user_info = $wechat->auth();
            if(!empty($user_info->openid)){
                $_SESSION['wechat.user'] = $user_info;
            }else{
                dd('授权失败');
            }
        }
        $request->attributes->add(['wechatUserInfo' => $user_info]);
        return $next($request);
    }

    /**
     * 设置session的缓存机制
     * @param int $expire
     */
    private function start_session($expire = 0)
    {
        if ($expire == 0) {
            $expire = ini_get('session.gc_maxlifetime');
        } else {
            ini_set('session.gc_maxlifetime', $expire);
        }
        if (empty($_COOKIE['PHPSESSID'])) {
            session_set_cookie_params($expire);
            isset($_SESSION) OR session_start();
        } else {
            isset($_SESSION) OR session_start();
            setcookie('PHPSESSID', session_id(), time() + $expire);
        }
    }

}