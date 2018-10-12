<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/9
 * Time: 9:17
 */

namespace app\manage\controller;

use app\manage\model\CalendarModel;
use app\manage\model\ClassleaveModel;
use app\manage\model\ClassModel;
use app\manage\model\ClassuserModel;
use think\Request;

class ClassController extends BaseController{

    public function indexAction()
    {
        return $this->fetch();
    }

    public function listAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('keyword');

        //处理参数
        if($param['keyword']){
            $where[] = ['CI_Name|CI_HeadmasterName|CI_SchoolName|CI_AddUName','like',"%{$param['keyword']}%"];
        }

        //获取数据
        $result = ClassModel::where($where)->paginate($page_size)->toArray();

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
        ClassModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }

    //班级成员
    public function userindexAction()
    {
        return $this->fetch();
    }

    public function userlistAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('keyword');

        //处理参数
        if($param['keyword']){
            $where[] = ['TU_CI_Name|TU_UName','like',"%{$param['keyword']}%"];
        }

        //获取数据
        $result = ClassuserModel::with('classes')->where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    /**
     * 删除班级成员
     * @param Request $request
     * @return \think\response\Json
     */
    public function userdestroyAction(Request $request)
    {
        $ids = $request->only('ids');
        ClassuserModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }

    public function classleaveAction()
    {
        return $this->fetch();
    }

    public function leavelistAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('keyword');

        //处理参数
        if($param['keyword']){
            $where[] = ['CL_AddUName|CL_AuditUName','like',"%{$param['keyword']}%"];
        }

        //获取数据
        $result = ClassleaveModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    /**
     * 删除班级成员
     * @param Request $request
     * @return \think\response\Json
     */
    public function leavedestroyAction(Request $request)
    {
        $ids = $request->only('ids');
        ClassleaveModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }

}
