<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/9
 * Time: 9:17
 */

namespace app\manage\controller;

use app\manage\model\CommentsModel;
use app\manage\model\CompetitioncategoryModel;
use app\manage\model\CompetitioninfoModel;
use app\manage\model\InviteModel;
use app\manage\model\TopicgroupModel;
use app\manage\model\TopicinfoModel;
use think\Request;
use think\Validate;

class CompetitionController extends BaseController{

    //赛吧主页面
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
            $where[] = ['CI_CC_Name|CI_Title|CI_Content|CI_Hosts|CI_Organizers|CI_Sponsor|CI_TitleSponsor|CI_LevelName|CI_SchoolName|CI_ProvName|CI_CityName|CI_LocationName|CI_AddUName','like',"%{$param['keyword']}%"];
        }

        //获取数据
        $result = CompetitioninfoModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    /**
     * 删除聚吧
     * @param Request $request
     * @return \think\response\Json
     */
    public function destroyAction(Request $request)
    {
        $ids = $request->only('ids');
        CompetitioninfoModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }

    //赛事分类页
    public function categoryAction($pid)
    {
        return $this->fetch('',compact('pid'));
    }

    public function categorylistAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('pid');

        //验证数据合法性
        $validate = Validate::make([
            'CC_PId|比赛类型' => 'number'
        ]);
        if(!$validate->check($param)){
            $res = ['code'=>204, 'msg'=>$validate->getError(), 'data'=>[]];
            return json($res);
        }

        //处理参数
        if($param['pid']){
            $where[] = ['CC_PId','=',$param['pid']];
        }

        //获取数据
        $result = CompetitioncategoryModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    /**
     * 删除聚吧小组
     * @param Request $request
     * @return \think\response\Json
     */
    public function categorydestroyAction(Request $request)
    {
        $ids = $request->only('ids');
        CompetitioncategoryModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }


}
