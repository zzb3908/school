<?php
/**
 * 秀吧管理
 * ShowmeController.php
 * @version     v1.0
 * @date        2018-08-12
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\controller;

class ShowmeController extends BaseController{

    protected $module_type = 201;//模块id

    public function indexAction(){
        $assign = [];
        $param = input();

        $this->assign($assign);
        return $this->fetch();
    }

    public function editAction(){
        $assign = [];

        $id = input('id',0);
        $showmeModel = model('Showme');

        if ($this->request->isPost()) {

            $param = input();

            if (empty($param['SI_SC_Id'])) {
                return json(['status'=>0, 'msg'=>'请选择分类！']);
            }

            $data = [
                'SI_Type' => $param['SI_Type'],
                'SI_SC_Id' => $param['SI_SC_Id'],
                'SI_SC_Name' => $param['SI_SC_Name'],
                'SI_Tags' => $param['SI_Tags'],
                'SI_Content' => $param['SI_Content'],
                'SI_LocationName' => $param['SI_LocationName'],
                'SI_LocationLat' => $param['SI_LocationLat'],
                'SI_LocationLong' => $param['SI_LocationLong'],
                'SI_Scope' => $param['SI_Scope'],
                'SI_MaterialsFace' => $param['SI_MaterialsFace'],
                // 'SI_CommentCount' => $param['SI_CommentCount'],
                // 'SI_LikeCount' => $param['SI_LikeCount'],
                // 'SI_CollectionCount' => $param['SI_CollectionCount'],
                // 'SI_ReportCount' => $param['SI_ReportCount'],
                // 'SI_ShareCount' => $param['SI_ShareCount'],
                'SI_Status' => $param['SI_Status'],
                'SI_AddUId' => $this->login['UI_Id'],
                'SI_AddUName' => $this->login['UI_Nickname'],
                'SI_AddAvatar' => $this->login['UI_Avatar'],
                'attachIds' => $param['attachIds'],//附件ids
            ];

            if ($id) {
                $result = $showmeModel->updateShowme($id, $data);
                $tips = '编辑';
            }else{
                $result = $showmeModel->addShowme($data);
                $tips = '添加';
            }

            if ($result) {
                $res = ['status'=>1, 'msg'=>$tips.'成功'];
            }else{
                $res = ['status'=>0, 'msg'=>$tips.'失败'];
            }
            return json($res);
        }else{
            $tagsModel = model('Tags');
            $assign['taglist'] = $tagsModel->fetchTagsListAll(['T_Module'=>$this->module_type]);

            $detail = $showmeModel->fetchShowmeDetail($id);

            $attach_list = $aids = [];
            $detail['attachPath'] = '';

            if ($detail['SI_Id']) {
                $attachmentsModel = model('Attachments');
                $attach_list = $attachmentsModel->fetchAttachmentsListAll(['A_DataId'=>$detail['SI_Id'], 'A_Module'=>$this->module_type]);

                foreach ($attach_list as $key => $value) {
                    $aids[] = $value['A_Id'];
                    $detail['attachPath'] = $value['A_AttachPath'];
                }
            }

            $assign['catlist'] = $showmeModel->fetchCategoryListAll(['SC_Status'=>2, 'SC_PId'=>0]);

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

        $showmeModel = model('Showme');
        $result = $showmeModel->fetchShowmeList($page, $page_size, $param);

        if ($result['list']) {
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['count'], 'data'=>$result['list']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
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

        $showmeModel = model('Showme');
        foreach ($ids as $key => $value) {
            $showmeModel->deleteShowme($value);
        }

        return json(['status'=>1, 'msg'=>'删除成功']);
    }



    /***************** 秀吧分类管理 ***********************/
    public function categoryAction(){
        $assign = [];
        $param = input();

        $this->assign($assign);
        return $this->fetch();
    }

    public function categoryeditAction(){
        $assign = [];

        $id = input('id',0);
        $showmeModel = model('Showme');

        if ($this->request->isPost()) {

            $param = input();

            $data = [
                'SC_PId' => (int) $param['SC_PId'],
                'SC_Name' => $param['SC_Name'],
                'SC_Picture' => $param['SC_Picture'] ? $param['SC_Picture'] : '',
                'SC_Status' => $param['SC_Status'],
                'SC_Sort' => (int) $param['SC_Sort'],
                'SC_IsHot' => (int) $param['SC_IsHot'],
                'SC_HotSort' => (int) $param['SC_HotSort'],
                'SC_AddUId' => $this->login['UI_Id'],
            ];

            if ($id) {
                $result = $showmeModel->updateCategory($id, $data);
                $tips = '编辑';
            }else{
                $result = $showmeModel->addCategory($data);
                $tips = '添加';
            }

            if ($result) {
                $res = ['status'=>1, 'msg'=>$tips.'成功'];
            }else{
                $res = ['status'=>0, 'msg'=>$tips.'失败'];
            }
            return json($res);
        }else{
            $detail = $showmeModel->fetchCategoryDetail($id);
            $detail['SC_PName'] = $showmeModel->fetchCategoryName($detail['SC_PId']);

            $assign['catlist'] = $showmeModel->fetchCategoryListAll(['SC_PId'=>0]);

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
    public function categorylistAction(){
        $page = input('page', 1);
        $page_size = input('limit', 20);
        $param = input();

        $showmeModel = model('Showme');
        $result = $showmeModel->fetchCategoryList($page, $page_size, $param);
        if ($result['list']) {
            foreach ($result['list'] as $key => $value) {
                $result['list'][$key]['SC_PName'] = $showmeModel->fetchCategoryName($value['SC_PId']);
            }
            $res = ['code'=>0, 'msg'=>'查询成功', 'count'=>$result['count'], 'data'=>$result['list']];
        }else{
            $res = ['code'=>204, 'msg'=>'没有数据', 'data'=>[]];
        }
        return json($res);
    }

    public function categorydeleteAction(){
        $param = input();
        if (empty($param['ids'])) {
            return json(['status'=>0, 'msg'=>'id不能为空']);
        }

        if (is_string($param['ids'])) {
            $ids = explode(',', $param['ids']);
        }else{
            $ids = $param['ids'];
        }

        $showmeModel = model('Showme');
        foreach ($ids as $key => $value) {
            $showmeModel->deleteCategory($value);
        }

        return json(['status'=>1, 'msg'=>'删除成功']);
    }
    
}
