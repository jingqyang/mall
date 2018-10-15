<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Validate;

class Members extends Base {

    //用户列表
    public function index1()
    {
        return $this->fetch();
    }
    public function index(){
        $create_time = input('get.create_time');
        $bis_id = input('get.bis_id',-1,'intval');

        $current_page = input('get.current_page',1,'intval');

        $limit = 10;
        $offset = ($current_page - 1) * $limit;
        $count = model('Members')->getAllMembersCount();

        $pages = ceil($count / $limit);
        $res = model('Members')->getAllMembers($limit,$offset,$create_time);

        //获取店铺信息
        $bis_res = Db::table('store_bis')->field('id as bis_id,bis_name')->where('status = 1')->select();

        return $this->fetch('',[
            'res'  => $res,
            'pages'  => $pages,
            'current_page'  => $current_page,
            'create_time'   => $create_time,
            'bis_id'  => $bis_id,
            'bis_res'  => $bis_res,

        ]);
    }

    //详情页面
    public function detail(){
        //获取数据
        $id = input('get.mem_id');
        //设置查询条件
        $where['m.id'] = $id;
        $res = Db::table('store_members')->alias('m')->field('')
                ->join('store_province p','m.province_id = p.id','LEFT')
                ->join('store_city c','m.city_id = c.id','LEFT')
                ->where($where)
                ->find();
        return $this->fetch('',[
            'res'   =>  $res
        ]);
    }

    public function add()
    {
        $bis_res = Db::table('store_bis')->field('id as bis_id,bis_name')->where('status = 1')->select();
        return $this->fetch('',[
            'bis_res'  => $bis_res
        ]);
    }




    //添加会员
    public function save()
    {

        if (!request()->isPost()) {
            $this->error('请求方式错误!');
        }

        //获取提交的数据
        $param = input('post.');
        $bis_id = input('post.bis_id', 0, 'intval');


        $file = request()->file('code_url');
            //上传图片相关
            $dat = [];
            //上传文件
            $file = request()->file('code_url');

            //移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH.'public'.DS.'images');
            //判断是否上传
            if ($info) {
                // 成功上传后 获取上传信息
                $dat['code_url'] = $info->getSaveName();

            } else {
                //上传失败获取错误信息
                echo $info->getError();
            }
            //设置添加到数据库的数据
            $data = [
                'bis_id' => 1,
//                'mem_id' => 1,
                'telephone' => $param['telephone'],
                'truename' => $param['truename'],
                'id_number' => $param['id_number'],
                'age' => $param['age'],
//                'code_url' => 'https://yp.dxshuju.com/app/public/images/member/'.str_replace('\\','/',$dat['code_url']),
                'code_url' => $dat['code_url'],
                'create_time' => date('Y-m-d H:i:s'),
                'province_id'=>'北京',
                'city_id'=>'海淀区',
                'address'=>'八家嘉园'
            ];

            $dat = [
                'telephone'=>$data['telephone'],
                'community_id'=>1,
            ];
            Db::table('yp_history_pay')->insert($dat);
            Db::table('yp_current_pay')->insert($dat);
            Db::table('yp_address')->insert($dat);


//        $rules = [
//            'telephone'=>'require|number|length:11',
//        ];
//
//        $validate = new Validate($rules);
//        $re = $validate->check($data);


//        $validate = validate('Members');
//        if (!$validate->check($data))
//        {
//            $this->error($validate->getError());
//        }


            $res = Db::table('yp_member')->insert($data);


            if ($res) {
//            $this->success("新增成功");
                return redirect('admin/members/index');
//            $this->success("新增成功");

            } else {
                $this->error('新增失败');
            }

    }

    public function edit()
    {
        $id = input('get.id');

        $res = Db::table('yp_member')->where(['id'=>$id])->find();

        $this->assign('res',$res);
        return $this->fetch();
    }

    //修改会员数据
    public function editSave()
    {
        //获取参数
        $id = input('post.id');

        $param = input('post.');

        //设置添加到数据库的数据


        $data = [
            'truename' => $param['truename'],
            'email' => $param['email'],
            'qq' => $param['qq'],
            'last_login_time' => date('Y-m-d H:i:s', time())
        ];


        //修改图片信息
        //上传文件
        $file = request()->file('code_url');
        if (empty($file)) {
            unset($data['code_url']);
        } else {
            //// 移动到框架应用根目录/public/uploads/ 目录下
//            $info = $file->move(ROOT_PATH . 'public' . DS . 'images', 'members');
//            $info = $file->move('https://yp.dxshuju.com/api/public/image/');
            $info = $file->move(ROOT_PATH.'public'.DS.'images');

//            $info = $file->move()

            //判断是否上传
            if ($info) {
                // 成功上传后 获取上传信息
                $data['code_url'] = $info->getSaveName();
//                $tu = 'https://yp.dxshuju.com/app/public/images/member/' . str_replace('\\', '/', $data['code_url']);

            } else {
                //上传失败获取错误信息
                echo $info->getError();
                exit();
            }
            $tu = $data['code_url'];

//            $tu = 'https://yp.dxshuju.com/app/public/images/member/' . str_replace('\\', '/', $data['code_url']);
            $data['code_url'] = $tu;
        }
        $where = ['id'=>$id];
        $re = Db::table('yp_member')->where($where)->update($data);

        if ($re)
        {
            return redirect('admin/Members/index');
        }else{
            $this->error('修改失败');
        }

    }

    public function remove()
    {
        $id = input('get.id');
        $res = Db::table('yp_member')->where(['id'=>$id])->delete();
        if ($res)
        {
            return redirect('members/index');
        }else{
            $this->error('删除数据失败');
        }




    }
}
