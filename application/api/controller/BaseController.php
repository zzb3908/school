<?php
namespace app\api\controller;

class BaseController extends \think\Controller{

    protected $data;

    protected function initialize(){
    	$this->data = $this->request->param();
    }
}
