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

use think\Db;

/**
 * 数据库备份逻辑
 */
class Database extends AdminBase
{
    
    /**
     * 获取数据表列表
     */
    public function getTableList()
    {
        
        $list  = Db::query('SHOW TABLE STATUS');
        
        return array_map('array_change_key_case', $list);
    }
    
    /**
     * 获取数据表列表只包含名称的索引数组
     */
    public function getTableListIndex()
    {
        
        $table_list = $this->getTableList();
        
        return  array_extract($table_list, 'name');
    }
    
    /**
     * 获取备份目录，不存在则创建
     */
    public function getBackupDir()
    {
        
        $path = "../data/";
        
        !is_dir($path) && mkdir($path, 0755, true);
        
        return $path;
    }
    
    /**
     * 通过time获取备份路径
     */
    public function getBackupPathByTime($time = 0)
    {
        
        // 获取备份文件信息
        $name  = date('Ymd-His', $time) . '-*.sql*';
        
        $path  = realpath($this->getBackupDir()) . DIRECTORY_SEPARATOR . $name;
        
        return $path;
    }

    /**
     * 数据备份
     */
    public function dataBackup()
    {

        $path = $this->getBackupDir();
        
        $config = [
            'path'     => realpath($path) . SYS_DS_PROS,
            'part'     => config('data_backup_part_size'),
            'compress' => config('data_backup_compress'),
            'level'    => config('data_backup_compress_level'),
        ];
        
        // 检查是否有正在执行的任务
        $lock = "{$config['path']}backup.lock";
        
        if (is_file($lock)) { return [RESULT_ERROR, '检测到有一个备份任务正在执行，请稍后再试！']; }
        
        // 创建锁文件
        file_put_contents($lock, TIME_NOW);
        
        // 检查备份目录是否可写
        if (!is_writeable($config['path'])) {  return [RESULT_ERROR, '备份目录不存在或不可写，请检查后重试！']; }
        
        // 生成备份文件信息
        $file = ['name' => date('Ymd-His', TIME_NOW), 'part' => DATA_NORMAL ];
        
        session('backup_file', $file);
        
        $Database = new \ob\Database($file, $config);
        
        if (false == $Database) { return [RESULT_ERROR, '备份初始化失败！']; }
        
        // 开始备份
        return $this->startBackup($Database, $this->getTableListIndex(), $lock);
    }
    
    /**
     * 开始备份
     */
    public function startBackup($database = null, $table_list = [], $lock = '')
    {
        
        $error_table = '';
        
        foreach ($table_list as $v)
        {
            
            $start  = $database->backup($v, 0);
            
            if ($start === false) { $error_table = $v; break; }
        }
        
        unlink($lock);
        
        session('backup_file', null);
        
        if (!empty($error_table)) { return [RESULT_ERROR, '备份出错，表名：' . $error_table]; }
        
        action_log('备份', '数据库备份');
        
        return [RESULT_SUCCESS, '备份成功'];
    }
    
    /**
     * 优化 or 修复 表
     */
    public function optimize($mark = true)
    {
        
        $table_list = $this->getTableListIndex();
        
        $tables = implode('`,`', $table_list);
        
        $list = $mark ? Db::query("OPTIMIZE TABLE `{$tables}`") : Db::query("REPAIR TABLE `{$tables}`");

        $text = $mark ? '优化' :  '修复';
        
        if (!$list) { return [RESULT_ERROR, $text . '出错']; }
        
        $mark ? action_log('优化', '数据库优化') : action_log('修复', '数据库修复');
        
        return [RESULT_SUCCESS, $text . '完成'];
    }
    
    /**
     * 获取备份列表
     */
    public function getBackupList()
    {
        
        $path = realpath($this->getBackupDir());

        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        
        $glob = new \FilesystemIterator($path,  $flag);
        
        return $this->backupListHandle($glob);
    }
    
    /**
     * 备份列表处理
     */
    public function backupListHandle($glob = null)
    {
        
        $list = [];
        
        foreach ($glob as $name => $file)
        {
            
            if (!preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) { continue; }
                
            $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

            $date = "{$name[0]}-{$name[1]}-{$name[2]}"; $time = "{$name[3]}:{$name[4]}:{$name[5]}"; $part = $name[6];

            if (isset($list["{$date} {$time}"])) {
                
                $info         = $list["{$date} {$time}"];
                
                $info['part'] = max($info['part'], $part);
                
                $info['size'] = $info['size'] + $file->getSize();
            } else {
                
                $info['part'] = $part;
                
                $info['size'] = $file->getSize();
            }
            
            $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
            
            $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
            
            $info['time']     = strtotime("{$date} {$time}");

            $list["{$date} {$time}"] = $info;
        }
        
        return $list;
    }
    
    /**
     * 删除备份文件
     */
    public function backupDel($time = 0)
    {
        
        $path = $this->getBackupPathByTime($time);
        
        array_map("unlink", glob($path));
        
        if (count(glob($path))) { return [RESULT_ERROR, '备份文件删除失败，请检查权限！']; }
        
        action_log('删除', '数据库备份文件删除，path：'. $path);
        
        return [RESULT_SUCCESS, '备份文件删除成功'];
    }
    
    /**
     * 数据还原
     */
    public function dataRestore($time = 0)
    {
        
        $path   = $this->getBackupPathByTime($time);
        
        $files  = glob($path);
        
        $list   = [];
        
        foreach($files as $name)
        {
            $basename = basename($name);
            $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
            $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
            $list[$match[6]] = array($match[6], $name, $gz);
        }
        
        ksort($list);

        // 检测文件正确性
        $last = end($list);
        
        if (!(count($list) === $last[0])) { return [RESULT_ERROR, '备份文件可能已经损坏，请检查！']; }
        
        // 开始还原
        return $this->startRestore($list);
    }
    
    
    /**
     * 开始还原
     */
    public function startRestore($list)
    {
        
        $path = $this->getBackupDir();
        
        $config = [
            'path'     => realpath($path) . SYS_DS_PROS,
            'compress' => config('data_backup_compress'),
        ];
        
        $error = '';
        
        foreach ($list as $file)
        {
        
            $database = new \ob\Database($file, $config);

            $start = $database->import(DATA_DISABLE);

            if (false === $start) { $error = '还原数据出错'; break; }
        }

        if (!empty($error)) { return [RESULT_ERROR, $error]; }
        
        action_log('还原', '数据库还原');
        
        return [RESULT_SUCCESS, '还原成功'];
    }

}
