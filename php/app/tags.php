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

// 行为目录路径
define('BEHAVIOR_PATH', 'app\\common\\behavior\\');

$data = [
    // 模块初始化
    'module_init'  => [],
    // 操作开始执行
    'action_begin' => [],
    // 视图内容过滤
    'view_filter'  => [],
    // 日志写入
    'log_write'    => [],
];

if (defined('BIND_MODULE') && BIND_MODULE == 'install') {
    
    return $data;
}
    
$data['app_init']   = [BEHAVIOR_PATH . 'InitBase', BEHAVIOR_PATH . 'InitHook'];

return $data;
