<?php
namespace app\admin\controller;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use think\Controller;
use app\api\controller\Image;
use think\Request;
use think\Url;
use think\Validate;
use think\Db;

class Product extends Base {

    //商品列表
    public function index(){
        $date_from = input('get.date_from');
        $date_to = input('get.date_to');
        $pro_name = input('get.pro_name');
        $bis_id = input('get.bis_id',0,'intval');

        $current_page = input('get.current_page',1,'intval');
        $limit = 10;
        $offset = ($current_page - 1) * $limit;
        $pro_count = model('Products')->getAllProductCount($bis_id,$date_from,$date_to,$pro_name);
        //总页码
        $pages = ceil($pro_count / $limit);

        $pro_res = model('Products')->getAllProducts($bis_id,$limit, $offset,$date_from,$date_to,$pro_name);

        //获取该店家所传商品规格维数
        $config_type_res = Db::table('store_bis')->field('config_type')->where('id = '.$bis_id)->find();
        //获取店铺信息
        $bis_res = Db::table('store_bis')->field('id as bis_id,bis_name')->where('status = 1')->select();
        return $this->fetch('',[
            'pro_res'  => $pro_res,
            'pages'  => $pages,
            'count'  => $pro_count,
            'current_page'  => $current_page,
            'date_from'  => $date_from,
            'date_to'  => $date_to,
            'pro_name'  => $pro_name,
            'config_type'  => $config_type_res['config_type'],
            'bis_id'  => $bis_id,
            'bis_res'  => $bis_res,
        ]);
    }

    //积分商品列表
    public function jf_index(){
        $bis_id = input('get.bis_id',0,'intval');
        $date_from = input('get.date_from');
        $date_to = input('get.date_to');
        $pro_name = input('get.pro_name');

        $current_page = input('get.current_page',1,'intval');
        $limit = 10;
        $offset = ($current_page - 1) * $limit;
        $pro_count = model('Products')->getAllJfProductCount($bis_id,$date_from,$date_to,$pro_name);
        //总页码
        $pages = ceil($pro_count / $limit);

        $pro_res = model('Products')->getAllJfProducts($bis_id,$limit, $offset,$date_from,$date_to,$pro_name);
        //获取店铺信息
        $bis_res = Db::table('store_bis')->field('id as bis_id,bis_name')->where('status = 1')->select();
        return $this->fetch('',[
            'pro_res'  => $pro_res,
            'pages'  => $pages,
            'count'  => $pro_count,
            'current_page'  => $current_page,
            'date_from'  => $date_from,
            'date_to'  => $date_to,
            'pro_name'  => $pro_name,
            'bis_res'  => $bis_res,
            'bis_id'  => $bis_id,
        ]);
    }

    //编辑商品(一维规格)
    public function edit1(){

        //获取参数
        $id = input('get.id');
        //获取店铺id
        $bis_res = Db::table('store_products')->field('bis_id')->where('id = '.$id)->find();
        $bis_id = $bis_res['bis_id'];
        //获取该商品信息
        $pro_res = model('Products')->getProInfoById($id);
//        echo '<pre>';
//        var_dump($pro_res);

        $first_category = model('Category')->getNormalFirstCategory();
        $defined_category = model('DefinedCategory')->getNormalFirstCategory($bis_id);
        $brand = model('Brand')->getNormalBrands($bis_id);
        $pro_config = model('Products')->getConfigInfo($id);

        $pro_config_count = count($pro_config);
        //判断该店家是否开启拼团
        $pintuan_res = Db::table('store_bis')->field('is_pintuan')->where('id = '.$bis_id)->find();
        return $this->fetch('',[
            'pro_res'  => $pro_res,
            'first_category'  => $first_category,
            'defined_category'  => $defined_category,
            'brand'  => $brand,
            'pro_config'  => $pro_config,
            'pro_config_count'  => $pro_config_count,
            'pro_id'  => $id,
            'is_pintuan'  => $pintuan_res['is_pintuan']
        ]);
    }

