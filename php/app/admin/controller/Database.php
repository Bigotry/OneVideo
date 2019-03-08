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
 * 数据库备份控制器
 */
class Database extends AdminBase
{
    
    /**
     * 优化表
     */
    public function optimize()
    {
        
        $this->jump($this->logicDatabase->optimize());
    }
    
    /**
     * 修复表
     */
    public function repair()
    {
        
        $this->jump($this->logicDatabase->optimize(false));
    }
    
    /**
     * 数据备份
     */
    public function dataBackup()
    {
        
        IS_POST && $this->jump($this->logicDatabase->dataBackup());
        
        $this->assign('list', $this->logicDatabase->getTableList());
        
        return $this->fetch('data_backup');
    }
    
    /**
     * 数据还原
     */
    public function dataRestore()
    {

        $this->assign('list', $this->logicDatabase->getBackupList());
        
        return $this->fetch('data_restore');
    }
    
    /**
     * 数据还原处理
     */
    public function dataRestoreHandle($time = 0)
    {

       $this->jump($this->logicDatabase->dataRestore($time));
    }
    
    /**
     * 备份删除
     */
    public function backupDel($time = 0)
    {

        $this->jump($this->logicDatabase->backupDel($time));
    }
}
