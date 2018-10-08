<?php
/**
 * 看板数据管理类
 * BoardModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\model;

use think\Model;
use app\common\service\Cache;

class BoardModel extends Model{

    protected $table = 'info_newsboard';
    protected $pk = 'NB_Id';
    protected $module_type = 105;//模块id

    /*====================select=====================*/
    public function fetchBoardList($page,$page_size,$param=[]){
        $where = [];

        if (isset($param['NB_Status'])) {
            $where[] = ['NB_Status','=',$param['NB_Status']];
        }else{
            $where[] = ['NB_Status','>',-1];
        }
        
        if (isset($param['NB_Id']) && $param['NB_Id']) {
            $where[] =['NB_Id','=',$param['NB_Id']];
        }
        if (isset($param['NB_Type']) && $param['NB_Type']) {
            $where[] =['NB_Type','=',$param['NB_Type']];
        }
        if (isset($param['NB_ProvCode']) && $param['NB_ProvCode']) {
            $where[] =['NB_ProvCode','=',$param['NB_ProvCode']];
        }
        if (isset($param['NB_CityCode']) && $param['NB_CityCode']) {
            $where[] =['NB_CityCode','=',$param['NB_CityCode']];
        }
        if (isset($param['NB_Title']) && $param['NB_Title']) {
            $where[] =['NB_Title','like','%'.$param['NB_Title'].'%'];
        }elseif (isset($param['title']) && $param['title']) {
            $where[] =['NB_Title','like','%'.$param['title'].'%'];
        }

        $whereString = '';
        if (isset($param['NB_Tags']) && $param['NB_Tags']) {
            $whereString .= "find_in_set({$param['NB_Tags']},NB_Tags)";
        }

        // 时间查询
        if ($param['startTime'] && $param['endTime']) {
            $where[] = ['NB_AddTime','between',[$param['startTime'],$param['endTime']]];
        }elseif ($param['startTime']) {
            $where[] = ['NB_AddTime','>=',$param['startTime']];
        }elseif ($param['endTime']) {
            $where[] = ['NB_AddTime','<=',$param['endTime']];
        }

        $order = 'NB_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        $count = $this->field('NB_Content',true)->where($where)->where($whereString)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->field('NB_Content',true)->where($where)->where($whereString)->order($order)->page($page,$page_size)->select();
        $res = ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];

        return $res;
    }

    //数据缓存使用事例
    public function fetchBoardDetail($id){
        return $this->where(['NB_Id'=>$id])->find();
    }

    /*=====================insert update delete====================*/
    public function addBoard($data){
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

        $newData['NB_AddTime'] = time();

        if(!isset($newData['NB_UpdateTime']) || empty($newData['NB_UpdateTime'])) {
            $newData['NB_UpdateTime'] = $newData['NB_AddTime'];
        }

        if(!isset($newData['NB_AddIP']) || empty($newData['NB_AddIP'])) {
            $newData['NB_AddIP'] = realip();
        }

        if(!isset($newData['NB_UpdateIP']) || empty($newData['NB_UpdateIP'])) {
            $newData['NB_UpdateIP'] = realip();
        }

        // 事务处理
        $this->startTrans();
        try {
            $id = $this->insertGetId($newData);
            if(!$id) {
                $this->rollback();
                return false;
            }

            if ($data['attachIds']) {
                $attachmentsModel = model('Attachments');

                //更新附件对应数据关系
                foreach (explode(',', $data['attachIds']) as $key => $value) {
                    $attachData = [
                        'A_Module' => $this->module_type,
                        'A_IsBackground' => 1, //后台上传的图片
                        'A_DataId' => $id,
                        'A_DataTitle' => $newData['NB_Title'],
                    ];
                    $attachmentsModel->updateAttachments($value,$attachData);
                }
            }

            // 提交事务
            $this->commit();
            return $id;
        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();
            return false;
        }
    }

    public function updateBoard($id,$data){
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

        if(!isset($newData['NB_UpdateIP']) || empty($newData['NB_UpdateIP'])) {
            $newData['NB_UpdateIP'] = realip();
        }

        $newData['NB_UpdateTime'] = time();

        // 事务处理
        $this->startTrans();
        try {
            $res = $this->where(['NB_Id'=>$id])->update($newData);
            if(!$res) {
                $this->rollback();
                return false;
            }

            if ($data['attachIds']) {
                $attachmentsModel = model('Attachments');

                //先删除当前资讯的附件
                $attachmentsModel->deleteAttachments([
                    ['A_Module','=',$this->module_type],
                    ['A_DataId','=',$id],
                    ['A_Id','not in',$data['attachIds']]
                ]);

                //更新附件对应数据关系
                foreach (explode(',', $data['attachIds']) as $key => $value) {
                    $attachData = [
                        'A_Module' => $this->module_type,
                        'A_IsBackground' => 1, //后台上传的图片
                        'A_DataId' => $id,
                        'A_DataTitle' => $newData['NB_Title'],
                    ];
                    $attachmentsModel->updateAttachments($value,$attachData);
                }
            }

            // 提交事务
            $this->commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();
            return false;
        }
    }

    public function deleteBoard($id, $real = false){
        if ($real === true) {
            return $this->where(['NB_Id'=>$id])->delete();
        }else{
            return $this->updateBoard($id, ['NB_Status'=>-1]);
        }
    }

}