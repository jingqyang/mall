<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/30
 * Time: 15:31
 */

namespace app\admin\controller;


use think\Db;

class Stage extends Base
{

    //展示
    public function index()
    {

        $current_page = input('get.current_page',1,'intval');
        $limit = 5;
        $offset = ($current_page - 1) * $limit;
        $count = model('Stage')->getAllStageCount();
        $pages = ceil($count / $limit);
        $res = \model('Stage')->getAllStage($offset,$limit);
        return $this->fetch('',[
            'res'=>$res,
            'pages'=>$pages,
            'current_page'  => $current_page,
        ]);
    }
    //添加
    public function add()
    {
        return $this->fetch('stage/add');
    }
    //添加纪录
    public function save()
    {
        if(!request()->isPost()){
            $this->error('请求方式错误!');
        }
        //获取提交的数据
        $phone = input('post.phone');



        $fen = Db::table('yp_member')->field('telephone,truename')->where(['telephone'=>$phone])->select();
        if (!$fen)
        {
            $data = [
                'phone'=>$phone,
                'start_time'=>date('Y-m-d H:i:s',time())
            ];
            $res = Db::table('yp_stage')->insert($data);
            if($res){
//            $this->success("新增成功");
                return redirect('stage/index');
            }else{
                $this->error('新增失败');
            }
        }else{
            $sh = [];
            foreach ($fen as $v)
            {
                $fen['telephone'] = $v['telephone'];
                $fen['truename'] = $v['truename'];
            }
            array_push($sh,$fen['telephone'],$fen['truename']);
            $shu = [
                'phone'=>$phone,
                'telephone'=>$sh[0],
                'turename'=>$sh[1],
                'start_time'=>date('Y-m-d H:i:s',time())
            ];
           $res =  Db::table('yp_stage')->insert($shu);
            if($res){
//            $this->success("新增成功");
                return redirect('stage/index');
            }else{
                $this->error('新增失败');
            }
        }




    }

    //修改
    public function edit()
    {
        $id = input('get.id');
        $res = Db::table('yp_stage')->where(['id'=>$id])->find();
        $this->assign('res',$res);
        return $this->fetch();
    }
    //修改操作
    public function editSave()
    {
        $id = input('post.id');
        $param = input('post.');
        $data = [
            'phone'=>$param['phone'],
            'stop_time'=>date('Y-m-d H:i:s',time())
        ];
        $res = Db::table('yp_stage')->where(['id'=>$id])->update($data);
        if ($res)
        {
            return redirect('stage/index');
        }else{
            $this->error('修改失败');
        }
    }
    //删除
    public function remove()
    {
        $id = input('get.id');
        $res = Db::table('yp_stage')->where(['id'=>$id])->delete();
        if ($res)
        {
            return redirect('stage/index');
        }else{
            $this->error('删除已缴数据失败');
        }
    }


}