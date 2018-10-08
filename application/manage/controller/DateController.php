<?php
/**
 * 学校管理
 * SchoolsController.php
 * @version     v1.0
 * @date        2018-08-12
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com
 * @link        http://www.openver.com
 */

namespace app\manage\controller;

use app\manage\model\DateModel;
use think\Request;
use think\Validate;

class DateController extends BaseController{

    public function indexAction()
    {
        return $this->fetch();
    }

    /**
     * 约吧列表
     * @param Request $request
     * @return \think\response\Json|void
     * @throws \think\exception\DbException
     */
    public function listAction(Request $request){
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('II_Title,II_Status');

        //验证数据合法性
        $validate = Validate::make([
            'II_Status|状态' => 'number'
        ]);
        if(!$validate->check($param)){
            $res = ['code'=>204, 'msg'=>$validate->getError(), 'data'=>[]];
            return json($res);
        }

        //处理参数
        if($param['II_Title']){
            $where[] = ['II_Title','like',"%{$param['II_Title']}%"];
        }
        if($param['II_Status'] || $param['II_Status'] === 0 ||$param['II_Status'] === '0'){
            $where[] = ['II_Status','=',$param['II_Status']];
        }

        //获取数据
        $result = DateModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

}
