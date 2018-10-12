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
use think\Request;
use think\Validate;

class CalendarController extends BaseController{

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
            $where[] = ['CE_Title|CE_Content|CI_AddUName|CE_DataType','like',"%{$param['keyword']}%"];
        }

        //获取数据
        $result = CalendarModel::where($where)->paginate($page_size)->toArray();

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
        CalendarModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }


}
