<?php
/**
 * 资讯管理
 * NewsController.php
 * @version     v1.0
 * @date        2018-08-12
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\controller;

class NewsController extends BaseController{

    public function indexAction(){
        $assign = [];
        $param = input();

        $areaModel = model('Area');
        $assign['arealist'] = $areaModel->fetchAreaListAll(['AI_PId'=>0]);

        //获取城市数据
        if ($param['NI_ProvCode']) {
            $assign['citylist'] = $areaModel->fetchAreaListAll(['AI_PId'=>$param['NI_ProvCode']]);
        }

        $this->assign($assign);
        return $this->fetch();
    }

    public function editAction(){
        $assign = [];

        $id = input('id',0);
        $newsModel = model('News');

        if ($this->request->isPost()) {

            $param = input();

            if (empty($param['NI_Module'])) {
                return json(['status'=>0, 'msg'=>'请选择资讯模块！']);
            }elseif (empty($param['NI_Type'])) {
                return json(['status'=>0, 'msg'=>'请选择资讯类型！']);
            }

            $data = [
                'NI_Module' => $param['NI_Module'],
                'NI_Type' => $param['NI_Type'],
                'NI_Title' => $param['NI_Title'],
                'NI_SubTitle' => $param['NI_SubTitle'],
                'NI_Tags' => $param['NI_Tags'],
                'NI_Content' => $param['NI_Content'],
                'NI_Summary' => $param['NI_Summary'],
                'NI_CommentIsOpen' => $param['NI_CommentIsOpen']=='on' ? 1 : -1,
                'NI_IsRecommendIndex' => $param['NI_IsRecommendIndex']=='on' ? 1 : 0,
                'NI_IsBigPicture' => $param['NI_IsBigPicture']=='on' ? 1 : 0,
                'attachIds' => $param['attachIds'],//附件ids
                // 'NI_Picture' => $param['NI_Picture'],
                'NI_VideoUrl' => $param['NI_VideoUrl'],
                'NI_Status' => $param['NI_Status'],
                'NI_CommentCount' => $param['NI_CommentCount'],
                'NI_LikeCount' => $param['NI_LikeCount'],
                'NI_CollectionCount' => $param['NI_CollectionCount'],
                'NI_ReportCount' => $param['NI_ReportCount'],
                'NI_ShareCount' => $param['NI_ShareCount'],
                'NI_ProvCode' => $param['NI_ProvCode'],
                'NI_ProvName' => $param['NI_ProvName'],
                'NI_CityCode' => $param['NI_CityCode'],
                'NI_CityName' => $param['NI_CityName'],
                'NI_SchoolIds' => $param['NI_SchoolIds'],
                'NI_SchoolNames' => $param['NI_SchoolNames'],
            ];

            if ($id) {
                $result = $newsModel->updateNews($id, $data);
                $tips = '编辑';
            }else{
                $result = $newsModel->addNews($data);
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

            $detail = $newsModel->fetchNewsDetail($id);

            if ($detail['NI_ProvCode']) {
                $schoolsModel = model('Schools');
                $assign['schoolslist'] = $schoolsModel->fetchSchoolsListAll(['S_ProvId'=>$detail['NI_ProvCode'], 'S_Id'=>$detail['NI_SchoolIds']]);
            }

            $attach_list = $aids = [];
            $detail['attachPath'] = '';

            if ($detail['NI_Id']) {
                $attachmentsModel = model('Attachments');
                $attach_list = $attachmentsModel->fetchAttachmentsListAll(['A_DataId'=>$detail['NI_Id'], 'A_Module'=>$detail['NI_Module']]);

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
     * 资讯列表json
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

        $newsModel = model('News');
        $result = $newsModel->fetchNewsList($page, $page_size, $param);

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

        $newsModel = model('News');

        if ($param['type'] == 'changeRecommend') {
            $tips = $param['recommend']==1 ? '设置推荐' : '取消推荐';
            $result = $newsModel->updateNews($param['id'], ['NI_IsRecommendIndex'=>$param['recommend']]);
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

        $newsModel = model('News');
        foreach ($ids as $key => $value) {
            $newsModel->deleteNews($value);
        }

        return json(['status'=>1, 'msg'=>'删除成功']);
    }

    /***************** 评论 ******************/
    public function commentsAction(){
        $param = input();
        $assign = [];

        $commentModel = model('Comments');

        $this->assign($assign);
        return $this->fetch();
    }

    /**
     * 评论列表json
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-12
     * @author      iclubs <iclubs@126.com>
     */
    public function commentslistAction(){
        $page = input('page', 1);
        $page_size = input('limit', 20);
        $param = input();

        $module = [];
        //指定查询资讯类型
        foreach (module_type(100) as $key => $value) {
            $module[] = $value['id'];
        }
        $param['C_Module'] = $module;

        $commentsModel = model('Comments');
        $result = $commentsModel->fetchCommentsList($page, $page_size, $param);

        if ($result['list']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['count'], 'data'=>$result['list']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    public function commentsActAction(){
        $param = input();
        if (empty($param['id'])) {
            return json(['status'=>0, 'msg'=>'id不能为空']);
        }

        $commentsModel = model('Comments');

        if ($param['type'] == 'changeTop') {
            $tips = $param['val']==1 ? '设置置顶' : '取消置顶';
            $result = $commentsModel->updateComments($param['id'], ['C_IsTop'=>$param['val']]);
            if ($result) {
                $res = ['status'=>1, 'msg'=>$tips.'成功'];
            }else{
                $res = ['status'=>0, 'msg'=>$tips.'失败'];
            }
        }elseif ($param['type'] == 'changeHot') {
            $tips = $param['val']==1 ? '设置热评' : '取消热评';
            $result = $commentsModel->updateComments($param['id'], ['C_IsHot'=>$param['val']]);
            if ($result) {
                $res = ['status'=>1, 'msg'=>$tips.'成功'];
            }else{
                $res = ['status'=>0, 'msg'=>$tips.'失败'];
            }
        }elseif ($param['type'] == 'changeStatus') {
            $tips = $param['val']==1 ? '通过审核' : '取消审核';
            $result = $commentsModel->updateComments($param['id'], ['C_Status'=>$param['val']]);
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

    public function commentsDeleteAction(){
        $param = input();
        if (empty($param['ids'])) {
            return json(['status'=>0, 'msg'=>'id不能为空']);
        }

        if (is_string($param['ids'])) {
            $ids = explode(',', $param['ids']);
        }else{
            $ids = $param['ids'];
        }

        $commentsModel = model('Comments');
        foreach ($ids as $key => $value) {
            $commentsModel->deleteComments($value);
        }

        return json(['status'=>1, 'msg'=>'删除成功']);
    }
    
}
