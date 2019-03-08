<?php

    class Wxpay{
        private $appid = '';//微信平台的appid
        private $mch_id = '';//商户号
        private $app_key = '';//商户密钥
        private $sign='';//签名
        private $notify_url = \app\common\service\Pay::NOTIFY_URL;//回调地址
        private $trade_type = 'APP';//支付类型
        private $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';//统一下单请求地址
        private $queryUrl = "https://api.mch.weixin.qq.com/pay/orderquery";//查询订单地址

        private $payUrl = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";//企业付款到零钱接口
        private $out_time = 30;
        public function __construct($param=[])
        {
            $this->appid = isset($param['appid'])?$param['appid']:$this->appid;
            $this->mch_id = isset($param['partnerid'])?$param['partnerid']:$this->mch_id;
            $this->app_key = isset($param['partnerkey'])?$param['partnerkey']:$this->app_key;
            $this->notify_url = isset($param['notify_url'])?$param['notify_url']:$this->notify_url;
            $this->out_time = isset($param['curl_timeout'])?$param['curl_timeout']:$this->out_time;
        }

        //统一下单
        public function getPrepay($arr=array()){
            if(empty($arr)){
                return false;
            }
            if(!isset($arr['out_trade_no']) || !isset($arr['total_fee']) || !isset($arr['spbill_create_ip']) || !isset($arr['body'])){
                return false;
            }
            //构造一个订单
            $order = array(
                "body" => $arr['body'],
                "appid" => $this->appid,
                "mch_id" => $this->mch_id,
                "nonce_str" => $this->getRandChar(32),
                "notify_url" => $this->notify_url,
                "out_trade_no" => $arr['out_trade_no'],
                "spbill_create_ip" => $arr['spbill_create_ip'],
                "total_fee" => intval($arr['total_fee'] * 100),//注意：前方有坑！！！最小单位是分，跟支付宝不一样。1表示1分钱。只能是整形。
                "trade_type" => empty($arr['trade_type']) ? $this->trade_type : $arr['trade_type']
            );
            !empty($arr['trade_type']) && $arr['trade_type'] == 'JSAPI' && $order['openid'] = $arr['openid'];
            //签名
            $this->sign = $this->getSign($order,$this->app_key);
            //请求服务器
            $xml = $this->ArrayToXml($order,$this->sign);
            //发起curl请求
            $result = $this->postXmlCurl($xml,$this->url,$this->out_time);
            //将xml转为数组
            $resultArr = $this->xmlToArray($result);
            if($resultArr['return_code'] == 'SUCCESS' && $resultArr['result_code'] == 'SUCCESS'){
                if($resultArr['trade_type'] == 'APP') {
                    $prepay = array(
                        "noncestr" => $resultArr['nonce_str'],
                        "prepayid" => $resultArr['prepay_id'],//上一步请求微信服务器得到nonce_str和prepay_id参数。
                        "appid" => $resultArr['appid'],
                        "package" => "Sign=WXPay",
                        "partnerid" => $resultArr['mch_id'],
                        "timestamp" => time()
                    );
                    //第二次验签
                    $sign = $this->getSign($prepay, $this->app_key);
                    $prepay['sign'] = $sign;
                    return $prepay;
                }elseif ($resultArr['trade_type'] == 'MWEB'){
                    return $resultArr['mweb_url'];
                }elseif ($resultArr['trade_type'] == 'JSAPI' ){
                    $prepay = [
                        'appId'=>$resultArr['appid'],
                        'timeStamp'=>''.time(),
                        'nonceStr'=>$this->getRandChar(32),
                        'package'=>"prepay_id=".$resultArr['prepay_id'],
                        'signType'=>'MD5'
                    ];
                    $sign = $this->getSign($prepay,$this->app_key);
                    $prepay['paySign'] = $sign;
                    //前端调起支付需要json不能encode编码
                    return $prepay;
                } else{
                    return false;
                }
            }else{
                return $resultArr;
            }
        }


        /**
         * 查询订单状态
         * @param  string $out_trade_no 订单号
         * @return boolean               订单查询结果
         */
        public function queryOrder($out_trade_no) {
            $nonce_str = $this->getRandChar(32);
            $data = array(
                'appid'        =>    $this->appid,
                'mch_id'    =>    $this->mch_id,
                'out_trade_no'    =>    $out_trade_no,
                'nonce_str'            =>    $nonce_str
            );
            $sign = $this->getSign($data,$this->app_key);
            $xml_data = $this->ArrayToXml($data,$sign);
            $result = $this->postXmlCurl($xml_data,$this->queryUrl,$this->out_time);
            $resultArr = $this->xmlToArray($result);
            if($resultArr['trade_state'] == "SUCCESS" && $resultArr['return_code'] == "SUCCESS" && $resultArr['result_code'] == "SUCCESS"){
                return $resultArr;
            }else {
                return false;
            }
        }


        /**
         * 获取指定长度的随机字符串
         * @param int $length
         * @return string <NULL, string>
         */
        function getRandChar($length){
           $str = null;
           $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
           $max = strlen($strPol)-1;
           for($i=0;$i<$length;$i++){
                $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
           }
           return $str;
        }


        /**
         * 以post方式提交xml到对应的接口url
         *
         * @param string $xml  需要post的xml数据
         * @param string $url  url
         * @param bool $useCert 是否需要证书，默认不需要
         * @param int $second   url执行超时时间，默认30s
         * @throws WxPayException
         */
        function postXmlCurl($xml, $url, $second=30, $useCert=false, $sslcert_path='', $sslkey_path='')
        {
            $ch = curl_init();
            //设置超时
            curl_setopt($ch, CURLOPT_TIMEOUT, $second);
            curl_setopt($ch,CURLOPT_URL, $url);

            //设置header
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            //要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);

            if($useCert == true){
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
                curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
                //设置证书
                //使用证书：cert 与 key 分别属于两个.pem文件
                curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
                curl_setopt($ch,CURLOPT_SSLCERT, $sslcert_path);
                curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
                curl_setopt($ch,CURLOPT_SSLKEY, $sslkey_path);
            }
            //post提交方式
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            //运行curl
            $data = curl_exec($ch);

            //返回结果
            if($data){
                curl_close($ch);
                return $data;
            } else {
                $error = curl_errno($ch);

                curl_close($ch);
                return false;
            }
        }

        /**
         * 获取当前服务器的IP
         * @return Ambigous <string, unknown>
         */
        function get_client_ip()
        {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $cip = $_SERVER['REMOTE_ADDR'];
            } elseif (getenv("REMOTE_ADDR")) {
                $cip = getenv("REMOTE_ADDR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $cip = getenv("HTTP_CLIENT_IP");
            } else {
                $cip = "127.0.0.1";
            }
            return $cip;
        }

        /**
         * XML转数组
         * @param unknown $xml
         * @return mixed
         */
        protected function xmlToArray($xml)
        {
            //将XML转为array
            $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
            return $array_data;
        }

        /**
         * 获取参数签名；
         * @param  array  $params 要传递的参数数组
         * @return String 通过计算得到的签名；
         */
        private function getSign($params,$app_key,$type=1) {
            ksort($params);        //将参数数组按照参数名ASCII码从小到大排序
            foreach ($params as $k => $item) {
                if (!empty($item)) {         //剔除参数值为空的参数
                    $newArr[] = $k.'='.$item;     // 整合新的参数数组
                }
            }
            $stringA = join("&", $newArr);         //使用 & 符号连接参数
            $stringSignTemp = $stringA."&key=".$app_key;        //拼接key
            // key是在商户平台API安全里自己设置的
            if($type) {
                $stringSignTemp = md5($stringSignTemp);       //将字符串进行MD5加密
                $sign = strtoupper($stringSignTemp);      //将所有字符转换为大写
            }else {
                $sign = hash_hmac("sha256",$stringSignTemp,$app_key);
            }
            return $sign;
        }

        /**
         * 数组转xml
         * @param $arr
         * @param $sign
         * @return string
         */
        private  function ArrayToXml($arr, $sign){
            $xml="<xml>\n";
            foreach ($arr as $key => $value) {
                $xml.="<".$key.">".$value."</".$key.">\n";
            }
            $xml.="<sign>".$sign."</sign>\n";
            $xml.="</xml>";
            return $xml;
        }

        /**
         * 异步通知信息验证
         * @return boolean|mixed
         */
        public function verifyNotify()
        {
            $xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
            if(!$xml){
                return false;
            }
            $wx_back = $this->xmlToArray($xml);
            if(empty($wx_back)){
                return false;
            }
            $checkSign = $this->getVerifySign($wx_back, $this->app_key);
            if($checkSign==$wx_back['sign']){
                return $wx_back;
            }
            return false;
        }

        /**
         * 验证签名
         * @param array $data
         * @param string $key
         * @return string
         */
        protected function getVerifySign($data, $key)
        {
            $String = $this->formatParameters($data, false);
            //签名步骤二：在string后加入KEY
            $String = $String . "&key=" . $key;
            //签名步骤三：MD5加密
            $String = md5($String);
            //签名步骤四：所有字符转为大写
            $result = strtoupper($String);
            return $result;
        }

        //将数组参数序列化为url参数
        protected function formatParameters($paraMap, $urlencode)
        {
            $buff = "";
            //字典序排序
            ksort($paraMap);
            foreach ($paraMap as $k => $v) {
                if($k=="sign"){
                    continue;
                }
                if ($urlencode) {
                    $v = urlencode($v);
                }
                //拼接字符串
                $buff .= $k . "=" . $v . "&";
            }
            $reqPar = '';
            //去掉最后一个&
            if (strlen($buff) > 0) {
                $reqPar = substr($buff, 0, strlen($buff) - 1);
            }
            return $reqPar;
        }

        /**
         *处理成功调用
         */
        public function success()
        {
            //处理后同步返回给微信
            exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');
        }

        /**
         * 处理失败调用参数为微信返回的数组可不传
         * @param null $verify_result
         */
        public function error($verify_result=null)
        {
            if($verify_result) {
                \Think\Log::record(json_encode($verify_result));
            }
            exit('<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[ERROR]]></return_msg></xml>');
        }
    }
?>