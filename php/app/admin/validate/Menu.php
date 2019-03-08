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

namespace app\admin\validate;

/**
 * 菜单验证器
 */
class Menu extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'name'  => 'require',
        'sort'  => 'require|number',
        'url'   => 'require|unique:menu'
    ];

    // 验证提示
    protected $message  =   [
        
        'name.require'    => '菜单不能为空',
        'sort.require'    => '排序值不能为空',
        'url.require'     => 'url不能为空',
        'url.unique'      => 'url已存在',
        'sort.number'     => '排序值必须为数字',
    ];

    // 应用场景
    protected $scene = [
        
        'add'  =>  ['name', 'sort', 'url'],
        'edit' =>  ['name', 'sort', 'url'],
    ];
    
}
