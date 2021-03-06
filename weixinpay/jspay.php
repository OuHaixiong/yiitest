<?php
function clientRealIp() {
    if (isset($_SERVER))
    {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $arr = explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]);/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip)
            {
                $ip = trim($ip);
                if ($ip != 'unknown')
                {$realip = $ip;break;}
            }
        }
        elseif (isset($_SERVER["HTTP_CLIENT_IP"]))
        {$realip = $_SERVER["HTTP_CLIENT_IP"];}
        else
        {$realip = $_SERVER["REMOTE_ADDR"];}
    }
    else
    {
        if (getenv('HTTP_X_FORWARDED_FOR'))
        {$realip = getenv('HTTP_X_FORWARDED_FOR');}
        elseif (getenv('HTTP_CLIENT_IP'))
        {$realip = getenv('HTTP_CLIENT_IP');}
        else
        {$realip = getenv('REMOTE_ADDR');}
    }
    return $realip;
}

function getIP() { // var_dump(getenv("HTTP_CLIENT_IP"));exit;
    //getenv($varname); 获取一个环境变量的值;如获取返回该变量值，否则返回 false。 如：$ip = getenv('REMOTE_ADDR');var_dump($ip); 获取ip
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
        // strcasecmp($str1, $str2)  二进制安全比较字符串（不区分大小写）;  如果 str1 小于 str2，返回负数；如果 str1 大于 str2，返回正数；二者相等则返回 0。
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = 'unknown';
    }
    return $ip;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>公众号支付测试网页</title>
        <script language="javascript" src="http://res.mail.qq.com/mmr/static/lib/js/jquery.js"></script>
        <script language="javascript" src="http://res.mail.qq.com/mmr/static/lib/js/lazyloadv3.js"></script>
        <script src="md5.js"></script>
        <script src="sha1.js"></script>
        <script Language="javascript">

		
            //商家测试请修改此四个参数，并将页面放在支付授权目录下，在申请了支付的公众账号访问此页面，方可调起支付。
            //修改开始
            function getPartnerId()
            {//替换partnerid
                return '1220432101'; // 1220432101   商户号(PartnerID)：
            }
            
            function getPartnerKey()
            {//替换partnerkey
                return 'b1f821e9f65521bcafe4d602d05135d2'; // 初始密钥(PartnerKey)： b1f821e9f65521bcafe4d602d05135d2 
            }
			
            function getAppId() // 没错
            {//替换appid
                return 'wx21d9ed144936561c'; // AppID为：wx21d9ed144936561c
                // AppSecret为：f53ea542630dc8a584bf4055956ad544
            }
            
            function getAppKey()
            {//替换appkey
                return 'WlRaqvZgQsPcxZ8o5rmp3juZEMihpWc8s5jagO8tLGhNtEIemqY8h926rc3f8sqAxOZqD5w7Yx7pDaomMhN3WcgcGXJuqmpIstBnT6Nro1AzuK1Cm0Pg91hi1NWJ0c0A';
            // PaySignKey为：WlRaqvZgQsPcxZ8o5rmp3juZEMihpWc8s5jagO8tLGhNtEIemqY8h926rc3f8sqAxOZqD5w7Yx7pDaomMhN3WcgcGXJuqmpIstBnT6Nro1AzuK1Cm0Pg91hi1NWJ0c0A
            }		
			//修改到此结束
		
            //辅助函数
            function Trim(str,is_global)
            {
                var result;
                result = str.replace(/(^\s+)|(\s+$)/g,"");
                if(is_global.toLowerCase()=="g") result = result.replace(/\s/g,"");
                return result;
            }
            function clearBr(key)
            {
                key = Trim(key,"g");
                key = key.replace(/<\/?.+?>/g,"");
                key = key.replace(/[\r\n]/g, "");
                return key;
            }
            
            //获取随机数
            function getANumber()
            {
                var date = new Date();
                var times1970 = date.getTime();
                var times = date.getDate() + "" + date.getHours() + "" + date.getMinutes() + "" + date.getSeconds();
                var encrypt = times * times1970;
                if(arguments.length == 1){
                    return arguments[0] + encrypt;
                }else{
                    return encrypt;
                }
                
            }

            //以下是package组包过程：
            
            var oldPackageString;//记住package，方便最后进行整体签名时取用
           
            function getPackage()
            {
                var banktype = "WX";
                var body = '产品名称';//商品名称信息，这里由测试网页填入。
                var fee_type = "1";//费用类型(支付币种)，这里1为默认的人民币
                var input_charset = "UTF-8";//字符集，可以使用GBK或者UTF-8
                var notify_url = 'http://wslm.dev.csc86.com/payCallback.php';//支付成功后将通知该地址
                var out_trade_no = ""+getANumber();//订单号，商户需要保证该字段对于本商户的唯一性
                var partner = getPartnerId();//测试商户号
                var spbill_create_ip = '<?php echo clientRealIp(); ?>';//用户浏览器的ip，这个需要在前端获取。这里使用127.0.0.1测试值
                if (!spbill_create_ip) {
                	spbill_create_ip = '10.10.10.10';
                }
                var total_fee = 1;//总金额。
                var partnerKey = getPartnerKey();//这个值和以上其他值不一样是：签名需要它，而最后组成的传输字符串不能含有它。这个key是需要商户好好保存的。
                
                //首先第一步：对原串进行签名，注意这里不要对任何字段进行编码。这里是将参数按照key=value进行字典排序后组成下面的字符串,在这个字符串最后拼接上key=XXXX。由于这里的字段固定，因此只需要按照这个顺序进行排序即可。
                var signString = "bank_type="+banktype+"&body="+body+"&fee_type="+fee_type+"&input_charset="+input_charset+"&notify_url="+notify_url+"&out_trade_no="+out_trade_no+"&partner="+partner+"&spbill_create_ip="+spbill_create_ip+"&total_fee="+total_fee+"&key="+partnerKey;
                
                var md5SignValue =  ("" + CryptoJS.MD5(signString)).toUpperCase();
                //然后第二步，对每个参数进行url转码，如果您的程序是用js，那么需要使用encodeURIComponent函数进行编码。
                
                
                banktype = encodeURIComponent(banktype);
                body=encodeURIComponent(body);
                fee_type=encodeURIComponent(fee_type);
                input_charset = encodeURIComponent(input_charset);
                notify_url = encodeURIComponent(notify_url);
                out_trade_no = encodeURIComponent(out_trade_no);
                partner = encodeURIComponent(partner);
                spbill_create_ip = encodeURIComponent(spbill_create_ip);
                total_fee = encodeURIComponent(total_fee);
                
                //然后进行最后一步，这里按照key＝value除了sign外进行字典序排序后组成下列的字符串,最后再串接sign=value
                var completeString = "bank_type="+banktype+"&body="+body+"&fee_type="+fee_type+"&input_charset="+input_charset+"&notify_url="+notify_url+"&out_trade_no="+out_trade_no+"&partner="+partner+"&spbill_create_ip="+spbill_create_ip+"&total_fee="+total_fee;
                completeString = completeString + "&sign="+md5SignValue;
                
                
                oldPackageString = completeString;//记住package，方便最后进行整体签名时取用
                
                return completeString;
            }
            
            
            //下面是app进行签名的操作：
            
            var oldTimeStamp ;//记住timestamp，避免签名时的timestamp与传入的timestamp时不一致
            var oldNonceStr ; //记住nonceStr,避免签名时的nonceStr与传入的nonceStr不一致
                     
            function getTimeStamp()
            {
                var timestamp=new Date().getTime();
                var timestampstring = timestamp.toString();//一定要转换字符串
                oldTimeStamp = timestampstring;
                return timestampstring;
            }
            
            function getNonceStr()
            {
                var $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                var maxPos = $chars.length;
                var noceStr = "";
                for (i = 0; i < 32; i++) {
                    noceStr += $chars.charAt(Math.floor(Math.random() * maxPos));
                }
                oldNonceStr = noceStr;
                return noceStr;
            }
            
            function getSignType()
            {
                return "SHA1";
            }
            
            function getSign()
            {
                var app_id = getAppId().toString();
                var app_key = getAppKey().toString();
                var nonce_str = oldNonceStr;
                var package_string = oldPackageString;
                var time_stamp = oldTimeStamp;
                //第一步，对所有需要传入的参数加上appkey作一次key＝value字典序的排序
                var keyvaluestring = "appid="+app_id+"&appkey="+app_key+"&noncestr="+nonce_str+"&package="+package_string+"&timestamp="+time_stamp;
                sign = CryptoJS.SHA1(keyvaluestring).toString();
                return sign;
            }

