<?php
header("Content-type: text/html; charset=utf-8"); 
 	
include 'yeepayCommon.php';
require_once 'HttpClient.class.php';

$data = array();
$data['p0_Cmd']    = "RefundOrd";
$data['p1_MerId']  = $p1_MerId;
$data['p2_Order']  = $_REQUEST['p2_Order'];
$data['pb_TrxId']  = $_REQUEST['pb_TrxId'];
$data['p3_Amt']    = $_REQUEST['p3_Amt'];
$data['p4_Cur']    = "CNY";
$data['p5_Desc']   = $_REQUEST['p5_Desc'];
$hmacstring        = HmacMd5(implode($data),$merchantKey);
$data['hmac']      = $hmacstring ;
//发送请求
$respdata  = HttpClient::quickPost($OrderURL_onLine, $data);
//var_dump($respdata);
//响应参数转数组
$arr  =   getresp($respdata);
//echo "return:".$arr ['hmac_safe'];

//本地签名参数
$arr1=array(
     'r0_Cmd'   => $arr['r0_Cmd'],
     'r1_Code'  => $arr['r1_Code'],
     'r2_TrxId' => $arr['r2_TrxId'],
     'r3_Amt'   => $arr['r3_Amt'],
     'r4_Cur'   => $arr['r4_Cur']); 
 
//本地签名
$hmacLocal = HmacLocal($arr1);
$safeLocal= gethamc_safe($arr1);
//echo "local:".$safeLocal ;
//验签
if($arr['hmac'] != $hmacLocal  || $arr['hmac_safe'] != $safeLocal)

{
	
	echo "签名验证失败";
	return;
}	


?> 
<html>
<head>
	<title>退款接口</title>
</head>
	<body>
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					订单退款结果
				</th>
		  	</tr>

			<tr >
				<td width="25%" align="left">&nbsp;业务类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="45"  align="left"> <?php echo $arr['r0_Cmd']; ?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r0_Cmd</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;退款结果</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r1_Code']; ?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r1_Code</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;易宝流水号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r2_TrxId']; ?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r2_TrxId</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;支付金额</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r3_Amt']; ?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r3_Amt</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;交易币种</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r4_Cur']; ?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r4_Cur</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;退款请求号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['r4_Order']; ?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r4_Order</td> 
			</tr> 

			<tr>
				<td width="25%" align="left">&nbsp;已退手续费</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $arr['rf_fee']; ?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rf_fee</td> 
			</tr> 

		</table>
		

		
	</body>
</html>