    //编辑商品(二维规格)
    public function edit2(){
        //获取参数
        $id = input('get.id');
        //获取店铺id
        $bis_res = Db::table('store_products')->field('bis_id')->where('id = '.$id)->find();
        $bis_id = $bis_res['bis_id'];
        //获取该商品信息
        $pro_res = model('Products')->getProInfoById($id);
        $first_category = model('Category')->getNormalFirstCategory();
        $defined_category = model('DefinedCategory')->getNormalFirstCategory($bis_id);
        $brand = model('Brand')->getNormalBrands($bis_id);
        $pro_config = model('Products')->getConfigInfo($id);
        $pro_config_count = count($pro_config);
        //判断该店家是否开启拼团
        $pintuan_res = Db::table('store_bis')->field('is_pintuan')->where('id = '.$bis_id)->find();
        return $this->fetch('',[
            'pro_res'  => $pro_res,
            'first_category'  => $first_category,
            'defined_category'  => $defined_category,
            'brand'  => $brand,
            'pro_config'  => $pro_config,
            'pro_config_count'  => $pro_config_count,
            'pro_id'  => $id,
            'is_pintuan'  => $pintuan_res['is_pintuan']
        ]);
    }

    //编辑积分商品
    public function jf_edit(){
        //获取参数
        $id = input('get.id');
        //获取店铺id
        $bis_res = Db::table('store_products')->field('bis_id')->where('id = '.$id)->find();
        $bis_id = $bis_res['bis_id'];
        //获取该商品信息
        $pro_res = model('Products')->getProInfoById($id);
        $first_category = model('Category')->getNormalFirstCategory();
        $defined_category = model('DefinedCategory')->getNormalFirstCategory($bis_id);
        $brand = model('Brand')->getNormalBrands($bis_id);
        $pro_config = model('Products')->getConfigInfo($id);
        $pro_config_count = count($pro_config);

        return $this->fetch('',[
            'pro_res'  => $pro_res,
            'first_category'  => $first_category,
            'defined_category'  => $defined_category,
            'brand'  => $brand,
            'pro_config'  => $pro_config,
            'pro_config_count'  => $pro_config_count,
            'pro_id'  => $id

        ]);
    }

    //更改状态
    public function updateStatus(){
        //获取参数
        $id = input('get.id');
        $status = input('get.status');
        $data['status'] = $status;
        $res = Db::table('store_products')->where('id = '.$id)->update($data);

        if($res){
            $this->success('更新状态成功!');
        }else{
            $this->error('更新状态失败!');
        }
    }

    //更改上架状态
    public function updateSaleStatus(){
        //获取参数
        $id = input('get.id');
        $on_sale = input('get.on_sale');
        $data['on_sale'] = $on_sale;
        $res = Db::table('store_products')->where('id = '.$id)->update($data);

        if($res){
            if($on_sale == 1){
                $this->success('上架成功!');
            }else{
                $this->success('下架成功!');
            }
        }else{
            $this->error('更新状态失败!');
        }
    }


    //更改推荐状态
    public function updateRecommendStatus(){
        //获取参数
        $id = input('get.id');
        $is_recommend = input('get.is_recommend');
        $data['is_recommend'] = $is_recommend;
        $data['update_time'] = date('Y-m-d H:i:s');
        $res = Db::table('store_products')->where('id = '.$id)->update($data);

        if($res){
            if($is_recommend == 1){
                $this->success('设置推荐成功!');
            }else{
                $this->success('取消推荐成功!');
            }
        }else{
            $this->error('更新状态失败!');
        }
    }

