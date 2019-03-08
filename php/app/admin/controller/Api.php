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
 * API管理控制器
 */
class Api extends AdminBase
{
    
    /**
     * API列表
     */
    public function apiList()
    {
        
        $where = [];
        
        !empty($this->param['search_data']) && $where['name'] = ['like', '%'.(string)$this->param['search_data'].'%'];
        
        $this->assign('list', $this->logicApi->getApiList($where, true, 'sort'));
        
        return $this->fetch('api_list');
    }
    
    /**
     * API添加
     */
    public function apiAdd()
    {
        
        IS_POST && $this->jump($this->logicApi->apiEdit($this->param));
        
        $this->apiAssignGroupList('group_list');
        
        $info['request_data_json']  = $this->getApiDataFieldDefault();
        $info['response_data_json'] = $this->getApiDataFieldDefault(false);
        
        $this->assign('info', $info);
        $this->assign('api_data_type_option', $this->logicApi->getApiDataOption());
        
        return $this->fetch('api_edit');
    }
    
    /**
     * 获取API数据字段默认值
     */
    public function getApiDataFieldDefault($mark = 'request_data')
    {
        
        return $mark == 'request_data' ? json_encode([['', 0, 0, '']]) : json_encode([['', 0, '']]);
    }
    
    /**
     * API编辑
     */
    public function apiEdit()
    {
        
        IS_POST && $this->jump($this->logicApi->apiEdit($this->param));
        
        $this->apiAssignGroupList('group_list');
        
        $info = $this->logicApi->getApiInfo(['id' => $this->param['id']]);
        
        !empty($info['request_data'])  ? $info['request_data_json']  = json_encode(relevance_arr_to_index_arr($info['request_data']))  : $info['request_data_json']  = $this->getApiDataFieldDefault();
        !empty($info['response_data']) ? $info['response_data_json'] = json_encode(relevance_arr_to_index_arr($info['response_data'])) : $info['response_data_json'] = $this->getApiDataFieldDefault(false);
        
        $this->assign('info', $info);
        $this->assign('api_data_type_option', $this->logicApi->getApiDataOption());
        
        return $this->fetch('api_edit');
    }
    
    /**
     * Assign API 分组
     */
    public function apiAssignGroupList($name = 'list')
    {
        
        $this->assign($name, $this->logicApi->getApiGroupList([], true, 'sort desc'));
    }
    
    /**
     * API分组列表
     */
    public function apiGroupList()
    {
        
        $this->apiAssignGroupList();
        
        return $this->fetch('api_group_list');
    }
    
    /**
     * API分组添加
     */
    public function apiGroupAdd()
    {
        
        IS_POST && $this->jump($this->logicApi->apiGroupEdit($this->param));
        
        return $this->fetch('api_group_edit');
    }
    
    /**
     * API分组编辑
     */
    public function apiGroupEdit()
    {
        
        IS_POST && $this->jump($this->logicApi->apiGroupEdit($this->param));
        
        $info = $this->logicApi->getApiGroupInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('api_group_edit');
    }
    
    /**
     * API分组删除
     */
    public function apiGroupDel($id = 0)
    {
        
        $this->jump($this->logicApi->apiGroupDel(['id' => $id]));
    }
    
    /**
     * 数据状态设置
     */
    public function setStatus()
    {
        
        $this->jump($this->logicAdminBase->setStatus('Api', $this->param));
    }
    
    /**
     * 排序
     */
    public function setSort()
    {
        
        $this->jump($this->logicAdminBase->setSort('Api', $this->param));
    }
}
