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
 * API逻辑
 */
class Api extends LogicBase
{
    
    /**
     * 获取API列表
     */
    public function getApiList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        return $this->modelApi->getList($where, $field, $order, $paginate);
    }
    
    /**
     * API编辑
     */
    public function apiEdit($data = [])
    {
        
        $validate_result = $this->validateApi->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateApi->getError()];
        }
        
        if (!$this->validateApi->checkFieldData($data)) {
            
            return [RESULT_ERROR, $this->validateApi->getError()];
        }
            
        !empty($data['is_request_data'])  ? $data['request_data']  = transform_array_to_json($data['request_data'])  : $data['request_data']  = '';
        !empty($data['is_response_data']) ? $data['response_data'] = transform_array_to_json($data['response_data']) : $data['response_data'] = '';
        
        $data['describe_text']     = html_entity_decode($data['describe_text']);
        $data['response_examples'] = html_entity_decode($data['response_examples']);
        
        $url = url('apiList');
        
        $result = $this->modelApi->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, 'API' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelApi->getError()];
    }
    
    /**
     * 获取API分组列表
     */
    public function getApiGroupList($where = [], $field = true, $order = '')
    {
        
        return $this->modelApiGroup->getList($where, $field, $order, false);
    }
    
    /**
     * API分组编辑
     */
    public function apiGroupEdit($data = [])
    {
        
        $validate_result = $this->validateApiGroup->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateApiGroup->getError()];
        }
        
        $url = url('apiGroupList');
        
        $result = $this->modelApiGroup->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, 'API分组' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelApiGroup->getError()];
    }
    
    /**
     * 获取API信息
     */
    public function getApiInfo($where = [], $field = true)
    {

        return $this->modelApi->getInfo($where, $field);
    }
    
    /**
     * 获取API分组信息
     */
    public function getApiGroupInfo($where = [], $field = true)
    {
        
        return $this->modelApiGroup->getInfo($where, $field);
    }
    
    /**
     * API分组删除
     */
    public function apiGroupDel($where = [])
    {
        
        $result = $this->modelApiGroup->deleteInfo($where);
        
        $result && action_log('删除', 'API分组删除' . '，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelApiGroup->getError()];
    }
    
    /**
     * 获取API数据类型选项
     */
    public function getApiDataOption()
    {
    
        $api_data_type_option  = parse_config_array('api_data_type_option');
        
        $options = '';

        foreach ($api_data_type_option as $k => $v)
        {
            $options .= "<option value='".$k."'>".$v."</option>";
        }

        return $options;
    }
}
