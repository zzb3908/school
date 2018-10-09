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

class CompetitioninfoModel extends Model{

    protected $table = 'extr_competitioninfo';
    protected $pk = 'CI_Id';

}