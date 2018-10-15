<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/23
 * Time: 9:40
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class HistoryPay extends Model
{
    public function getAllHistory()
    {
        $res = Db::table('yp_history_pay')->count();
        return $res;
    }

    public function getAllHistoryPay($limit,$offset)
    {
        $res = Db::table('yp_history_pay')->field('id,member_id,order_no,assessment,stop_time')
            ->order('stop_time desc')
            ->limit($offset,$limit)
            ->select();
        return $res;
    }
}