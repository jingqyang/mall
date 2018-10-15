<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/23
 * Time: 8:02
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class CurrentPay extends Model
{
    public function getAllCurrent($limit,$offset)
    {

            $listorder = [
                'start_time'=>'desc'
            ];

        return Db::table('yp_current_pay')
            ->alias('pay')
//            ->join('yp_current_pay pay','mem.telephone = pay.telephone','LEFT')
            ->field('id,pay_data,user_name,telephone,assessment,start_time,stop_time')
           // ->where(['bis_id'=>1])
            // ->order($listorder)
            ->order($listorder)
            ->limit($offset,$limit)
            ->select();


    }

    public function getAllCurrentPayCount()
    {

            $res = Db::table('yp_current_pay')->count();
            return $res;
    }

    public function getAllCurrentJf()
    {
        $telephone  = input('get.telephone');

        $res = Db::table('yp_current_pay')->where(['telephone'=>$telephone])->count();
        return $res;
    }

    public function getAllPay($limit,$offset)
    {
        $telephone  = input('get.telephone');
        $listorder = [
            'start_time'=>'desc'
        ];
        $res = Db::table('yp_current_pay')
            ->where(['telephone'=>$telephone])
            ->order($listorder)
//            ->limit($offset,$limit)
            ->select();
  
        return $res;
    }

}
