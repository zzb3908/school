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

class SocietyinvitealbumModel extends Model{

    protected $table = 'assi_societyinvitealbum';
    protected $pk = 'SIA_Id';

    public function societyinvite()
    {
        return $this->belongsTo(SocietyinviteModel::class,'SIA_SL_Id','SI_Id')->field('SI_SI_Name,SI_Id,SI_Title')->bind('SI_SI_Name,SI_Id,SI_Title');
    }

}