<?php
namespace app\api\controller;

class IndexController extends BaseController{

    public function indexAction(){

        $data = [
        	'Code' => 200,
            'Msg' => '查询成功',
            'Data' => [
                ['name'=>'哈罗', 'age'=>'23' ,'gender'=>'男'],
                ['name'=>'小鱼儿', 'age'=>'18', 'gender'=>'未知'],
                ['name'=>'方方', 'age'=>'18', 'gender'=>'女'],
            ],
        ];
        return json($data);
    }

    public function helloAction()
    {
        halt($this->request->header());
    }
}