    //修改商品
    public function updateProduct(){
        if(!request()->isPost()){
            $this->error('请求方式错误!');
        }
        //获取提交的数据
        $param = input('post.');

        //验证数据
        $validate = validate('Product');
        if(!$validate->scene('update')->check($param)){
            $this->error($validate->getError());
        }

        //上传图片相关
        $image = new Image();

        //设置图片

        $images_data = [];






        if($_FILES['config_image1']['error'] == 0){
            $images_data['config_image1'] = $image->uploadS('config_image1','product');
            $images_data['config_image1'] = str_replace("\\", "/", $images_data['config_image1']);
        }
        if($_FILES['config_image2']['error'] == 0){
            $images_data['config_image2'] = $image->uploadS('config_image2','product');
            $images_data['config_image2'] = str_replace("\\", "/", $images_data['config_image2']);
        }
        if($_FILES['config_image3']['error'] == 0){
            $images_data['config_image3'] = $image->uploadS('config_image3','product');
            $images_data['config_image3'] = str_replace("\\", "/", $images_data['config_image3']);
        }
        if($_FILES['config_image4']['error'] == 0){
            $images_data['config_image4'] = $image->uploadS('config_image4','product');
            $images_data['config_image4'] = str_replace("\\", "/", $images_data['config_image4']);
        }
        if($_FILES['wx_config_image1']['error'] == 0){
            $images_data['wx_config_image1'] = $image->uploadS('wx_config_image1','product');
            $images_data['wx_config_image1'] = str_replace("\\", "/", $images_data['wx_config_image1']);
        }
        if($_FILES['wx_config_image2']['error'] == 0){
            $images_data['wx_config_image2'] = $image->uploadS('wx_config_image2','product');
            $images_data['wx_config_image2'] = str_replace("\\", "/", $images_data['wx_config_image2']);
        }
        if($_FILES['wx_config_image3']['error'] == 0){
            $images_data['wx_config_image3'] = $image->uploadS('wx_config_image3','product');
            $images_data['wx_config_image3'] = str_replace("\\", "/", $images_data['wx_config_image3']);
        }
        if($_FILES['wx_config_image4']['error'] == 0){
            $images_data['wx_config_image4'] = $image->uploadS('wx_config_image4','product');
            $images_data['wx_config_image4'] = str_replace("\\", "/", $images_data['wx_config_image4']);
        }
        if($_FILES['wx_config_image5']['error'] == 0){
            $images_data['wx_config_image5'] = $image->uploadS('wx_config_image5','product');
            $images_data['wx_config_image5'] = str_replace("\\", "/", $images_data['wx_config_image5']);
        }
        if($_FILES['wx_config_image6']['error'] == 0){
            $images_data['wx_config_image6'] = $image->uploadS('wx_config_image6','product');
            $images_data['wx_config_image6'] = str_replace("\\", "/", $images_data['wx_config_image6']);
        }
        if($_FILES['wx_config_image7']['error'] == 0){
            $images_data['wx_config_image7'] = $image->uploadS('wx_config_image7','product');
            $images_data['wx_config_image7'] = str_replace("\\", "/", $images_data['wx_config_image7']);
        }
        if($_FILES['wx_config_image8']['error'] == 0){
            $images_data['wx_config_image8'] = $image->uploadS('wx_config_image8','product');
            $images_data['wx_config_image8'] = str_replace("\\", "/", $images_data['wx_config_image8']);
        }
        if($_FILES['wx_config_image9']['error'] == 0){
            $images_data['wx_config_image9'] = $image->uploadS('wx_config_image9','product');
            $images_data['wx_config_image9'] = str_replace("\\", "/", $images_data['wx_config_image9']);
        }
        if($_FILES['wx_config_image10']['error'] == 0){
            $images_data['wx_config_image10'] = $image->uploadS('wx_config_image10','product');
            $images_data['wx_config_image10'] = str_replace("\\", "/", $images_data['wx_config_image10']);
        }
        if($_FILES['image']['error'] == 0){
            $images_data['image'] = $image->uploadS('image','product');
            $images_data['image'] = str_replace("\\", "/", $images_data['image']);
        }
        if($_FILES['thumb']['error'] == 0){
            $images_data['thumb'] = $image->uploadS('thumb','product');
            $images_data['thumb'] = str_replace("\\", "/", $images_data['thumb']);
        }

        //设置更新的数据
        $product_data = [
            'cat1_id' => $param['cat1_id'],
            'cat2_id' => $param['cat2_id'],
            'cat3_id' => '',
            'defined_cat1_id' => $param['d_cat1_id'],
            'defined_cat2_id' => $param['d_cat2_id'],
            'p_name' => $param['p_name'],
            'brand' => $param['brand'],
            'unit' => $param['unit'],
            'producing_area' => $param['producing_area'],
            'original_price' => $param['original_price'],
            'associator_discount' => $param['associator_discount'],
            'associator_price' => $param['associator_price'],
            'pintuan_price' => !empty($param['pintuan_price']) ? $param['pintuan_price'] : $param['associator_price'],
//            'vip_discount' => $param['vip_discount'],
//            'vip_price' => $param['vip_price'],
//            'vvip_discount' => $param['vvip_discount'],
//            'vvip_price' => $param['vvip_price'],
            'listorder' => $param['listorder'],
            'weight' => $param['weight'],
            'huohao' => $param['huohao'],
            'rate' => $param['rate'],
            'jifen' => $param['jifen'],
            'rec_rate' => $param['rec_rate'] / 100,
            'pintuan_count' => $param['pintuan_count'] ? $param['pintuan_count'] : 2,
            'keywords' => $param['keywords'],
            'wx_introduce' => $param['wx_description'],
//            'nature' => $param['nature'],
            'update_time' => date('Y-m-d H:i:s')
        ];

        //更新商品表
        $p_res = model('Products')->where('id = '.$param['pro_id'])->update($product_data);

        $images_data['update_time'] = date('Y-m-d H:i:s');
        //更新图片表
        $image_res = Db::table('store_pro_images')->where('p_id = '.$param['pro_id'])->update($images_data);


        $this->success("修改成功!");
    }

