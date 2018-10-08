<?php
namespace app\manage\controller;

class BaseController extends \think\Controller{

    protected $login;

    protected function initialize(){
    	$assign = [];

        $assign['login'] = $this->login = [
            'UI_Id' => '1',
            'UI_UserType' => '1',
            'UI_Nickname' => '飞鱼',
            'UI_Realname' => '张华',
            'UI_MobilePhone' => '15356694001',
            'UI_Gender' => '1',
            'UI_Avatar' => '',
        ];

        $this->assign($assign);
    }
}
