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

include_once("WxPayHelper.php");
$commonUtil = new CommonUtil();
$wxPayHelper = new WxPayHelper();

$ip = clientRealIp();
$ip = trim($ip);
if (empty($ip)) {
	$ip = '10.10.10.10';
}
$wxPayHelper->setParameter('bank_type', 'WX');
$wxPayHelper->setParameter('body', '这是一个很好的商品，标题');
$wxPayHelper->setParameter('partner', '1220432101');
$wxPayHelper->setParameter('out_trade_no', $commonUtil->create_noncestr());
$wxPayHelper->setParameter('total_fee', '1');
$wxPayHelper->setParameter('fee_type', '1');
$wxPayHelper->setParameter('notify_url', 'http://wslm.dev.csc86.com/payCallback.php');
$wxPayHelper->setParameter('spbill_create_ip', $ip);
$wxPayHelper->setParameter('input_charset', 'UTF-8');
?>
<html>
<script language="javascript">
function callpay()
{
	WeixinJSBridge.invoke('getBrandWCPayRequest', <?php echo $wxPayHelper->create_biz_package(); ?>, function(res){
	WeixinJSBridge.log(res.err_msg);
	alert(res.err_code+res.err_desc+res.err_msg);
	});
}
</script>

<body>
<button type="button" onclick="callpay()">wx pay test</button>
</body>
</html>
