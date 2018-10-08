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

class DateModel extends Model{

    protected $table = 'extr_inviteinfo';
    protected $pk = 'II_Id';

}