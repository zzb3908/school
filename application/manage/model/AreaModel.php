<?php
/**
 * 地区数据管理类
 * AreaModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\model;

use think\Model;
use app\common\service\Cache;

class AreaModel extends Model{

    protected $table = 'sys_areainfo';
    protected $pk = 'AI_Id';

    /*====================select=====================*/
    public function fetchAreaList($page,$page_size,$param=[]){
        $where = [];
        
        if (isset($param['AI_PId']) && $param['AI_PId']) {
            $where[] = ['AI_PId','=',$param['AI_PId']];
        }
        if (isset($param['AI_Name']) && $param['AI_Name']) {
            $where[] = ['AI_Name','like','%'.$param['AI_Name'].'%'];
        }
        if (isset($param['AI_ZJM']) && $param['AI_ZJM']) {
            $where[] = ['AI_ZJM','like','%'.$param['AI_ZJM'].'%'];
        }

        $count = $this->where($where)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->where($where)->order('AI_Id asc')->page($page,$page_size)->select();
        return ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
    }

    public function fetchAreaListAll($param=[]){
        $where = [];
        
        if (isset($param['AI_PId'])) {
            $where[] = ['AI_PId','=',$param['AI_PId']];
        }
        if (isset($param['AI_Name']) && $param['AI_Name']) {
            $where[] = ['AI_Name','like','%'.$param['AI_Name'].'%'];
        }
        if (isset($param['AI_ZJM']) && $param['AI_ZJM']) {
            $where[] = ['AI_ZJM','like','%'.$param['AI_ZJM'].'%'];
        }

        return $this->where($where)->order('AI_Id asc')->select();
    }

    //数据缓存使用事例
    public function fetchAreaDetail($id){
        $where = ['AI_Id'=>$id];
        return $this->where($where)->find();
    }

    /*=====================insert update delete====================*/
    public function addArea($data){
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

    public function updateArea($id,$data){
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

        return $this->where(array('AI_Id'=>$id))->update($newData);
    }

    public function deleteArea($id, $real = false){
        return $this->where(array('AI_Id'=>$id))->delete();
    }

}