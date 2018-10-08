<?php
/**
 * 用户数据管理类
 * UserinfoModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\api\model;

use think\Model;
use app\common\service\Cache;

class UserinfoModel extends Model{

    protected $table = 'own_userinfo';
    protected $pk = 'UI_Id';

    /*====================select=====================*/
    public function fetchUserinfoList($page,$page_size,$param=[]){
        $where = [];

        if (isset($param['UI_Status'])) {
            $where[] = ['UI_Status','=',$param['UI_Status']];
        }else{
            $where[] = ['UI_Status','=',2];
        }
        
        if (isset($param['UI_UserType']) && $param['UI_UserType']!=='') {
            $where[] =['UI_UserType','=',$param['UI_UserType']];
        }

        if (isset($param['UI_IsAdmin']) && $param['UI_IsAdmin']!=='') {
            $where[] = ['UI_IsAdmin','=',$param['UI_IsAdmin']];
        }

        if (isset($param['UI_Nickname']) && $param['UI_Nickname']) {
            $where[] =['UI_Nickname','like','%'.$param['UI_Nickname'].'%'];
        }

        if (isset($param['UI_Realname']) && $param['UI_Realname']) {
            $where[] =['UI_Realname','like','%'.$param['UI_Realname'].'%'];
        }

        if (isset($param['UI_MobilePhone']) && $param['UI_MobilePhone']) {
            $where[] =['UI_MobilePhone','like','%'.$param['UI_MobilePhone'].'%'];
        }

        if (isset($param['UI_Gender']) && $param['UI_Gender']!=='') {
            $where[] =['UI_Gender','=',$param['UI_Gender']];
        }

        if (isset($param['UI_ProvId']) && $param['UI_ProvId']) {
            $where[] =['UI_ProvId','=',$param['UI_ProvId']];
        }

        if (isset($param['UI_CityId']) && $param['UI_CityId']) {
            $where[] =['UI_CityId','=',$param['UI_CityId']];
        }

        if (isset($param['UI_SchoolTypeId']) && $param['UI_SchoolTypeId']) {
            $where[] =['UI_SchoolTypeId','=',$param['UI_SchoolTypeId']];
        }

        if (isset($param['UI_SchoolId']) && $param['UI_SchoolId']) {
            $where[] =['UI_SchoolId','=',$param['UI_SchoolId']];
        }

        if (isset($param['UI_StudentId']) && $param['UI_StudentId']) {
            $where[] =['UI_StudentId','=',$param['UI_StudentId']];
        }

        if (isset($param['UI_ProfessionId']) && $param['UI_ProfessionId']) {
            $where[] =['UI_ProfessionId','=',$param['UI_ProfessionId']];
        }

        if (isset($param['UI_Degree']) && $param['UI_Degree']) {
            $where[] =['UI_Degree','=',$param['UI_Degree']];
        }

        if (isset($param['UI_Tags']) && $param['UI_Tags']) {
            $whereTags = '';
            foreach (explode(',', $param['UI_Tags']) as $key => $value) {
                $whereTags .= "find_in_set('".$value."',UI_Tags) or ";
            }
            if($whereTags) $where[] =['','exp',Db::raw(trim($whereTags,'or '))];
        }

        if (isset($param['UI_AuthType']) && $param['UI_AuthType']) {
            $where[] =['UI_AuthType','=',$param['UI_AuthType']];
        }

        if (isset($param['UI_RegisterIP']) && $param['UI_RegisterIP']) {
            $where[] =['UI_RegisterIP','like','%'.$param['UI_RegisterIP'].'%'];
        }
        
        // 时间查询
        if ($param['startTime'] && $param['endTime']) {
            $where[] = ['UI_RegisterTime','between',[$param['startTime'],$param['endTime']]];
        }elseif ($param['startTime']) {
            $where[] = ['UI_RegisterTime','>=',$param['startTime']];
        }elseif ($param['endTime']) {
            $where[] = ['UI_RegisterTime','<=',$param['endTime']];
        }

        $order = 'UI_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_Userinfo_fetchUserinfoList_'.$page.'_'.$page_size.'_'.http_build_query($where).'_'.$order);
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

    public function fetchUserinfoDetail($id){
        $where = ['UI_Id'=>$id];
        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_Userinfo_fetchUserinfoDetail_'.$id);
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

    public function fetchUserinfoBase($id){
        $where = ['UI_Id'=>$id];
        $field = 'UI_Id,UI_Nickname,UI_Realname,UI_MobilePhone,UI_Brithday,UI_Gender,UI_Avatar';
        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_Userinfo_fetchUserinfoDetail_'.$id);
            if(!$cacheClass->get($cache_key)){
                $res = $this->field($field)->where($where)->find();
                $cacheClass->set($cache_key,$res,120);
            }else{
                $res = $cacheClass->get($cache_key);
            }
        }else{
            $res = $this->field($field)->where($where)->find();
        }
        return $res;
    }

    public function fetchUserinfoAvatar($id){
        $where = ['UI_Id'=>$id];
        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_Userinfo_fetchUserinfoAvatar_'.$id);
            if(!$cacheClass->get($cache_key)){
                $res = $this->where($where)->value('UI_Avatar');
                $cacheClass->set($cache_key,$res,120);
            }else{
                $res = $cacheClass->get($cache_key);
            }
        }else{
            $res = $this->where($where)->value('UI_Avatar');
        }
        return $res;
    }

    /*=====================insert update delete====================*/
    public function addUserinfo($data){
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

        $newData['UI_AddTime'] = time();

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

    public function updateUserinfo($id,$data){
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
            $res = $this->where(['UI_Id'=>$id])->update($newData);
            if(!$res) {
                $this->rollback();
                return false;
            }

            $this->delCache(['UI_Id'=>$id]);

            // 提交事务
            $this->commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();
            return false;
        }
    }

    public function deleteUserinfo($id, $real = false){
        $this->delCache(['UI_Id'=>$id]);
        
        return $this->where(['UI_Id'=>$id])->delete();
    }

    public function delCache($param=[], $where=[], $order='UI_Id desc'){
        $cacheClass = new Cache();

        if ($param['UI_Id']) {
            //详情缓存
            $cache_key = $cacheClass->getKey('Api_News_fetchNewsInfo_'.$param['UI_Id']);
            $cacheClass->remove($cache_key);
        }elseif ($param['page'] && $where) {
            //列表缓存
            $cache_key = $cacheClass->getKey('Api_News_fetchUserinfoList_'.$param['page'].'_'.$param['page_size'].'_'.http_build_query($where).'_'.$order);
            $cacheClass->remove($cache_key);
        }

        return true;
    }

}