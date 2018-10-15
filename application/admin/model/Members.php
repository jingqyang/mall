<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Members extends Model{

    //获取所有会员信息
    public function getAllMembers($limit,$offset,$create_time){

            $where = ['mem.bis_id'=>1];
            $listorder = [
                'mem.create_time'  => 'desc'
            ];
        if($create_time){
            $new_date_from = $create_time.' 00:00:00';
            $where .= " and pro.create_time >= '$new_date_from'";
        }
            return Db::table('yp_member')->alias('mem')
                ->field('mem.id,mem.truename,mem.create_time,mem.type,mem.jifen,mem.telephone')
                ->join('store_bis bis','mem.bis_id = bis.id','LEFT')
                ->where($where)
                ->order($listorder)
                ->limit($offset,$limit)
                ->select();
        }


//        echo '<pre>';
//        var_dump($res);
//        exit();
//        return $res;


    //获取所有会员数量
    public function getAllMembersCount(){

        $res = Db::table('yp_member')->where(['bis_id'=>1])->count();
        return $res;
    }

    //根据id获取会员信息
    public function getMemberInfoById($id){
        $where = [
            'id'  => $id
        ];

        $res = Db::table('store_members')->where($where)->find();
        return $res;
    }

}

?>