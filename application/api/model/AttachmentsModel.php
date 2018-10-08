<?php
/**
 * 附件管理类
 * AttachmentsModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\api\model;

use think\Model;
use app\common\service\Cache;

class AttachmentsModel extends Model{

    protected $table = 'own_attachments';
    protected $pk = 'A_Id';

    /*====================select=====================*/
    public function fetchAttachmentsList($page,$page_size,$param=[]){
        $where = [];
        
        if (isset($param['A_Module']) && $param['A_Module']) {
            $where[] = ['A_Module','=',$param['A_Module']];
        }
        if (isset($param['A_IsBackground']) && $param['A_IsBackground']) {
            $where[] = ['A_IsBackground','=',$param['A_IsBackground']];
        }
        if (isset($param['A_DataId']) && $param['A_DataId']) {
            $where[] = ['A_DataId','=',$param['A_DataId']];
        }
        if (isset($param['A_DataTitle']) && $param['A_DataTitle']) {
            $where[] = ['A_DataTitle','like','%'.$param['A_DataTitle'].'%'];
        }
        if (isset($param['A_DataType']) && $param['A_DataType']) {
            $where[] = ['A_DataType','=',$param['A_DataType']];
        }
        if (isset($param['A_IsMain']) && $param['A_IsMain']) {
            $where[] = ['A_IsMain','=',$param['A_IsMain']];
        }
        if (isset($param['A_AttachType']) && $param['A_AttachType']) {
            $where[] = ['A_AttachType','=',$param['A_AttachType']];
        }
        if (isset($param['A_AddUId']) && $param['A_AddUId']) {
            $where[] = ['A_AddUId','=',$param['A_AddUId']];
        }

        $count = $this->where($where)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->where($where)->order('A_Order desc,A_Id desc')->page($page,$page_size)->select();
        return ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
    }

    public function fetchAttachmentsListAll($param=[]){
        $where = [];
        
        if (isset($param['A_Module']) && $param['A_Module']) {
            $where[] = ['A_Module','=',$param['A_Module']];
        }
        if (isset($param['A_IsBackground']) && $param['A_IsBackground']) {
            $where[] = ['A_IsBackground','=',$param['A_IsBackground']];
        }
        if (isset($param['A_DataId']) && $param['A_DataId']) {
            $where[] = ['A_DataId','=',$param['A_DataId']];
        }
        if (isset($param['A_DataTitle']) && $param['A_DataTitle']) {
            $where[] = ['A_DataTitle','like','%'.$param['A_DataTitle'].'%'];
        }
        if (isset($param['A_DataType']) && $param['A_DataType']) {
            $where[] = ['A_DataType','=',$param['A_DataType']];
        }
        if (isset($param['A_IsMain']) && $param['A_IsMain']) {
            $where[] = ['A_IsMain','=',$param['A_IsMain']];
        }
        if (isset($param['A_AttachType']) && $param['A_AttachType']) {
            $where[] = ['A_AttachType','=',$param['A_AttachType']];
        }
        if (isset($param['A_AddUId']) && $param['A_AddUId']) {
            $where[] = ['A_AddUId','=',$param['A_AddUId']];
        }

        return $this->where($where)->order('A_Order desc,A_Id desc')->select();
    }

    //数据缓存使用事例
    public function fetchAttachmentsDetail($id){
        return $this->where(['A_Id'=>$id])->find();
    }

    public function fetchAttachmentsDetailByWhere($where){
        return $this->where($where)->find();
    }

    public function fetchAttachmentsMaxId(){
        return $this->max('A_Id');
    }

    /*=====================insert update delete====================*/
    public function addAttachments($data){
        if (!is_array($data) || empty($data)) return false;

        $newData = [];

        //排除无效字段
        $fields = $this->getTableFields();
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $newData[$key] = $value;
            }
        }

        if(empty($newData)) return false;

        $newData['A_AddTime'] = time();

        if(!isset($newData['A_AddIP']) || empty($newData['A_AddIP'])) {
            $newData['A_AddIP'] = realip();
        }

        return $this->insertGetId($newData); //插入数据后返回自增ID
    }

    public function updateAttachments($id,$data){
        if (empty($id) || !is_array($data) || empty($data)) return false;

        $newData = [];

        //排除无效字段
        $fields = $this->getTableFields();
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $newData[$key] = $value;
            }
        }

        if(empty($newData)) return false;

        return $this->where(array('A_Id'=>$id))->update($newData);
    }

    public function deleteAttachments($where=[], $real = false){
        if(empty($where)) return false;
        return $this->where($where)->delete();
    }

}