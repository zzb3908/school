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

namespace app\manage\model;

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
            $where[] = ['NI_Status','>',-1];
        }
        
        if (isset($param['NI_Id']) && $param['NI_Id']) {
            $where[] =['NI_Id','=',$param['NI_Id']];
        }
        if (isset($param['NI_Module']) && $param['NI_Module']) {
            $where[] = ['NI_Module','=',$param['NI_Module']];
        }
        if (isset($param['NI_Type']) && $param['NI_Type']) {
            $where[] =['NI_Type','=',$param['NI_Type']];
        }
        if (isset($param['NI_ProvCode']) && $param['NI_ProvCode']) {
            $where[] =['NI_ProvCode','=',$param['NI_ProvCode']];
        }
        if (isset($param['NI_CityCode']) && $param['NI_CityCode']) {
            $where[] =['NI_CityCode','=',$param['NI_CityCode']];
        }
        if (isset($param['NI_Title']) && $param['NI_Title']) {
            $where[] =['NI_Title','like','%'.$param['NI_Title'].'%'];
        }elseif (isset($param['title']) && $param['title']) {
            $where[] =['NI_Title','like','%'.$param['title'].'%'];
        }

        $whereString = '';
        if (isset($param['NI_Tags']) && $param['NI_Tags']) {
            $whereString .= "find_in_set({$param['NI_Tags']},NI_Tags)";
        }

        // 时间查询
        if ($param['startTime'] && $param['endTime']) {
            $where[] = ['NI_AddTime','between',[$param['startTime'],$param['endTime']]];
        }elseif ($param['startTime']) {
            $where[] = ['NI_AddTime','>=',$param['startTime']];
        }elseif ($param['endTime']) {
            $where[] = ['NI_AddTime','<=',$param['endTime']];
        }

        $order = 'NI_Id desc';
        if ($param['orderField'] && $param['orderType']) {
            $order = $param['orderField'] .' '. $param['orderType'];
        }

        $count = $this->field('NI_Content',true)->where($where)->where($whereString)->count();
        $pages = ceil($count/$page_size);
        if($page<1) $page = 1;
        $list = $this->field('NI_Content',true)->where($where)->where($whereString)->order($order)->page($page,$page_size)->select();

        $commentsModel = model('Comments');
        foreach ($list as $key => $value) {
            $list[$key]['NI_ModuleName'] = module_name($value['NI_Module']);
            $list[$key]['NI_CommentCount'] = $commentsModel->fetchCommentsCount(['C_Module'=>$value['NI_Module'],'C_DataId'=>$value['NI_Id']]);
        }
        $res = ['count'=>$count,'page_size'=>$page_size,'page'=>$page,'pages'=>$pages,'list'=>obj2arr($list)];

        return $res;
    }

    //数据缓存使用事例
    public function fetchNewsDetail($id){
        return $this->where(['NI_Id'=>$id])->find();
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

        $newData['NI_AddTime'] = time();

        if(!isset($newData['NI_UpdateTime']) || empty($newData['NI_UpdateTime'])) {
            $newData['NI_UpdateTime'] = $newData['NI_AddTime'];
        }

        if(!isset($newData['NI_AddIP']) || empty($newData['NI_AddIP'])) {
            $newData['NI_AddIP'] = realip();
        }

        if(!isset($newData['NI_UpdateIP']) || empty($newData['NI_UpdateIP'])) {
            $newData['NI_UpdateIP'] = realip();
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
                        'A_Module' => $newData['NI_Module'],
                        'A_IsBackground' => 1, //后台上传的图片
                        'A_DataId' => $id,
                        'A_DataTitle' => $newData['NI_Title'],
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

        if(!isset($newData['NI_UpdateIP']) || empty($newData['NI_UpdateIP'])) {
            $newData['NI_UpdateIP'] = realip();
        }

        $newData['NI_UpdateTime'] = time();

        // 事务处理
        $this->startTrans();
        try {
            $res = $this->where(['NI_Id'=>$id])->update($newData);
            if(!$res) {
                $this->rollback();
                return false;
            }

            if ($data['attachIds']) {
                $attachmentsModel = model('Attachments');

                //先删除当前资讯的附件
                $attachmentsModel->deleteAttachments([
                    ['A_Module','=',$newData['NI_Module']],
                    ['A_DataId','=',$id],
                    ['A_Id','not in',$data['attachIds']]
                ]);

                //更新附件对应数据关系
                foreach (explode(',', $data['attachIds']) as $key => $value) {
                    $attachData = [
                        'A_Module' => $newData['NI_Module'],
                        'A_IsBackground' => 1, //后台上传的图片
                        'A_DataId' => $id,
                        'A_DataTitle' => $newData['NI_Title'],
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

    public function deleteNews($id, $real = false){
        if ($real === true) {
            return $this->where(['NI_Id'=>$id])->delete();
        }else{
            return $this->updateNews($id, ['NI_Status'=>-1]);
        }
    }

    //更新登录次数与登录时间
    public function updateLoginCount($id){
        return $this->where(['NI_Id'=>$id])->update(
            array(
                'login_time' => array('exp',time()),
                'login_count' => array('exp','login_count+1'),
                )
            );
    }
}