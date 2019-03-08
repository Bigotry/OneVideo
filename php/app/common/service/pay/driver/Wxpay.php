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

use app\api\error\Common;
use app\common\service\pay\Driver;
use app\common\service\Pay;

/**
 * 微信支付
 */
class Wxpay extends Pay implements Driver
{
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return ['appid' => 'appid 微信公众号唯一标识', 'appsecret' => 'appsecret', 'partnerid' => '受理商ID（商户号）', 'partnerkey' => '商户支付密钥Key'];
    }
    
    /**
     * 微信支付基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '微信支付驱动', 'driver_class' => 'Wxpay', 'driver_describe' => '微信支付', 'author' => 'Bigotry', 'version' => '1.0'];
    }
    
    /**
     * 支付
     */
    public function pay($order=[],$type='web')
    {
        switch ($type)
        {
            case 'app' :
                return $this->getPrePay($order);
                break;
            case 'web':
                return $this->getPayCode($order);
                break;
            case 'h5':
                return $this->getH5Pay($order);
                break;
            case 'JSAPI':
                return $this->getJsApi($order);
                break;
            default:
                //补充...
                break;
        }
    }
    
    /**
     * 获取配置信息
     */
    public function config()
    {
        
        $wxpay_config['curl_timeout']   = 30;
        $wxpay_config['notify_url'] = Pay::NOTIFY_URL;
        $db_config = $this->driverConfig('Wxpay');
        
        return array_merge($wxpay_config, $db_config);
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
        
        require_once('wxpay/WxPayPubHelper.php');

        //使用统一支付接口
        $unifiedOrder = new wxpay\UnifiedOrder_pub();
        
        
        $unifiedOrder->setParameter("body", $order['body']);//商品描述
        //自定义订单号，此处仅作举例
        $config = $this->config();
        
        $unifiedOrder->setConfig($config);

        $unifiedOrder->setParameter("out_trade_no",$order['order_sn']);			//商户订单号
        // $unifiedOrder->setParameter("fee_type","USD");
        $unifiedOrder->setParameter("total_fee",$order['order_amount']*100);	//总金额
        $unifiedOrder->setParameter("notify_url", Pay::NOTIFY_URL);				//通知地址
        $unifiedOrder->setParameter("trade_type","NATIVE");						//交易类型

        //获取统一支付接口结果
        $unifiedOrderResult = $unifiedOrder->getResult();
        //商户根据实际情况设置相应的处理流程
        if ($unifiedOrderResult["return_code"] == "FAIL") {
                //商户自行增加处理流程
                echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";
        } elseif ($unifiedOrderResult["result_code"] == "FAIL") {
                //商户自行增加处理流程
                echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
                echo "错误代码描述：".$unifiedOrderResult['err_code_des']."<br>";
        } elseif ($unifiedOrderResult["code_url"] != NULL) {
                //从统一支付接口获取到code_url
                $code_url = $unifiedOrderResult["code_url"];
                //商户自行增加处理流程
                //......
        }
        /**/

        //模版输出
        ob_start();
        
        require_once('wxpay/tmp.php');
        
        $info = ob_get_contents();
        
        ob_clean();

        return $info;
    }

    /**
     * APP支付
     * @param array $order
     * @return array|bool|mixed
     */
    public function getPrePay($order=[])
    {
        require_once "wxpay/Wxpay.php";
        $wx = new \Wxpay($this->config());
        if(empty($order)) {
            $order = [
                "body"=>"测试",
                "out_trade_no" => date("YmdHis") . mt_rand(1000,9999) . time(),
                "total_fee" => 0.01,
                "spbill_create_ip" => $wx->get_client_ip()
            ];
        }
        $result_data = $wx->getPrepay($order);
        return $result_data;
    }

    /**
     * H5支付
     * @param array $order
     * @return array|bool|mixed
     */
    public function getH5Pay($order=[])
    {
        require_once "wxpay/Wxpay.php";
        $wx = new \Wxpay($this->config());
        if(empty($order)) {
            $order = [
                "body"=>"测试",
                "out_trade_no" => date("YmdHis") . mt_rand(1000,9999) . time(),
                "total_fee" => 0.01,
                "spbill_create_ip" => $wx->get_client_ip(),
                "trade_type"=>"MWEB",
                "scene_info"=>"{\"h5_info\": {\"type\":\"Wap\",\"wap_url\": \"https://pay.qq.com\",\"wap_name\": \"腾讯充值\"}} "
            ];
        }
        $result_data = $wx->getPrepay($order);
        return $result_data;
    }

    public function getJsApi($order = [])
    {
        require_once "wxpay/Wxpay.php";
        $wx = new \Wxpay($this->config());
        return $wx->getPrepay($order);
    }
    
    //设置配置信息
    private function setParameter($obj)
    {
        
        //获取当前支付方式的配置信息
        $CONFIG = $this->config();

        //数据库配置信息
        $obj->setParameter("appid",$CONFIG['appid']);
        $obj->setParameter("secret",$CONFIG['appsecret']);
        $obj->setParameter("mch_id",$CONFIG['partnerid']);
        $obj->setParameter("partnerkey",$CONFIG['partnerkey']);
        $obj->setParameter("curl_timeout",$CONFIG['curl_timeout']);
        $obj->curl_timeout = $CONFIG['curl_timeout'];
    }
    
    
    //（微信服务器）订单查询，获取返回的所有信息
    public function order_query($out_trade_no)
    {

        //建立请求
        require_once('wxpay/WxPayPubHelper.php');

        //使用订单查询接口
        $orderQuery = new wxpay\OrderQuery_pub();

        //设置必填参数
        $orderQuery->setConfig( $this->config() );
        $orderQuery->setParameter("out_trade_no","$out_trade_no");//商户订单号

        //获取订单查询结果
        $orderQueryResult = $orderQuery->getResult();

        return $orderQueryResult;
    }
	
    //订单是否成功支付
    public function is_paid($out_trade_no)
    {
        
        $orderQueryResult = $this->order_query($out_trade_no);
        
        if ($orderQueryResult['trade_state'] == 'SUCCESS') {

            return 1;
        }

        return 0;
    }
	
    //打印订单详情，测试用
    public function print_order_query($out_trade_no)
    {

        $orderQueryResult = $this->order_query($out_trade_no);

        //商户根据实际情况设置相应的处理流程,此处仅作举例
        if ($orderQueryResult["return_code"] == "FAIL") {
                echo "通信出错：".$orderQueryResult['return_msg']."<br>";
        } elseif ($orderQueryResult["result_code"] == "FAIL") {
                echo "错误代码：".$orderQueryResult['err_code']."<br>";
                echo "错误代码描述：".$orderQueryResult['err_code_des']."<br>";
        } else {
                echo "交易状态：".$orderQueryResult['trade_state']."<br>";
                echo "设备号：".$orderQueryResult['device_info']."<br>";
                echo "用户标识：".$orderQueryResult['openid']."<br>";
                echo "是否关注公众账号：".$orderQueryResult['is_subscribe']."<br>";
                echo "交易类型：".$orderQueryResult['trade_type']."<br>";
                echo "付款银行：".$orderQueryResult['bank_type']."<br>";
                echo "总金额：".$orderQueryResult['total_fee']."<br>";
                echo "现金券金额：".$orderQueryResult['coupon_fee']."<br>";
                echo "货币种类：".$orderQueryResult['fee_type']."<br>";
                echo "微信支付订单号：".$orderQueryResult['transaction_id']."<br>";
                echo "商户订单号：".$orderQueryResult['out_trade_no']."<br>";
                echo "商家数据包：".$orderQueryResult['attach']."<br>";
                echo "支付完成时间：".$orderQueryResult['time_end']."<br>";
        }
    }
    
    /**
     * 获取订单号
     */
    public function getOrderSn()
    {
        
        $xml = file_get_contents("php://input");

        require_once('wxpay/WxPayPubHelper.php');

        $notify = new wxpay\Notify_pub();

        $retArr = $notify->xmlToArray($xml);

        $order_sn = str_replace("mob", "", $retArr['out_trade_no']);

        return $order_sn;
    }
    
    /**
     * 支付通知处理
     */
    public function notify()
    {

        //建立请求
        require_once('wxpay/WxPayPubHelper.php');

        //使用通用通知接口
        $notify = new wxpay\Notify_pub();

        //存储微信的回调
        $xml = file_get_contents("php://input");
        
        $notify->saveData($xml);

        $CONFIG = $this->config();
        
        $notify->setConfig( $CONFIG );
        
        
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if ($notify->checkSign() == FALSE) {
                $notify->setReturnParameter("return_code","FAIL");//返回状态码
                $notify->setReturnParameter("return_msg","签名失败");//返回信息
        } else {
                $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }

        //订单状态
        if ($notify->checkSign()==TRUE && $notify->data["return_code"] == "SUCCESS") {
            
            return $notify->data;
        }

        return false;
    }
}
