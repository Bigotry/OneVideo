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

namespace app\common\service\pay\driver;

use app\common\service\pay\Driver;
use app\common\service\Pay;

/**
 * 支付宝
 */
class Alipay extends Pay implements Driver
{
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return ['alipay_account' => '支付宝帐户', 'alipay_partner' => '合作身份者id', 'alipay_key' => '安全检验码',"alipay_appid"=>"支付宝appid","alipay_rsaPrivateKey"=>"商户私钥(可填写路径)","alipay_alipayrsaPublicKey"=>"支付宝公钥(可填写路径)"];
    }
    
    /**
     * 支付宝基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '支付宝驱动', 'driver_class' => 'Alipay', 'driver_describe' => '支付宝支付', 'author' => 'Bigotry', 'version' => '1.0'];
    }
    
    /**
     * 支付
     */
    public function pay($order,$type='web')
    {
        if($type == 'web') {
            return $this->getPayCode($order);
        }elseif ($type == 'app'){
            return $this->createAppPara($order);
        }else{
            //补充...
        }
    }
    
    /**
     * 获取配置信息
     */
    public function config()
    {
        
        $alipay_config['sign_type']    	= strtoupper('MD5');
        $alipay_config['input_charset']	= strtolower('utf-8');
        $alipay_config['transport']    	= 'http';			//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $alipay_config['cacert']    	= 'alipay/cacert.pem';	//ca证书路径地址，用于curl中ssl校验//请保证cacert.pem文件在当前文件夹目录中
        $alipay_config['notify_url']    = Pay::NOTIFY_URL;
        $db_config = $this->driverConfig('Alipay');
        
        return array_merge($alipay_config, $db_config);
    }
    
    /**
     * 获取支付代码
     * @param array $order	订单信息数组
     * 			主要元素
     * 				order_sn		订单号
     * 				subject			商品名称
     * 				order_amount            订单总额
     * 				body			订单描述
     * 				show_url
     * @return multitype:string
     */
    public function getPayCode($order)
    {
        
        $alipay_config = $this->config();

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" 			=> "create_direct_pay_by_user",
            "partner" 			=> trim($alipay_config['alipay_partner']),
            "payment_type"		=> 1,
            "notify_url"		=> Pay::NOTIFY_URL,		//服务器异步通知页面路径,支付宝服务器主动通知商户网站里指定的页面http路径。
            "return_url"		=> Pay::CALLBACK_URL,		//页面跳转同步通知页面路径,支付宝处理完请求后，当前页面自动跳转到商户网站里指定页面的http路径。
            "seller_email"		=> trim($alipay_config['alipay_account']),
            "out_trade_no"		=> $order['order_sn'],
            "subject"			=> !empty($order['subject']) ? $order['subject'] : $order['body'],			//商品的标题/交易标题/订单标题/订单关键字等。该参数最长为128个汉字。
            "total_fee"			=> $order['order_amount'],		//该笔订单的资金总额，单位为RMB-Yuan。取值范围为[0.01，100000000.00]，精确到小数点后两位。
            "body"			=> $order['body'],				//对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加传给body。
            "show_url"			=> !empty($order['show_url']) ? $order['show_url'] : '',			//商品展示网址。收银台页面上，商品展示的超链接。
            "anti_phishing_key"         => trim($alipay_config['alipay_key']),
            "exter_invoke_ip"           => $_SERVER["REMOTE_ADDR"],
            "_input_charset"            => 'utf-8'
        );
        
        require_once('alipay/AlipaySubmit.php');
        
        $alipaySubmit = new alipay\AlipaySubmit($alipay_config);
        
        return $alipaySubmit->buildRequestForm($parameter);
    }

    /**
     * APP支付
     * @param $order
     * @return string
     */
    public function createAppPara($order)
    {
        require_once "alipay/Alipay.php";
        $alipay = new \Alipay($this->config());
        if(empty($order)){
            $order = [
                'subject'=>"测试",
                'out_trade_no'=>date("YmdHis").getRandom().time(),
                'price'=>0.01
            ];
        }
        return $alipay->createAppPara($order);
    }

    
    /**
     * 获取订单号
     */
    public function getOrderSn()
    {
        
        if (!empty($_POST['out_trade_no'])) {
            
            return $_POST['out_trade_no'];
        }
        
        if (!empty($_GET['out_trade_no'])) {
            
            return $_GET['out_trade_no'];
        }
        
        return null;
    }
    
    /**
     * 支付通知处理
     */
    public function notify()
    {
        
        $alipay_config = $this->config();

        require_once('alipay/AlipayNotify.php');
        
        $alipayNotify = new alipay\AlipayNotify($alipay_config);

        //验证是否为支付宝发送过来的请求
            
        $verify_result = $alipayNotify->verifyNotify();

        $successArr = array('TRADE_FINISHED','TRADE_SUCCESS','success');
        
        if ($verify_result && in_array($_POST['trade_status'], $successArr)){

            return true;
        }
        
        return false;
    }
}
