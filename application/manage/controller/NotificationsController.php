<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/9
 * Time: 9:17
 */

namespace app\manage\controller;

use app\manage\model\NotificationsModel;
use think\Request;

class NotificationsController extends BaseController{

    public function indexAction(Request $request)
    {
        $module = $request->module;
        return $this->fetch('',compact('module'));
    }

    public function listAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('keyword,module');

        //处理参数
        if($param['keyword']){
            $where[] = ['N_Title','like',"%{$param['keyword']}%"];
        }
        $where[] = ['N_Module','=',$param['module']];

        //获取数据
        $result = NotificationsModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    /**
     * 删除班级
     * @param Request $request
     * @return \think\response\Json
     */
    public function destroyAction(Request $request)
    {
        $ids = $request->only('ids');
        NotificationsModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }



}
