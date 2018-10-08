<?php
namespace app\manage\controller;

class AjaxController extends \think\Controller{

    public function getSchoolsAction(){
        $param = input();

        $schoolsModel = model('Schools');
        $list = $schoolsModel->fetchSchoolsListAll(['S_ProvId'=>$param['spid'], 'S_Id'=>$param['sid']]);

        $option = '<option value="">选择学校</option>';
        foreach ($list as $key => $value) {
            if (in_array($value['S_Id'], explode(',', $param['sid']))) {
                $selected = 'selected';
            }else{
                $selected = '';
            }

            $option .= '<option value="'.$value['S_Id'].'" '.$selected.'>'.$value['S_Name'].'</option>';
        }
        return json(['status'=>1, 'msg'=>'查询成功', 'data'=>$option]);
    }

    public function getCityAction(){
        $S_ProvId = input('spid');
        $areaModel = model('Area');
        $list = $areaModel->fetchAreaListAll(['AI_PId'=>$S_ProvId]);

        $option = '<option value="">选择城市</option>';
        foreach ($list as $key => $value) {
            $option .= '<option value="'.$value['AI_Id'].'">'.$value['AI_Name'].'</option>';
        }
        return json(['status'=>1, 'msg'=>'查询成功', 'data'=>$option]);
    }

    public function getZjmAction(){
        $keyword = input('keyword');
        return json(['status'=>1, 'msg'=>'查询成功', 'data'=>pinyin($keyword)]);
    }

    public function testAction(){
        $schoolsModel = model('Schools');
        $list = $schoolsModel->fetchSchoolsProListAll([]);
        // foreach ($list as $key => $value) {
        //     $schoolsModel->updateSchoolsPro($value['SP_Id'], ['SP_ZJM'=>pinyin($value['SP_Name'])]);
        // }
        halt($list);
    }
}
