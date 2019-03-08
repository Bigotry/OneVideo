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

namespace app\api\logic;

/**
 * 接口文档逻辑
 */
class Document extends ApiBase
{

    /**
     * 获取接口列表
     */
    public function getApiList($where = [], $field = true, $order = '', $paginate = false)
    {
        
        $api_group_list = $this->modelApiGroup->getList($where, $field, $order, $paginate);
        
        $api_list = $this->modelApi->getList($where, $field, $order, $paginate);
        
        foreach ($api_group_list as &$group_info)
        {
            
            $group_info = $group_info->toArray();
            
            begin:
                
            foreach ($api_list as $k => $api_info)
            {
                if ($api_info['group_id'] == $group_info['id'])
                {
                    $group_info['api_list'][] = $api_info;
                    
                    unset($api_list[$k]);
                    
                    $api_list = array_values($api_list);
                    
                    goto begin;
                }
            }
        }
        
        return $api_group_list;
    }
    
    /**
     * 获取接口分组列表
     */
    public function getApiGroupList($where = [], $field = true, $order = '', $paginate = false)
    {
        
        return $this->modelApiGroup->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 获取接口信息
     */
    public function getApiInfo($where = [], $field = true)
    {
        
        $info = $this->modelApi->getInfo($where, $field);
        
        $api_data_type_option = parse_config_array('api_data_type_option');
        $api_status_option    = parse_config_array('api_status_option');
        
        $info_array = $info->toArray();
        
        if (!empty($info_array['request_data'])) {
            foreach ($info_array['request_data'] as &$v)
            {
                $v['data_type']  = $api_data_type_option[$v['data_type']];
                $v['is_require'] = $v['is_require'] ? '是' : '否';
            }
        }
        
        if (!empty($info_array['response_data'])) {
            foreach ($info_array['response_data'] as &$v)
            {
                $v['data_type']  = $api_data_type_option[$v['data_type']];
            }
        }
        
        $info_array['request_type_text'] = $info_array['request_type'] ? 'GET' : 'POST';
        $info_array['api_status_text']   = $api_status_option[$info_array['api_status']];
        
        return $this->apiAttachField($info_array);
    }
    
    /**
     * API附加字段
     */
    public function apiAttachField($info_array)
    {
        empty($info_array['request_data']) && $info_array['request_data'] = [];
        
        if ($info_array['is_page'])
        {
            $page_attach_field = config('page_attach_field');
            
            foreach ($page_attach_field as $field) {
                
                array_unshift($info_array['request_data'], $field);
            }
        }
        
        $info_array['is_request_sign']      && array_unshift($info_array['request_data'],   config('data_sign_attach_field'));
        
        $info_array['is_user_token']        && array_unshift($info_array['request_data'],   config('user_token_attach_field'));
        
        empty($info_array['request_data'])  && $info_array['request_data'] = [];
        
        array_unshift($info_array['request_data'], config('access_token_attach_field'));
        
        empty($info_array['response_data']) && $info_array['response_data'] = [];
        
        $info_array['is_response_sign']     && array_unshift($info_array['response_data'],  config('data_sign_attach_field'));
        
        return $info_array;
    }
    
    /**
     * API错误码数据
     */
    public function apiErrorCodeData()
    {
        
        $path = APP_PATH . 'api' . SYS_DS_PROS . RESULT_ERROR;
        
        $file_list  = file_list($path);
        
        $code_data = [];
        
        foreach ($file_list as $v)
        {
            
            $class_path = SYS_DS_CONS . SYS_APP_NAMESPACE . SYS_DS_CONS . 'api' . SYS_DS_CONS . RESULT_ERROR . SYS_DS_CONS;    
            
            $class_name = sr($v, EXT);
            
            $ref = new \ReflectionClass($class_path . $class_name);

            $props = $ref->getStaticProperties();
            
            foreach ($props as $k => $v)
            {

                $data['class']          = $class_name;
                $data['property']       = $k;
                $data[API_CODE_NAME]    = $v[API_CODE_NAME];
                $data[API_MSG_NAME]     = $v[API_MSG_NAME];
                
                $code_data[] = $data;
            }
        }
        
        return $code_data;
    }
}
