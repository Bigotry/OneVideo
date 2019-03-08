<?php
header("Content-type: text/html; charset=utf-8"); 

 	
include 'yeepayCommon.php';
require_once 'HttpClient.class.php';
 		
$data = array();
$data['p0_Cmd']    = "RefundResults";
$data['p1_MerId']  = $p1_MerId;
$data['p2_Order']  = $_REQUEST['p2_Order'];
$data['pb_TrxId']  = $_REQUEST['pb_TrxId'];

$hmacstring        = HmacMd5(implode($data),$merchantKey);
$data['hmac']      = $hmacstring ;

//发送请求
$respdata  = HttpClient::quickPost($reqURL_onLine, $data);
//var_dump($respdata );
//响应参数转数组
$arr  =  getresp($respdata);
//echo "return:".$arr['hmac'];
//本地签名
$hmacLocal = HmacLocal($arr);
$safeLocal= gethamc_safe($arr);
//echo "local:".$safeLocal;
//验签
if($arr['hmac'] != $hmacLocal || $arr['hmac_safe'] != $safeLocal)

{
	
	echo "签名验证失败";
	return;
}	

?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<title>退款查询结果</title>
</head>
	<body>	
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					退款查询结果
				</th>
		  	</tr>

			<tr >
				<td width="25%" align="left">&nbsp;业务类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="45"  align="left"> <?php echo $arr['r0_Cmd'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r0_Cmd</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;查询结果</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r1_Code'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r1_Code</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;易宝流水号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r2_TrxId'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r2_TrxId</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;退款请求号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r4_Order'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r4_Order</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;退款申请结果</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"><?php echo $arr['refundStatus'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">refundStatus</td> 
			</tr>


			<tr>
				<td width="25%" align="left">&nbsp;退至银行状态</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['refundFrpStatus'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">refundFrpStatus</td> 
			</tr> 


		</table>


	</body>
</html>
