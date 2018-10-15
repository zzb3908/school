<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 17/8/6
 * Time: 上午2:57
 */
namespace app\common\lib\exception;
use think\Exception;

class ApiException extends Exception {

    public $message = '';
    public $httpCode = 500;
    public $code = 0;
    /**
     * API异常构造方法
     * @param string $message
     * @param int $httpCode http状态码
     * @param int $code     自定义状态码
     */
    public function __construct($message = '', $httpCode = 0, $code = 0) {
        $this->httpCode = $httpCode;
        $this->message = $message;
        $this->code = $code;
    }
}