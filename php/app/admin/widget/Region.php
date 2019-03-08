<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Jack YanTC <yanshixin.com>                             |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\admin\widget;

/**
 * 区域选择小物件
 */
class Region extends WidgetBase
{

    /**
     * 显示小图标选择视图
     */
    public function index($name = '', $province = '', $city = '', $county = '')
    {
        
        $this->assign('widget_data', compact('name', 'province', 'city', 'county'));

        return $this->fetch('admin@widget/region/index');
    }
}
