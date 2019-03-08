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
 * 配置验证器
 */
class Config extends ValidateBase
{
    
    // 验证规则
    protected $rule =   [
        'name'          => 'require|unique:config',
        'title'         => 'require',
    ];

    // 验证提示
    protected $message  =   [
        'name.require'         => '标识不能为空',
        'name.unique'          => '标识已经存在',
        'title.require'        => '名称不能为空',
    ];
}
