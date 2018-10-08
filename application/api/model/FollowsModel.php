<?php
/**
 * 关注数据管理类
 * FollowsModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\api\model;

use think\Model;
use app\common\service\Cache;

class FollowsModel extends Model{

    protected $table = 'own_follows';
    protected $pk = 'F_Id';

    /*====================select=====================*/
    public function fetchFollowsList($page,$page_size,$param=[]){
        $where = [];
        
        if (isset($param['F_IsMutualFollow']) && $param['F_IsMutualFollow']!=='') {
            $where[] =['F_IsMutualFollow','=',$param['F_IsMutualFollow']];
        }
        if (isset($param['F_Module']) && $param['F_Module']) {
            $where[] = ['F_Module','=',$param['F_Module']];
        }
        if (isset($param['F_FollowUId']) && $param['F_FollowUId']) {
            $where[] =['F_FollowUId','=',$param['F_FollowUId']];
        }
        if (isset($param['F_FollowUName']) && $param['F_FollowUName']) {
            $where[] =['F_FollowUName','like','%'.$param['F_FollowUName'].'%'];
        }
        if (isset($param['F_FansUId']) && $param['F_FansUId']) {
            $where[] =['F_FansUId','=',$param['F_FansUId']];
        }
        if (isset($param['F_FansUNamme']) && $param['F_FansUNamme']) {
            $where[] =['F_FansUNamme','like','%'.$param['F_FansUNamme'].'%'];
        }
        
        // 时间查询
        if ($param['startTime'] && $param['endTime']) {
            $where[] = ['F_AddTime','between',[$param['startTime'],$param['endTime']]];
        }elseif ($param['startTime']) {
            $where[] = ['F_AddTime','>=',$param['startTime']];
        }elseif ($param['endTime']) {
            $where[] = ['F_AddTime','<=',$param['endTime']];
        }

        $order = 'F_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_Follows_fetchFollowsList_'.$page.'_'.$page_size.'_'.http_build_query($where).'_'.$order);
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
    public function fetchFollowsDetail($id){
        $where = ['F_Id'=>$id];
        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_Follows_fetchFollowsDetail_'.$id);
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
    public function addFollows($data){
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

        $newData['F_AddTime'] = time();

        // 事务处理
        $this->startTrans();
        try {
            $id = $this->insertGetId($newData);
            if(!$id) {
                $this->rollback();
                return false;
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

    public function updateFollows($id,$data){
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

        // 事务处理
        $this->startTrans();
        try {
            $res = $this->where(['F_Id'=>$id])->update($newData);
            if(!$res) {
                $this->rollback();
                return false;
            }

            $this->delCache(['F_Id'=>$id]);

            // 提交事务
            $this->commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();
            return false;
        }
    }

    public function deleteFollows($id, $real = false){
        $this->delCache(['F_Id'=>$id]);
        
        return $this->where(['F_Id'=>$id])->delete();
    }

    public function delCache($param=[], $where=[], $order='F_Id desc'){
        $cacheClass = new Cache();

        if ($param['F_Id']) {
            //详情缓存
            $cache_key = $cacheClass->getKey('Api_News_fetchNewsInfo_'.$param['F_Id']);
            $cacheClass->remove($cache_key);
        }elseif ($param['page'] && $where) {
            //列表缓存
            $cache_key = $cacheClass->getKey('Api_News_fetchFollowsList_'.$param['page'].'_'.$param['page_size'].'_'.http_build_query($where).'_'.$order);
            $cacheClass->remove($cache_key);
        }

        return true;
    }

}