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

namespace app\common\service\storage\driver;

use app\common\service\storage\Driver;
use app\common\service\Storage;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * 七牛云
 */
class Qiniu extends Storage implements Driver
{
    
    /**
     * 驱动基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '七牛云驱动', 'driver_class' => 'Qiniu', 'driver_describe' => '七牛云存储', 'author' => 'Bigotry', 'version' => '1.0'];
    }
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return ['access_key' => '七牛云密钥AK', 'secret_key' => '七牛云密钥SK', 'bucket_name' => '上传空间名Bucket'];
    }
    
    /**
     * 获取配置信息
     */
    public function config()
    {
        
        return $this->driverConfig('Qiniu');
    }
    
    /**
     * 上传图片
     */
    public function uploadPicture($file_id = 0)
    {
        
        $token = $this->getToken();
        
        $uploadMgr = new UploadManager();

        $info = $this->modelPicture->getInfo(['id' => $file_id]);
        
        $path_arr = explode(SYS_DS_PROS, $info['path']); 
  
        $file_path = PATH_PICTURE . $path_arr[0] . DS . $path_arr[1];
        
        $save_path = 'upload' . SYS_DS_PROS . 'picture' . SYS_DS_PROS . $path_arr[0] . SYS_DS_PROS . $path_arr[1];
        
        $result = $uploadMgr->putFile($token, $save_path, $file_path);
        
        $thumb_file_path = PATH_PICTURE . $path_arr[0] . DS . 'thumb' . DS;
        $thumb_save_path = 'upload' . SYS_DS_PROS . 'picture' . SYS_DS_PROS . $path_arr[0] . SYS_DS_PROS . 'thumb' . SYS_DS_PROS;
        
        $uploadMgr->putFile($token, $thumb_save_path . 'small_'   . $path_arr[1]   , $thumb_file_path . 'small_'   . $path_arr[1]);
        $uploadMgr->putFile($token, $thumb_save_path . 'medium_'  . $path_arr[1]   , $thumb_file_path . 'medium_'  . $path_arr[1]);
        $uploadMgr->putFile($token, $thumb_save_path . 'big_'     . $path_arr[1]   , $thumb_file_path . 'big_'     . $path_arr[1]);
        
        if ($result[1] !== null) {
            
            return false;
        }

        //$this->pictureDel($info['path']);
        
        return $result[0]['key'];
    }
    
    
    /**
     * 获取Token
     */
    public function getToken()
    {
        
        $config = $this->config();
        
        $auth = new Auth($config['access_key'], $config['secret_key']);

        $token = $auth->uploadToken($config['bucket_name']);
        
        return $token;
    }
    
    /**
     * 上传文件
     */
    public function uploadFile($file_id = 0)
    {
        
        $token = $this->getToken();
        
        $uploadMgr = new UploadManager();

        $info = $this->modelFile->getInfo(['id' => $file_id]);
        
        $path_arr = explode(SYS_DS_PROS, $info['path']); 
        
        $file_path = PATH_FILE . $path_arr[0] . DS . $path_arr[1];
        
        $save_path = 'upload' . SYS_DS_PROS . 'file' . SYS_DS_PROS . $path_arr[0] . SYS_DS_PROS . $path_arr[1];
        
        $result = $uploadMgr->putFile($token, $save_path, $file_path);
        
        if ($result[1] !== null) {
            
            return false;
        }

        //$this->fileDel($info['path']);
        
        return $result[0]['key'];
    }

    public function deletePicture($file_id = 0)
    {
        $config = $this->config();

        $auth = new Auth($config['access_key'], $config['secret_key']);

        $_config = new \Qiniu\Config();
        $bucketManager = new \Qiniu\Storage\BucketManager($auth, $_config);

        $info = $this->modelPicture->getInfo(['id' => $file_id]);

        $path_arr = explode(SYS_DS_PROS, $info['path']);

        $save_path = 'upload' . SYS_DS_PROS . 'picture' . SYS_DS_PROS . $path_arr[0] . SYS_DS_PROS . $path_arr[1];

        $err = $bucketManager->delete($config['bucket_name'], $save_path);

        if ($err) {
            return $err;
        }
        return false;
    }

    public function deleteFile($file_id = 0)
    {
        $config = $this->config();

        $auth = new Auth($config['access_key'], $config['secret_key']);

        $_config = new \Qiniu\Config();
        $bucketManager = new \Qiniu\Storage\BucketManager($auth, $_config);

        $info = $this->modelFile->getInfo(['id' => $file_id]);

        $path_arr = explode(SYS_DS_PROS, $info['path']);

        $save_path = 'upload' . SYS_DS_PROS . 'file' . SYS_DS_PROS . $path_arr[0] . SYS_DS_PROS . $path_arr[1];

        $thumb_save_path = 'upload' . SYS_DS_PROS . 'picture' . SYS_DS_PROS . $path_arr[0] . SYS_DS_PROS . 'thumb' . SYS_DS_PROS;

        $err = $bucketManager->delete($config['bucket_name'], $save_path);
        $bucketManager->delete($config['bucket_name'], $thumb_save_path . 'small_'    . $path_arr[1]);
        $bucketManager->delete($config['bucket_name'], $thumb_save_path . 'medium_'    . $path_arr[1]);
        $bucketManager->delete($config['bucket_name'], $thumb_save_path . 'big_'    . $path_arr[1]);

        if ($err) {
            return $err;
        }
        return false;
    }
}
