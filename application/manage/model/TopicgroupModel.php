<?php
/**
 * Created by PhpStorm.
 * User: 59470
 * Date: 2018/10/9
 * Time: 9:17
 */

namespace app\manage\model;

use think\Model;
use app\common\service\Cache;
use think\model\concern\SoftDelete;

class TopicgroupModel extends Model{

    use SoftDelete;
    protected $table = 'extr_topicgroup';
    protected $pk = 'TG_Id';
    protected $deleteTime = 'TG_DeleteTime';
    protected $defaultSoftDelete = 0;

}