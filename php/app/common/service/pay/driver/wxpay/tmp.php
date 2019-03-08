<script src="/static/module/common/pay/wxpay/jquery-2.0.3.min.js"></script>
<script src="/static/module/common/pay/wxpay/qrcode.js"></script>

<div id="wx_pay_qrcode" style="height: 180px; width: 180px; margin:200 auto; text-align: center;"></div>

<script>
    
        if(<?php echo $unifiedOrderResult["code_url"] != NULL; ?>)
        {
                var url = "<?php echo $code_url;?>";
                //参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
                var qr = qrcode(10, 'M');
                qr.addData(url);
                qr.make();
                var wording=document.createElement('p');
                wording.innerHTML = "微信扫码支付";
                var code=document.createElement('DIV');
                code.innerHTML = qr.createImgTag();
                var element=document.getElementById("wx_pay_qrcode");
                element.innerHTML = '';
                element.appendChild(code);
                element.appendChild(wording);
        }
        var timer;
        var jump=1;
        
//        $(function(){
//                //轮询订单付款状态
//                timer = window.setInterval(check_order,1000); 
//                
//                function check_order(){
//                        $.get("/payment/checkPayStatus", { order_sn: "<?php echo $order['order_sn']; ?>" },function(data){
//                                
//                                if('succeed' == data){	//支付成功后页面跳转
//                                    
//                                    window.location.href="/center/paylist"; 
//
//                                    jump=0;
//                                    
//                                    clearInterval(timer);
//                                }
//                        });
//                }
//        })
</script>
