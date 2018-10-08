<?php
// error_reporting(E_ALL | E_STRICT); //开发环境 输出所有错误
// error_reporting(E_ERROR | E_PARSE); //正式环境 输出致命错误,语法错误

return [
    'use_cache'              => false,
    'api_host'               => 'http://api.com',
    'app_debug'              => true,
    // 默认输出类型
    'default_return_type'    => 'json',

    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '\app\api\exception\HandleController',

];
