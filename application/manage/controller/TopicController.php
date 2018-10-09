<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/9
 * Time: 9:17
 */

namespace app\manage\controller;

use app\manage\model\CommentsModel;
use app\manage\model\InviteModel;
use app\manage\model\TopicgroupModel;
use think\Request;
use think\Validate;

class TopicController extends BaseController{

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
        $param     = $request->only('TG_TC_Id,TG_Status,TG_SchoolName');

        //验证数据合法性
        $validate = Validate::make([
            'TG_TC_Id|分类' => 'number',
            'TG_Status|状态' => 'number'
        ]);
        if(!$validate->check($param)){
            $res = ['code'=>204, 'msg'=>$validate->getError(), 'data'=>[]];
            return json($res);
        }

        //处理参数
        if($param['TG_SchoolName']){
            $where[] = ['TG_SchoolName','like',"%{$param['TG_SchoolName']}%"];
        }
        if($param['TG_Name']){
            $where[] = ['TG_Name','like',"%{$param['TG_Name']}%"];
        }
        if($param['TG_TC_Id']){
            $where[] = ['TG_TC_Id','=',$param['TG_TC_Id']];
        }
        if($param['TG_Status']){
            $where[] = ['TG_Status','=',$param['TG_Status']];
        }

        //获取数据
        $result = TopicgroupModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    /**
     * 是否推荐
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function updateAction(Request $request)
    {
        $data = TopicgroupModel::get($request->id);
        $data->TG_IsRecommend = $request->val;
        if($data->save()){
            $res = ['status'=>1, 'msg'=>'设置成功'];
        }else{
            $res = ['status'=>0, 'msg'=>'设置失败'];
        }
        return json($res);
    }

    /**
     * 删除小组
     * @param Request $request
     * @return \think\response\Json
     */
    public function destroyAction(Request $request)
    {
        $ids = $request->only('ids');
        TopicgroupModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }

}
