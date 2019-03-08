<?php
$p2_Order = "WYTK" . date("ymd_His") . rand(10, 99);
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
		  	<td colspan="2" bgcolor="#CEE7BD">易宝支付订单退款请求接口演示：</td>
		  </tr>
			<form method="post" action="sendRefundOrd.php" targe="_blank">
		  		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;退款请求号</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="p2_Order" id="p2_Order" value="<?php echo $p2_Order ?>" />&nbsp;</td>
      </tr>
		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;易宝支付交易流水号</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="pb_TrxId" id="pb_TrxId" value="" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
      
		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;退款金额</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="p3_Amt" id="p3_Amt" value="" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
      		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;交易币种</td>
		  	<td align="left">&nbsp;&nbsp;<input type="text" name="p4_Cur" id="p4_Cur" value="CNY" />&nbsp;<span style="color:#FF0000;font-weight:100;">*</span></td>
      </tr>
		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;退款说明</td>
		  	<td align="left">&nbsp;&nbsp;<input size="50" type="text" name="p5_Desc" id="p5_Desc" value="" /></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;</td>
		  	<td align="left">&nbsp;&nbsp;<input type="submit" value="马上提交" /></td>
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