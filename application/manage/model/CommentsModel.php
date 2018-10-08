<?php
/**
 * 评论数据管理类
 * CommentsModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\model;

use think\Model;
use app\common\service\Cache;

class CommentsModel extends Model{

    protected $table = 'own_comments';
    protected $pk = 'C_Id';

    /*====================select=====================*/
    public function fetchCommentsList($page,$page_size,$param=[]){
        $where = [];
        
        if (isset($param['C_Module']) && $param['C_Module']) {
            $where[] = ['C_Module','in',$param['C_Module']];
        }
        if (isset($param['C_DataId']) && $param['C_DataId']) {
            $where[] = ['C_DataId','=',$param['C_DataId']];
        }
        if (isset($param['keyword']) && $param['keyword']) {
            $where[] = ['C_DataTitle|C_Content','like','%'.$param['keyword'].'%'];
        }
        if (isset($param['C_DataUId']) && $param['C_DataUId']) {
            $where[] = ['C_DataUId','=',$param['C_DataUId']];
        }
        if (isset($param['C_DataStatus']) && $param['C_DataStatus']) {
            $where[] = ['C_DataStatus','=',$param['C_DataStatus']];
        }
        if (isset($param['C_IsTop'])) {
            $where[] = ['C_IsTop','=',$param['C_IsTop']];
        }
        if (isset($param['C_IsHot'])) {
            $where[] = ['C_IsHot','=',$param['C_IsHot']];
        }
        if (isset($param['C_IsReply'])) {
            $where[] = ['C_IsReply','=',$param['C_IsReply']];
        }
        if (isset($param['C_Status'])) {
            $where[] = ['C_Status','=',$param['C_Status']];
        }

        if (isset($param['C_IsAuthor'])) {
            $where[] = ['C_IsAuthor','=',$param['C_IsAuthor']];
        }
        if (isset($param['C_AddUId'])) {
            $where[] = ['C_AddUId','=',$param['C_AddUId']];
        }
        if (isset($param['C_AddIP'])) {
            $where[] = ['C_AddIP','like','%'.$param['C_AddIP'].'%'];
        }
        if (isset($param['C_IsGuest'])) {
            $where[] = ['C_IsGuest','=',$param['C_IsGuest']];
        }
        if (isset($param['C_GuestUId'])) {
            $where[] = ['C_GuestUId','=',$param['C_GuestUId']];
        }

        $order = 'C_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        $count = $this->where($where)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->where($where)->order($order)->page($page,$page_size)->select();
        foreach ($list as $key => $value) {
            $list[$key]['C_ModuleName'] = module_name($value['C_Module']);
        }
        return ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
    }

    public function fetchCommentsListAll($param=[]){
        $where = [];
        
        if (isset($param['C_Module']) && $param['C_Module']) {
            $where[] = ['C_Module','=',$param['C_Module']];
        }
        if (isset($param['C_DataId']) && $param['C_DataId']) {
            $where[] = ['C_DataId','=',$param['C_DataId']];
        }
        if (isset($param['C_DataUId']) && $param['C_DataUId']) {
            $where[] = ['C_DataUId','=',$param['C_DataUId']];
        }
        if (isset($param['C_DataStatus']) && $param['C_DataStatus']) {
            $where[] = ['C_DataStatus','=',$param['C_DataStatus']];
        }
        if (isset($param['C_IsTop'])) {
            $where[] = ['C_IsTop','=',$param['C_IsTop']];
        }
        if (isset($param['C_IsHot'])) {
            $where[] = ['C_IsHot','=',$param['C_IsHot']];
        }
        if (isset($param['C_IsReply'])) {
            $where[] = ['C_IsReply','=',$param['C_IsReply']];
        }
        if (isset($param['C_Status'])) {
            $where[] = ['C_Status','=',$param['C_Status']];
        }

        if (isset($param['C_IsAuthor'])) {
            $where[] = ['C_IsAuthor','=',$param['C_IsAuthor']];
        }
        if (isset($param['C_AddUId'])) {
            $where[] = ['C_AddUId','=',$param['C_AddUId']];
        }
        if (isset($param['C_AddIP'])) {
            $where[] = ['C_AddIP','like',$param['C_AddIP']];
        }
        if (isset($param['C_IsGuest'])) {
            $where[] = ['C_IsGuest','=',$param['C_IsGuest']];
        }
        if (isset($param['C_GuestUId'])) {
            $where[] = ['C_GuestUId','=',$param['C_GuestUId']];
        }

        $list = $this->where($where)->order('C_Id asc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['C_ModuleName'] = module_name($value['C_Module']);
        }
        return $list;
    }

    public function fetchCommentsCount($param=[]){
        $where = [];
        
        if (isset($param['C_Module']) && $param['C_Module']) {
            $where[] = ['C_Module','=',$param['C_Module']];
        }
        if (isset($param['C_DataId']) && $param['C_DataId']) {
            $where[] = ['C_DataId','=',$param['C_DataId']];
        }
        if (isset($param['C_DataUId']) && $param['C_DataUId']) {
            $where[] = ['C_DataUId','=',$param['C_DataUId']];
        }
        if (isset($param['C_DataStatus']) && $param['C_DataStatus']) {
            $where[] = ['C_DataStatus','=',$param['C_DataStatus']];
        }
        if (isset($param['C_IsTop'])) {
            $where[] = ['C_IsTop','=',$param['C_IsTop']];
        }
        if (isset($param['C_IsHot'])) {
            $where[] = ['C_IsHot','=',$param['C_IsHot']];
        }
        if (isset($param['C_IsReply'])) {
            $where[] = ['C_IsReply','=',$param['C_IsReply']];
        }
        if (isset($param['C_Status'])) {
            $where[] = ['C_Status','=',$param['C_Status']];
        }

        if (isset($param['C_IsAuthor'])) {
            $where[] = ['C_IsAuthor','=',$param['C_IsAuthor']];
        }
        if (isset($param['C_AddUId'])) {
            $where[] = ['C_AddUId','=',$param['C_AddUId']];
        }
        if (isset($param['C_AddIP'])) {
            $where[] = ['C_AddIP','like',$param['C_AddIP']];
        }
        if (isset($param['C_IsGuest'])) {
            $where[] = ['C_IsGuest','=',$param['C_IsGuest']];
        }
        if (isset($param['C_GuestUId'])) {
            $where[] = ['C_GuestUId','=',$param['C_GuestUId']];
        }

        return $this->where($where)->count();
    }

    //数据缓存使用事例
    public function fetchCommentsDetail($id){
        return $this->where(['C_Id'=>$id])->find();
    }

    /*=====================insert update delete====================*/
    public function addComments($data){
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

        return $this->insertGetId($newData); //插入数据后返回自增ID
    }

    public function updateComments($id,$data){
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

        return $this->where(['C_Id'=>$id])->update($newData);
    }

    public function deleteComments($id, $real = false){
        return $this->where(['C_Id'=>$id])->delete();
    }

}