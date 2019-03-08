<?php
header("Content-type: text/html; charset=utf-8"); 
include 'yeepayCommon.php';	
	
#	只有支付成功时易宝支付才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$data=array();

$data['p1_MerId']		 = $_REQUEST['p1_MerId'];	
$data['r0_Cmd']		   = $_REQUEST['r0_Cmd'];
$data['r1_Code']	   = $_REQUEST['r1_Code'];
$data['r2_TrxId']    = $_REQUEST['r2_TrxId'];
$data['r3_Amt']      = $_REQUEST['r3_Amt'];
$data['r4_Cur']		   = $_REQUEST['r4_Cur']; 
$data['r5_Pid']		   = $_REQUEST['r5_Pid'] ;
$data['r6_Order']	   = $_REQUEST['r6_Order'];
$data['r7_Uid']		   = $_REQUEST['r7_Uid'];
$data['r8_MP']		   = $_REQUEST['r8_MP'] ;
$data['r9_BType']	   = $_REQUEST['r9_BType']; 
$data['hmac']			   = $_REQUEST['hmac'];
$data['hmac_safe']   = $_REQUEST['hmac_safe'];

//var_dump($data);
//本地签名
$hmacLocal = HmacLocal($data);
// echo "</br>hmacLocal:".$hmacLocal;
$safeLocal= gethamc_safe($data);
// echo "</br>safeLocal:".$safeLocal;




 //验签
if($data['hmac']	 != $hmacLocal    || $data['hmac_safe'] !=$safeLocal)
{	
	echo "验签失败";
	return;
}else{
	 if ($data['r1_Code']=="1" ){

      if($data['r9_BType']=="1"){
			
			echo  "支付成功！在线支付页面返回";
		}elseif($data['r9_BType']=="2"){
			#如果需要应答机制则必须回写success.
			echo "SUCCESS";
			return;	 
		}
	 
  }
}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<title>通知回调</title>
</head>
	<body>
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					通知回调结果
				</th>
		  	</tr>
			
			<tr >
				<td width="25%" align="left">&nbsp;商户编号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="45"  align="left"> <?php echo $p1_MerId;?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">p1_MerId</td> 
			</tr>

			<tr >
				<td width="25%" align="left">&nbsp;业务类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="45"  align="left"> <?php echo $data['r0_Cmd'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r0_Cmd</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;支付结果</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $data['r1_Code'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r1_Code</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;易宝流水号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left">  <?php echo $data['r2_TrxId'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r2_TrxId</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;支付金额</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $data['r3_Amt'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r3_Amt</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;交易币种</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $data['r4_Cur'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r4_Cur</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商品名称</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"><?php echo $data['r5_Pid'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r5_Pid</td> 
			</tr> 

			<tr>
				<td width="25%" align="left">&nbsp;商户订单号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $data['r6_Order'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r6_Order</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;易宝会员ID</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"><?php echo $data['r7_Uid'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r7_Uid</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;扩展信息</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $data['r8_MP'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r8_MP</td> 
			</tr>


			<tr>
				<td width="25%" align="left">&nbsp;通知类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left"> <?php echo $data['r9_BType'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">r9_BType</td> 
			</tr> 

			<tr>
				<td width="25%" align="left">&nbsp;支付通道编码</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left">  <?php echo $_REQUEST['rb_BankId'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rb_BankId</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;银行订单号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left">  <?php echo $_REQUEST['ro_BankOrderId'];?></td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">ro_BankOrderId</td> 
			</tr>


			<tr>
				<td width="25%" align="left">&nbsp;支付成功时间</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left">  <?php echo $_REQUEST['rp_PayDate'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rp_PayDate</td> 
			</tr> 

			<tr>
				<td width="25%" align="left">&nbsp;神州行充值卡号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left">  <?php echo $_REQUEST['rq_CardNo'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rq_CardNo</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;通知时间</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left">  <?php echo $_REQUEST['ru_Trxtime'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">ru_Trxtime</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;用户手续费</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left">  <?php echo $_REQUEST['rq_SourceFee'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rq_SourceFee</td> 
			</tr>


			<tr>
				<td width="25%" align="left">&nbsp;商户手续费</td>
				<td width="5%"  align="center"> : </td> 
				<td width="35%" align="left">  <?php echo $_REQUEST['rq_TargetFee'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="30%" align="left">rq_TargetFee</td> 
			</tr> 

		</table>

	</body>
</html>