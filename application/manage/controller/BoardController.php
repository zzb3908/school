<?php
/**
 * 看板管理
 * BoardController.php
 * @version     v1.0
 * @date        2018-08-12
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\controller;

class BoardController extends BaseController{

    public function indexAction(){
        $assign = [];
        $param = input();

        $areaModel = model('Area');
        $assign['arealist'] = $areaModel->fetchAreaListAll(['AI_PId'=>0]);

        //获取城市数据
        if ($param['NB_ProvCode']) {
            $assign['citylist'] = $areaModel->fetchAreaListAll(['AI_PId'=>$param['NB_ProvCode']]);
        }

        $this->assign($assign);
        return $this->fetch();
    }

    public function editAction(){
        $assign = [];

        $id = input('id',0);
        $newsModel = model('Board');

        if ($this->request->isPost()) {

            $param = input();

            if (empty($param['NB_Module'])) {
                return json(['status'=>0, 'msg'=>'请选择看板分类！']);
            }elseif (empty($param['NB_Type'])) {
                return json(['status'=>0, 'msg'=>'请选择看板类型！']);
            }

            $data = [
                'NB_Module' => $param['NB_Module'],
                'NB_Type' => $param['NB_Type'],
                'NB_Title' => $param['NB_Title'],
                'NB_SubTitle' => $param['NB_SubTitle'],
                'NB_Tags' => $param['NB_Tags'],
                'NB_Content' => $param['NB_Content'],
                'NB_CommentIsOpen' => $param['NB_CommentIsOpen']=='on' ? 1 : -1,
                'NB_IsRecommendIndex' => $param['NB_IsRecommendIndex']=='on' ? 1 : 0,
                'attachIds' => $param['attachIds'],//附件ids
                'NB_Status' => $param['NB_Status'],
                'NB_ProvCode' => $param['NB_ProvCode'],
                'NB_ProvName' => $param['NB_ProvName'],
                'NB_CityCode' => $param['NB_CityCode'],
                'NB_CityName' => $param['NB_CityName'],
                'NB_SchoolIds' => $param['NB_SchoolIds'],
                'NB_SchoolNames' => $param['NB_SchoolNames'],
            ];

            if ($id) {
                $result = $newsModel->updateBoard($id, $data);
                $tips = '编辑';
            }else{
                $result = $newsModel->addBoard($data);
                $tips = '添加';
            }

            if ($result) {
                $res = ['status'=>1, 'msg'=>$tips.'成功'];
            }else{
                $res = ['status'=>0, 'msg'=>$tips.'失败'];
            }
            return json($res);
        }else{
            $areaModel = model('Area');
            //获取省份数据
            $assign['arealist'] = $areaModel->fetchAreaListAll(['AI_PId'=>0]);

            $tagsModel = model('Tags');
            $assign['taglist'] = $tagsModel->fetchTagsListAll(['T_Module'=>101]);

            $detail = $newsModel->fetchBoardDetail($id);

            if ($detail['NB_ProvCode']) {
                $schoolsModel = model('Schools');
                $assign['schoolslist'] = $schoolsModel->fetchSchoolsListAll(['S_ProvId'=>$detail['NB_ProvCode'], 'S_Id'=>$detail['NB_SchoolIds']]);
            }

            $attach_list = $aids = [];
            $detail['attachPath'] = '';

            if ($detail['NB_Id']) {
                $attachmentsModel = model('Attachments');
                $attach_list = $attachmentsModel->fetchAttachmentsListAll(['A_DataId'=>$detail['NB_Id'], 'A_Module'=>105]);

                foreach ($attach_list as $key => $value) {
                    $aids[] = $value['A_Id'];
                    $detail['attachPath'] = $value['A_AttachPath'];
                }
            }

            $detail['attachIds'] = implode(',', $aids);
            $assign['attach_list'] = $attach_list;
            $assign['detail'] = $detail;

            $this->assign($assign);
            return $this->fetch();
        }
    }

    /**
     * 看板列表json
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

        $newsModel = model('Board');
        $result = $newsModel->fetchBoardList($page, $page_size, $param);

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

        $newsModel = model('Board');

        if ($param['type'] == 'changeRecommend') {
            $tips = $param['recommend']==1 ? '设置推荐' : '取消推荐';
            $result = $newsModel->updateBoard($param['id'], ['NB_IsRecommendIndex'=>$param['recommend']]);
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
        $param = input();
        if (empty($param['ids'])) {
            return json(['status'=>0, 'msg'=>'id不能为空']);
        }

        if (is_string($param['ids'])) {
            $ids = explode(',', $param['ids']);
        }else{
            $ids = $param['ids'];
        }

        $newsModel = model('Board');
        foreach ($ids as $key => $value) {
            $newsModel->deleteBoard($value);
        }

        return json(['status'=>1, 'msg'=>'删除成功']);
    }
    
}
