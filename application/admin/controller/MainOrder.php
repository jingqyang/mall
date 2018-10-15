<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/22
 * Time: 18:05
 */

namespace app\admin\controller;


use think\Db;

class MainOrder extends  Base
{
    //已缴费
    public function index()
    {
        $current_page = input('get.current_page',1,'intval');
        $limit = 5;
        $offset = ($current_page - 1) * $limit;
        $count = model('MainOrder')->getAllMain();
        $pages = ceil($count / $limit);
        $res = \model('MainOrder')->getAllMainOrder($offset,$limit);

        return $this->fetch('',[
            'res'=>$res,
            'pages'=>$pages,
            'current_page'  => $current_page,
        ]);
    }
    //未缴费
    public function indexno()
    {
        $current_page = input('get.current_page',1,'intval');
        $limit = 5;
        $offset = ($current_page - 1) * $limit;
        $count = model('MainOrder')->getAllMainno();
        $pages = ceil($count / $limit);
        $res = \model('MainOrder')->getAllMainOrderno($offset,$limit);

        return $this->fetch('',[
            'res'=>$res,
            'pages'=>$pages,
            'current_page'  => $current_page,
        ]);
    }
    //已缴费
    public function add()
    {
        return $this->fetch('main_order/add');
    }

    //未缴费
    public function addno()
    {
        return $this->fetch('main_order/addno');
    }

    //添加已缴费记录操作
    public function save()
    {
        if(!request()->isPost()){
            $this->error('请求方式错误!');
        }
        //获取提交的数据
        $param = input('post.');
        $data = [
            'order_no'=>$param['order_no'],
            'total_amount'=>$param['total_amount'],
            'pay_status'=>1,
            'create_time'=>date('Y-m-d H:i:s',time())
        ];
        $res = Db::table('yp_main_order')->insert($data);
        if($res){
//            $this->success("新增成功");
            return redirect('MainOrder/index');
        }else{
            $this->error('新增失败');
        }
    }

    //添加未缴费记录操作
    public function saveno()
    {
        if(!request()->isPost()){
            $this->error('请求方式错误!');
        }
        //获取提交的数据
        $param = input('post.');
        $data = [
            'order_no'=>$param['order_no'],
            'total_amount'=>$param['total_amount'],
            'pay_status'=>1,
            'create_time'=>date('Y-m-d H:i:s',time())
        ];
        $res = Db::table('yp_main_order')->insert($data);
        if($res){
//            $this->success("新增成功");
            return redirect('MainOrder/indexno');
        }else{
            $this->error('新增失败');
        }
    }

    //修改已缴费记录
    public function edit()
    {
        $id = input('get.id');
        $res = Db::table('yp_main_order')->where(['id'=>$id])->find();
        $this->assign('res',$res);
        return $this->fetch();
    }

    //修改未缴缴费记录
    public function editno()
    {
        $id = input('get.id');
        $res = Db::table('yp_main_order')->where(['id'=>$id])->find();
        $this->assign('res',$res);
        return $this->fetch();
    }

    //修改已缴费记录操作
    public function editSave()
    {
        $id = input('post.id');
        $param = input('post.');
        $data = [
            'order_no'=>$param['order_no'],
            'total_amount'=>$param['total_amount'],
            'update_time'=>date('Y-m-d H:i:s',time())
        ];
        $res = Db::table('yp_main_order')->where(['id'=>$id])->update($data);
        if ($res)
        {
            return redirect('main_order/index');
        }else{
            $this->error('修改失败');
        }
    }

    //修改未缴费记录操作
    public function editSaveno()
    {
        $id = input('post.id');
        $param = input('post.');
        $data = [
            'order_no'=>$param['order_no'],
            'total_amount'=>$param['total_amount'],
            'update_time'=>date('Y-m-d H:i:s',time())
        ];
        $res = Db::table('yp_main_order')->where(['id'=>$id])->update($data);
        if ($res)
        {
            return redirect('main_order/indexno');
        }else{
            $this->error('修改失败');
        }
    }

    //删除已缴费记录
    public function remove()
    {
        $id = input('get.id');
        $res = Db::table('yp_main_order')->where(['id'=>$id])->delete();
        if ($res)
        {
            return redirect('main_order/index');
        }else{
            $this->error('删除已缴数据失败');
        }
    }

    //删除未缴费记录
    public function removeno()
    {
        $id = input('get.id');
        $res = Db::table('yp_main_order')->where(['id'=>$id])->delete();
        if ($res)
        {
            return redirect('main_order/indexno');
        }else{
            $this->error('删除已缴数据失败');
        }
    }
}