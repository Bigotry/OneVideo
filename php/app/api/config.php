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

//配置文件

empty(STATIC_DOMAIN) ? $static = [] :  $static['__STATIC__'] = STATIC_DOMAIN . SYS_DS_PROS . SYS_STATIC_DIR_NAME;

return [
    
    // 视图输出字符串内容替换
    'view_replace_str' => $static,
    
    /* 带分页接口附加字段 */
    'page_attach_field' => [
            [
                'field_name'        => 'page',
                'data_type'         => '字符',
                'is_require'        => '否',
                'field_describe'    => "访问页码【分页附加参数】",
            ],
            [
                'field_name'        => 'list_rows',
                'data_type'         => '字符',
                'is_require'        => '否',
                'field_describe'    => "每页记录数量【分页附加参数】",
            ],
    ],
    
    /* 带user_token接口附加字段 */
    'user_token_attach_field' => [
        'field_name'        => 'user_token',
        'data_type'         => '字符',
        'is_require'        => '是',
        'field_describe'    => "用户Token【Token附加参数】",
    ],
    
    /* access_token 附加字段 */
    'access_token_attach_field' => [
        'field_name'        => 'access_token',
        'data_type'         => '字符',
        'is_require'        => '是',
        'field_describe'    => "访问Token【Token附加参数】",
    ],
    
    /* data_sign 附加字段 */
    'data_sign_attach_field' => [
        'field_name'        => 'data_sign',
        'data_type'         => '字符',
        'is_require'        => '是',
        'field_describe'    => "数据签名【数据验证附加字段】",
    ],
    
    /* 数据签名时需要过滤的字段 */
    'data_sign_filter_field' => ['page', 'list_rows', 'user_token', 'access_token', 'data_sign']
];
