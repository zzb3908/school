<?php
/**
 * 秀吧数据管理类
 * ShowmeModel.php
 * @version     v1.0
 * @date        2018-09-05
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\model;

use think\Db;
use think\Model;
use app\common\service\Cache;

class ShowmeModel extends Model{

    protected $table = 'extr_showmeinfo';
    protected $pk = 'SI_Id';
    protected $module_type = 201;//模块id

    /*====================select=====================*/
    public function fetchShowmeList($page,$page_size,$param=[]){
        $where = [];

        if (isset($param['SI_Status']) && $param['SI_Status']!=='') {
            $where[] = ['SI_Status','=',$param['SI_Status']];
        }else{
            $where[] = ['SI_Status','>',-1];
        }
        
        if (isset($param['SI_Type']) && $param['SI_Type']) {
            $where[] = ['SI_Type','=',$param['SI_Type']];
        }
        if (isset($param['SI_SC_Id']) && $param['SI_SC_Id']) {
            $where[] = ['SI_SC_Id','=',$param['SI_SC_Id']];
        }
        if (isset($param['SI_Scope']) && $param['SI_Scope']) {
            $where[] = ['SI_Scope','=',$param['SI_Scope']];
        }
        if (isset($param['SI_AddUId']) && $param['SI_AddUId']) {
            $where[] = ['SI_AddUId','=',$param['SI_AddUId']];
        }
        if (isset($param['SI_AddUName']) && $param['SI_AddUName']) {
            $where[] = ['SI_AddUName','like','%'.$param['SI_AddUName'].'%'];
        }
        if (isset($param['SI_AddIP']) && $param['SI_AddIP']) {
            $where[] = ['SI_AddIP','like','%'.$param['SI_AddIP'].'%'];
        }

        if (isset($param['SI_Tags']) && $param['SI_Tags']) {
            foreach (explode(',', $param['SI_Tags']) as $key => $value) {
                // $whereString .= "find_in_set('".$value."',SI_Tags) or ";
                $where[] =['','exp',Db::raw("find_in_set('".$value."',SI_Tags)")];
            }
        }

        // 时间查询
        if ($param['startTime'] && $param['endTime']) {
            $where[] = ['SI_AddTime','between',[$param['startTime'],$param['endTime']]];
        }elseif ($param['startTime']) {
            $where[] = ['SI_AddTime','>=',$param['startTime']];
        }elseif ($param['endTime']) {
            $where[] = ['SI_AddTime','<=',$param['endTime']];
        }

        // 排序
        $order = 'SI_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        $count = $this->where($where)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->where($where)->order($order)->page($page,$page_size)->select();
        $res = ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];

        return $res;
    }

    //数据缓存使用事例
    public function fetchShowmeDetail($id){
        return $this->where(['SI_Id'=>$id])->find();
    }

    /*=====================insert update delete====================*/
    public function addShowme($data){
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

        $newData['SI_AddTime'] = time();

        if(!isset($newData['SI_AddIP']) || empty($newData['SI_AddIP'])) {
            $newData['SI_AddIP'] = realip();
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
                        'A_DataTitle' => $newData['SI_Content'],
                    ];
                    $attachmentsModel->updateAttachments($value,$attachData);
                }
            }

            //添加时间轴关系
            $data = [
                'ST_FormId' => $id,
                'ST_FormUId' => $newData['SI_AddUId'],
                'ST_AddUId' => $newData['SI_AddUId'],
                'ST_IsOwn' => 1,
            ];
            $this->addShowmeTimeLine($data);

            // 提交事务
            $this->commit();
            return $id;
        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();
            return false;
        }
    }

    public function updateShowme($id,$data){
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
            $this->where(['SI_Id'=>$id])->update($newData);

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
                        'A_DataTitle' => $newData['SI_Content'],
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

    public function deleteShowme($id, $real = false){
        if ($real === true) {
            return $this->where(['SI_Id'=>$id])->delete();
        }else{
            return $this->updateShowme($id, ['SI_Status'=>-1]);
        }
    }


    /***************** 分类操作 ****************/
    public function fetchCategoryListAll($param=[]){
        $where = [];

        if (isset($param['SC_Status']) && $param['SC_Status']!=='') {
            $where[] = ['SC_Status','=',$param['SC_Status']];
        }else{
            $where[] = ['SC_Status','>',-1];
        }
        
        if (isset($param['SC_PId'])) {
            $where[] = ['SC_PId','=',$param['SC_PId']];
        }
        if (isset($param['SC_Name']) && $param['SC_Name']) {
            $where[] = ['SC_Name','like','%'.$param['SC_Name'].'%'];
        }
        if (isset($param['SC_IsHot']) && $param['SC_IsHot']!=='') {
            $where[] = ['SC_IsHot','=',$param['SC_IsHot']];
        }
        if (isset($param['SC_AddUId']) && $param['SC_AddUId']) {
            $where[] = ['SC_AddUId','=',$param['SC_AddUId']];
        }
        if (isset($param['SI_AddIP']) && $param['SI_AddIP']) {
            $where[] = ['SI_AddIP','like','%'.$param['SI_AddIP'].'%'];
        }

        // 排序
        $order = 'SC_Sort desc,SC_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }
        return $this->table('extr_showmecategory')->where($where)->order($order)->select();
    }

    public function fetchCategoryList($page,$page_size,$param=[]){
        $where = [];

        if (isset($param['SC_Status']) && $param['SC_Status']!=='') {
            $where[] = ['SC_Status','=',$param['SC_Status']];
        }else{
            $where[] = ['SC_Status','>',-1];
        }
        
        if (isset($param['SC_PId'])) {
            $where[] = ['SC_PId','=',$param['SC_PId']];
        }
        if (isset($param['SC_Name']) && $param['SC_Name']) {
            $where[] = ['SC_Name','like','%'.$param['SC_Name'].'%'];
        }
        if (isset($param['SC_IsHot']) && $param['SC_IsHot']!=='') {
            $where[] = ['SC_IsHot','=',$param['SC_IsHot']];
        }
        if (isset($param['SC_AddUId']) && $param['SC_AddUId']) {
            $where[] = ['SC_AddUId','=',$param['SC_AddUId']];
        }
        if (isset($param['SC_AddIP']) && $param['SC_AddIP']) {
            $where[] = ['SC_AddIP','like','%'.$param['SC_AddIP'].'%'];
        }

        // 排序
        $order = 'SC_Sort desc,SC_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        $count = $this->table('extr_showmecategory')->where($where)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->table('extr_showmecategory')->where($where)->order($order)->page($page,$page_size)->select();
        $res = ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];

        return $res;
    }

    public function fetchCategoryDetail($id){
        return $this->table('extr_showmecategory')->where(['SC_Id'=>$id])->find();
    }

    public function fetchCategoryName($id){
        return $this->table('extr_showmecategory')->where(['SC_Id'=>$id])->value('SC_Name');
    }

    /*=====================insert update delete====================*/
    public function addCategory($data){
        if (!is_array($data) || empty($data)) return false;

        $newData = [];

        //排除无效字段
        $fields = $this->getTableFields('extr_showmecategory');
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $newData[$key] = $value;
            }
        }

        if(empty($newData)) return false;

        $newData['SC_AddTime'] = time();

        if(!isset($newData['SC_AddIP']) || empty($newData['SC_AddIP'])) {
            $newData['SC_AddIP'] = realip();
        }

        // 事务处理
        $this->startTrans();
        try {
            $id = $this->table('extr_showmecategory')->insertGetId($newData);
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

    public function updateCategory($id,$data){
        if (empty($id) || !is_array($data) || empty($data)) return false;

        $newData = [];

        //排除无效字段
        $fields = $this->getTableFields('extr_showmecategory');
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $newData[$key] = $value;
            }
        }

        if(empty($newData)) return false;

        // 事务处理
        $this->startTrans();
        try {
            $this->table('extr_showmecategory')->where(['SC_Id'=>$id])->update($newData);

            // 提交事务
            $this->commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();
            return false;
        }
    }

    public function deleteCategory($id, $real = false){
        if ($real === true) {
            return $this->table('extr_showmecategory')->where(['SC_Id'=>$id])->delete();
        }else{
            return $this->updateCategory($id, ['SC_Status'=>-1]);
        }
    }


    /************************* 秀吧时间轴 *****************************/

    public function fetchShowmeTimeLineList($page,$page_size,$param=[]){
        $where = [];
        
        if (isset($param['ST_FormId']) && $param['ST_FormId']) {
            $where[] = ['ST_FormId','=',$param['ST_FormId']];
        }
        if (isset($param['ST_FormUId']) && $param['ST_FormUId']) {
            $where[] = ['ST_FormUId','=',$param['ST_FormUId']];
        }
        if (isset($param['ST_IsOwn']) && $param['ST_IsOwn']!=='') {
            $where[] = ['ST_IsOwn','=',$param['ST_IsOwn']];
        }
        if (isset($param['ST_AddUId']) && $param['ST_AddUId']) {
            $where[] = ['ST_AddUId','=',$param['ST_AddUId']];
        }

        // 时间查询
        if ($param['startTime'] && $param['endTime']) {
            $where[] = ['ST_AddTime','between',[$param['startTime'],$param['endTime']]];
        }elseif ($param['startTime']) {
            $where[] = ['ST_AddTime','>=',$param['startTime']];
        }elseif ($param['endTime']) {
            $where[] = ['ST_AddTime','<=',$param['endTime']];
        }

        // 排序
        $order = 'ST_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        $count = $this->table('extr_showmetimeline')->where($where)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->table('extr_showmetimeline')->where($where)->order($order)->page($page,$page_size)->select();
        return ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];
    }

    public function addShowmeTimeLine($data){
        if (!is_array($data) || empty($data)) return false;

        $newData = [];

        //排除无效字段
        $fields = $this->getTableFields('extr_showmetimeline');
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $newData[$key] = $value;
            }
        }

        if(empty($newData)) return false;

        $newData['ST_AddTime'] = time();

        // 事务处理
        $this->startTrans();
        try {
            $id = $this->table('extr_showmetimeline')->insertGetId($newData);
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

}