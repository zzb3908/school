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

class SchoolsController extends BaseController{

    public function indexAction(){
        $assign = [];
        $param = input();

        $areaModel = model('Area');
        $assign['arealist'] = $areaModel->fetchAreaListAll(['AI_PId'=>0]);

        //获取城市数据
        if ($param['S_ProvId']) {
            $assign['citylist'] = $areaModel->fetchAreaListAll(['AI_PId'=>$param['S_ProvId']]);
        }

        $this->assign($assign);
        return $this->fetch();
    }

    public function editAction(){
        $assign = [];

        $id = input('id',0);
        $model = model('Schools');

        if ($this->request->isPost()) {

            $param = input();

            if (empty($param['S_Name'])) {
                return json(['status'=>0, 'msg'=>'学校名称必须填写！']);
            }elseif (empty($param['S_ProvId'])) {
                return json(['status'=>0, 'msg'=>'请选择所在地区！']);
            }

            if ($id) {
                $result = $model->updateSchools($id, $param);
                $tips = '编辑';
            }else{
                $result = $model->addSchools($param);
                $tips = '添加';
            }

            if ($result) {
                $res = ['status'=>1, 'msg'=>$tips.'成功'];
            }else{
                $res = ['status'=>0, 'msg'=>$tips.'失败'];
            }
            return json($res);
        }else{
            $assign['detail'] = $detail = $model->fetchSchoolsDetail($id);
            if ($detail['S_Id'] && empty($detail)) {
                $this->error('数据不存在！');
            }

            $areaModel = model('Area');
            $assign['arealist'] = $areaModel->fetchAreaListAll(['AI_PId'=>0]);

            //获取城市数据
            if ($detail['S_ProvId']) {
                $assign['citylist'] = $areaModel->fetchAreaListAll(['AI_PId'=>$detail['S_ProvId']]);
            }

            $this->assign($assign);
            return $this->fetch();
        }
    }

    /**
     * 学校列表json
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

        $model = model('Schools');
        $result = $model->fetchSchoolsList($page, $page_size, $param);

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

        $model = model('Schools');

        if ($param['type'] == 'changeRecommend') {
            $tips = $param['recommend']==1 ? '设置推荐' : '取消推荐';
            $result = $model->updateSchools($param['id'], ['NI_IsRecommendIndex'=>$param['recommend']]);
            if ($result) {
                $res = ['status'=>1, 'msg'=>$tips.'成功'];
            }else{
                $res = ['status'=>0, 'msg'=>$tips.'失败'];
            }
        }else{
            $res = ['status'=>0, 'msg'=>'无效操作！'];
        }
        return json($res);
    }

    public function deleteAction(){
        return json(['status'=>0, 'msg'=>'学校删除功能未开放！']);

        $param = input();
        if (empty($param['ids'])) {
            return json(['status'=>0, 'msg'=>'id不能为空']);
        }

        if (is_string($param['ids'])) {
            $ids = explode(',', $param['ids']);
        }else{
            $ids = $param['ids'];
        }

        $model = model('Schools');
        foreach ($ids as $key => $value) {
            $detail = $model->fetchSchoolsProDetailByWhere(['SP_PId'=>$value]);
            if ($detail) {
                return json(['status'=>0, 'msg'=>'当前学校下有专业(系)，请先删除专业！']);
            }
            $model->deleteSchools($value);
        }

        return json(['status'=>1, 'msg'=>'删除成功']);
    }

    // 专业/系 管理
    public function proAction(){
        $param = input();
    
        $assign = [];

        $model = model('Schools');
        $assign['detail'] = $detail = $model->fetchSchoolsDetail($param['id']);
        if (empty($detail)) {
            $this->error('学校数据不存在');
        }

        $this->assign($assign);
        return $this->fetch();
    }

    /**
     * 专业列表json
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-12
     * @author      iclubs <iclubs@126.com>
     */
    public function prolistAction(){
        $page = input('page', 1);
        $page_size = input('limit', 20);
        $param = input();

        $model = model('Schools');
        $result = $model->fetchSchoolsProList($page, $page_size, $param);
        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]['SP_PName'] = $model->fetchSchoolsName($value['SP_PId']);
        }

        if ($result['list']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['count'], 'data'=>$result['list']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    public function proactAction(){
        $param = input();

        if (empty($param['id'])) {
            return json(['status'=>0, 'msg'=>'id不能为空']);
        }

        $model = model('Schools');
        $detail = $model->fetchSchoolsProDetail($param['id']);
        if (empty($detail)) {
            return json(['status'=>0, 'msg'=>'修改失败！专业不存在！']);
        }

        if ($param['field'] == 'SP_Name') {
            $where = [
                ['SP_PId', '=', $detail['SP_PId']],
                ['SP_Id', '<>', $param['id']],
                ['SP_Name', '=', $param['value']],
            ];
            $exist = $model->fetchSchoolsProDetailByWhere($where);
            if ($exist) {
                return json(['status'=>0, 'msg'=>'修改失败！专业(系)名称已存在']);
            }

            $result = $model->updateSchoolsPro($param['id'], ['SP_Name'=>$param['value']]);
            if ($result) {
                $res = ['status'=>1, 'msg'=>'修改成功'];
            }else{
                $res = ['status'=>0, 'msg'=>'修改失败！'];
            }
        }elseif ($param['field'] == 'SP_ZJM') {
            $result = $model->updateSchoolsPro($param['id'], ['SP_ZJM'=>$param['value']]);
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

    public function prodeleteAction(){
        $param = input();
        if (empty($param['ids'])) {
            return json(['status'=>0, 'msg'=>'id不能为空']);
        }

        if (is_string($param['ids'])) {
            $ids = explode(',', $param['ids']);
        }else{
            $ids = $param['ids'];
        }

        $model = model('Schools');
        foreach ($ids as $key => $value) {
            $model->deleteSchoolsPro($value);
        }

        return json(['status'=>1, 'msg'=>'删除成功']);
    }

    public function proeditAction(){
        $assign = [];

        $id = input('id',0);
        $model = model('Schools');

        if ($this->request->isPost()) {

            $param = input();

            if (empty($param['pid'])) {
                return json(['status'=>0, 'msg'=>'学校ID不能为空！']);
            }

            $SP_Id = $model->fetchSchoolsProMaxId($param['pid']);
            $SP_Id = empty($SP_Id) ? $param['pid'].'001' : ($SP_Id+1);
            $data = [
                'SP_Id' => $SP_Id,
                'SP_PId' => $param['pid'],
                'SP_Name' => '新专业'.$SP_Id,
                'SP_ZJM' => 'XZY'.$SP_Id,
            ];
            $result = $model->addSchoolsPro($data);
            if ($result) {
                $res = ['status'=>1, 'msg'=>'添加成功'];
            }else{
                $res = ['status'=>0, 'msg'=>'添加失败'];
            }
            return json($res);
        }

    }
    
}
