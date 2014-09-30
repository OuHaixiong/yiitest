

<!DOCTYPE html>
<html>
    <head>
        <title>公众号支付测试网页</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
        <meta id="viewport" name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1; user-scalable=no;" />
        
        <style>
            
            
            body { margin:0;padding:0;background:#eae9e6; }
            body,p,table,td,th { font-size:14px;font-family:helvetica,Arial,Tahoma; }
            h1 { font-family:Baskerville,HelveticaNeue-Bold,helvetica,Arial,Tahoma; }
            a { text-decoration:none;color:#385487;}
            
            
            .container {  }
            .title { }
            #content {padding:30px 20px 20px;color:#111;box-shadow:0 1px 4px #ccc; background:#f7f2ed;  }
            .seeAlso { padding:15px 20px 30px; }
            
            .headpic div { margin:20px 0 0;}
            .headpic img { display:block;}
            
            .title h1 { font-size:22px;font-weight:bold;padding:0;margin:0;line-height:1.2;color:#1f1f1f; }
            .title p { color:#aaa;font-size:12px;margin:5px 0 0;padding:0;font-weight:bold;}
            .pic { margin:20px 0; }
            .articlecontent img { display:block;clear:both;box-shadow:0px 1px 3px #999; margin:5px auto;}
            .articlecontent p { text-indent: 2em; font-family:Georgia,helvetica,Arial,Tahoma;line-height:1.4; font-size:16px; margin:20px 0;  }
            
            
            .seeAlso h3 { font-size:16px;color:#a5a5a5;}
            .seeAlso ul { margin:0;padding:0; }
            .seeAlso li {  font-size:16px;list-style-type:none;border-top:1px solid #ccc;padding:2px 0;}
            .seeAlso li a { border-bottom:none;display:block;line-height:1.1; padding:13px 0; }
            
            .clr{ clear:both;height:1px;overflow:hidden;}
            
            
            .fontSize1 .title h1 { font-size:20px; }
            .fontSize1 .articlecontent p {  font-size:14px; }
            .fontSize1 .weibo .nickname,.fontSize1 .weibo .comment  { font-size:11px; }
            .fontSize1 .moreOperator { font-size:14px; }
            
            .fontSize2 .title h1 { font-size:22px; }
            .fontSize2 .articlecontent p {  font-size:16px; }
            .fontSize2 .weibo .nickname,.fontSize2 .weibo .comment  { font-size:13px; }
            .fontSize2 .moreOperator { font-size:16px; }
            
            .fontSize3 .title h1 { font-size:24px; }
            .fontSize3 .articlecontent p {  font-size:18px; }
            .fontSize3 .weibo .nickname,.fontSize3 .weibo .comment  { font-size:15px; }
            .fontSize3 .moreOperator { font-size:18px; }
            
            .fontSize4 .title h1 { font-size:26px; }
            .fontSize4 .articlecontent p {  font-size:20px; }
            .fontSize4 .weibo .nickname,.fontSize4 .weibo .comment  { font-size:16px; }
            .fontSize4 .moreOperator { font-size:20px; }
            
            .jumptoorg { display:block;margin:16px 0 16px; }
            .jumptoorg a {  }
            
            .moreOperator a { color:#385487; }
            
            .moreOperator .share{ border-top:1px solid #ddd; }
            
            .moreOperator .share a{ display:block;border:1px solid #ccc;border-radius:4px;margin:20px 0;border-bottom-style:solid;background:#f8f7f1;color:#000; }
            
            .moreOperator .share a span{ display:block;padding:10px 10px;border-radius:4px;text-align:center;border-top:1px solid #eee;border-bottom:1px solid #eae9e3;font-weight:bold; }
            
            .moreOperator .share a:hover,
            .moreOperator .share a:active { background:#efedea; }
            @media only screen and (-webkit-min-device-pixel-ratio: 2) {
            }
            </style>
        <script language="javascript">
        function invoke() {
        	  var obj='{"appId":"wx21d9ed144936561c","package":"bank_type=WX&body=%E8%BF%99%E6%98%AF%E4%B8%80%E4%B8%AA%E5%BE%88%E5%A5%BD%E7%9A%84%E5%95%86%E5%93%81%EF%BC%8C%E6%A0%87%E9%A2%98&fee_type=1&input_charset=UTF-8&notify_url=http%3A%2F%2Fwslm.dev.csc86.com%2FpayCallback.php&out_trade_no=fNSdbqvwobdZUGlC&partner=1220432101&spbill_create_ip=10.10.10.10&total_fee=1&sign=6B3B4FA03AFEE4B687685A4CB7BEBC84","timeStamp":1408861762,"nonceStr":"gL7izK9mtLObbdj6","paySign":"36677dea2e2d8eec0732bc506060344c7d7af91f","signType":"sha1"}';
        	  alert(obj);
        	  WeixinJSBridge.invoke('getBrandWCPayRequest', obj, function(res) {
        	    if (res.err_msg == "get_brand_wcpay_request:ok") {
        	      alert('支付成功！');
        	    } else {
        	      alert('支付失败');
        	    }
        	    alert(res.err_msg);
        	    // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回ok，但并不保证它绝对可靠。
        	    //因此微信团队建议，当收到ok返回时，向商户后台询问是否收到交易成功的通知，若收到通知，前端展示交易成功的界面；若此时未收到通知，商户后台主动调用查询订单接口，查询订单的当前状态，并反馈给前端展示相应的界面。
        	  });
        	}
           
          
            </script>
    </head>
    <body>
	
        <div class="WCPay">
            <p><h1 class="title" onclick="invoke()"><a href="javascript:void(0);">点击提交测试</a></h1></p>
        </div>

        
</body>
</html>