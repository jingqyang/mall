<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/28
 * Time: 23:30
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;

class Provinces extends Controller
{
    public function index()
    {
//        $province = Db::table('yp_provinces')->where(['provinceid'=>110000])->select();
//        $this->assign('province',$province);
//        $this->display();
        $this->fetch();
//$this->display();

    }

    public function getRegion()
    {
        $region[] = Db::table('yp_areas')->select();
        foreach ($region as $k=>$v)
        {
            $rea[] = $v[$k]['areaid'];
            $rea[] = $v[$k]['area'];
        }
        echo json_encode($rea);
    }

}