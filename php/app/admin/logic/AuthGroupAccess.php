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
 * 授权逻辑
 */
class AuthGroupAccess extends AdminBase
{
    
    /**
     * 获得权限菜单列表
     */
    public function getAuthMenuList($member_id = 0)
    {
        
        $sort = 'sort';
        
        if (IS_ROOT) {
            
            return $this->logicMenu->getMenuList([], true, $sort);
        }
        
        // 获取用户组列表
        $group_list = $this->getMemberGroupInfo($member_id);
        
        $menu_ids = [];
        
        foreach ($group_list as $group_info) {
            
            // 合并多个分组的权限节点并去重
            !empty($group_info['rules']) && $menu_ids = array_unique(array_merge($menu_ids, explode(',', trim($group_info['rules'], ','))));
        }
        
        // 若没有权限节点则返回
        if (empty($menu_ids)) {
            
            return $menu_ids;
        }
        
        // 查询条件
        $where = ['id' => ['in', $menu_ids]];
        
        return $this->logicMenu->getMenuList($where, true, $sort);
    }
    
    /**
     * 获得权限菜单URL列表
     */
    public function getAuthMenuUrlList($auth_menu_list = [])
    {
        
        $auth_list = [];
        
        foreach ($auth_menu_list as $info) {
            
            $auth_list[] = $info['url'];
        }

        return $auth_list;
    }
    
    /**
     * 获取会员所属权限组信息
     */
    public function getMemberGroupInfo($member_id = 0)
    {
        
        $this->modelAuthGroupAccess->alias('a');
        
        is_array($member_id) ? $where['a.member_id'] = ['in', $member_id] : $where['a.member_id'] = $member_id;
        
        $where['a.status']    = DATA_NORMAL;
        
        $field = 'a.member_id, a.group_id, g.name, g.describe, g.rules';
        
        $join = [
                    [SYS_DB_PREFIX . 'auth_group g', 'a.group_id = g.id'],
                ];
        
        $this->modelAuthGroupAccess->join = $join;
        
        return $this->modelAuthGroupAccess->getList($where, $field, '', false);
    }
    
    /**
     * 获取授权列表
     */
    public function getAuthGroupAccessList($where = [], $field = 'member_id,group_id', $order = 'member_id', $paginate = false)
    {
        
        return $this->modelAuthGroupAccess->getList($where, $field, $order, $paginate);
    }
}
