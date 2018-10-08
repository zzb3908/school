<?php
/**
 * 资讯数据管理类
 * NewsModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\api\model;

use think\Model;
use app\common\service\Cache;

class NewsModel extends Model{

	protected $table = 'info_newsinfo';
	protected $pk = 'NI_Id';

	/*====================select=====================*/
    public function fetchNewsList($page,$page_size,$param=[]){
    	$where = [];

        if (isset($param['NI_Status'])) {
            $where[] = ['NI_Status','=',$param['NI_Status']];
        }else{
            $where[] = ['NI_Status','=',2];//默认已审核
        }
        
        if (isset($param['NI_Id']) && $param['NI_Id']) {
            $where[] = ['NI_Id','=',$param['NI_Id']];
        }
        if (isset($param['NI_IsRecommendIndex']) && $param['NI_IsRecommendIndex']) {
            $where[] = ['NI_IsRecommendIndex','=',$param['NI_IsRecommendIndex']];
        }
        if (isset($param['NI_Module']) && $param['NI_Module']) {
            $where[] = ['NI_Module','=',$param['NI_Module']];
        }
        if (isset($param['NI_Type']) && $param['NI_Type']) {
            $where[] = ['NI_Type','=',$param['NI_Type']];
        }
        if (isset($param['NI_ProvCode']) && $param['NI_ProvCode']) {
            $where[] = ['NI_ProvCode','=',$param['NI_ProvCode']];
        }
        if (isset($param['NI_CityCode']) && $param['NI_CityCode']) {
            $where[] = ['NI_CityCode','=',$param['NI_CityCode']];
        }
        if (isset($param['NI_Title']) && $param['NI_Title']) {
            $where[] = ['NI_Title|NI_SubTitle','like','%'.$param['NI_Title'].'%'];
        }elseif (isset($param['title']) && $param['title']) {
            $where[] = ['NI_Title','like','%'.$param['title'].'%'];
        }

        $whereString = '';
        if (isset($param['NI_Tags']) && $param['NI_Tags']) {
            foreach (explode(',', $param['NI_Tags']) as $key => $value) {
                $whereString .= "find_in_set('".$value."',NI_Tags) or ";
            }
            $whereString = rtrim($whereString,'or ');
        }

        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_News_fetchNewsList_'.$page.'_'.$page_size.'_'.http_build_query($where));
            if(!$cacheClass->get($cache_key)){
                $count = $this->field('NI_Content',true)->where($where)->where($whereString)->count();
                $pages = ceil($count/$page_size);
                if($page<1) $page = 1;
                $list = $this->field('NI_Content',true)->where($where)->where($whereString)->order('NI_Id desc')->page($page,$page_size)->select();
                $res = ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
                $cacheClass->set($cache_key,$res,120);
            }else{
                $res = $cacheClass->get($cache_key);
            }
        }else{
            $count = $this->field('NI_Content',true)->where($where)->where($whereString)->count();
            $pages = ceil($count/$page_size);
            if($page<1) $page = 1;
            $list = $this->field('NI_Content',true)->where($where)->where($whereString)->order('NI_Id desc')->page($page,$page_size)->select();
            $res = ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
        }
        return $res;
    }

    //数据缓存使用事例
    public function fetchNewsDetail($id){
        $where = ['NI_Id'=>$id];
        if (config('use_cache')) {
            $cacheClass = new Cache();
            $cache_key = $cacheClass->getKey('Api_News_fetchNewsInfo_'.$id);
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
    public function addNews($data){
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

        if(!isset($newData['NI_AddTime']) || empty($newData['NI_AddTime'])) {
            $newData['NI_AddTime'] = time();
        }

        if(!isset($newData['NI_UpdateTime']) || empty($newData['NI_UpdateTime'])) {
            $newData['NI_UpdateTime'] = $newData['NI_AddTime'];
        }

        if(!isset($newData['NI_AddIP']) || empty($newData['NI_AddIP'])) {
            $newData['NI_AddIP'] = realip();
        }

        if(!isset($newData['NI_UpdateIP']) || empty($newData['NI_UpdateIP'])) {
            $newData['NI_UpdateIP'] = realip();
        }

        return $this->insertGetId($newData); //插入数据后返回自增ID
    }

    public function updateNews($id,$data){
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

        if(!isset($newData['NI_UpdateTime']) || empty($newData['NI_UpdateTime'])) {
            $newData['NI_UpdateTime'] = time();
        }

        if(!isset($newData['NI_UpdateIP']) || empty($newData['NI_UpdateIP'])) {
            $newData['NI_UpdateIP'] = realip();
        }

        return $this->where(array('NI_Id'=>$id))->update($newData);
    }

    public function deleteNews($id, $real = false){
        if ($real === true) {
            return $this->where(array('NI_Id'=>$id))->delete();
        }else{
            return $this->updateNews($id, ['NI_Status'=>-1]);
        }
    }

    //更新登录次数与登录时间
    public function updateLoginCount($id){
        return $this->where(array('NI_Id'=>$id))->update(
            array(
                'login_time' => array('exp',time()),
                'login_count' => array('exp','login_count+1'),
                )
            );
    }
}