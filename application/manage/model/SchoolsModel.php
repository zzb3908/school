<?php
/**
 * 学校数据管理类
 * SchoolsModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\model;

use think\Model;
use app\common\service\Cache;

class SchoolsModel extends Model{

    protected $table = 'sys_schools';
    protected $pk = 'S_Id';

    /*====================select=====================*/
    public function fetchSchoolsList($page,$page_size,$param=[]){
        $where = [];
        
        if (isset($param['S_Country']) && $param['S_Country']) {
            $where[] = ['S_Country','=',$param['S_Country']];
        }
        if (isset($param['S_ProvId']) && $param['S_ProvId']) {
            $where[] = ['S_ProvId','=',$param['S_ProvId']];
        }
        if (isset($param['S_CityId']) && $param['S_CityId']) {
            $where[] = ['S_CityId','=',$param['S_CityId']];
        }
        if (isset($param['S_Name']) && $param['S_Name']) {
            $where[] = ['S_Name|S_ZJM','like','%'.$param['S_Name'].'%'];
        }
        if (isset($param['S_ZJM']) && $param['S_ZJM']) {
            $where[] = ['S_ZJM','like','%'.$param['S_ZJM'].'%'];
        }

        $count = $this->where($where)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->where($where)->order('S_Id desc')->page($page,$page_size)->select();
        return ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
    }

    public function fetchSchoolsListAll($param=[]){
        $where = $whereOr = [];
        
        if (isset($param['S_Country']) && $param['S_Country']) {
            $where[] = ['S_Country','=',$param['S_Country']];
        }
        if (isset($param['S_ProvId']) && $param['S_ProvId']) {
            $where[] = ['S_ProvId','=',$param['S_ProvId']];
        }
        if (isset($param['S_CityId']) && $param['S_CityId']) {
            $where[] = ['S_CityId','=',$param['S_CityId']];
        }
        if (isset($param['S_Name']) && $param['S_Name']) {
            $where[] = ['S_Name','like','%'.$param['S_Name'].'%'];
        }
        if (isset($param['S_ZJM']) && $param['S_ZJM']) {
            $where[] = ['S_ZJM','like','%'.$param['S_ZJM'].'%'];
        }
        if (isset($param['S_Id']) && $param['S_Id']) {
            $whereOr[] = ['S_Id','in',$param['S_Id']];
        }

        return $this->where($where)->whereOr($whereOr)->order('S_Id desc')->select();
    }

    public function fetchSchoolsDetail($id){
        return $this->where(['S_Id'=>$id])->find();
    }

    public function fetchSchoolsName($id){
        return $this->where(['S_Id'=>$id])->value('S_Name');
    }

    /*=====================insert update delete====================*/
    public function addSchools($data){
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

    public function updateSchools($id,$data){
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

        return $this->where(array('S_Id'=>$id))->update($newData);
    }

    public function deleteSchools($id, $real = false){
        return $this->where(array('S_Id'=>$id))->delete();
    }


    /*====================院系 sys_schoolprofessional =====================*/
    public function fetchSchoolsProList($page,$page_size,$param=[]){
        $where = [];
        
        if (isset($param['SP_PId']) && $param['SP_PId']) {
            $where[] = ['SP_PId','=',$param['SP_PId']];
        }
        if (isset($param['SP_Name']) && $param['SP_Name']) {
            $where[] = ['SP_Name|SP_ZJM','like','%'.$param['SP_Name'].'%'];
        }
        if (isset($param['SP_ZJM']) && $param['SP_ZJM']) {
            $where[] = ['SP_ZJM','like','%'.$param['SP_ZJM'].'%'];
        }

        $count = $this->table('sys_schoolprofessional')->where($where)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->table('sys_schoolprofessional')->where($where)->order('SP_Id desc')->page($page,$page_size)->select();
        return ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
    }

    public function fetchSchoolsProListAll($param=[]){
        $where = [];
        
        if (isset($param['SP_PId']) && $param['SP_PId']) {
            $where[] = ['SP_PId','=',$param['SP_PId']];
        }
        if (isset($param['SP_Name']) && $param['SP_Name']) {
            $where[] = ['SP_Name','like','%'.$param['SP_Name'].'%'];
        }
        if (isset($param['SP_ZJM']) && $param['SP_ZJM']) {
            $where[] = ['SP_ZJM','like','%'.$param['SP_ZJM'].'%'];
        }

        return $this->table('sys_schoolprofessional')->where($where)->order('SP_Id desc')->select();
    }

    public function fetchSchoolsProDetail($id){
        return $this->table('sys_schoolprofessional')->where(['SP_Id'=>$id])->find();
    }

    public function fetchSchoolsProDetailByWhere($where){
        return $this->table('sys_schoolprofessional')->where($where)->find();
    }

    public function fetchSchoolsProMaxId($pid = 0){
        if ($pid) {
            return $this->table('sys_schoolprofessional')->where('SP_PId','=',$pid)->max('SP_Id');
        }else{
            return $this->table('sys_schoolprofessional')->max('SP_Id');
        }
    }

    /*=====================insert update delete====================*/
    public function addSchoolsPro($data){
        if (!is_array($data) || empty($data)) return false;

        $newData = [];

        //排除无效字段
        $fields = $this->getTableFields('sys_schoolprofessional');
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $newData[$key] = $value;
            }
        }

        if(empty($newData)) return false;

        return $this->table('sys_schoolprofessional')->insertGetId($newData); //插入数据后返回自增ID
    }

    public function updateSchoolsPro($id,$data){
        if (empty($id) || !is_array($data) || empty($data)) return false;

        $newData = [];

        //排除无效字段
        $fields = $this->getTableFields('sys_schoolprofessional');
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $newData[$key] = $value;
            }
        }

        if(empty($newData)) return false;

        $this->table('sys_schoolprofessional')->where(array('SP_Id'=>$id))->update($newData);
        return true;
    }

    public function deleteSchoolsPro($id, $real = false){
        return $this->table('sys_schoolprofessional')->where(array('SP_Id'=>$id))->delete();
    }

}