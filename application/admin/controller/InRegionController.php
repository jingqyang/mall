<?php
namespace app\admin\Controller;


use Home\Model\InRegionModel;
use Think\Controller;

class InRegionController extends Controller
{
    protected $model;
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
//        $one = $this->one();
//        $two = $this->two();

//        $this->assign('one',$one);
//        $this->assign('two',$two);
        $this->display('one');
    }

    public function one()
    {
        $one = M('ln_region')->where("parent_id=1")->select();
//        var_dump($one);
//        echo json_encode($one);
        $this->ajaxReturn($one,'json');
    }

    public function two()
    {
        $two = M('ln_region')->where("parent_id=".$_POST['id'])->select();
        $this->ajaxReturn($two,'json');
    }

    public function three()
    {
        $three = M('ln_region')->where('parent_id='.$_POST['id'])->select();
        $this->ajaxReturn($three,'json');
    }
}