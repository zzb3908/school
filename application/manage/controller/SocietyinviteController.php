<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/9
 * Time: 9:17
 */

namespace app\manage\controller;

use app\manage\model\BookcategoryModel;
use app\manage\model\BookinfoModel;
use app\manage\model\CalendarModel;
use app\manage\model\SocietyinvitealbumModel;
use app\manage\model\SocietyinviteModel;
use app\manage\model\SocietyuserModel;
use think\Request;
use think\Validate;

class SocietyinviteController extends BaseController{

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
            $where[] = ['SI_SI_Name|SI_SchoolName','like',"%{$param['keyword']}%"];
        }

        //获取数据
        $result = SocietyinviteModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    /**
     * 删除书籍
     * @param Request $request
     * @return \think\response\Json
     */
    public function destroyAction(Request $request)
    {
        $ids = $request->only('ids');
        SocietyinviteModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }

    public function societyinvitealbumAction()
    {
        return $this->fetch();
    }

    public function societyinvitealbumListAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('keyword');

        //处理参数
        if($param['keyword']){
            $where[] = ['SI_SI_Name|SI_SchoolName','like',"%{$param['keyword']}%"];
        }

        //获取数据
        $result = SocietyinvitealbumModel::with('societyinvite')->where($where)->paginate($page_size)->toArray();


        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    public function societyinvitealbumDestroyAction(Request $request)
    {
        $ids = $request->only('ids');
        SocietyinvitealbumModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }

    public function societyuserAction()
    {
        return $this->fetch();
    }

    public function societyuserListAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('keyword');

        //处理参数
        if($param['keyword']){
            $where[] = ['SU_SI_Name|SU_UName|SU_AuditUName','like',"%{$param['keyword']}%"];
        }

        //获取数据
        $result = SocietyuserModel::where($where)->paginate($page_size)->toArray();


        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    public function societyuserDestroyAction(Request $request)
    {
        $ids = $request->only('ids');
        SocietyuserModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }


}
