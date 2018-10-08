<?php
/**
 * 资讯管理接口
 * NewsController.php
 * @version     v1.0
 * @date        2018-08-14
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\api\controller;

class NewsController extends BaseController{

    /**
     * 资讯列表
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-14
     * @author      iclubs <iclubs@126.com>
     * @link        /news/list
     */
    public function listAction(){
        $page = isset($this->data['page'])&&$this->data['page'] ? $this->data['page'] : 1;
        $page_size = isset($this->data['page_size'])&&$this->data['page_size'] ? $this->data['page_size'] : 10;
        if (isset($this->data['limit'])&&$this->data['limit']) {
            $page_size = $this->data['limit'];
        }

        $newsModel = model('News');
        $result = $newsModel->fetchNewsList($page, $page_size, $this->data);

        if ($result['list']) {
            $attachModel = model('Attachments');
            foreach ($result['list'] as $key => $value) {
                $result['list'][$key]['NI_AddDate'] = format_time($value['NI_AddTime']);
                $attachments = $attachModel->fetchAttachmentsListAll(['A_Module'=>$value['NI_Module'], 'A_DataId'=>$value['NI_Id']]);
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
                $result['list'][$key]['picNum'] = $value['NI_Type']==1 ? count($attachlist) : 0;
                $result['list'][$key]['attachments'] = $attachlist;
            }
            $res = ['Code'=>200, 'Msg'=>'查询成功', 'Data'=>$result];
        }else{
            $res = ['Code'=>204, 'Msg'=>'没有数据', 'Data'=>[]];
        }
        return json($res);
    }

    /**
     * 资讯详情
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-14
     * @author      iclubs <iclubs@126.com>
     * @link        /news/detail
     */
    public function detailAction(){
        if (empty($this->data['NI_Id'])) {
            return json(['Code'=>400, 'Msg'=>'入参NI_Id不能为空']);
        }

        $newsModel = model('News');
        $result = $newsModel->fetchNewsDetail($this->data['NI_Id']);
        if ($result) {
            $result['NI_AddDate'] = format_time($result['NI_AddTime']);

            $attachModel = model('Attachments');
            $attachments = $attachModel->fetchAttachmentsListAll(['A_Module'=>$result['NI_Module'], 'A_DataId'=>$result['NI_Id']]);
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
            $result['picNum'] = $result['NI_Type']==1 ? count($attachlist) : 0;
            $result['attachments'] = $attachlist;
            $res = ['Code'=>200, 'Msg'=>'查询成功', 'Data'=>$result];
        }else{
            $res = ['Code'=>204, 'Msg'=>'没有数据'];
        }
        return json($res);
    }

    /**
     * 资讯添加
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-14
     * @author      iclubs <iclubs@126.com>
     * @link        /news/add
     */
    public function addAction(){
        if (empty($this->data)) {
            return json(['Code'=>400, 'Msg'=>'入参数据不能为空']);
        }

        $newsModel = model('News');
        $result = $newsModel->addNews($this->data);
        if ($result) {
            $res = ['Code'=>200, 'Msg'=>'添加成功', 'Data'=>$result];
        }else{
            $res = ['Code'=>400, 'Msg'=>'添加失败'];
        }
        return json($res);
    }

    /**
     * 资讯修改
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-14
     * @author      iclubs <iclubs@126.com>
     * @link        /news/update
     */
    public function updateAction(){
        if (empty($this->data['NI_Id'])) {
            return json(['Code'=>400, 'Msg'=>'入参NI_Id不能为空']);
        }

        $id = $this->data['NI_Id'];
        unset($this->data['NI_Id']);

        if (empty($this->data)) {
            return json(['Code'=>400, 'Msg'=>'入参数据不能为空']);
        }

        $newsModel = model('News');
        $result = $newsModel->updateNews($id,$this->data);

        return json(['Code'=>200, 'Msg'=>'修改成功', 'Data'=>$result]);
    }

    /**
     * 资讯删除
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-14
     * @author      iclubs <iclubs@126.com>
     * @link        /news/update
     */
    public function deleteAction(){
        if (empty($this->data['NI_Id'])) {
            return json(['Code'=>400, 'Msg'=>'入参NI_Id不能为空']);
        }

        $newsModel = model('News');
        $result = $newsModel->deleteNews($this->data['NI_Id']);
        if ($result) {
            $res = ['Code'=>200, 'Msg'=>'删除成功', 'Data'=>$result];
        }else{
            $res = ['Code'=>400, 'Msg'=>'删除失败'];
        }
        return json($res);
    }
}
