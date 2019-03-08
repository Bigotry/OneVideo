<?php
header("Content-type: text/html; charset=utf-8"); 

include 'yeepayCommon.php';	
		
#	商家设置用户购买商品的支付信息.
##易宝支付平台统一使用GBK/utf-8编码方式,参数如用到中文，请注意转码
$data = array();
#业务类型
$data['p0_Cmd']				= "Buy";
#	商户订单号,选填.
$data['p1_MerId']     = $p1_MerId;
##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易宝支付会自动生成随机的商户订单号.
$data['p2_Order']			= $_REQUEST['p2_Order'];
#	支付金额,必填.
##单位:元，精确到分.
$data['p3_Amt']			  = $_REQUEST['p3_Amt'];
#	交易币种,固定值"CNY".
$data['p4_Cur']				= "CNY";
#	商品名称
##用于支付时显示在易宝支付网关左侧的订单产品信息.
$data['p5_Pid']			  = $_REQUEST['p5_Pid'];
#	商品种类
$data['p6_Pcat']		  = $_REQUEST['p6_Pcat'];
#	商品描述 
$data['p7_Pdesc']		  = $_REQUEST['p7_Pdesc'];
#	商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
$data['p8_Url']			  = $_REQUEST['p8_Url'];	
#	送货地址
$data['p9_SAF']			  = $_REQUEST['p9_SAF'];
#	商户扩展信息
##商户可以任意填写1K 的字符串,支付成功时将原样返回.												
$data['pa_MP']			 = $_REQUEST['pa_MP'];
#	支付通道编码
##默认为""，到易宝支付网关.若不需显示易宝支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.			
$data['pd_FrpId']		  = $_REQUEST['pd_FrpId'];
#	订单有效期
$data['pm_Period']	  = $_REQUEST['pm_Period'];
#	订单有效期单位
##默认为"day": 天;
$data['pn_Unit']	    = $_REQUEST['pn_Unit'];
#	应答机制
$data['pr_NeedResponse']	    = $_REQUEST['pr_NeedResponse'];;
#	用户姓名
$data['pt_UserName']					= $_REQUEST['pt_UserName'];
#	身份证号
$data['pt_PostalCode']			  = $_REQUEST['pt_PostalCode'];
#	地区
$data['pt_Address']		        = $_REQUEST['pt_Address'];
#	银行卡号
$data['pt_TeleNo']			      = $_REQUEST['pt_TeleNo'];
#	手机号
$data['pt_Mobile']			      = $_REQUEST['pt_Mobile'];
# 邮件地址
$data['pt_Email']			        = $_REQUEST['pt_Email'];
# 用户标识
$data['pt_LeaveMessage']			= $_REQUEST['pt_LeaveMessage'];
#签名串
$hmac                         = HmacMd5(implode($data),$merchantKey);


?> 
<html>
<head>
<title>To YeePay Page</title>
</head>
<body onload="document.yeepay.submit();">
<form name='yeepay' action='<?php echo $reqURL_onLine; ?>' method='post'>
<input type='hidden' name='p0_Cmd'					value='<?php echo $data['p0_Cmd']; ?>'>
<input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
<input type='hidden' name='p2_Order'				value='<?php echo $data['p2_Order']; ?>'>
<input type='hidden' name='p3_Amt'					value='<?php echo $data['p3_Amt']; ?>'>
<input type='hidden' name='p4_Cur'					value='<?php echo $data['p4_Cur']; ?>'>
<input type='hidden' name='p5_Pid'					value='<?php echo $data['p5_Pid']; ?>'>
<input type='hidden' name='p6_Pcat'					value='<?php echo $data['p6_Pcat']; ?>'>
<input type='hidden' name='p7_Pdesc'				value='<?php echo $data['p7_Pdesc']; ?>'>
<input type='hidden' name='p8_Url'					value='<?php echo $data['p8_Url']; ?>'>
<input type='hidden' name='p9_SAF'					value='<?php echo $data['p9_SAF']; ?>'>
<input type='hidden' name='pa_MP'						value='<?php echo $data['pa_MP']; ?>'>
<input type='hidden' name='pd_FrpId'				value='<?php echo $data['pd_FrpId']; ?>'>
<input type='hidden' name='pm_Period'				value='<?php echo $data['pm_Period']; ?>'>
<input type='hidden' name='pn_Unit'				  value='<?php echo $data['pn_Unit']; ?>'>
<input type='hidden' name='pr_NeedResponse'	value='<?php echo $data['pr_NeedResponse']; ?>'>
<input type='hidden' name='pt_UserName'			value='<?php echo $data['pt_UserName']; ?>'>
<input type='hidden' name='pt_PostalCode'		value='<?php echo $data['pt_PostalCode']; ?>'>
<input type='hidden' name='pt_Address'			value='<?php echo $data['pt_Address']; ?>'>
<input type='hidden' name='pt_TeleNo'				value='<?php echo $data['pt_TeleNo']; ?>'>
<input type='hidden' name='pt_Mobile'				value='<?php echo $data['pt_Mobile']; ?>'>
<input type='hidden' name='pt_Email'				value='<?php echo $data['pt_Email']; ?>'>
<input type='hidden' name='pt_LeaveMessage'	value='<?php echo $data['pt_LeaveMessage']; ?>'>
<input type='hidden' name='hmac'						value='<?php echo $hmac; ?>'>
</form>
</body>
</html>