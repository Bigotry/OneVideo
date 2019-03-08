<?php
header("Content-type: text/html; charset=utf-8"); 

 	
include 'yeepayCommon.php';
require_once 'HttpClient.class.php';
 		
$data = array();
$data['p0_Cmd']    = "CancelOrd";
$data['p1_MerId']  = $p1_MerId;
$data['pb_TrxId']  = $_REQUEST['pb_TrxId'];
$data['pv_Ver']    = $_REQUEST['pv_Ver'];
$hmacstring        = HmacMd5(implode($data),$merchantKey);
$data['hmac']      = $hmacstring ;

//发送请求
$respdata  = HttpClient::quickPost($reqURL_onLine, $data);
//var_dump($respdata );
//响应参数
$arr  =  getresp($respdata);
//echo "return:".$arr['hmac'];
//本地签名
$hmacLocal = HmacLocal($arr);
$safeLocal= gethamc_safe($arr);

//echo "local:".$hmacLocal;
//验签
if($arr['hmac'] != $hmacLocal   || $arr['hmac_safe'] != $safeLocal)
{
	
	echo "签名验证失败";
	return;
}	

?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<title>订单取消</title>
</head>
	<body>	
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					订单取消
				</th>
		  	</tr>

			<tr>
				<td width="25%" align="left">&nbsp;业务类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r0_Cmd'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r0_Cmd</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;撤销结果</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r1_Code'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r1_Code</td> 
			</tr>

		</table>
	</body>
</html>

