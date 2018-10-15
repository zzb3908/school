<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/15
 * Time: 14:09
 */
namespace  app\api\controller;

use app\common\lib\exception\ApiException;
use think\Exception;
use think\Request;

class InviteinfoController extends BaseController
{
    public function listAction(Request $request)
    {
        try {
            dump($request->user);die;
        } catch (Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }
}