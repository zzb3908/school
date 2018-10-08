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

namespace app\api\model;

use think\Model;
use app\common\service\Cache;

class BoardModel extends Model{

    protected $table = 'info_newsboard';
    protected $pk = 'NB_Id';

    /*====================select=====================*/
    public function fetchBoardList($page,$page_size,$param=[]){
        $where = [];

        if (isset($param['NB_Status'])) {
            $where[] = ['NB_Status','=',$param['NB_Status']];
        }else{
            $where[] = ['NB_Status','=',2];//默认已审核
        }
        
        if (isset($param['NB_Id']) && $param['NB_Id']) {
            $where[] =['NB_Id','=',$param['NB_Id']];
        }
        if (isset($param['NB_IsRecommendIndex']) && $param['NB_IsRecommendIndex']) {
            $where[] = ['NB_IsRecommendIndex','=',$param['NB_IsRecommendIndex']];
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
            foreach (explode(',', $param['NB_Tags']) as $key => $value) {
                $whereString .= "find_in_set('".$value."',NB_Tags) or ";
            }
            $whereString = rtrim($whereString,'or ');
        }

        $order = 'NB_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_News_fetchBoardList_'.$page.'_'.$page_size.'_'.http_build_query($where).'_'.$order);
            if(!$cacheClass->get($cache_key)){
                $count = $this->where($where)->where($whereString)->count();
                $pages = ceil($count/$page_size);
                if($page<1) $page = 1;
                $list = $this->where($where)->where($whereString)->order($order)->page($page,$page_size)->select();
                $res = ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
                $cacheClass->set($cache_key,$res,120);
            }else{
                $res = $cacheClass->get($cache_key);
            }
        }else{
            $count = $this->where($where)->where($whereString)->count();
            $pages = ceil($count/$page_size);
            if($page<1) $page = 1;
            $list = $this->where($where)->where($whereString)->order($order)->page($page,$page_size)->select();
            $res = ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
        }
        return $res;
    }

    //数据缓存使用事例
    public function fetchBoardDetail($id){
        $where = ['NB_Id'=>$id];
        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_Board_fetchBoardDetail_'.$id);
            if(!$cacheClass->get($cache_key)){
                $res = $this->where($where)->find();
                $cacheClass->set($cache_key,$res,120);
            }else{
                $res = $cacheClass->get($cache_key);
            }
        }else{
            $res = $this->where($where)->find();
        }
        return $res;
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
                        'A_Module' => 105,
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
                    ['A_Module','=',105],
                    ['A_DataId','=',$id],
                    ['A_Id','not in',$data['attachIds']]
                ]);

                //更新附件对应数据关系
                foreach (explode(',', $data['attachIds']) as $key => $value) {
                    $attachData = [
                        'A_Module' => 105,
                        'A_IsBackground' => 1, //后台上传的图片
                        'A_DataId' => $id,
                        'A_DataTitle' => $newData['NB_Title'],
                    ];
                    $attachmentsModel->updateAttachments($value,$attachData);
                }
            }

            $this->delCache(['NB_Id'=>$id]);

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
        $this->delCache(['NB_Id'=>$id]);
        
        if ($real === true) {
            return $this->where(['NB_Id'=>$id])->delete();
        }else{
            return $this->updateBoard($id, ['NB_Status'=>-1]);
        }
    }

    public function delCache($param=[], $where=[], $order='NB_Id desc'){
        $cacheClass = new Cache();

        if ($param['NB_Id']) {
            //详情缓存
            $cache_key = $cacheClass->getKey('Api_News_fetchNewsInfo_'.$param['NB_Id']);
            $cacheClass->remove($cache_key);
        }elseif ($param['page'] && $where) {
            //列表缓存
            $cache_key = $cacheClass->getKey('Api_News_fetchBoardList_'.$param['page'].'_'.$param['page_size'].'_'.http_build_query($where).'_'.$order);
            $cacheClass->remove($cache_key);
        }

        return true;
    }

}