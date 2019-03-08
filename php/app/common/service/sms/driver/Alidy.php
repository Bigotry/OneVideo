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

namespace app\common\service\sms\driver;

use app\common\service\sms\Driver;
use app\common\service\Sms;

/**
 * 阿里大鱼短信服务驱动
 */
class Alidy extends Sms implements Driver
{
    
    /**
     * 驱动基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '阿里大鱼驱动', 'driver_class' => 'Alidy', 'driver_describe' => '阿里大鱼短信驱动', 'author' => 'Bigotry', 'version' => '1.0'];
    }
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return ['access_key' => '阿里大鱼密钥AK', 'secret_key' => '阿里大鱼密钥SK'];
    }
    
    /**
     * 获取配置信息
     */
    public function config()
    {
        
        return $this->driverConfig('Alidy');
    }
    
    /**
     * 发送短信
     */
    public function sendSms($parameter = [])
    {
        
        $alidy_config = $this->config();
        
        $sms = new alidy\SmsApi($alidy_config['access_key'], $alidy_config['secret_key']);
        
        $response = $sms->sendSms(
                
                    $parameter['sign_name'],
                    $parameter['template_code'],
                    $parameter['phone_number'],
                    $parameter['template_param']
                );
        
        return $response->Code == 'OK' ? true : false;
    }
   
}
