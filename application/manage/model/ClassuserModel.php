<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/9
 * Time: 9:17
 */

namespace app\manage\model;

use think\Model;

class ClassuserModel extends Model{

    protected $table = 'assi_classuser';
    protected $pk = 'TU_Id';

    public function classes()
    {
        return $this->belongsTo('ClassModel','TU_CI_Id','CI_Id')->field('CI_SchoolName,CI_Id')->bind('CI_SchoolName');
    }

}