<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/22
 * Time: 18:05
 */

namespace app\admin\controller;


use think\Db;

class HistoryPay extends Base
{
    public function index()
    {
        $current_page = input('get.current_page',1,'intval');
        $limit = 10;
        $offset = ($current_page - 1) * $limit;
        $count = model('HistoryPay')->getAllHistory();
        $pages = ceil($count / $limit);
        $res = \model('HistoryPay')->getAllHistoryPay($limit,$offset);
        return $this->fetch('',[
            'res'=>$res,
            'current_page'  => $current_page,
            'pages'=>$pages,

        ]);
    }

    public function add()
    {
        return $this->fetch('history_pay/add');
    }

    public function save()
    {

        if(!request()->isPost()){
            $this->error('请求方式错误!');

        }
        //获取提交的数据
        $param = input('post.');

        $data = [
            'member_id'=>$param['member_id'],
            'order_no'=>$param['order_no'],
            'assessment'=>$param['assessment'],
            'stop_time'=>date('Y-m-d H:i:s',time())
        ];


        $res = Db::table('yp_history_pay')->insert($data);
        if($res){
//            $this->success("新增成功");
            return redirect('HistoryPay/index');
        }else{
            $this->error('新增失败');
        }
    }

    public function edit()
    {
        $id = input('get.id');
        $res = Db::table('yp_history_pay')->where(['id'=>$id])->find();
        $this->assign('res',$res);
        return $this->fetch();
    }

    public function editSave()
    {
        $id = input('post.id');
        $param = input('post.');
        $data = [
            'order_no'=>$param['order_no'],
            'member_id'=>$param['member_id'],
            'assessment'=>$param['assessment'],
            'stop_time'=>date('Y-m-d H:i:s',time()),
        ];
        $res = Db::table('yp_history_pay')->where(['id'=>$id])->update($data);
        if ($res)
        {
            return redirect('history_pay/index');
        }else{
            $this->error('修改失败');
        }
    }

    public function remove()
    {
        $id = input('get.id');
        $res = Db::table('yp_history_pay')->where(['id'=>$id])->delete();
        if ($res)
        {
            return redirect('history_pay/index');
        }else{
            $this->error('删除数据失败');
        }
    }

}