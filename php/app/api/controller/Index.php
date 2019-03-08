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

namespace app\api\controller;

use app\common\controller\ControllerBase;

/**
 * 首页控制器
 */
class Index extends ControllerBase
{
    
    /**
     * 首页方法
     */
    public function index()
    {
        
        $list = $this->logicDocument->getApiList([], true, 'sort');
        
        $code_list = $this->logicDocument->apiErrorCodeData();
        
        $this->assign('code_list', $code_list);
        
        $content = $this->fetch('content_default');

        $this->assign('content', $content);
        
        $this->assign('list', $list);
        
        return $this->fetch();
    }
    
    /**
     * API详情
     */
    public function details($id = 0)
    {

        $list = $this->logicDocument->getApiList([], true, 'sort');
        
        $info = $this->logicDocument->getApiInfo(['id' => $id]);
        
        $this->assign('info', $info);
        
        // 测试期间使用token ， 测试完成请删除
        $this->assign('test_access_token', get_access_token());
        
        $content = $this->fetch('content_template');
        
        if (IS_AJAX) {
            
            return throw_response_exception(['content' => $content]);
        }
        
        $this->assign('content', $content);
        
        $this->assign('list', $list);
        
        return $this->fetch('index');
    }
}
