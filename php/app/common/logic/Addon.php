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

namespace app\common\logic;

use think\Db;

/**
 * 插件逻辑
 */
class Addon extends LogicBase
{
    
    // 插件实例
    protected static $instance = [];
    
    /**
     * 获取插件列表
     */
    public function getAddonList()
    {
        
        $object_list = $this->getUninstalledList();
       
        $list = [];
        
        foreach ($object_list as $object) {
            
            $addon_info = $object->addonInfo();
            
            $info = $this->modelAddon->getInfo(['name' => $addon_info['name']]);
            
            $addon_info['is_install'] = empty($info) ? DATA_DISABLE : DATA_NORMAL;
            
            $list[] = $addon_info;
        }
        
        return $list;
    }
    
    /**
     * 获取未安装插件列表
     */
    public function getUninstalledList()
    {
        
        $dir_list = get_dir(PATH_ADDON);
        
        foreach ($dir_list as $v) {
            
            $class = SYS_DS_CONS . SYS_ADDON_DIR_NAME . SYS_DS_CONS . $v . SYS_DS_CONS .ucfirst($v);
            
            if (!isset(self::$instance[$class])) {
                
                self::$instance[$class] = new $class();
            }
        }
        
        return self::$instance;
    }
    
    /**
     * 获取钩子列表
     */
    public function getHookList($where = [], $field = true, $order = '')
    {
        
        $m = LAYER_MODEL_NAME . ucwords(SYS_HOOK_DIR_NAME);
        
        return $this->$m->getList($where, $field, $order);
    }
    
    /**
     * 执行插件sql
     */
    public function executeSql($name = '', $sql_name = '')
    {
        
	$sql_string = file_get_contents(PATH_ADDON . $name . DS . 'data' . DS . $sql_name.'.sql');
        
	$sql = explode(";\n", str_replace("\r", "\n", $sql_string));
        
	foreach ($sql as $value) {
            
            !empty(trim($value)) && Db::execute($value);
	}
    }
    
    /**
     * 执行插件
     */
    public function executeAction($addon_name = null, $controller_name = null, $action_name = null)
    {
        
        $class_path = SYS_DS_CONS . SYS_ADDON_DIR_NAME . SYS_DS_CONS . $addon_name . SYS_DS_CONS . LAYER_CONTROLLER_NAME . SYS_DS_CONS . $controller_name;
        
        return (new $class_path())->$action_name();
    }
    
    /**
     * 插件安装
     */
    public function addonInstall($name = null)
    {
        
        $strtolower_name = strtolower($name);

        $class_path = SYS_DS_CONS . SYS_ADDON_DIR_NAME . SYS_DS_CONS . $strtolower_name . SYS_DS_CONS . $name;
        
        $this->executeSql($strtolower_name, 'install');
        
        action_log('安装', '插件安装，name：' . $strtolower_name);
        
        return (new $class_path())->addonInstall();
    }
    
    /**
     * 插件卸载
     */
    public function addonUninstall($name = null)
    {
        
        $strtolower_name = strtolower($name);

        $class_path = SYS_DS_CONS . SYS_ADDON_DIR_NAME . SYS_DS_CONS . $strtolower_name . SYS_DS_CONS . $name;
        
        $this->executeSql($strtolower_name, 'uninstall');
        
        action_log('卸载', '插件卸载，name：' . $strtolower_name);
        
        return (new $class_path())->addonUninstall();
    }
}
