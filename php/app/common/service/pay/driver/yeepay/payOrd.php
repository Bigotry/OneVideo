<?php
header("Content-type: text/html; charset=utf-8"); 
$p2_Order = "WY" . date("ymd_His") . rand(10, 99);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px #107929">
		  <tr>
		    <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
		  </tr>
		 
		  <tr>
		  	<td colspan="2" bgcolor="#CEE7BD">订单支付接口演示：</td>
		  </tr>
			<form method="post" action="sendPayOrd.php" targe="_blank">

		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;商户订单号</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p2_Order" id="p2_Order" value="<?php echo $p2_Order ;?>"/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;支付金额</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p3_Amt" id="p3_Amt" value="0.01" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>

		  <tr>
		  	<td align="left">&nbsp;&nbsp;商品名称</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p5_Pid" id="p5_Pid"  value="productname"/>&nbsp;<span style="color:#FF0000;font-weight:100;">选择一键支付时，必填</span></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;商品种类</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p6_Pcat" id="p6_Pcat"  value="producttype"/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;商品描述</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p7_Pdesc" id="p7_Pdesc"  value="productdesc"/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;接收支付成功数据的地址</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p8_Url" id="p8_Url" value="http://172.21.0.84/demo/wy/callback.php" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
	   <tr>
		  	<td align="left">&nbsp;&nbsp;送货地址</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p9_SAF" id="p9_SAF"  value="0"/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;商户扩展信息</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pa_MP" id="pa_MP"  value=""/></td>
      </tr>
	  <tr>
		  	<td align="left">&nbsp;&nbsp;支付通道编码</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="pd_FrpId" /><!--支付通道编码在易宝支付产品(HTML版)通用接口使用说明中-->
      </tr>
		 <tr>
		  	<td align="left">&nbsp;&nbsp;订单有效期</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pm_Period" id="pm_Period"  value="7"/></td>
      </tr>
	  <tr>
		  	<td align="left">&nbsp;&nbsp;订单有效期单位</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pn_Unit" id="pn_Unit"  value="day"/></td>
      </tr>
	   <tr>
		  	<td align="left">&nbsp;&nbsp;应答机制</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pr_NeedResponse" id="pr_NeedResponse"  value="1"/></td>
      </tr>
		  
		  	<tr>
		  	<td align="left">&nbsp;&nbsp;用户姓名</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_UserName" id="pt_UserName"  value=""/></td>
      </tr>
		  
		  	<tr>
		  	<td align="left">&nbsp;&nbsp;身份证号</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_PostalCode" id="pt_PostalCode"  value=""/></td>
      </tr>
		  
		  <tr>
		  	<td align="left">&nbsp;&nbsp;地区</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_Address" id="pt_Address"  value=""/></td>
      </tr>
		  
		  <tr>
		  	<td align="left">&nbsp;&nbsp;银行卡号</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_TeleNo" id="pt_TeleNo"  value=""/></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;手机号</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_Mobile" id="pt_Mobile"  value=""/></td>
      </tr>
		  
		  <tr>
		  	<td align="left">&nbsp;&nbsp;邮件地址</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_Email" id="pt_Email"  value=""/></td>
      </tr>
		  
		  
		  <tr>
		  	<td align="left">&nbsp;&nbsp;用户标识</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="pt_LeaveMessage" id="pt_LeaveMessage"  value=""/></td>
      </tr>
		  
		  
		  <tr>
		  	<td align="left">&nbsp;</td>
		  	<td align="left">&nbsp;&nbsp;<input type="submit" value="马上支付" /></td>
      </tr>
    </form>
      <tr>
      	<td height="5" bgcolor="#6BBE18" colspan="2"></td>
      </tr>
      </table></td>
        </tr>
      </table>
	</body>
</html>
