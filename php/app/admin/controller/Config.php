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
 * 配置控制器
 */
class Config extends AdminBase
{
    
    /**
     * 系统设置
     */
    public function setting()
    {
        
        IS_POST && $this->jump($this->logicConfig->settingSave($this->param));
        
        $where = empty($this->param['group']) ? ['group' => 1] : ['group' => $this->param['group']];
        
        $this->getConfigCommonData();
        
        $this->assign('list', $this->logicConfig->getConfigList($where, true, 'sort', false));
        
        $this->assign('group', $where['group']);
        
        return  $this->fetch('setting');
    }

    /**
     * 配置列表
     */
    public function configList()
    {
        
        $this->getConfigCommonData();
        
        $data = $this->logicConfig->getConfigListFilter($this->param);
        
        $this->assign('list', $data['list']);
        
        $this->assign('group', $data['group']);
        
        return $this->fetch('config_list');
    }
    
    /**
     * 获取通用数据
     */
    public function getConfigCommonData()
    {
        
        $config_group_list = parse_config_array('config_group_list');
        
        $config_type_list  = parse_config_array('config_type_list');
        
        $this->assign('config_group_list', $config_group_list);
        
        $this->assign('config_type_list' , $config_type_list);
    }
    
    /**
     * 配置添加
     */
    public function configAdd()
    {
        
        IS_POST && $this->jump($this->logicConfig->configAdd($this->param));
        
        $this->getConfigCommonData();
        
        !empty($this->param['group']) && $this->assign('info', ['group'=> $this->param['group']]);
        
        return  $this->fetch('config_edit');
    }
    
    /**
     * 配置编辑
     */
    public function configEdit()
    {
        
        IS_POST && $this->jump($this->logicConfig->configEdit($this->param));
        
        $info = $this->logicConfig->getConfigInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        $this->getConfigCommonData();
        
        return $this->fetch('config_edit');
    }
    
    /**
     * 数据状态设置
     */
    public function setStatus()
    {
        
        $this->jump($this->logicAdminBase->setStatus('Config', $this->param));
    }
    
    /**
     * 排序
     */
    public function setSort()
    {
        
        $this->jump($this->logicAdminBase->setSort('Config', $this->param));
    }
}
