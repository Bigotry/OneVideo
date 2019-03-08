<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\common\validate;

/**
 * 会员验证器
 */
class Member extends ValidateBase
{
    
    // 验证规则
    protected $rule = [
        'username'  => 'require|length:6,30|unique:member',
        'password'  => 'require|length:6,30',
        'email'     => 'require|email|unique:member',
        'mobile'    => 'require|mobile|unique:member',
    ];

    // 验证提示
    protected $message = [
        'username.require'    => '用户名不能为空',
        'username.length'     => '用户名长度为6-30个字符之间',
        'username.unique'     => '用户名已存在',
        'password.require'    => '密码不能为空',
        'password.length'     => '密码长度为6-30个字符之间',
        'email.require'       => '邮箱不能为空',    
        'email.email'         => '邮箱格式不正确', 
        'email.unique'        => '邮箱已存在', 
        'mobile.require'      => '手机号码不能为空',    
        'mobile.unique'       => '手机号码已存在', 
    ];

    // 应用场景
    protected $scene = [
        'add'  =>  ['username','password','email'],
    ];
}
