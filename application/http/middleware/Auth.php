<?php

namespace app\http\middleware;

use think\facade\Session;

class Auth
{
    public function handle($request, \Closure $next)
    {
        // admin端登录验证
        /*if(Session::has('admin_user')){
            return $next($request);
        }else{
            return redirect('admin.login');
        }*/
        return $next($request);
    }
}
