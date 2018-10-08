<?php
/**
 * 标签数据管理类
 * TagsModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\model;

use think\Model;
use app\common\service\Cache;

class TagsModel extends Model{

    protected $table = 'sys_tags';
    protected $pk = 'T_Id';

    /*====================select=====================*/
    public function fetchTagsList($page,$page_size,$param=[]){
        $where = [];
        
        if (isset($param['T_Module']) && $param['T_Module']) {
            $where[] = ['T_Module','=',$param['T_Module']];
        }
        if (isset($param['T_Name']) && $param['T_Name']) {
            $where[] = ['T_Name|T_ZJM','like','%'.$param['T_Name'].'%'];
        }
        if (isset($param['T_ZJM']) && $param['T_ZJM']) {
            $where[] = ['T_ZJM|T_Name','like','%'.$param['T_ZJM'].'%'];
        }

        //排序
        $order = 'T_Sort desc,T_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        $count = $this->where($where)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->where($where)->order($order)->page($page,$page_size)->select();
        if ($list) {
            foreach ($list as $key => $value) {
                if(empty($value['T_Module'])) continue;
                $list[$key]['T_ModuleName'] = module_name($value['T_Module']);
            }
        }
        return ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
    }

    public function fetchTagsListAll($param=[]){
        $where = [];
        
        if (isset($param['T_Module']) && $param['T_Module']) {
            $where[] = ['T_Module','=',$param['T_Module']];
        }
        if (isset($param['T_Name']) && $param['T_Name']) {
            $where[] = ['T_Name|T_ZJM','like','%'.$param['T_Name'].'%'];
        }
        if (isset($param['T_ZJM']) && $param['T_ZJM']) {
            $where[] = ['T_ZJM|T_Name','like','%'.$param['T_ZJM'].'%'];
        }

        $list = $this->where($where)->order('T_Sort desc,T_Id desc')->select();
        if ($list) {
            foreach ($list as $key => $value) {
                if(empty($value['T_Module'])) continue;
                $list[$key]['T_ModuleName'] = module_name($value['T_Module']);
            }
        }
        return $list;
    }

    //数据缓存使用事例
    public function fetchTagsDetail($id){
        $info = $this->where(['T_Id'=>$id])->find();
        if ($info && $info['T_Module']) {
            $info['T_ModuleName'] = module_name($info['T_Module']);
        }
        return $info;
    }

    public function fetchTagsDetailByWhere($where){
        $info = $this->where($where)->find();
        if ($info && $info['T_Module']) {
            $info['T_ModuleName'] = module_name($info['T_Module']);
        }
        return $info;
    }

    public function fetchTagsMaxId(){
        return $this->max('T_Id');
    }

    /*=====================insert update delete====================*/
    public function addTags($data){
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

        $newData['T_AddTime'] = time();

        if(!isset($newData['T_AddIP']) || empty($newData['T_AddIP'])) {
            $newData['T_AddIP'] = realip();
        }

        return $this->insertGetId($newData); //插入数据后返回自增ID
    }

    public function updateTags($id,$data){
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

        return $this->where(array('T_Id'=>$id))->update($newData);
    }

    public function deleteTags($id, $real = false){
        return $this->where(array('T_Id'=>$id))->delete();
    }

}