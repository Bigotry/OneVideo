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

namespace app\common\logic;

use think\Image;

/**
 * 文件处理逻辑
 */
class File extends LogicBase
{
    
    /**
     * 图片上传
     * small,medium,big
     */
    public function pictureUpload($name = 'file', $thumb_config = ['small' => 100, 'medium' => 500, 'big' => 1000])
    {
        
        $object_info = request()->file($name);
        
        $sha1  = $object_info->hash();
        
        $picture_info = $this->modelPicture->getInfo(['sha1' => $sha1], 'id,name,path,sha1');
        
        if (!empty($picture_info)) { return $picture_info; }
        
        $object = $object_info->move(PATH_PICTURE);
        
        $save_name = $object->getSaveName();
        
        $save_path = PATH_PICTURE . $save_name;
        
        $picture_dir_name = substr($save_name, 0, strrpos($save_name, DS));
        
        $filename = $object->getFilename();
        
        $thumb_dir_path = PATH_PICTURE . $picture_dir_name . DS . 'thumb';
        
        !file_exists($thumb_dir_path) && @mkdir($thumb_dir_path, 0777, true);
        
        Image::open($save_path)->thumb($thumb_config['small']   , $thumb_config['small'])->save($thumb_dir_path  . DS . 'small_'  . $filename);
        Image::open($save_path)->thumb($thumb_config['medium']  , $thumb_config['medium'])->save($thumb_dir_path . DS . 'medium_' . $filename);
        Image::open($save_path)->thumb($thumb_config['big']     , $thumb_config['big'])->save($thumb_dir_path    . DS . 'big_'    . $filename);
        
        $data = ['name' => $filename, 'path' => $picture_dir_name. SYS_DS_PROS . $filename, 'sha1' => $sha1];
        
        $result = $this->modelPicture->setInfo($data);

        unset($object);

        $url = $this->checkStorage($result);
        
        if ($result) { $data['id'] = $result; $url && $data['url'] = $url; return $data; }
        
        return  false;
    }
    
    /**
     * 文件上传
     */
    public function fileUpload($name = 'file')
    {
        
        $object_info = request()->file($name);
        
        $sha1  = $object_info->hash();
        
        $file_info = $this->modelFile->getInfo(['sha1' => $sha1], 'id,name,path,sha1');
        
        if (!empty($file_info)) {
         
            return $file_info;
        }
        
        $object = $object_info->move(PATH_FILE);
        
        $save_name = $object->getSaveName();
        
        $file_dir_name = substr($save_name, 0, strrpos($save_name, DS));
        
        $filename = $object->getFilename();
        
        $data = ['name' => $filename, 'path' => $file_dir_name. SYS_DS_PROS . $filename, 'sha1' => $sha1];
        
        $result = $this->modelFile->setInfo($data);

        unset($object);
        
        $url = $this->checkStorage($result, 'uploadFile');
        
        if ($result) {
            
            $data['id'] = $result;

            $url && $data['url'] = $url;
            
            return $data;
        }
        
        return  false;
    }
    
    /**
     * 云存储
     */
    public function checkStorage($result = 0, $method = 'uploadPicture')
    {
        
        $storage_driver = config('storage_driver');
        
        if (empty($storage_driver)) {
            
            return false;
        }
        
        $driver = SYS_DRIVER_DIR_NAME . $storage_driver;
        
        $storage_result = $this->serviceStorage->$driver->$method($result);
        
        $method != 'uploadPicture' ? $this->modelFile->setFieldValue(['id' => $result], 'url', $storage_result) : $this->modelPicture->setFieldValue(['id' => $result], 'url', $storage_result);

        return $storage_result;
    }

    /**
     * 获取指定目录下的所有文件
     * @param null $path
     * @return array
     */
    public function getFileByPath($path = null)
    {
        $dirs = new \FilesystemIterator($path);
        $arr = [];
        foreach ($dirs as $v)
        {
            if($v->isdir())
            {
                $_arr = $this->getFileByPath($path ."/". $v->getFilename());
                $arr = array_merge($arr,$_arr);
            }else{
                $arr[] = $path . "/" . $v->getFilename();
            }
        }
        return $arr;
    }

    public function checkPictureExists($param = []) {
        return $this->modelPicture->where('sha1',$param['sha1'])->find();
    }

    public function checkFileExists($param = []) {
        return $this->modelFile->where('sha1',$param['sha1'])->find();
    }
}
