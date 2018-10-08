<?php
/**
 * 秀吧管理接口
 * ShowmeController.php
 * @version     v1.0
 * @date        2018-09-25
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\api\controller;

class ShowmeController extends BaseController{

    protected $module_type = 201;//秀吧模块id

    /**
     * 秀吧列表
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-09-25
     * @author      iclubs <iclubs@126.com>
     * @link        /showme/list
     */
    public function listAction(){
        $page = isset($this->data['page'])&&$this->data['page'] ? $this->data['page'] : 1;
        $page_size = isset($this->data['page_size'])&&$this->data['page_size'] ? $this->data['page_size'] : 10;
        if (isset($this->data['limit'])&&$this->data['limit']) {
            $page_size = $this->data['limit'];
        }

        $showmeModel = model('Showme');
        $userModel = model('Userinfo');
        $result = $showmeModel->fetchShowmeList($page, $page_size, $this->data);

        if ($result['list']) {
            $attachModel = model('Attachments');
            foreach ($result['list'] as $key => $value) {
                $result['list'][$key]['SI_AddDate'] = format_time($value['SI_AddTime']);

                $attachments = $attachModel->fetchAttachmentsListAll(['A_Module'=>$this->module_type, 'A_DataId'=>$value['SI_Id']]);
                $attachlist = [];
                foreach ($attachments as $k => $v) {
                    if($k>=3) break;
                    $attachlist[$k]['A_Id'] = $v['A_Id'];
                    $attachlist[$k]['A_IsBackground'] = $v['A_IsBackground'];
                    $attachlist[$k]['A_AttachName'] = $v['A_AttachName'];
                    $attachlist[$k]['A_AttachType'] = $v['A_AttachType'];
                    $attachlist[$k]['A_AttachPath'] = config('upload_host').$v['A_AttachPath'];
                    $attachlist[$k]['A_AttachSize'] = $v['A_AttachSize'] ? $v['A_AttachSize'] / 1000 : $v['A_AttachSize'];//转换成kb
                    $attachlist[$k]['A_ImageWidth'] = $v['A_ImageWidth'];
                    $attachlist[$k]['A_ImageHeight'] = $v['A_ImageHeight'];
                }
                $result['list'][$key]['picNum'] = count($attachlist);
                $result['list'][$key]['attachments'] = $attachlist;

                $userinfo = $userModel->fetchUserinfoBase($value['SI_AddUId']);
                if ($userinfo) {
                    $result['list'][$key]['userinfo'] = $userinfo;
                }else{
                    $result['list'][$key]['userinfo'] = [];
                }
                
            
            }
            $res = ['Code'=>200, 'Msg'=>'查询成功', 'Data'=>$result];
        }else{
            $res = ['Code'=>204, 'Msg'=>'没有数据', 'Data'=>[]];
        }
        return json($res);
    }

    /**
     * 秀吧详情
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-14
     * @author      iclubs <iclubs@126.com>
     * @link        /showme/detail
     */
    public function detailAction(){
        if (empty($this->data['SI_Id'])) {
            return json(['Code'=>400, 'Msg'=>'入参SI_Id不能为空']);
        }

        $showmeModel = model('Showme');
        $result = $showmeModel->fetchShowmeDetail($this->data['SI_Id']);
        if ($result) {
            $result['SI_AddDate'] = format_time($result['SI_AddTime']);

            $attachModel = model('Attachments');
            $attachments = $attachModel->fetchAttachmentsListAll(['A_Module'=>$this->module_type, 'A_DataId'=>$result['SI_Id']]);
            $attachlist = [];
            foreach ($attachments as $k => $v) {
                if($k>=3) break;
                $attachlist[$k]['A_Id'] = $v['A_Id'];
                $attachlist[$k]['A_IsBackground'] = $v['A_IsBackground'];
                $attachlist[$k]['A_AttachName'] = $v['A_AttachName'];
                $attachlist[$k]['A_AttachType'] = $v['A_AttachType'];
                $attachlist[$k]['A_AttachPath'] = config('upload_host').$v['A_AttachPath'];
                $attachlist[$k]['A_AttachSize'] = $v['A_AttachSize'] ? $v['A_AttachSize'] / 1000 : $v['A_AttachSize'];//转换成kb
                $attachlist[$k]['A_ImageWidth'] = $v['A_ImageWidth'];
                $attachlist[$k]['A_ImageHeight'] = $v['A_ImageHeight'];
            }
            $result['picNum'] = count($attachlist);
            $result['attachments'] = $attachlist;
            $res = ['Code'=>200, 'Msg'=>'查询成功', 'Data'=>$result];
        }else{
            $res = ['Code'=>204, 'Msg'=>'没有数据'];
        }
        return json($res);
    }

    /**
     * 秀吧添加
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-14
     * @author      iclubs <iclubs@126.com>
     * @link        /showme/add
     */
    public function addAction(){
        if (empty($this->data)) {
            return json(['Code'=>400, 'Msg'=>'入参数据不能为空']);
        }

        $showmeModel = model('Showme');
        $result = $showmeModel->addShowme($this->data);
        if ($result) {
            $res = ['Code'=>200, 'Msg'=>'添加成功', 'Data'=>$result];
        }else{
            $res = ['Code'=>400, 'Msg'=>'添加失败'];
        }
        return json($res);
    }

    /**
     * 秀吧修改
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-14
     * @author      iclubs <iclubs@126.com>
     * @link        /showme/update
     */
    public function updateAction(){
        if (empty($this->data['SI_Id'])) {
            return json(['Code'=>400, 'Msg'=>'入参SI_Id不能为空']);
        }

        $id = $this->data['SI_Id'];
        unset($this->data['SI_Id']);

        if (empty($this->data)) {
            return json(['Code'=>400, 'Msg'=>'入参数据不能为空']);
        }

        $showmeModel = model('Showme');
        $result = $showmeModel->updateShowme($id,$this->data);

        return json(['Code'=>200, 'Msg'=>'修改成功', 'Data'=>$result]);
    }

    /**
     * 秀吧删除
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-14
     * @author      iclubs <iclubs@126.com>
     * @link        /showme/update
     */
    public function deleteAction(){
        if (empty($this->data['SI_Id'])) {
            return json(['Code'=>400, 'Msg'=>'入参SI_Id不能为空']);
        }

        $showmeModel = model('Showme');
        $result = $showmeModel->deleteShowme($this->data['SI_Id']);
        if ($result) {
            $res = ['Code'=>200, 'Msg'=>'删除成功', 'Data'=>$result];
        }else{
            $res = ['Code'=>400, 'Msg'=>'删除失败'];
        }
        return json($res);
    }
}