//             function getSign()
//             {
//                 var app_id = getAppId().toString();
//                 var app_key = getAppKey().toString();
//                 var nonce_str = oldNonceStr;
//                 var package_string = oldPackageString;
//                 var time_stamp = oldTimeStamp;
//                 //第一步，对所有需要传入的参数加上appkey作一次key＝value字典序的排序
//                 var keyvaluestring = "appid="+app_id+"&appkey="+app_key+"&noncestr="+nonce_str+"&package="+package_string+"&timestamp="+time_stamp;
//                 sign = CryptoJS.SHA1(keyvaluestring).toString();
//                 return sign;
//             }
            
            
            
            
            </script>
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
            function auto_remove(img){
                div=img.parentNode.parentNode;div.parentNode.removeChild(div);
                img.onerror="";
                return true;
            }
            
            function changefont(fontsize){
                if(fontsize < 1 || fontsize > 4)return;
                $('#content').removeClass().addClass('fontSize' + fontsize);
            }
            
            function writeObj(obj){ 
                var description = ""; 
                for(var i in obj){   
                    var property=obj[i];   
                    description+=i+" = "+property+"\n";  
                }   
                alert(description); 
            } 
//             alert(getPackage());
//             alert(getNonceStr());
//             alert(getTimeStamp());
//             alert(getSignType());
//             alert(getSign());
            
            // 当微信内置浏览器完成内部初始化后会触发WeixinJSBridgeReady事件。
            document.addEventListener('WeixinJSBridgeReady', 
                    function onBridgeReady() { //公众号支付
                        jQuery('a#getBrandWCPayRequest').click(function(e){
                             WeixinJSBridge.invoke('getBrandWCPayRequest',{
                                   "appId" : getAppId(), //公众号名称，由商户传入
                                   "timeStamp" : getTimeStamp(), //时间戳
                                   "nonceStr" : getNonceStr(), //随机串
                                   "package" : getPackage(),//扩展包
                                   "signType" : getSignType(), //微信签名方式:1.sha1
                                   "paySign" : getSign() //微信签名
                                   },function(res){
                                   if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                                	   alert('支付成功');
                                   } else {
                                       WeixinJSBridge.log(res);
                                	   alert(res.err_msg);
                                	   writeObj(res);
                                   }
                                   // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回ok，但并不保证它绝对可靠。
                                   //因此微信团队建议，当收到ok返回时，向商户后台询问是否收到交易成功的通知，若收到通知，前端展示交易成功的界面；若此时未收到通知，商户后台主动调用查询订单接口，查询订单的当前状态，并反馈给前端展示相应的界面。
                                   }); 
                             
                             });
                                      
                                      
                                      
                                      WeixinJSBridge.log('yo~ ready.');
                                      
                                      }, false)
            
            if(jQuery){
                jQuery(function(){
                       
                       var width = jQuery('body').width() * 0.87;
                       jQuery('img').error(function(){
                                           var self = jQuery(this);
                                           var org = self.attr('data-original1');
                                           self.attr("src", org);
                                           self.error(function(){
                                                      auto_remove(this);
                                                      });
                                           });
                       jQuery('img').each(function(){
                                          var self = jQuery(this);
                                          var w = self.css('width');
                                          var h = self.css('height');
                                          w = w.replace('px', '');
                                          h = h.replace('px', '');
                                          if(w <= width){
                                          return;
                                          }
                                          var new_w = width;
                                          var new_h = Math.round(h * width / w);
                                          self.css({'width' : new_w + 'px', 'height' : new_h + 'px'});
                                          self.parents('div.pic').css({'width' : new_w + 'px', 'height' : new_h + 'px'});
                                          });
                       });
            }
            </script>
    </head>
    <body>
	
        <div class="WCPay">
            <p>微信支付JSAPI测试页面</p>
            <p>请将您申请公众账号支付权限的四个参数替换页面中的参数：partnerid、partnerkey、appid、appkey</p>
            <p>将此页面放在的支付授权测试目录下，测试微信号需添加白名单，并在公众账号内发起访问此页面<p>
			<p>即可检查公众账号支付功能是否正常</p>
            <p></p>
        <a id="getBrandWCPayRequest" href="javascript:void(0);"><h1 class="title">点击提交测试</h1></a>        </div>
        
        <p><a href="wxpay-jsapi-demo.php">最原始的js支付</a></p>
        <p><a href="jsapicall.php">最原始的jsapi支付(PHP生成pager，js调用)</a></p>
        <p><a href="new.php">我线上测试环境的支付</a></p>
        <p><a href="http://wslm.dev.csc86.com/life">跳转到微生活列表页</a></p>
        <p>IP：<?php echo getIP();var_dump($_SERVER['REMOTE_ADDR']);?></p>
        
        <script type="text/javascript" src="http://counter.sina.com.cn/ip/" charset="gb2312"></script>
<script type="text/javascript">   
document.writeln("IP地址："+ILData[0]+"<br />");             //输出接口数据中的IP地址   
document.writeln("地址类型："+ILData[1]+"<br />");         //输出接口数据中的IP地址的类型   
document.writeln("地址类型："+ILData[2]+"<br />");         //输出接口数据中的IP地址的省市  
document.writeln("地址类型："+ILData[3]+"<br />");         //输出接口数据中的IP地址的  
document.writeln("地址类型："+ILData[4]+"<br />");         //输出接口数据中的IP地址的运营商  
</script> 

<p>
微信浏览器头信息：<br />
<?php echo $_SERVER['HTTP_USER_AGENT']; ?>
</p>
        
</body>
</html>