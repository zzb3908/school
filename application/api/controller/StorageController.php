<?php
/**
 * 文件存储管理
 * StorageController.php
 * @version     版本号
 * @date         日期
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\api\controller;

class StorageController extends BaseController{

    /**
     * 文件上传管理，公共接口
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-12
     * @author      iclubs <iclubs@126.com>
     */
    public function uploadAction(){
    	$param = $this->request->param();
    	if (!in_array($param['type'], ['file','images','video'])) {
    		return json(['Code'=>400, 'Msg'=>'上传类型无效']);
    	}elseif (empty($param['module'])) {
            return json(['Code'=>400, 'Msg'=>'模块类型不能为空']);
        }

    	$savedir = ($param['module'] ? $param['type'] .'/'. $param['module'] : $param['type']) . '/' . date('Ymd');

        $result = upload($_FILES['file'], $savedir, $param['type'], '', false, 'qiniu');
        if ($result['status']==1) {
            $attachModel = model('Attachments');
            $attachData = [
                'A_Module' => $param['module_type'] ? $param['module_type'] : 0,
                'A_IsBackground' => $param['backend']==1 ? 1 : 2,//1是后台，非1是用户
                'A_AddUId' => $param['add_uid'] ? $param['add_uid'] : 0,
                'A_AddUName' => $param['add_uname'] ? $param['add_uname'] : '',
                'A_AttachName' => $_FILES['file']['name'],
                'A_AttachType' => $result['data']['filemime'],
                'A_AttachPath' => $result['data']['qiniu']['original']['key'],
                'A_AttachSize' => $result['data']['filesize'],
                'A_ImageWidth' => $result['data']['width'] ? $result['data']['width'] : 0,
                'A_ImageHeight' => $result['data']['height'] ? $result['data']['height'] : 0,
            ];
            $attachId = $attachModel->addAttachments($attachData);

        	$data = [
        		'name' => $_FILES['file']['name'],
        		'url' => config('upload_host').$result['data']['qiniu']['original']['key'],
                'id' => $attachId,
                'screenshot' => $param['type']=='video' ? config('upload_host').$result['data']['qiniu']['original']['screenshot'] : '',
        	];
        	$res = ['Code'=>200, 'Msg'=>'上传成功', 'Data'=>$data];
        }else{
        	$res = ['Code'=>400, 'Msg'=>$result['msg']];
        }
        return json($res);
    }

    /**
     * 编辑器上传
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-12
     * @author      iclubs <iclubs@126.com>
     */
    public function editorUploadAction(){
    	$param = $this->request->param();
    	if (!in_array($param['type'], ['file','images','video'])) {
    		return json(['Code'=>400, 'Msg'=>'上传类型无效']);
    	}elseif (empty($param['module'])) {
            return json(['Code'=>400, 'Msg'=>'模块类型不能为空']);
        }

    	$savedir = ($param['module'] ? $param['type'] .'/'. $param['module'] : $param['type']) . '/' . date('Ymd');

    	/* LayEdit 指定的返回json格式
			{
			  "code": 0 //0表示成功，其它失败
			  ,"msg": "" //提示信息 //一般上传失败后返回
			  ,"data": {
			    "src": "图片路径"
			    ,"title": "图片名称" //可选
			  }
			}
    	*/

        $result = upload($_FILES['file'], $savedir, $param['type'], '', false, 'qiniu');
        if ($result['status']==1) {
            $data = [
                'name' => $_FILES['file']['name'],
                'url' => config('upload_host').$result['data']['qiniu']['original']['key'],
            ];
        	$res = ['code'=>0, 'msg'=>'上传成功', 'data'=>$data];
        }else{
        	$res = ['code'=>1, 'msg'=>$result['msg']];
        }
        return json($res);
    }
}