    //修改积分商品
    public function jf_updateProduct(){
        if(!request()->isPost()){
            $this->error('请求方式错误!');
        }
        //获取提交的数据
        $param = input('post.');

        //验证数据
        $validate = validate('Product');
        if(!$validate->scene('update')->check($param)){
            $this->error($validate->getError());
        }

        //上传图片相关
        $image = new Image();

        //设置图片
        if($_FILES['config_image1']['error'] == 0){
            $images_data['config_image1'] = $image->uploadS('config_image1','product');
            $images_data['config_image1'] = str_replace("\\", "/", $images_data['config_image1']);
        }
        if($_FILES['config_image2']['error'] == 0){
            $images_data['config_image2'] = $image->uploadS('config_image2','product');
            $images_data['config_image2'] = str_replace("\\", "/", $images_data['config_image2']);
        }
        if($_FILES['config_image3']['error'] == 0){
            $images_data['config_image3'] = $image->uploadS('config_image3','product');
            $images_data['config_image3'] = str_replace("\\", "/", $images_data['config_image3']);
        }
        if($_FILES['config_image4']['error'] == 0){
            $images_data['config_image4'] = $image->uploadS('config_image4','product');
            $images_data['config_image4'] = str_replace("\\", "/", $images_data['config_image4']);
        }
        if($_FILES['wx_config_image1']['error'] == 0){
            $images_data['wx_config_image1'] = $image->uploadS('wx_config_image1','product');
            $images_data['wx_config_image1'] = str_replace("\\", "/", $images_data['wx_config_image1']);
        }
        if($_FILES['wx_config_image2']['error'] == 0){
            $images_data['wx_config_image2'] = $image->uploadS('wx_config_image2','product');
            $images_data['wx_config_image2'] = str_replace("\\", "/", $images_data['wx_config_image2']);
        }
        if($_FILES['wx_config_image3']['error'] == 0){
            $images_data['wx_config_image3'] = $image->uploadS('wx_config_image3','product');
            $images_data['wx_config_image3'] = str_replace("\\", "/", $images_data['wx_config_image3']);
        }
        if($_FILES['wx_config_image4']['error'] == 0){
            $images_data['wx_config_image4'] = $image->uploadS('wx_config_image4','product');
            $images_data['wx_config_image4'] = str_replace("\\", "/", $images_data['wx_config_image4']);
        }
        if($_FILES['wx_config_image5']['error'] == 0){
            $images_data['wx_config_image5'] = $image->uploadS('wx_config_image5','product');
            $images_data['wx_config_image5'] = str_replace("\\", "/", $images_data['wx_config_image5']);
        }
        if($_FILES['wx_config_image6']['error'] == 0){
            $images_data['wx_config_image6'] = $image->uploadS('wx_config_image6','product');
            $images_data['wx_config_image6'] = str_replace("\\", "/", $images_data['wx_config_image6']);
        }
        if($_FILES['wx_config_image7']['error'] == 0){
            $images_data['wx_config_image7'] = $image->uploadS('wx_config_image7','product');
            $images_data['wx_config_image7'] = str_replace("\\", "/", $images_data['wx_config_image7']);
        }
        if($_FILES['wx_config_image8']['error'] == 0){
            $images_data['wx_config_image8'] = $image->uploadS('wx_config_image8','product');
            $images_data['wx_config_image8'] = str_replace("\\", "/", $images_data['wx_config_image8']);
        }
        if($_FILES['wx_config_image9']['error'] == 0){
            $images_data['wx_config_image9'] = $image->uploadS('wx_config_image9','product');
            $images_data['wx_config_image9'] = str_replace("\\", "/", $images_data['wx_config_image9']);
        }
        if($_FILES['wx_config_image10']['error'] == 0){
            $images_data['wx_config_image10'] = $image->uploadS('wx_config_image10','product');
            $images_data['wx_config_image10'] = str_replace("\\", "/", $images_data['wx_config_image10']);
        }
        if($_FILES['image']['error'] == 0){
            $images_data['image'] = $image->uploadS('image','product');
            $images_data['image'] = str_replace("\\", "/", $images_data['image']);
        }
        if($_FILES['thumb']['error'] == 0){
            $images_data['thumb'] = $image->uploadS('thumb','product');
            $images_data['thumb'] = str_replace("\\", "/", $images_data['thumb']);
        }

        //设置更新的数据
        $product_data = [
            'cat1_id' => $param['cat1_id'],
            'cat2_id' => $param['cat2_id'],
            'cat3_id' => '',
            'defined_cat1_id' => $param['d_cat1_id'],
            'defined_cat2_id' => $param['d_cat2_id'],
            'p_name' => $param['p_name'],
            'brand' => $param['brand'],
            'unit' => $param['unit'],
            'producing_area' => $param['producing_area'],
            'original_price' => $param['original_price'],
            'associator_discount' => $param['associator_discount'],
            'associator_price' => $param['associator_price'],
            'ex_jifen' => $param['ex_jifen'],
            'listorder' => $param['listorder'],
            'weight' => $param['weight'],
            'huohao' => $param['huohao'],
            'rate' => $param['rate'],
            'keywords' => $param['keywords'],
            'wx_introduce' => $param['wx_description'],
            'update_time' => date('Y-m-d H:i:s')
        ];

        //更新商品表
        $p_res = model('Products')->where('id = '.$param['pro_id'])->update($product_data);

        $images_data['update_time'] = date('Y-m-d H:i:s');

        //更新图片表
        $image_res = Db::table('store_pro_images')->where('p_id = '.$param['pro_id'])->update($images_data);


        $this->success("修改成功!");
    }

