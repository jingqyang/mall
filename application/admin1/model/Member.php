<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/8/16
 * Time: 9:20
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class Member extends Model
{
    //添加会员
    public function add($data){
        $data['status'] = 0;
        return Db::table('yp_member')->insert($data);
    }



    //获取品牌信息
    public function getMember($bis_id,$limit,$offset){
        if($bis_id == 0){
            $where = [
                'b.status' => ['neq',-1]
            ];
        }else{
            $where = [
                'b.status' => ['neq',-1],
                'b.bis_id'  => $bis_id
            ];
        }

        $order = [
            'b.create_time'  => 'desc'
        ];
        return Db::table('yp_member')->alias('b')->field('b.*,bis.bis_name')
            ->where($where)
            ->limit($offset,$limit)
            ->order($order)
            ->select();
    }

    //获取品牌信息
    public function getMemberCount($bis_id){
        if($bis_id == 0){
            $where = [
                'status' => ['neq',-1]
            ];
        }else{
            $where = [
                'status' => ['neq',-1],
                'bis_id'  => $bis_id
            ];
        }


        return Db::table('yp_member')->where($where)->count();
    }

    //获取正常的品牌信息
    public function getNormalMember($bis_id){
        $where = [
            'bis_id'  => $bis_id,
            'status'  => 1
        ];

        return Db::table('member')->where($where)->select();
    }


}