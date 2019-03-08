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

namespace app\api\controller;

use app\common\controller\ControllerBase;
use think\Hook;

/**
 * 接口基类控制器
 */
class ApiBase extends ControllerBase
{
    
    /**
     * 基类初始化
     */
    public function __construct()
    {
        
        parent::__construct();
        
        $this->logicApiBase->checkParam($this->param);
        
        // 接口控制器钩子
        Hook::listen('hook_controller_api_base', $this->request);
        
        debug('api_begin');
    }
    
    /**
     * API返回数据
     */
    public function apiReturn($code_data = [], $return_data = [], $return_type = 'json')
    {
        
        debug('api_end');
        
        $result = $this->logicApiBase->apiReturn($code_data, $return_data, $return_type);
        
        return $result;
    }
}
