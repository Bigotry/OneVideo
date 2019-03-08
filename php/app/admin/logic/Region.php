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
 * 省市县三级联动小物件逻辑
 */
class Region extends AdminBase
{

    /**
     * 组合下拉框选项信息
     */
    public function combineOptions($id = 0, $list = [], $default_option_text = '')
    {
        
        $data = "<option value =''>$default_option_text</option>";
        
        foreach ($list as $vo)
        {
            $data .= "<option ";
            
            !($id == $vo['id']) ?: $data .= " selected ";
            
            $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
        }
        
        return $data;
    }
    
    /**
     * 获取省市县选项信息
     */
    public function getRegionOptions($upid = 0, $level = 1, $select_id = 0)
    {
        
        $list = $this->getRegionList(['upid' => $upid, 'level' => $level]);
        
        switch ($level)
        {
            case 1: $default_option_text = "---请选择省份---"; break;
            case 2: $default_option_text = "---请选择城市---"; break;
            case 3: $default_option_text = "---请选择区县---"; break;
            default: $this->error('省市县 level 不存在');
        }
        
        return $this->combineOptions($select_id, $list, $default_option_text);
    }
    
    /**
     * 获取区域列表
     */
    public function getRegionList($where = [])
    {
        
        $cache_key = 'cache_region_' . md5(serialize($where));
        
        $cache_list = cache($cache_key);
        
        if (!empty($cache_list)) {
            
            return $cache_list;
        }
        
        $list = $this->modelRegion->getList($where, true, 'id', false);
        
        !empty($list) && cache($cache_key, $list);
        
        return $list;
    }
}
