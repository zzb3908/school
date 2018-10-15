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

class SocietyinviteModel extends Model{

    protected $table = 'assi_societyinvite';
    protected $pk = 'SI_Id';


}