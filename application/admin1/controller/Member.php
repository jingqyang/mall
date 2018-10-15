<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Member extends Base {

    //用户列表
//    public function index(){
//        $bis_id = input('get.bis_id',0,'intval');
//
//        $current_page = input('get.current_page',1,'intval');
//        $limit = 10;
//        $offset = ($current_page - 1) * $limit;
//
//        //总数量
//        if ($bis_id != 0 ){
//            $where = "a.bis_id = ".$bis_id.'and';
//        }else{
//            $where = '';
//        }
//        $count = Db::table('yp_member')->alias('a')
//            ->join('yp_bis b','a.bis_id = b.id','LEFT')
//            ->where($where.'a.status = 1 and b.status = 1')
//            ->count();
//        //总页码
//        $pages = ceil($count / $limit);
//        $res = Db::table('yp_member')->alias('a')
//            ->where("status = 1")
//            ->limit($offset,$limit)
//            ->order('create_time desc')
//            ->select();
//        $index = 0 ;
//        foreach ($res as $val)
//        {
//            if(strlen($val['content']) > 40){
//                $res[$index]['content'] = substr($val['content'],0,40).'...';
//            }else{
//                $res[$index]['content'] = $val['content'];
//            }
//
//            $index ++;
//        }
//        //获取会员信息
//        $bis_res = Db::table('yp_member')->select();
//
//
//        return $this->fetch('',[
//            'res'  => $res,
//            'pages'  => $pages,
//            'current_page'  => $current_page,
//            'count'  =>  $count,
//            'bis_id'  => $bis_id,
//            'bis_res'  => $bis_res,
//        ]);
//
//    }

//    //详情页面
//    public function detail(){
//        //获取数据
//        $id = input('get.mem_id');
//        //设置查询条件
//        $where['m.id'] = $id;
//        $res = Db::table('store_members')->alias('m')->field('')
//            ->join('store_province p','m.province_id = p.id','LEFT')
//            ->join('store_city c','m.city_id = c.id','LEFT')
//            ->where($where)
//            ->find();
//        return $this->fetch('',[
//            'res'   =>  $res
//        ]);
//    }

    public function index1()
    {
        return $this->fetch();
    }


}
