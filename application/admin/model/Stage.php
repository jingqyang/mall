<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/30
 * Time: 15:31
 */

namespace app\admin\model;

use think\Db;
use think\Model;

class Stage extends Model
{
    //总数
    public function getAllStageCount()
    {
        $res = Db::table('yp_stage')->count();
        return $res;
    }

    //数据
    public function getAllStage($offset,$limit)
    {
        $res = Db::table('yp_stage')
            ->order('start_time desc')
            ->limit($offset,$limit)
            ->select();
        return $res;
    }
}