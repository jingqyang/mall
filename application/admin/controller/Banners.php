<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/10/11
 * Time: 17:57
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;
use think\Image;

class Banners extends Controller
{
    //分期banner列表
    public function index(){
        $current_page = input('get.current_page',1,'intval');
        $bis_id = input('get.bis_id',0,'intval');
        $limit = 10;
        $offset = ($current_page - 1) * $limit;
        //获取总数
        $count = model('Banners')->getAllRecommendsCount($bis_id);
        $pages = ceil($count / $limit);
        $rec_res = model('Banners')->getAllRecommends($bis_id,$limit,$offset);
        //获取店铺信息
        $bis_res = Db::table('store_bis')->field('id as bis_id,bis_name')->where('status = 1')->select();
        return $this->fetch('',[
            'rec_res'  => $rec_res,
            'pages'  => $pages,
            'current_page'  => $current_page,
            'bis_id'  => $bis_id,
            'bis_res'  => $bis_res,
        ]);
    }


    //添加推荐位页面
//    public function add(){
//        //获取店铺信息
//        $bis_res = Db::table('store_bis')->field('id as bis_id,bis_name')->where('status = 1')->select();
//        return $this->fetch('',[
//            'bis_res'  => $bis_res
//        ]);
//    }

    //添加平台推荐位页面
    public function add(){
        return $this->fetch();
    }

    //编辑推荐位
    public function edit(){
        //获取参数
        $id = input('get.id');
        //获取该商品信息
        $rec_res = model('Banners')->getRecInfoById($id);

        return $this->fetch('',[
            'rec_res'  => $rec_res
        ]);
    }

    //编辑平台推荐位
    public function home_edit(){
        //获取参数
        $id = input('get.id');
        //获取该商品信息
        $rec_res = model('Recommend')->getHomeRecInfoById($id);

        return $this->fetch('',[
            'rec_res'  => $rec_res
        ]);
    }


    //添加平台推荐位
    public function save(){
        if(!request()->isPost()){
            $this->error('请求方式错误!');
        }
        //获取提交的数据
        $param = input('post.');

        //验证数据
        $validate = validate('Recommend');
        if(!$validate->scene('add1')->check($param)){
            $this->error($validate->getError());
        }

        //上传图片相关





//
//        $image = new Image();
//        $image_error = $_FILES['image']['error'];
//
//
//        if($image_error == 0){
//            $image_data = $image->uploadS('image','recommend');
//            $image_data = str_replace("\\", "/", $image_data);
//        }
        $recommend_data = [];
        $file = request()->file('image');

        //移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH.'public'.DS.'img');


        //判断是否上传
        if ($info){
            // 成功上传后 获取上传信息
            $recommend_data['image'] = $info->getSaveName();

        }else{
            //上传失败获取错误信息
            echo $info->getError();
        }
        $tu = str_replace('\\','/',$recommend_data['image']);

        //设置添加到数据库的数据
        $recommend_data = [
            'bis_id'=>1,
            'image' => $tu,
            'rout_ios' => $param['rout_ios'],
            'rout_android' => $param['rout_android'],
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s')
        ];
        //上传文件


        //普通数据添加到商品表中
        $r_res = Db::table('yp_banners')->insert($recommend_data);
        if($r_res){
            $this->success("新增成功",'banners/index');
        }else{
            $this->error('新增失败');
        }
    }

    //修改推荐位信息
    public function update(){
        if(!request()->isPost()){
            $this->error('请求方式错误!');
        }
        //获取提交的数据
        $param = input('post.');
        //验证数据
        $validate = validate('Recommend');
        if(!$validate->scene('update')->check($param)){
            $this->error($validate->getError());
        }
        $data = [];
        $file = request()->file('image');
        if (empty($file)){
            unset($data['image']);
        }else{
            //// 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH.'public'.DS.'img');

            //判断是否上传
            if ($info){
                // 成功上传后 获取上传信息
                $data['image'] = $info->getSaveName();
                $tu = 'http://ypadmin.dxshuju.com/img/'.str_replace('\\','/',$data['image']);

            }else{
                //上传失败获取错误信息
                echo $info->getError();
            }
            $tu = 'http://ypadmin.dxshuju.com/img/'.str_replace('\\','/',$data['image']);


        }


        //设置更新数据
        $data['rout_ios'] = $param['rout_ios'];
        $data['rout_android'] = $param['rout_android'];
        $data['update_time'] = date('Y-m-d H:i:s');

        //更新数据
        Db::table('yp_banners')->where('id = '.$param['id'])->update($data);

        $this->success("修改成功",'banners/index');
    }





    public function remove()
    {
        $id = input('get.id');
        $res = Db::table('yp_banners')->where(['id'=>$id])->delete();
        if ($res)
        {
            return redirect('banners/index');
        }else{
            $this->error('删除数据失败');
        }
    }

}