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
 * 视频验证器
 */
class Video extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        'name'          => 'require',
        'category_id'   => 'require',
    ];

    // 验证提示
    protected $message  =   [
        'name.require'         => '视频名称不能为空',
        'category_id.require'  => '视频分类必须选择',
    ];
    
    // 应用场景
    protected $scene = [
        'edit'  =>  ['name', 'category_id']
    ];
}
