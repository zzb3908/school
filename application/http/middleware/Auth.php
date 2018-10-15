<?php

namespace app\http\middleware;

use app\api\model\UserinfoModel;
use app\common\lib\exception\ApiException;
use think\facade\Session;

class Auth
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @throws ApiException
     * @throws \think\exception\DbException
     */
    public function handle($request, \Closure $next)
    {
        // 签名验证
        if($this->isLogin()){
            $request->user = $this->isLogin();
            return $next($request);
        }else{
            throw new ApiException('您没有登录',401,config('code.no_login'));
        }
    }

    /**
     * 判定是否登录
     * @return UserinfoModel|bool|null
     * @throws \think\exception\DbException
     */
    public function isLogin() {
        $headers = request()->header();
        $user = UserinfoModel::get(['UI_Id'=>$headers['id']]);
        if($user->isEmpty()){
            return true;
        }
        return $user;
    }
}
