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

namespace app\common\controller;

use think\Controller;
use think\Hook;

/**
 * 系统通用控制器基类
 */
class ControllerBase extends Controller
{
    
    // 请求参数
    protected $param;
    
    /**
     * 基类初始化
     */
    public function _initialize()
    {
        
        // 初始化请求信息
        $this->initRequestInfo();
        
        // 初始化全局静态资源
        $this->initCommonStatic();
        
        // 初始化响应类型
        $this->initResponseType();
        
        // 通用控制器钩子
        Hook::listen('hook_controller_common_base', $this->request);
    }
    
    /**
     * 初始化请求信息
     */
    final private function initRequestInfo()
    {
        
        defined('IS_POST')          or define('IS_POST',         $this->request->isPost());
        defined('IS_GET')           or define('IS_GET',          $this->request->isGet());
        defined('IS_AJAX')          or define('IS_AJAX',         $this->request->isAjax());
        defined('IS_PJAX')          or define('IS_PJAX',         $this->request->isPjax());
        defined('IS_MOBILE')        or define('IS_MOBILE',       $this->request->isMobile());
        defined('MODULE_NAME')      or define('MODULE_NAME',     strtolower($this->request->module()));
        defined('CONTROLLER_NAME')  or define('CONTROLLER_NAME', strtolower($this->request->controller()));
        defined('ACTION_NAME')      or define('ACTION_NAME',     strtolower($this->request->action()));
        defined('URL')              or define('URL',             CONTROLLER_NAME . SYS_DS_PROS . ACTION_NAME);
        defined('URL_TRUE')         or define('URL_TRUE',        $this->request->url(true));
        defined('DOMAIN')           or define('DOMAIN',          $this->request->domain());
        defined('URL_ROOT')         or define('URL_ROOT',        $this->request->root());

        $this->param = $this->request->param();
    }
    
    /**
     * 初始化响应类型
     */
    final private function initResponseType()
    {
        
        IS_AJAX && !IS_PJAX  ? config('default_ajax_return', 'json') : config('default_ajax_return', 'html');
    }
    
    /**
     * 系统通用跳转
     */
    final protected function jump($jump_type = null, $message = null, $url = null)
    {
        
        $data = is_array($jump_type) ? $this->parseJumpArray($jump_type) : $this->parseJumpArray([$jump_type, $message, $url]);
        
        $success  = RESULT_SUCCESS;
        $error    = RESULT_ERROR;
        $redirect = RESULT_REDIRECT;
        
        $u = 'url';
        $m = 'message';
        
        !empty($data[$u]) && $data[$u] = DOMAIN . $data[$u];
        
        switch ($data['jump_type'])
        {
            case $success  : $this->$success($data[$m], $data[$u]); break;
            case $error    : $this->$error($data[$m], $data[$u]);   break;
            case $redirect : $this->$redirect($data[$u]);           break;
            default        : exception('系统跳转失败:' . $data[$u]);
        }
    }
    
    /**
     * 解析跳转数组
     */
    final protected function  parseJumpArray($jump_array = [])
    {
        
        !isset($jump_array[2]) && $jump_array[2] = null;
       
        return ['jump_type' => $jump_array[0], 'message' => $jump_array[1], 'url' => $jump_array[2]];
    }
    
    /**
     * 初始化全局静态资源
     */
    final protected function initCommonStatic()
    {
        
        $this->assign('loading_icon', config('loading_icon'));
        
        $this->assign('pjax_mode',    config('pjax_mode'));
        
        $this->assign('static_root',  DOMAIN . dirname(URL_ROOT) . SYS_DS_PROS . SYS_STATIC_DIR_NAME . SYS_DS_PROS);
    }
    
    /**
     * 获取逻辑层实例
     */
    public function __get($name)
    {
        
        !str_prefix($name, LAYER_LOGIC_NAME) && exception('逻辑层引用需前缀:' . LAYER_LOGIC_NAME);
        
        return model(sr($name, LAYER_LOGIC_NAME), LAYER_LOGIC_NAME);
    }
}
