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

namespace app\admin\logic;

/**
 * 菜单逻辑
 */
class Menu extends AdminBase
{
    
    // 面包屑
    public static $crumbs       = [];
    
    // 菜单Select结构
    public static $menuSelect   = [];
    
    /**
     * 菜单转视图
     */
    public function menuToView($menu_list = [], $child = 'child')
    {
        
        $menu_view = '';
        
        //遍历菜单列表
        foreach ($menu_list as $menu_info) {
            
            if (!empty($menu_info[$child])) {
             
                $icon = empty($menu_info['icon']) ? 'fa-dot-circle-o' : $menu_info['icon'];
                
                $menu_view.=  "<li menu_id='".$menu_info['id']."'>
                                 <a href='javascript:;'><i class='fa $icon'></i> <span>".$menu_info['name']."</span>
                                   <span class='pull-right-container'>
                                     <i class='fa fa-angle-left pull-right'></i>
                                   </span>
                                 </a>
                                 <ul class='treeview-menu'>".$this->menuToView($menu_info[$child],  $child)."</ul>
                               </li>";
                
            } else {
                
                $icon = empty($menu_info['icon']) ? 'fa-circle-o' : $menu_info['icon'];
                
                $url = url($menu_info['url']);
                
                $menu_view .= "<li menu_id='".$menu_info['id']."'><a href='$url'><i class='fa $icon'></i> <span>".$menu_info['name']."</span></a></li>";
            }
       }
       
       return $menu_view;
    }
    
    /**
     * 菜单转Select
     */
    public function menuToSelect($menu_list = [], $level = 0, $name = 'name', $child = 'child')
    {
        
        $menu_list_count = count($menu_list);
        
        foreach ($menu_list as $k => $info) {
            
            empty($k) && ++$level;
            
            $tmp_str = str_repeat("&nbsp;", $level * 6) . "├";
            
            $info[$name] = $tmp_str . $info[$name] . "&nbsp;";
            
            array_push(self::$menuSelect, $info);
            
            if (!array_key_exists($child, $info)) {

                $k != $menu_list_count - DATA_NORMAL ? : $level > DATA_NORMAL && --$level;
                
            } else {
                
                $tmp_ary = $info[$child];
                
                unset($info[$child]);
                
                $this->menuToSelect($tmp_ary, $level, $name, $child);
            }
        }
        
        return self::$menuSelect;
    }
    
    /**
     * 菜单转Checkbox
     */
    public function menuToCheckboxView($menu_list = [], $child = 'child')
    {
        
        $menu_view = '';
        
        $id = input('id');
        
        $auth_group_info = $this->logicAuthGroup->getGroupInfo(['id' => $id], 'rules');
        
        $rules_array = str2arr($auth_group_info['rules']);
        
        //遍历菜单列表
        foreach ($menu_list as $menu_info) {
            
            $icon = empty($menu_info['icon']) ? 'fa-dot-circle-o' : $menu_info['icon'];
            
            $checkbox_select = in_array($menu_info['id'], $rules_array) ? "checked='checked'" : '';
            
            if (!empty($menu_info[$child])) {
                
                $menu_view.=  "<div class='box box-header admin-node-header'>
                                          <div class='box-header'><div class='checkbox'> <label>
                                                  <input class='rules_all' type='checkbox' name='rules[]' value='".$menu_info['id']."' $checkbox_select > <i class='fa $icon'></i>  ".$menu_info['name']."
                                              </label> </div></div>
                                    <div class='box-body'> ".$this->menuToCheckboxView($menu_info[$child],  $child)." </div>
                                </div>";
                
            } else {
                
                $menu_view.=    "<label class='admin-node-label'>  <input type='checkbox' name='rules[]' value='".$menu_info['id']."'  $checkbox_select > &nbsp;<i class='fa $icon'></i>  ".$menu_info['name']."  </label>";
            }
       }
       
       return $menu_view;
    }
    
    /**
     * 菜单选择
     */
    public function selectMenu($menu_view = '')
    {
        
        $map['url']    = URL;
        $map['module'] = MODULE_NAME;
                
        $menu_info = $this->getMenuInfo($map);
        
        // 获取自己及父菜单列表
        $this->getParentMenuList($menu_info['id']);

        // 选中面包屑中的菜单

        foreach (self::$crumbs as $menu_info) {

            $replace_data = "menu_id='".$menu_info['id']."'";
            
            $menu_view = str_replace($replace_data, " class='active' ", $menu_view);
        }
        
       return $menu_view;
    }
    
    /**
     * 获取自己及父菜单列表
     */
    public function getParentMenuList($menu_id = 0)
    {
        
        $menu_info = $this->getMenuInfo(['id' => $menu_id]);
        
        !empty($menu_info['pid']) && $this->getParentMenuList($menu_info['pid']);
        
        self::$crumbs [] = $menu_info;
    }
    
    /**
     * 获取面包屑
     */
    public function getCrumbsView()
    {
        
        $crumbs_view = "<ol class='breadcrumb'>";
      
        foreach (self::$crumbs as $menu_info) {
            
            $icon = empty($menu_info['icon']) ? 'fa-circle-o' : $menu_info['icon'];
            
            $crumbs_view .= "<li><a><i class='fa $icon'></i> ".$menu_info['name']."</a></li>";
        }
        
        $crumbs_view .= "</ol>";
        
        return $crumbs_view;
    }
    
    /**
     * 获取菜单列表
     */
    public function getMenuList($where = [], $field = true, $order = '', $paginate = false)
    {
        
        return $this->modelMenu->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 获取菜单信息
     */
    public function getMenuInfo($where = [], $field = true)
    {
        
        return $this->modelMenu->getInfo($where, $field);
    }
    
    /**
     * 菜单添加
     */
    public function menuAdd($data = [])
    {
        
        $validate_result = $this->validateMenu->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateMenu->getError()];
        }
        
        $result = $this->modelMenu->setInfo($data);
        
        $result && action_log('新增', '新增菜单，name：' . $data['name']);
        
        $url = url('menuList', ['pid' => $data['pid'] ? $data['pid'] : 0]);
        
        return $result ? [RESULT_SUCCESS, '菜单添加成功', $url] : [RESULT_ERROR, $this->modelMenu->getError()];
    }
    
    /**
     * 菜单编辑
     */
    public function menuEdit($data = [])
    {
        
        $validate_result = $this->validateMenu->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateMenu->getError()];
        }
        
        $url = url('menuList', ['pid' => $data['pid'] ? $data['pid'] : 0]);
        
        $result = $this->modelMenu->setInfo($data);
        
        $result && action_log('编辑', '编辑菜单，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '菜单编辑成功', $url] : [RESULT_ERROR, $this->modelMenu->getError()];
    }
    
    /**
     * 菜单删除
     */
    public function menuDel($where = [])
    {
        
        $result = $this->modelMenu->deleteInfo($where);
        
        $result && action_log('删除', '删除菜单，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '菜单删除成功'] : [RESULT_ERROR, $this->modelMenu->getError()];
    }
    
    /**
     * 获取默认页面标题
     */
    public function getDefaultTitle()
    {
        
        return $this->modelMenu->getValue(['module' => MODULE_NAME, 'url' => URL], 'name');
    }
}
