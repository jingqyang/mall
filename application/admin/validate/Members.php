<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/9/22
 * Time: 12:18
 */

namespace app\admin\validate;


use think\Validate;

class Members extends Validate
{
//    protected $rul = [
//        ['telephone','require|number|min:11','手机号必须是数字|手机号不能少于11个字符|手机号不能多于11个字符'],
//        ['username','alphaNum|min:4|max:16', '用户名只能由英文或数字组成|用户名不能少于4个字符|用户名不能超过16个字符'],
//    ];
    protected $rule = [
        'telephone'  => 'require|number|min:11'
    ];
    protected $scene = [
        'register' => ['telephone','username']
    ];
}