    //更新配置信息1
    public function updateConfigInfo1(){
        //获取参数
        $param = input('post.');

        $data = [
            'content1_name'   => $param['content1_name'],
            'con_content1'   => $param['con_content1'],
            'price'   => $param['unit_price'],
            'inventory'   => $param['inventory'],
            'group_price'   => !empty($param['group_price']) ? $param['group_price'] : $param['unit_price'],
        ];

        $res = Db::table('store_pro_config')->where('id = '.$param['config_id'])->update($data);
        if($res){
            return show(1,'success','edit1?id='.$param['pro_id']);
        }else{
            return show(0,'error');
        }
    }

    //更新配置信息2
    public function updateConfigInfo2(){
        //获取参数
        $param = input('post.');

        $data = [
            'content1_name'   => $param['content1_name'],
            'con_content1'   => $param['con_content1'],
            'content2_name'   => $param['content2_name'],
            'con_content2'   => $param['con_content2'],
            'price'   => $param['unit_price'],
            'inventory'   => $param['inventory'],
            'group_price'   => !empty($param['group_price']) ? $param['group_price'] : $param['unit_price'],
        ];

        $res = Db::table('store_pro_config')->where('id = '.$param['config_id'])->update($data);
        if($res){
            return show(1,'success','edit2?id='.$param['pro_id']);
        }else{
            return show(0,'error');
        }
    }

