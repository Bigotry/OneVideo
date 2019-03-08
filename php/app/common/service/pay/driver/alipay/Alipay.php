<?php  

    class Alipay{
        public $alipay_config = array(  
          //appid
           'appid' =>'',
            //商户密钥
           'rsaPrivateKey' =>'',//私钥
           //支付宝公钥
            'alipayrsaPublicKey'=>'',//公钥(自己的程序里面用不到)
           'partner'=>'',//(商家的参数,新版本的好像用不到)
           'input_charset'=> 'utf-8',//编码
           'notify_url' =>'',//回调地址(支付宝支付成功后回调修改订单状态的地址)
           'payment_type' =>1,//(固定值)
           'seller_id'  =>  '',//合作伙伴身份（PID）
           'charset'    => 'utf-8',//编码
           'sign_type' => 'RSA2',//签名方式
           'timestamp' =>'',
           'version'   =>"1.0",//固定值
           'url'       => 'https://openapi.alipay.com/gateway.do',//固定值
           'method'    => 'alipay.trade.app.pay',//固定值
         );

        function __construct($params=[]){
            $this->alipay_config['timestamp'] = date("Y-m-d H:i:s");
            $this->alipay_config['seller_id'] = $params['alipay_partner'];
            $this->alipay_config['appid'] = $params['alipay_appid'];
            $this->alipay_config['rsaPrivateKey'] = $params['alipay_rsaPrivateKey'];
            $this->alipay_config['alipayrsaPublicKey'] = $params['alipay_alipayrsaPublicKey'];
        }

        //生成提交支付宝参数数组
        public function createAppPara($params=array()) {

            //构造业务请求参数的集合(订单信息)
           $content = array();
           $content['body'] = '';
           $content['subject'] = $params['subject'];//商品的标题/交易标题/订单标题/订单关键字等
           $content['out_trade_no'] = $params['out_trade_no'];//商户网站唯一订单号
           $content['timeout_express'] = '1d';//该笔订单允许的最晚付款时间
           $content['total_amount'] = floatval($params['price']);//订单总金额(必须定义成浮点型)
           $content['product_code'] = 'QUICK_MSECURITY_PAY';//销售产品码，商家和支付宝签约的产品码，为固定值QUICK_MSECURITY_PAY
           // $content['store_id'] = 'BJ_001';//商户门店编号
           $con = json_encode($content);//$content是biz_content的值,将之转化成字符串
              //公共参数
           $param = array();
            require_once "AopClient.php";
           $Client = new AopClient();//实例化支付宝sdk里面的AopClient类,下单时需要的操作,都在这个类里面
           $param['app_id'] = $this->alipay_config['appid'];//支付宝分配给开发者的应用ID
           $param['method'] = $this->alipay_config['method'];//接口名称
           $param['charset'] = $this->alipay_config['charset'];//请求使用的编码格式
           $param['sign_type'] = $this->alipay_config['sign_type'];//商户生成签名字符串所使用的签名算法类型
           $param['timestamp'] = $this->alipay_config['timestamp'];//发送请求的时间
           $param['version'] = $this->alipay_config['version'];//调用的接口版本，固定为：1.0
           $param['notify_url'] = $this->alipay_config['notify_url'];//支付宝服务器主动通知地址
           $param['biz_content'] = $con;//业务请求参数的集合,长度不限,json格式
           //生成签名
           $paramStr = $Client->getSignContent($param);
           $sign = $Client->alonersaSign($paramStr,$this->alipay_config['rsaPrivateKey'],'RSA2',is_file($this->alipay_config['rsaPrivateKey'])?true:false);
            $param['sign'] = $sign;
            $str = $Client->getSignContentUrlencode($param);
            return $str;
        }
    }
?>