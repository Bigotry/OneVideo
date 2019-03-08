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

/**
 * 配置逻辑
 */
class Config extends LogicBase
{
    
    /**
     * 获取配置列表
     */
    public function getConfigList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        return $this->modelConfig->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 获取配置列表过滤
     */
    public function getConfigListFilter($param = [])
    {
        
        $where = [];
        
        $group = empty($param['group']) ? DATA_DISABLE : (int)$param['group'];
        
        !empty($group) && $where['group'] = $group;
        
        !empty($param['search_data']) && $where['name|title'] = ['like', '%'.(string)$param['search_data'].'%'];
        
        $sort = 'sort asc, create_time desc';
        
        if (!empty($param['order_field'])) {
            
            
            $sort = empty($param['order_val']) ? $param['order_field'] . ' asc' : $param['order_field'] . ' desc';
        }
        
        $data['list'] = $this->getConfigList($where, true, $sort);
        
        $data['group'] = $group;
        
        return $data;
    }
    
    /**
     * 获取配置信息
     */
    public function getConfigInfo($where = [], $field = true)
    {
        
        return $this->modelConfig->getInfo($where, $field);
    }
    
    /**
     * 系统设置
     */
    public function settingSave($data = [])
    {
        
        foreach ($data as $name => $value)
        {
            
            $where = array('name' => $name);
            
            $this->modelConfig->updateInfo($where, ['value' => $value]);
        }
        
        action_log('设置', '系统设置保存');
        
        return [RESULT_SUCCESS, '设置保存成功'];
    }
    
    /**
     * 配置添加
     */
    public function configAdd($data = [])
    {
        
        $validate_result = $this->validateConfig->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateConfig->getError()];
        }
        
        $url = url('configList', array('group' => $data['group'] ? $data['group'] : 0));
        
        $result = $this->modelConfig->setInfo($data);
        
        $result && action_log('新增', '新增配置，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '配置添加成功', $url] : [RESULT_ERROR, $this->modelConfig->getError()];
    }
    
    /**
     * 配置编辑
     */
    public function configEdit($data = [])
    {
        
        $validate_result = $this->validateConfig->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateConfig->getError()];
        }
        
        $url = url('configList', array('group' => $data['group'] ? $data['group'] : 0));
        
        $result = $this->modelConfig->setInfo($data);
        
        $result && action_log('编辑', '编辑配置，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '配置编辑成功', $url] : [RESULT_ERROR, $this->modelConfig->getError()];
    }
    
    /**
     * 配置删除
     */
    public function configDel($where = [])
    {
        
        $result = $this->modelConfig->deleteInfo($where);
        
        $result && action_log('删除', '删除配置，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '菜单删除成功'] : [RESULT_ERROR, $this->modelConfig->getError()];
    }
}
