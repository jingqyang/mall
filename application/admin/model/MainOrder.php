<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/23
 * Time: 10:23
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class MainOrder extends Model
{
    //已缴费记录总和
    public function getAllMain()
    {
        $res = Db::table('yp_main_order')->where(['pay_status'=>1])->count();
        return $res;
    }

    //未缴费记录总和
    public function getAllMainno()
    {
        $res = Db::table('yp_main_order')->where(['pay_status'=>0])->count();
        return $res;
    }

    //已缴费记录
    public function getAllMainOrder($offset,$limit)
    {
        $res = Db::table('yp_main_order')->field('id,pay_status,order_no,total_amount,update_time,create_time')
            ->order('create_time desc')
            ->limit($offset,$limit)
            ->where(['pay_status'=>1])
            ->select();

        return $res;
    }

    //未缴费记录
    public function getAllMainOrderno($offset,$limit)
    {
        $res = Db::table('yp_main_order')->field('id,pay_status,order_no,total_amount,update_time,create_time')
            ->order('create_time desc')
            ->limit($offset,$limit)
            ->where(['pay_status'=>0])
            ->select();

        return $res;
    }
}