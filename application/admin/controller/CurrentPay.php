<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/22
 * Time: 18:05
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;
use think\Model;

class CurrentPay extends Base
{

    public function index()
    {

        $telephone  = input('get.telephone');

//        $bis_id = input('get.bis_id',-1,'intval');
        $current_page = input('get.current_page',1,'intval');
        $limit = 10;
        $offset = ($current_page - 1) * $limit;
        $count = model('CurrentPay')->getAllCurrentPayCount($telephone);

        $pages = ceil($count / $limit);

        $res = \model('CurrentPay')->getAllCurrent($limit,$offset);
        return $this->fetch('',[
            'res'=>$res,
            'pages'=>$pages,
            'current_page'  => $current_page,
//            'bis_id'  => $bis_id,
            'count'=>$count,
            'telephone'=>$telephone
        ]);

    }

    //缴费信息
    public function index_jf()
    {
        $telephone  = input('get.telephone');

        $current_pag = input('get.current_page',1,'intval');
        $limit = 2;
        $offset = ($current_pag - 1) * $limit;
        $cur_count = \model('CurrentPay')->getAllCurrentJf();

        //总页码
        $page = ceil($cur_count / $limit);

        $cur_res = \model('CurrentPay')->getAllPay($offset,$limit);

        return $this->fetch('',[
            'cur_res'   => $cur_res,
            'page' =>  $page,
            'current_pag'  =>  $current_pag,
            'count' =>  $cur_count,
            'telephone' =>  $telephone
        ]);

    }
    public function add()
    {
        return $this->fetch('current_pay/add');
    }

    public function save()
    {

        if(!request()->isPost()){
            $this->error('请求方式错误!');

        }
        //获取提交的数据
        $param = input('post.');
            $start_time = input('get.start_time');
            $stop_time = input('get.stop_time');
            $data = [
                'user_name'=>$param['user_name'],
                'telephone'=>$param['telephone'],
                'charge_item'=>$param['charge_item'],
                'floor'=>$param['floor'],
                'unit'=>$param['unit'],
                'room'=>$param['room'],
                'area'=>$param['area'],
                'assessment'=>$param['assessment'],
                'start_time'=>date('Y-m-d H:i:s',time()),
                'community_id'=>(int)1,
                'sf_pay'=>0,
                'pay_data'=>'欠费'
//                'stop_time'=>$stop_time.' 00:00:00'
            ];


        $res = Db::table('yp_current_pay')->insert($data);


//        $telephone = $data['telephone'];
//        $where = [
//            'telephone'=>$telephone
//        ];
//        $his = Db::table('yp_history_pay')->where($where)->select();
//        $add = Db::table('yp_address')->where($where)->select();
//        $mem = Db::table('yp_member')->where($where)->select();
//
//        if ($his == [] && $add == [] && $mem == [])
//        {
//            $hisr = [
//                'telephone'=>$telephone,
//                'community_id'=>(int)1,
//                'start_time'=>date('Y-m-d H:i:s',time()),
//            ];
//            $addr = [
//                'telephone'=>$telephone,
//                'community_id'=>(int)1,
//            ];
//            $memr = [
//                'telephone'=>$telephone,
//                'province_id'=>'北京',
//                'city_id'=>'海淀区',
//                'address'=>'八家嘉园',
//                'create_time'=>date('Y-m-d H:i:s',time())
//            ];
//
//            Db::table('yp_history_pay')->insert($hisr);
//            Db::table('yp_address')->insert($addr);
//            Db::table('yp_member')->insert($memr);
//
//
//
//        }

            if($res){
//            $this->success("新增成功");
                return redirect('CurrentPay/index');
            }else{
                $this->error('新增失败');
            }






    }

    public function edit()
    {
        $id = input('get.id');
        $res = Db::table('yp_current_pay')->where(['id'=>$id])->find();

        $this->assign('res',$res);
        return $this->fetch();
    }
    public function edit_jf()
    {
        $id = input('get.id');
        $res = Db::table('yp_current_pay')->where(['id'=>$id])->find();

        $this->assign('res',$res);
        return $this->fetch();
    }

    public function editSave()
    {
        $id = input('post.id');
        $param = input('post.');

        $data = [
            'user_name'=>$param['user_name'],
            'floor'=>$param['floor'],
            'unit'=>$param['unit'],
            'room'=>$param['room'],
            'area'=>$param['area'],
            'assessment'=>$param['assessment'],
            'stop_time'=>date('Y-m-d H:i:s',time())

        ];
        $res = Db::table('yp_current_pay')->where(['id'=>$id])->update($data);
        if ($res)
        {
            return redirect('current_pay/index');
        }else{
            $this->error('修改失败');
        }
    }
    public function editSaves()
    {
        $id = input('post.id');
        $param = input('post.');

        $data = [
            'user_name'=>$param['user_name'],
            'floor'=>$param['floor'],
            'unit'=>$param['unit'],
            'room'=>$param['room'],
            'area'=>$param['area'],
            'assessment'=>$param['assessment'],
            'stop_time'=>date('Y-m-d H:i:s',time())

        ];
        $res = Db::table('yp_current_pay')->where(['id'=>$id])->update($data);
        if ($res)
        {
           return $this->success('修改数据成功','index');
//            return redirect('admin/current_pay/index_jf');
        }else{
            $this->error('修改失败');
        }
    }

    public function remove()
    {
        $id = input('get.id');
        $res = Db::table('yp_current_pay')->where(['id'=>$id])->delete();
        if ($res)
        {
            return redirect('current_pay/index');
        }else{
            $this->error('删除数据失败');
        }
    }
    public function removes()
    {
        $id = input('get.id');
        $res = Db::table('yp_current_pay')->where(['id'=>$id])->delete();
        if ($res)
        {
            return redirect('current_pay/index');
        }else{
            $this->error('删除数据失败');
        }
    }
}