    //添加配置信息1
    public function addConfigInfo1(){
        //获取参数
        $param = input('post.');

        $data = [
            'pro_id'   => $param['pro_id'],
            'content1_name'   => $param['content1_name'],
            'con_content1'   => $param['con_content1'],
            'price'   => $param['unit_price'],
            'inventory'   => $param['inventory'],
            'group_price'   => !empty($param['group_price']) ? $param['group_price'] : $param['unit_price'],
        ];

        $res = Db::table('store_pro_config')->insert($data);
        if($res){
            return show(1,'success','edit1?id='.$param['pro_id']);
        }else{
            return show(0,'error');
        }
    }

    //添加配置信息2
    public function addConfigInfo2(){
        //获取参数
        $param = input('post.');

        $data = [
            'pro_id'   => $param['pro_id'],
            'content1_name'   => $param['content1_name'],
            'con_content1'   => $param['con_content1'],
            'content2_name'   => $param['content2_name'],
            'con_content2'   => $param['con_content2'],
            'price'   => $param['unit_price'],
            'inventory'   => $param['inventory'],
            'group_price'   => !empty($param['group_price']) ? $param['group_price'] : $param['unit_price'],
        ];

        $res = Db::table('store_pro_config')->insert($data);
        if($res){
            return show(1,'success','edit2?id='.$param['pro_id']);
        }else{
            return show(0,'error');
        }
    }

    //更新积分商品配置信息
    public function jf_updateConfigInfo(){
        //获取参数
        $param = input('post.');

        $data = [
            'content1_name'   => $param['content1_name'],
            'con_content1'   => $param['con_content1'],
            'price'   => $param['unit_price'],
            'inventory'   => $param['inventory'],
            'ex_jifen'   => $param['ex_jifen'],
        ];

        $res = Db::table('store_pro_config')->where('id = '.$param['config_id'])->update($data);
        if($res){
            return show(1,'success','jf_edit?id='.$param['pro_id']);
        }else{
            return show(0,'error');
        }
    }

    //添加积分商品配置信息
    public function jf_addConfigInfo(){
        //获取参数
        $param = input('post.');

        $data = [
            'pro_id'   => $param['pro_id'],
            'content1_name'   => $param['content1_name'],
            'con_content1'   => $param['con_content1'],
            'price'   => $param['unit_price'],
            'inventory'   => $param['inventory'],
            'ex_jifen'   => $param['ex_jifen'],
        ];

        $res = Db::table('store_pro_config')->insert($data);
        if($res){
            return show(1,'success','jf_edit?id='.$param['pro_id']);
        }else{
            return show(0,'error');
        }
    }

    //删除配置信息1
    public function removeConfigInfo1(){
        //获取参数
        $param = input('post.');

        $data = [
            'status'  => 0
        ];

        $res = Db::table('store_pro_config')->where('id = '.$param['config_id'])->update($data);
        if($res){
            return show(1,'success','edit1?id='.$param['pro_id']);
        }else{
            return show(0,'error');
        }
    }
    //删除配置信息2
    public function removeConfigInfo2(){
        //获取参数
        $param = input('post.');

        $data = [
            'status'  => 0
        ];

        $res = Db::table('store_pro_config')->where('id = '.$param['config_id'])->update($data);
        if($res){
            return show(1,'success','edit2?id='.$param['pro_id']);
        }else{
            return show(0,'error');
        }
    }

    //删除积分商品配置信息
    public function jf_removeConfigInfo(){
        //获取参数
        $param = input('post.');

        $data = [
            'status'  => 0
        ];

        $res = Db::table('store_pro_config')->where('id = '.$param['config_id'])->update($data);
        if($res){
            return show(1,'success','jf_edit?id='.$param['pro_id']);
        }else{
            return show(0,'error');
        }
    }

