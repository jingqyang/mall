<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/28
 * Time: 23:30
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class Provinces extends Model
{
    public function index()
    {
        $a = Db::table('yp_provinces')->where(['provinceid'=>110000])->select();
        var_dump($a);
    }
}