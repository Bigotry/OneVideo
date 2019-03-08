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

namespace app\admin\controller;

/**
 * 插件控制器
 */
class Addon extends AdminBase
{
    
    /**
     * 执行插件控制器
     */
    public function execute($addon_name = null, $controller_name = null, $action_name = null)
    {
        
        return $this->logicAddon->executeAction($addon_name, $controller_name, $action_name);
    }
    
    /**
     * 执行插件安装
     */
    public function addonInstall($name = null)
    {
        
        $this->jump($this->logicAddon->addonInstall($name));
    }
    
    /**
     * 执行插件卸载
     */
    public function addonUninstall($name = null)
    {
        
        $this->jump($this->logicAddon->addonUninstall($name));
    }
    
    /**
     * 插件列表
     */
    public function addonList()
    {
        
        $this->assign('list', $this->logicAddon->getAddonList());
        
        return $this->fetch('addon_list');
    }
    
    /**
     * 钩子列表
     */
    public function hookList()
    {
        
        $this->assign('list', $this->logicAddon->getHookList());
        
        return $this->fetch('hook_list');
    }
}
