<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/9
 * Time: 9:17
 */

namespace app\manage\controller;

use app\manage\model\CalendarModel;
use app\manage\model\UsersModel;
use think\Request;

class UsersController extends BaseController{

    public function indexAction()
    {
        return $this->fetch();
    }

    public function listAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('keyword,UI_UserType');

        //处理参数
        if($param['keyword']){
            $where[] = ['UI_Nickname|UI_Realname|UI_SchoolName','like',"%{$param['keyword']}%"];
        }
        if($param['UI_UserType']){
            $where[] = ['UI_UserType','=',$param['UI_UserType']];
        }

        //获取数据
        $result = UsersModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    public function destroyAction(Request $request)
    {
        $ids = $request->only('ids');
        UsersModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }


}