    //获取指定商品下的配置信息
    public function getConfigInfoByProid(){
        //获取参数
        $id = input('post.id');
        $pro_config = model('Products')->getConfigInfo($id);

        if($pro_config){
            return show(1,'success',$pro_config);
        }else{
            return show(0,'error');
        }


    }


    public function removes1()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('config_image1')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image1' => '']);
        $this->success('删除成功');
    }
    public function removes2()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('config_image2')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image2' => '']);
        $this->success('删除成功');
    }
    public function removes3()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('config_image3')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image3' => '']);
        $this->success('删除成功');
    }
    public function removes4()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('config_image4')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image4' => '']);
        $this->success('删除成功');
    }
    public function removes5()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('wx_config_image1')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image1' => '']);
        $this->success('删除成功');
    }

    public function removes6()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('wx_config_image2')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image2' => '']);
        $this->success('删除成功');
    }
    public function removes7()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('wx_config_image3')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image3' => '']);
        $this->success('删除成功');
    }
    public function removes8()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('wx_config_image4')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image4' => '']);
        $this->success('删除成功');
    }
    public function removes9()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('wx_config_image5')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image5' => '']);
        $this->success('删除成功');
    }
    public function removes10()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('wx_config_image6')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image6' => '']);
        $this->success('删除成功');
    }
    public function removes11()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('wx_config_image7')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image7' => '']);
        $this->success('删除成功');
    }
    public function removes12()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('wx_config_image8')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image8' => '']);
        $this->success('删除成功');
    }
    public function removes13()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        Db::table('store_pro_images')->field('wx_config_image9')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image9' => '']);
        $this->success('删除成功');
    }
    public function removes14()
    {
        $ids = Request::instance()->get('id/a');
        $id = (int)$ids['id'];
        $a = Db::table('store_pro_images')->field('wx_config_image10')->where(['p_id'=>$id])->select();
        Db::table('store_pro_images')->where(['p_id'=>$id])->update(['wx_config_image10' => '']);
        $this->success('删除成功');
    }



