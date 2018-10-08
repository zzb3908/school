<?php
namespace app\api\exception;

use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;

class HandleController extends Handle{

    public function render(Exception $e)
    {
        if ($e instanceof HttpException || $e instanceof ValidateException) {
            $statusCode = $e->getStatusCode();
        }else{
            $statusCode = 404;
        }

        if (config('app_debug') === true) {
            return parent::render($e);
        }else{
            $msg = $this->getMessage($e);
            switch ($statusCode) {
                case 403:
                    @header("HTTP/1.x 403 Forbidden");
                    @header('Status: 403 Forbidden');
                    $res = array('Code'=>403,'Msg'=>$msg);
                    break;
                case 404:
                    @header("HTTP/1.x 404 File Not Found");
                    @header('Status: 404 File Not Found');
                    $res = array('Code'=>404,'Msg'=>$msg);
                    break;
                case 500:
                    @header('HTTP/1.x 500 Internal Server Error');
                    @header('Status: 500 Internal Server Error');
                    $res = array('Code'=>500,'Msg'=>$msg);
                    break;
                case 502:
                    @header('HTTP/1.x 502 Bad Gateway');
                    @header('Status: 502 Bad Gateway');
                    $res = array('Code'=>502,'Msg'=>$msg);
                    break;
                case 503:
                    @header('HTTP/1.x 503 Service Unavailable');
                    @header('Status: 503 Service Unavailable');
                    $res = array('Code'=>503,'Msg'=>$msg);
                    break;
                default:
                    @header("HTTP/1.x 404 File Not Found");
                    @header('Status: 404 File Not Found');
                    $res = array('Code'=>404,'Msg'=>$msg);
                    break;
            }
            return json($res);
        }
    }

}