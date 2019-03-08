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
 * 易宝
 */
class Yeepay extends Pay implements Driver
{
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return ['yeepay_merid' => '易宝商户号', 'yeepay_key' => '易宝密钥'];
    }
    
    /**
     * 支付宝基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '易宝支付驱动', 'driver_class' => 'Yeepay', 'driver_describe' => '易宝支付', 'author' => 'Bigotry', 'version' => '1.0'];
    }
    
    /**
     * 支付
     */
    public function pay($order)
    {
        
        return $this->getPayCode($order);
    }
    
    /**
     * 获取配置信息
     */
    public function config()
    {
        
        return $this->driverConfig('Yeepay');
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
        
        require_once('yeepay/yeepayCommon.php');
        
        $config = $this->config();
        
        #	商家设置用户购买商品的支付信息.
        ##易宝支付平台统一使用GBK/GB2312编码方式,参数如用到中文，请注意转码
        $data = array();
        #业务类型
        $data['p0_Cmd']             = "Buy";
        #	商户订单号,选填.
        $data['p1_MerId']           = $config['yeepay_merid'];
        ##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易宝支付会自动生成随机的商户订单号.
        $data['p2_Order']           = $order['order_sn'];
        #	支付金额,必填.
        ##单位:元，精确到分.
        $data['p3_Amt']             = $order['order_amount'];
        #	交易币种,固定值"CNY".
        $data['p4_Cur']             = "CNY";
        $data['p5_Pid']             = "";
        #	商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
        $data['p8_Url']             = config('notify_url');
        
        #签名串
        $data['hmac']               = HmacMd5(implode($data), $config['yeepay_key']);
        
        $sHtml = "<html><head><title>支付安全跳转中...</title><meta http-equiv='content-type' content=".'text/html; charset=utf-8'."></head><body onload='document.yeepay.submit();'>";
        
        //$sHtml .= "<p>付款后刷新此页面...</p>"; target='_blank'
        $sHtml .= "<form name='yeepay' action='".$reqURL_onLine."' method='post'>";
        
        foreach($data as $key => $val)
        {
            
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

        //submit按钮控件请不要含有name属性
        $sHtml.= "</form> <div style='text-align: center;margin: 50px;'><h1>支付安全跳转中...</h1></div></body></html>";
        
        return $sHtml;
    }
    
    /**
     * 获取订单号
     */
    public function getOrderSn()
    {
        
        if (!empty($_REQUEST['r6_Order'])) {
            
            return $_REQUEST['r6_Order'];
        }
        
        if (!empty($_GET['r6_Order'])) {
            
            return $_GET['r6_Order'];
        }
        
        return null;
    }
    
    /**
     * 支付通知处理
     */
    public function notify()
    {
        
        if ($_REQUEST['r9_BType'] == "2") {
            
            require_once('yeepay/yeepayCommon.php');
            
            $data = array();

            $config = $this->config();
            
            $data['p1_MerId']   = $_REQUEST['p1_MerId'];	
            $data['r0_Cmd']     = $_REQUEST['r0_Cmd'];
            $data['r1_Code']    = $_REQUEST['r1_Code'];
            $data['r2_TrxId']   = $_REQUEST['r2_TrxId'];
            $data['r3_Amt']     = $_REQUEST['r3_Amt'];
            $data['r4_Cur']	= $_REQUEST['r4_Cur']; 
            $data['r5_Pid']	= $_REQUEST['r5_Pid'] ;
            $data['r6_Order']	= $_REQUEST['r6_Order'];
            $data['r7_Uid']	= $_REQUEST['r7_Uid'];
            $data['r8_MP']	= $_REQUEST['r8_MP'] ;
            $data['r9_BType']	= $_REQUEST['r9_BType']; 
            $data['hmac']	= $_REQUEST['hmac'];
            $data['hmac_safe']  = $_REQUEST['hmac_safe'];

            $hmacLocal = HmacLocal($data, $config['yeepay_key']);
            
            $safeLocal = gethamc_safe($data, $config['yeepay_key']);
            
            $data['hmacLocal']	= $hmacLocal;
            $data['safeLocal']  = $safeLocal;
            
            // 验签
            if ($data['hmac'] != $hmacLocal || $data['hmac_safe'] != $safeLocal) {

               return false;
            }

            if ($data['r1_Code'] =="1") {

               return true;
            }
        }
        
        return false;
    }
}
