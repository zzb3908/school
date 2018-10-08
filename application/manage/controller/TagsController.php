<?php
/**
 * 标签管理
 * TagsController.php
 * @version     v1.0
 * @date        2018-08-12
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\controller;

class TagsController extends BaseController{

    // 标签/系 管理
    public function indexAction(){
        $param = input();
    
        $assign = [];

        $assign['module_type'] = json_encode(config('module_type'), JSON_UNESCAPED_UNICODE);
        // $assign['module_type'] = config('module_type');

        // die($assign['module_type']);

        $this->assign($assign);
        return $this->fetch();
    }

    /**
     * 列表json
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-12
     * @author      iclubs <iclubs@126.com>
     */
    public function listAction(){
        $page = input('page', 1);
        $page_size = input('limit', 20);
        $param = input();

        $model = model('Tags');
        $result = $model->fetchTagsList($page, $page_size, $param);

        if ($result['list']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['count'], 'data'=>$result['list']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    public function actAction(){
        $param = input();

        if (empty($param['id'])) {
            return json(['status'=>0, 'msg'=>'id不能为空']);
        }

        $model = model('Tags');
        $detail = $model->fetchTagsDetail($param['id']);
        if (empty($detail)) {
            return json(['status'=>0, 'msg'=>'修改失败！标签不存在！']);
        }

        if ($param['field'] == 'T_Name') {
            $where = [
                ['T_Module', '=', $detail['T_Module']],
                ['T_Id', '<>', $param['id']],
                ['T_Name', '=', $param['value']],
            ];
            $exist = $model->fetchTagsDetailByWhere($where);
            if ($exist) {
                return json(['status'=>0, 'msg'=>'修改失败！标签名称已存在']);
            }

            $result = $model->updateTags($param['id'], ['T_Name'=>$param['value']]);
            if ($result) {
                $res = ['status'=>1, 'msg'=>'修改成功'];
            }else{
                $res = ['status'=>0, 'msg'=>'修改失败！'];
            }
        }elseif ($param['field'] == 'T_ZJM') {
            $result = $model->updateTags($param['id'], ['T_ZJM'=>$param['value']]);
            if ($result) {
                $res = ['status'=>1, 'msg'=>'修改成功'];
            }else{
                $res = ['status'=>0, 'msg'=>'修改失败！'];
            }
        }elseif ($param['field'] == 'T_Sort') {
            $result = $model->updateTags($param['id'], ['T_Sort'=>$param['value']]);
            if ($result) {
                $res = ['status'=>1, 'msg'=>'修改成功'];
            }else{
                $res = ['status'=>0, 'msg'=>'修改失败！'];
            }
        }elseif ($param['field'] == 'T_Module') {
            $result = $model->updateTags($param['id'], ['T_Module'=>$param['value']]);
            if ($result) {
                $res = ['status'=>1, 'msg'=>'修改成功'];
            }else{
                $res = ['status'=>0, 'msg'=>'修改失败！'];
            }
        }else{
            $res = ['status'=>0, 'msg'=>'无效操作！'];
        }
        return json($res);
    }

    public function deleteAction(){
        $param = input();
        if (empty($param['ids'])) {
            return json(['status'=>0, 'msg'=>'id不能为空']);
        }

        if (is_string($param['ids'])) {
            $ids = explode(',', $param['ids']);
        }else{
            $ids = $param['ids'];
        }

        $model = model('Tags');
        foreach ($ids as $key => $value) {
            $model->deleteTags($value);
        }

        return json(['status'=>1, 'msg'=>'删除成功']);
    }

    public function editAction(){
        $assign = [];

        $id = input('id',0);
        $tagsModel = model('Tags');

        if ($this->request->isPost()) {

            $param = input();

            /*if (empty($param['T_Module'])) {
                return json(['status'=>0, 'msg'=>'请先选择类型！']);
            }*/

            if ($id) {
                $data = [
                    'T_Module' => $param['T_Module'] ? $param['T_Module'] : 0,
                    'T_Name' => $param['T_Name'],
                    'T_ZJM' => $param['T_ZJM'],
                    'T_Sort' => $param['T_Sort'],
                ];
                $result = $tagsModel->updateTags($id, $data);
                $tips = '编辑';
            }else{
                $tid = $tagsModel->fetchTagsMaxId() + 1;
                $data = [
                    'T_Module' => $param['T_Module'] ? $param['T_Module'] : 0,
                    'T_Name' => '新标签'.$tid,
                    'T_ZJM' => 'XBQ'.$tid,
                    'T_Sort' => 0,
                    'T_AddUId' => 0,
                ];
                $result = $tagsModel->addTags($data);
                $tips = '添加';
            }

            if ($result) {
                $res = ['status'=>1, 'msg'=>$tips.'成功'];
            }else{
                $res = ['status'=>0, 'msg'=>$tips.'失败'];
            }
            return json($res);
        }else{

            $detail = $tagsModel->fetchTagsDetail($id);

            $assign['detail'] = $detail;

            $this->assign($assign);
            return $this->fetch();
        }

    }
    
}
