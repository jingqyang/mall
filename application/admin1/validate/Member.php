<?php
/**
 * Created by PhpStorm.
 * User: ls19980819
 * Date: 2018/8/16
 * Time: 13:58
 */

namespace app\admin\validate;


class Member
{
    //规则设置
    protected $rule = [
        ['username','alphaNum|min:4|max:16', '用户名只能由英文或数字组成|用户名不能少于4个字符|用户名不能超过16个字符'],
        ['password','alphaNum|min:6|max:20','密码只能由英文和数字组成|密码不能少于6个字符|密码不能超过20个字符'],
    ];

}