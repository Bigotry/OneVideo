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
 * 文件清理逻辑
 */
class FileClean extends AdminBase
{
    
    /**
     * 获取文件列表
     */
    public function getFileList()
    {
        
        $picture_list = $this->getFileListByPath(PATH_PICTURE);
        
        $file_list    = $this->getFileListByPath(PATH_FILE);
        
        return compact('picture_list', 'file_list');
    }
    
    /**
     * 根据路径获取文件列表
     */
    public function getFileListByPath($path = '')
    {
        
        $data_list = [];
        
        rm_empty_dir($path);
        
        if (!is_dir($path)) {
            return $data_list;
        }

        $dirs = new \FilesystemIterator($path);
        
        foreach ($dirs as $dir)
        {
            
            if (!$dir->isDir()) { continue; }

            $files = new \FilesystemIterator($path . $dir->getFilename());
            
            foreach ($files as $file)
            {

                if ($file->isFile()) {
                    
                    $data['file_name']  = $file->getFilename();
                    $data['file_size']  = format_bytes($file->getSize());
                    $data['file_ctime'] = format_time($file->getCTime());
                    $data['file_path']  = $file->getPath();
                    
                    $data_list[] = $data;
                }
            }
        }
        
        return $data_list;
    }
    
    /**
     * 获取文件清理列表
     */
    public function getFileClearList()
    {
        
        $list = $this->getFileList();
        
        $picture_list = $this->getFileShowList($this->modelPicture, $list, 'picture_list');
        
        $file_list = $this->getFileShowList($this->modelFile, $list, 'file_list');
        
        return array_merge($picture_list, $file_list);
    }
    
    /**
     * 获取文件清理显示列表
     */
    public function getFileShowList($model = null, $file_list = [], $field = '')
    {
        
        $file_list_temp = $file_list[$field];
        
        $file_name_list = array_extract($file_list_temp, 'file_name');
        
        $list = $model->getList(['name' => ['in', $file_name_list]], 'id,name', TIME_CT_NAME, false);
                
        $db_file_name_list = array_extract($list, 'name');
        
        $diff_list = array_diff($file_name_list, $db_file_name_list);
        
        $return_list = [];
        
        foreach ($file_list_temp as $file)
        {
            in_array($file['file_name'], $diff_list) && $return_list[] = $file;
        }
        
        return $return_list;
    }
    
    /**
     * 文件清理
     */
    public function fileClear()
    {
        
        $this->fileClearFile();
        
        $this->fileClearDb();
        
        action_log('清理', '文件清理');
        
        return [RESULT_SUCCESS, '文件清理成功'];
    }
    
    /**
     * 物理文件清理
     */
    public function fileClearFile()
    {
        
        $list = session('file_clear_list');
        
        foreach ($list as $info)
        {
            
            unlink($info['file_path'] . DS . $info['file_name']);
            
            $big_path       = $info['file_path'] . DS . 'thumb' . DS . 'big_'       . $info['file_name'];
            $medium_path    = $info['file_path'] . DS . 'thumb' . DS . 'medium_'    . $info['file_name'];
            $small_path     = $info['file_path'] . DS . 'thumb' . DS . 'small_'     . $info['file_name'];
            
            file_exists($big_path)      && unlink($big_path);
            file_exists($medium_path)   && unlink($medium_path);
            file_exists($small_path)    && unlink($small_path);
        }
        
        session('file_clear_list', null);
    }
    
    /**
     * 文件数据库清理
     */
    public function fileClearDb()
    {
        
        $this->fileClearDbByType('picture');
        
        $this->fileClearDbByType('file');
    }
    
    /**
     * 根据文件类型清理数据库
     */
    public function fileClearDbByType($type = 'picture')
    {
        
        $sys_field = $type == 'picture' ? parse_config_array('sys_picture_field') : parse_config_array('sys_file_field');
        
        $file_ids = [];
        
        foreach ($sys_field as $k => $v)
        {
            
            $list_ids = model(substr($k,2))->getColumn([], $v);
            
            foreach ($list_ids as $id)
            {
                !is_numeric($id) && $list_ids = array_merge($list_ids, str2arr($id));
            }
            
            $file_ids = array_merge($file_ids, $list_ids);
        }
        
        $allow_ids = array_values(array_unique($file_ids));
        
        $model = $type == 'picture' ? $this->modelPicture : $this->modelFile;

        $fn = $type == 'picture' ? "deletePicture" : "deleteFile";

        $this->deleteYunFile($model,$fn,$allow_ids);
        
        $model->deleteInfo(['id' => ['not in', $allow_ids]], true);
    }

    public function deleteYunFile($model = null,$fn = "",$allow_ids = [])
    {
        $storage_driver = config('storage_driver');

        if (empty($storage_driver)) {

            return false;
        }

        $driver = SYS_DRIVER_DIR_NAME . $storage_driver;

        $list = $model->where(['id' => ['not in', $allow_ids]])->select();
        foreach ($list as $v)
        {
            if(!empty($v['url']))
            {
                $this->serviceStorage->$driver->$fn($v['id']);
            }
        }
    }
}
