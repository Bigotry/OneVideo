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

namespace app\common\behavior;

use think\Loader;

/**
 * 初始化基础信息行为
 */
class InitBase
{

    /**
     * 初始化行为入口
     */
    public function run()
    {
        
        // 初始化常量
        $this->initConst();
        
        // 初始化配置
        $this->initConfig();
        
        // 注册命名空间
        $this->registerNamespace();
    }
    
    /**
     * 初始化常量
     */
    private function initConst()
    {
        
        // 初始化分层名称常量
        $this->initLayerConst();
        
        // 初始化结果常量
        $this->initResultConst();
        
        // 初始化数据状态常量
        $this->initDataStatusConst();
        
        // 初始化时间常量
        $this->initTimeConst();
        
        // 初始化系统常量
        $this->initSystemConst();
        
        // 初始化路径常量
        $this->initPathConst();
        
        // 初始化API常量
        $this->initApiConst();
    }
    
    /**
     * 初始化分层名称常量
     */
    private function initLayerConst()
    {
        
        define('LAYER_LOGIC_NAME'       , 'logic');
        define('LAYER_MODEL_NAME'       , 'model');
        define('LAYER_SERVICE_NAME'     , 'service');
        define('LAYER_CONTROLLER_NAME'  , 'controller');
        define('LAYER_VALIDATE_NAME'    , 'validate');
        define('LAYER_VIEW_NAME'        , 'view');
    }
    
    /**
     * 初始化结果标识常量
     */
    private function initResultConst()
    {
        
        define('RESULT_SUCCESS'         , 'success');
        define('RESULT_ERROR'           , 'error');
        define('RESULT_REDIRECT'        , 'redirect');
        define('RESULT_MESSAGE'         , 'message');
        define('RESULT_URL'             , 'url');
        define('RESULT_DATA'            , 'data');
    }
    
    /**
     * 初始化数据状态常量
     */
    private function initDataStatusConst()
    {
        
        define('DATA_STATUS_NAME'       , 'status');
        define('DATA_NORMAL'            , 1);
        define('DATA_DISABLE'           , 0);
        define('DATA_DELETE'            , -1);
        define('DATA_SUCCESS'           , 1);
        define('DATA_ERROR'             , 0);
    }
    
    /**
     * 初始化API常量
     */
    private function initApiConst()
    {
        
        define('API_CODE_NAME'          , 'code');
        define('API_MSG_NAME'           , 'msg');
    }
    
    /**
     * 初始化时间常量
     */
    private function initTimeConst()
    {
        
        define('TIME_CT_NAME'           , 'create_time');
        define('TIME_UT_NAME'           , 'update_time');
        define('TIME_NOW'               , time());
    }
    
    /**
     * 初始化路径常量
     */
    private function initPathConst()
    {
        
        define('PATH_ADDON'             , ROOT_PATH   . SYS_ADDON_DIR_NAME . DS);
        define('PATH_PUBLIC'            , ROOT_PATH   . 'public'    . DS);
        define('PATH_UPLOAD'            , PATH_PUBLIC . 'upload'    . DS);
        define('PATH_PICTURE'           , PATH_UPLOAD . 'picture'   . DS);
        define('PATH_FILE'              , PATH_UPLOAD . 'file'      . DS);
        define('PATH_SERVICE'           , ROOT_PATH   . DS . SYS_APP_NAMESPACE . DS . SYS_COMMON_DIR_NAME . DS . LAYER_SERVICE_NAME . DS);
        define('PATH_COMMON_LOGIC'      , SYS_APP_NAMESPACE . SYS_DS_CONS . SYS_COMMON_DIR_NAME . SYS_DS_CONS . LAYER_LOGIC_NAME . SYS_DS_CONS);
    }
    
    /**
     * 初始化系统常量
     */
    private function initSystemConst()
    {

        define('SYS_APP_NAMESPACE'      , config('app_namespace'));
        define('SYS_HOOK_DIR_NAME'      , 'hook');
        define('SYS_ADDON_DIR_NAME'     , 'addon');
        define('SYS_DRIVER_DIR_NAME'    , 'driver');
        define('SYS_COMMON_DIR_NAME'    , 'common');
        define('SYS_STATIC_DIR_NAME'    , 'static');
        define('SYS_VERSION'            , '1.3.3');
        define('SYS_ADMINISTRATOR_ID'   , 1);
        define('SYS_DS_PROS'            , '/');
        define('SYS_DS_CONS'            , '\\');
        
        $database_config                = config('database');
        
        define('SYS_DB_PREFIX'          , $database_config['prefix']);
        define('SYS_ENCRYPT_KEY'        , $database_config['sys_data_key']);
    }
    
    /**
     * 初始化配置信息
     */
    private function initConfig()
    {

        $model = model(SYS_COMMON_DIR_NAME . SYS_DS_PROS . 'Config');
        
        $config_list = auto_cache('config_list', create_closure($model, 'all'));
        
        foreach ($config_list as $info) {
            
            $config_array[$info['name']] = $info['value'];
        }
            
        config($config_array);
        
        $this->initTmconfig();
    }
    
    /**
     * 初始化动态配置信息
     */
    private function initTmconfig()
    {
        
        $list_rows                  = config('list_rows');
        $api_key                    = config('api_key');
        $jwt_key                    = config('jwt_key');
        $static_domain              = config('static_domain');

        define('DB_LIST_ROWS'       , empty($list_rows)                 ? 10        : $list_rows);
        define('API_KEY'            , empty($api_key)                   ? 'OneBase' : $api_key);
        define('JWT_KEY'            , empty($jwt_key)                   ? 'OneBase' : $jwt_key);
        define('STATIC_DOMAIN'      , empty($static_domain)             ? null      : $static_domain);
    }
    
    /**
     * 注册命名空间
     */
    private function registerNamespace()
    {
        
        // 注册插件根命名空间
        Loader::addNamespace(SYS_ADDON_DIR_NAME, PATH_ADDON);
    }
}
