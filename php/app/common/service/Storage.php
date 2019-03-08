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

namespace app\common\service;

/**
 * 云存储服务
 */
class Storage extends ServiceBase implements BaseInterface
{
    
    /**
     * 服务基本信息
     */
    public function serviceInfo()
    {
        
        return ['service_name' => '云存储服务', 'service_class' => 'Storage', 'service_describe' => '系统云存储服务，用于整合多个云储存平台', 'author' => 'Bigotry', 'version' => '1.0'];
    }

    protected function pictureDel($path)
    {
        $info = explode(SYS_DS_PROS,$path);
        $file_url = PATH_PICTURE . $path;
        unlink(str_replace('\\','/',$file_url));

        $big_path       = PATH_PICTURE . $info[0] . DS . 'thumb' . DS . 'big_'       . $info[1];
        $medium_path    = PATH_PICTURE . $info[0] . DS . 'thumb' . DS . 'medium_'    . $info[1];
        $small_path     = PATH_PICTURE . $info[0] . DS . 'thumb' . DS . 'small_'     . $info[1];

        $big_path = str_replace('\\','/',$big_path);
        $medium_path = str_replace('\\','/',$medium_path);
        $small_path = str_replace('\\','/',$small_path);

        file_exists($big_path)      && unlink($big_path);
        file_exists($medium_path)   && unlink($medium_path);
        file_exists($small_path)    && unlink($small_path);
    }

    protected function fileDel($path)
    {
        $file_url = PATH_FILE . $path;
        unlink(str_replace('\\','/',$file_url));
    }

}