//    public function removes()
//    {
//
//        $ids = Request::instance()->get('id/a');
//        $id = (int)$ids['id'];
//
//
//        $res = Db::table('store_pro_images')
//            ->field('image,thumb,config_image1,config_image2,config_image3,config_image4,
//            wx_config_image1,wx_config_image2,wx_config_image3,wx_config_image4,wx_config_image5,
//            wx_config_image6,wx_config_image7,wx_config_image8,wx_config_image9,wx_config_image10
//            ')
//            ->where(['p_id'=>$id])
//            ->select();
//
//        foreach ($res as $k => $v){
//            $res[$k]['image'] = $v['image'];
//            $res[$k]['thumb'] = $v['thumb'];
//            $res[$k]['config_image1'] = $v['config_image1'];
//            $res[$k]['config_image2'] = $v['config_image2'];
//            $res[$k]['config_image3'] = $v['config_image3'];
//            $res[$k]['config_image4'] = $v['config_image4'];
//            $res[$k]['wx_config_image1'] = $v['wx_config_image1'];
//            $res[$k]['wx_config_image2'] = $v['wx_config_image2'];
//            $res[$k]['wx_config_image3'] = $v['wx_config_image3'];
//            $res[$k]['wx_config_image4'] = $v['wx_config_image4'];
//            $res[$k]['wx_config_image5'] = $v['wx_config_image5'];
//            $res[$k]['wx_config_image6'] = $v['wx_config_image6'];
//            $res[$k]['wx_config_image7'] = $v['wx_config_image7'];
//            $res[$k]['wx_config_image8'] = $v['wx_config_image8'];
//            $res[$k]['wx_config_image9'] = $v['wx_config_image9'];
//            $res[$k]['wx_config_image10'] = $v['wx_config_image10'];
//        }
////        echo '<pre>';
////        var_dump($res[$k]['config_image1'], $res[$k]['config_image2'], $res[$k]['config_image3'], $res[$k]['config_image4']);
////        exit();
////
//
//        if ( $res[$k]['config_image2'] = '' || $res[$k]['config_image2'] != '')
//        {
////            $a = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image2' => 'config_image2']);
////            $res = Db::table('store_pro_images')->where(['p_id'=>$id])->find();
//            echo '删除成功1';
//            echo '<pre>';
////            var_dump($res);
//            exit();
//
//        }elseif ($res[$k]['config_image3'] = '' || $res[$k]['config_image3'] != '')
//        {
////            $b = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image3' => 'config_image3']);
//            echo '删除成功2';
//            exit();
//
////            var_dump($b);
//        }elseif ($res[$k]['config_image4'] = '' || $res[$k]['config_image4'] != '')
//        {
////            $c = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image4' => 'config_image4']);
////            $c = Db::table('store_pro_images')->where(['p_id'=>$id])->select();
//            echo '删除成功4';
//            exit();
//
//            echo '</pre>';
////            var_dump($c);
//        }else{
//            echo '232165';
//            exit();
//        }
//
//
//
////        if ($res[$k]['config_image2'] = 'uploads/product.jpg' || $res[$k]['config_image2'] = '' || $res[$k]['config_image2'] != '')
////        {
//////            $a = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image2' => 'config_image2']);
//////            $res = Db::table('store_pro_images')->where(['p_id'=>$id])->find();
////            echo '删除成功1';
////            echo '<pre>';
//////            var_dump($res);
////            exit();
////
////        }elseif ($res[$k]['config_image3'] = 'uploads/product.jpg' || $res[$k]['config_image3'] = '' || $res[$k]['config_image3'] != '')
////        {
//////            $b = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image3' => 'config_image3']);
////            echo '删除成功2';
////            exit();
////
//////            var_dump($b);
////        }elseif ($res[$k]['config_image4'] = 'uploads/product.jpg' || $res[$k]['config_image4'] = '' || $res[$k]['config_image4'] != '')
////        {
//////            $c = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image4' => 'config_image4']);
//////            $c = Db::table('store_pro_images')->where(['p_id'=>$id])->select();
////            echo '删除成功4';
////            exit();
////
////            echo '</pre>';
//////            var_dump($c);
////        }else{
////            echo '232165';
////            exit();
////        }
//exit();
//        switch ($res)
//        {
//            case $res['config_image2'] = 'uploads/product.jpg' || $res['config_image2'] = '' || $res['config_image2'] != '':
//
//                $a = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image2' => 'config_image2']);
//                $res = Db::table('store_pro_images')->where(['p_id'=>$id])->find();
////                $a = Db::table('store_pro_images')->where(['p_id'=>$id])->select();
//            echo '删除成功';
//                echo '<pre>';
//                var_dump($res);
//                break;
//            case $res['config_image3'] = 'uploads/product.jpg' || $res['config_image3'] = '' || $res['config_image3'] != '':
//                $b = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image3' => 'config_image3']);
//                echo '删除成功';
//
//                var_dump($b);
//                break;
//            case $res['config_image4'] = 'uploads/product.jpg' || $res['config_image4'] = '' || $res['config_image4'] != '':
//
//                $c = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image4' => 'config_image4']);
//                $c = Db::table('store_pro_images')->where(['p_id'=>$id])->select();
//                echo '删除成功';
//
//                echo '</pre>';
//                var_dump($c);
//                break;
//            default:
//                echo "No number between 1 and 3";
//        }
//        exit();
//        if ($res['config_image2'] = 'uploads/product.jpg' || $res['config_image2'] = '')
//        {
//            $a = Db::table('store_pro_images')->where(['p_id'=>$id])->update(['config_image2' => '']);
//            var_dump($a);
//            exit();
//        }else{
//            echo 2;
//            exit();
//        }
//        $this->assign('res',$res);
//    }

    public function removeImage()
    {
        $id = input('post.id');

        $data = ['store_pro_images'];
        $res = Db::table('store_pro_images')->where(['id'=>$id])->update($data);
    }




}
