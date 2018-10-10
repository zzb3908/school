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
use app\manage\model\CommentsModel;
use app\manage\model\SpecialColumnModel;
use think\Request;
use think\Validate;

class BookController extends BaseController{

    public function indexAction()
    {
        $book_categories = BookcategoryModel::all();
        return $this->fetch('',compact('book_categories'));
    }

    public function listAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('BI_BC_Id,keyword');

        //验证数据合法性
        $validate = Validate::make([
            'BI_BC_Id|书籍类型' => 'number'
        ]);
        if(!$validate->check($param)){
            $res = ['code'=>204, 'msg'=>$validate->getError(), 'data'=>[]];
            return json($res);
        }

        //处理参数
        if($param['keyword']){
            $where[] = ['BI_AuthorName|BI_Name','like',"%{$param['keyword']}%"];
        }
        if($param['BI_BC_Id']){
            $where[] = ['BI_BC_Id','=',$param['BI_BC_Id']];
        }

        //获取数据
        $result = BookinfoModel::where($where)->paginate($page_size)->toArray();

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
        BookinfoModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }



    /**
     * 是否开启评论
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function updateAction(Request $request)
    {
        $data = BookinfoModel::get($request->id);
        $data->BI_CommentIsOpen = $request->val;
        if($data->save()){
            $res = ['status'=>1, 'msg'=>'设置成功'];
        }else{
            $res = ['status'=>0, 'msg'=>'设置失败'];
        }
        return json($res);
    }

    public function messagesAction()
    {
        return $this->fetch();
    }

    /**
     * 读吧留言列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function messagesListAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('keyword');

        //处理参数
        if($param['keyword']){
            $where[] = ['C_DataTitle','like',"%{$param['keyword']}%"];
        }
        $where[] = ['C_Module','=','205'];

        //获取数据
        $result = CommentsModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    public function categoryAction()
    {
        return $this->fetch();
    }

    /**
     * 书籍类别
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function categoryListAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('keyword');

        //处理参数
        if($param['keyword']){
            $where[] = ['BC_Name','like',"%{$param['keyword']}%"];
        }

        //获取数据
        $result = BookcategoryModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    public function specialcolumnAction()
    {
        $book_categories = BookcategoryModel::all();
        return $this->fetch('',compact('book_categories'));
    }

    /**
     * 专题列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function specialcolumnListAction(Request $request)
    {
        //获取参数
        $page      = $request->page;
        $page_size = $request->limit;
        $param     = $request->only('SC_BC_Id,keyword');

        //验证数据合法性
        $validate = Validate::make([
            'SC_BC_Id|书籍类型' => 'number'
        ]);
        if(!$validate->check($param)){
            $res = ['code'=>204, 'msg'=>$validate->getError(), 'data'=>[]];
            return json($res);
        }

        //处理参数
        if($param['keyword']){
            $where[] = ['SC_CC_Name|SC_Name|SC_AddUName','like',"%{$param['keyword']}%"];
        }
        if($param['SC_BC_Id']){
            $where[] = ['SC_BC_Id','=',$param['SC_BC_Id']];
        }

        //获取数据
        $result = SpecialColumnModel::where($where)->paginate($page_size)->toArray();

        if ($result['data']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['total'], 'data'=>$result['data']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    /**
     * 删除专题
     * @param Request $request
     * @return \think\response\Json
     */
    public function specialcolumndestroyAction(Request $request)
    {
        $ids = $request->only('ids');
        SpecialColumnModel::destroy($ids['ids']);
        return json(['status'=>1, 'msg'=>'删除成功']);
    }



    /**
     * 是否开启专题评论
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function specialcolumnupdateAction(Request $request)
    {
        $data = SpecialColumnModel::get($request->id);
        $data->SC_CommentIsOpen = $request->val;
        if($data->save()){
            $res = ['status'=>1, 'msg'=>'设置成功'];
        }else{
            $res = ['status'=>0, 'msg'=>'设置失败'];
        }
        return json($res);
    }

}
