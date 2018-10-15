<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 17/8/6
 * Time: 上午2:45
 */
namespace app\common\lib\exception;
use think\exception\Handle;

class ApiHandleException extends  Handle {

    /**
     * http 状态码
     * @var int
     */
    public $httpCode = 500;

    public function render(\Exception $e) {
        // 如果开启调试异常且不是API异常就交给系统处理
        if(config('app_debug') == true && !$e instanceof ApiException) {
            return parent::render($e);
        }else{
            // 如果是API异常交给
            if ($e instanceof ApiException) {
                $this->httpCode = $e->httpCode;
            }
            return  show($e->code, $e->getMessage(), [], $this->httpCode);
        }
    }

}