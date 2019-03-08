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
 * 回收站控制器
 */
class Trash extends AdminBase
{
    
    /**
     * 回收站列表
     */
    public function trashList()
    {
        
        $this->assign('list', $this->logicTrash->getTrashList());
        
        return $this->fetch('trash_list');
    }
    
    /**
     * 数据列表
     */
    public function trashDataList()
    {
        
        $data = $this->logicTrash->getTrashDataList($this->param['name']);
        
        $this->assign('model_name', $data['model_name']);
        $this->assign('list', $data['list']);
        $this->assign('dynamic_field', $data['dynamic_field']);
        
        return $this->fetch('trash_data_list');
    }
    
    /**
     * 数据删除
     */
    public function trashDataDel($model_name = '', $id = 0)
    {
        
        $this->jump($this->logicTrash->trashDataDel($model_name, $id));
    }
    
    /**
     * 数据恢复
     */
    public function restoreData($model_name = '', $id = 0)
    {
        
        $this->jump($this->logicTrash->restoreData($model_name, $id));
    }
}
