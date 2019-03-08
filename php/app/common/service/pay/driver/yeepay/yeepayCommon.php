<?php
include 'merchantProperties.php';
include 'HttpClient.class.php';
 	#时间设置
	date_default_timezone_set('PRC');
	#支付请求、 退款查询接口地址
	$reqURL_onLine = "https://www.yeepay.com/app-merchant-proxy/node";
	#订单查询，退款、撤销
  $OrderURL_onLine="https://cha.yeepay.com/app-merchant-proxy/command";	

  
  if (!function_exists('getresp')) {
      
        #响应参数转换成数组
        function getresp($respdata)
        {
                  $result = explode("\n",urldecode($respdata));
                  $output = array();

            foreach ($result as $data) 
            {
            $arr = explode('=',$data);
            $output[$arr[0]] = $arr[1];
            }
         return $output;
        }
  }
  
  if (!function_exists('HmacLocal')) {

        #生成本地签名hmac(不适用于回调通知)
        function HmacLocal($data, $merchantKey)
        {
                $text="";
                
                while (list($key,$value) = each($data))
                {
                    if(isset($key) && $key!="hmac" && $key!="hmac_safe") 
                    {   

                        $text .=    $value;
                    }

                }

                return HmacMd5($text, $merchantKey);

        }   

  }
 
  
  if (!function_exists('gethamc_safe')) {

        //生成本地的安全签名数据
        function gethamc_safe($data, $merchantKey)
        {
                $text="";
                
                while (list($key,$value) = each($data))
                {
                    if( $key!="hmac" && $key!="hmac_safe" && $value !=null)
                    {

                        $text .=  $value."#" ;
                    }

                }
                $text1= rtrim( trim($text), '#' );

                return HmacMd5($text1,$merchantKey);

        }  
  }

 

  
    if (!function_exists('HmacMd5')) {
        
  
        //生成hmac

        function HmacMd5($data,$key)
        {
        // RFC 2104 HMAC implementation for php.
        // Creates an md5 HMAC.
        // Eliminates the need to install mhash to compute a HMAC
        // Hacked by Lance Rushing(NOTE: Hacked means written)

        //需要配置环境支持iconv，否则中文参数不能正常处理
//        $key = iconv("GBK","utf-8",$key);
//        $data = iconv("GBK","utf-8",$data);
        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
        $key = pack("H*",md5($key));
        }
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack("H*",md5($k_ipad . $data)));
        }


    }


?> 
