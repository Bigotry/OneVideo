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

namespace app\admin\validate;

/**
 * API验证器
 */
class Api extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        'name'          => 'require|unique:api',
        'api_url'       => 'require',
    ];

    // 验证提示
    protected $message  =   [
        'name.require'         => '接口名称不能为空',
        'name.unique'          => '接口名称已经存在',
        'api_url.require'      => '请求地址不能为空',
    ];
    
    // 应用场景
    protected $scene = [
        'edit'  =>  ['name','api_url'],
    ];
    
    // 扩展验证规则
    public function checkFieldData($data)
    {
        
        if ($data['group_id'] == DATA_DISABLE) {
            
            $this->error = '请选择接口分组'; return false;
        }
        
        $data_empty = false;
        
        if (!empty($data['is_request_data']) && !empty($data['request_data']['field_name']))
        {
            foreach ($data['request_data']['field_name'] as $v)
            {
                if (empty($v)) { $data_empty = true; break; }
            }
        }
        
        if (!empty($data['is_response_data']) && !empty($data['response_data']['field_name']))
        {
            foreach ($data['response_data']['field_name'] as $v)
            {
                if (empty($v)) { $data_empty = true; break; }
            }
        }
        
        $data_empty && $this->error = '请求数据或响应数据字段填写不完整';
        
        return $data_empty ? false : true;
    }
}
