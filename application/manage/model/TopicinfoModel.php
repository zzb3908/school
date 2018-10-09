<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/9
 * Time: 9:17
 */

namespace app\manage\model;

use think\Model;
use think\model\concern\SoftDelete;

class TopicinfoModel extends Model{

    protected $table = 'extr_topicinfo';
    protected $pk = 'TI_Id';

}