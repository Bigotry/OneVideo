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

namespace app\admin\widget;

/**
 * 编辑器小物件
 */
class Editor extends WidgetBase
{
    
    /**
     * 显示编辑器
     */
    public function index($name = '', $value = '')
    {
        
        $widget_config['editor_height'] = '300px';
        $widget_config['editor_resize_type'] = 1;
        
        $this->assign('widget_config', $widget_config);
        $this->assign('widget_data', compact('name', 'value'));
        
        return $this->fetch('admin@widget/editor/index');
    }
}
