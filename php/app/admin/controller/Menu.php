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
 * 菜单控制器
 */
class Menu extends AdminBase
{
    
    /**
     * 菜单列表
     */
    public function menuList()
    {
        
        $where = empty($this->param['pid']) ? ['pid' => 0] : ['pid' => $this->param['pid']];
        
        $this->assign('list', $this->logicMenu->getMenuList($where, true, 'sort', DB_LIST_ROWS));
        
        $this->assign('pid', $where['pid']);
        
        return $this->fetch('menu_list');
    }
    
    /**
     * 获取菜单Select结构数据
     */
    public function getMenuSelectData()
    {
        
        $menu_select = $this->logicMenu->menuToSelect($this->authMenuTree);
        
        $this->assign('menu_select', $menu_select);
    }
    
    /**
     * 菜单添加
     */
    public function menuAdd()
    {
        
        $this->param['module'] = MODULE_NAME;
        
        IS_POST && $this->jump($this->logicMenu->menuAdd($this->param));
        
        //获取菜单Select结构数据
        $this->getMenuSelectData();
        
        !empty($this->param['pid']) && $this->assign('info', ['pid'=> $this->param['pid']]);
        
        return $this->fetch('menu_edit');
    }
    
    /**
     * 菜单编辑
     */
    public function menuEdit()
    {
        
        IS_POST && $this->jump($this->logicMenu->menuEdit($this->param));
        
        $info = $this->logicMenu->getMenuInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        //获取菜单Select结构数据
        $this->getMenuSelectData();
        
        return $this->fetch('menu_edit');
    }
    
    /**
     * 数据状态设置
     */
    public function setStatus()
    {
        
        $this->jump($this->logicAdminBase->setStatus('Menu', $this->param));
    }
    
    /**
     * 排序
     */
    public function setSort()
    {
        
        $this->jump($this->logicAdminBase->setSort('Menu', $this->param));
    }
}
