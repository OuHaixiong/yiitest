<?php
// 可以多个文件夹，组成控制器 ，访问如： /index.php/test/wo/abc/a/123
class WoController extends Controller
{
    public function init() {
//         Yii::app()->request->getParam('bb', 1);
    }
    
    public function actionIndex() {
    	$this->render('/test/test');
    	//<!-- 获取服务器上的时间戳
    	//var_dump($_SERVER['REQUEST_TIME']);exit;
    	//var_dump(Yii::app()->getRequest()->userHostAddress); //<!-- 获取客户端的ip
    	//var_dump(Yii::app()->request->url);exit; //<!-- 得到当前url不包括域名
    }
    
    public function actionAbc() {
$url = 'http://263372.m.weimob.com/activity/ScratchCard?_tj_twtype=11&_tj_pid=263372&_tt=1&_tj_graphicid=35692&_tj_title=%E5%88%AE%E5%88%AE%E5%8D%A1%E6%B4%BB%E5%8A%A8%E5%BC%80%E5%A7%8B%E4%BA%86&_tj_keywords=%E5%88%AE%E5%88%AE%E5%8D%A1&id=35692&bid=261828&wechatid=oUISDuJd696h0daAPyjOPY4tfRIc&pid=263372&v=21e35ad41897dc76c7068b12682ad038';
echo $this->requestUrl($url);
    }
    
    /**
     * 通过curl访问网页
     * @param unknown $url
     * @param string $PostData
     * @return mixed|string
     */
    final private function requestUrl( $url, $PostData=false ) {
        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 4.1.2; zh-cn; SCH-N719 Build/JZO54K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 MicroMessenger/5.3.0.51_r697493.440');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //链接超时时间
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
            if( $PostData ) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $PostData);
            }
    
            $content = curl_exec($ch);
            curl_close($ch);
            return $content;
        }
        catch(Exception $error) {
            return "";
        }
    }
    
    public function actionBb() {
$url = 'http://263372.m.weimob.com/activity/ScratchCard?_tj_twtype=11&_tj_pid=263372&_tt=1&_tj_graphicid=35692&_tj_title=%E5%88%AE%E5%88%AE%E5%8D%A1%E6%B4%BB%E5%8A%A8%E5%BC%80%E5%A7%8B%E4%BA%86&_tj_keywords=%E5%88%AE%E5%88%AE%E5%8D%A1&id=35692&bid=261828&wechatid=oUISDuJd696h0daAPyjOPY4tfRIc&pid=263372&v=21e35ad41897dc76c7068b12682ad038';
echo $this->requestUrl($url);

    }
    
    public function actionTest() {
        $str = '{"status":false,"msg":"\u53c2\u6570\u9519\u8bef"}';
        $str = json_decode($str);
        var_dump($str);
        
        $request = '<xml><OpenId><![CDATA[oEQpDuCgUw_WQxtflHQ4PgwJnVGc]]></OpenId>
<AppId><![CDATA[wx21d9ed144936561c]]></AppId>
<IsSubscribe>1</IsSubscribe>
<TimeStamp>1415176968</TimeStamp>
<NonceStr><![CDATA[zQgIHeSTiHLqYKuH]]></NonceStr>
<AppSignature><![CDATA[67d105bbf9616df1e2e3dfa628c01598cdf30e95]]></AppSignature>
<SignMethod><![CDATA[sha1]]></SignMethod>
</xml>';
        $params = '{"attach":"e3dffe06-203d-4ac6-83a3-da292a936286|631","bank_billno":"201411055650863","bank_type":"3006","discount":"0","fee_type":"1","input_charset":"UTF-8","notify_id":"-Uu5A93xXSgFahHBp5rhazS5jkett0EBNgj_u-iFEJ0Tfv2I5x_nlCiYUdzpfn00u7iByEV9meSHUHb3iRBMFYRBico11RBY","out_trade_no":"f3a6865c9b0b4b9da97a01806e7f1999","partner":"1220432101","product_fee":"1","sign":"8E45777CB639D90A0AB3469B011081A8","sign_type":"MD5","time_end":"20141105164248","total_fee":"1","trade_mode":"1","trade_state":"0","transaction_id":"1220432101201411056155461293","transport_fee":"0"}';
        $data = array('attach'=>'e3dffe06-203d-4ac6-83a3-da292a936286|631');
        
        $xml = simplexml_load_string( $request, 'SimpleXMLElement', LIBXML_NOCDATA );
        if (($xml instanceof SimpleXMLElement) && isset($xml->OpenId) && isset($data['attach'])) {
            $attach = explode('|', $data['attach']);
            $url = 'http://wslm.dev.csc86.com/life/send';
            $data = array(
                'type'    => 1,
                'toUser'  => $xml->OpenId->__toString(),
                'orderId' => $attach[1],
                'userId'  => $attach[0],
            );
            var_dump($data);exit;
            $boolean = $this->requestUrl($url, $data);
            var_dump($boolean);
        }
        
    }
    
    public function filters() { //<!-- 覆盖父类的此方法,可对每一个action执行之前进行过滤
    	//parent::filters();
    	return array(
    		'AccessControl - Test', //<!-- 除test action之外所有的action都调用filterAccessControl进行过滤
            array('application.filters.MyFilter + Test'), 
            //<!-- 只对test action调用MyFilter进行过滤,application.filters.MyFilter代表路径/protected/filters/MyFilter.php
    	    'postOnly + Bb,submit', //<!-- 只有通过 POST 请求才能访问 wo/bb 页面；postOnly 是 yii 内置的过滤器，还有 ajaxOnly (只允许 ajax 请求) 也是内置的过滤器.
    	    //'PutOnly'
    	);
    }
    
    public function filterAccessControl($filterChain) {
//     	echo '123';
    	$filterChain->run(); //<!-- 这里会执行完对应的action后才往下执行
//     	echo '456';
    	
    }

    //<!-- 在服务端就需要过滤只有是 PUT 请求才可以访问到该控制器
    public function filterPutOnly($filterChain) {
    	if (Yii::app()->getRequest()->getIsPutRequest()) {
    		$filterChain->run();
    	} else {
    		throw new CHttpException(400, '请求无效');
    	}
    }
    
    /**
     * 加密解密练习
     */
    public function actionEncrypt() {
        //         $encrypt = new CSecurityManager();
        $encrypt = Yii::app()->securityManager;
        /* $string = '我laokao鸡123abc';
         $encrypt->setEncryptionKey('wokao');
        $str = $encrypt->encrypt($string);
        $filename = Yii::getPathOfAlias('webroot') . '\data\encryptString';
        file_put_contents($filename, $str); */
        $filename = Yii::getPathOfAlias('webroot') . '\data\encryptString';
        $str = file_get_contents($filename);
        $encrypt->setEncryptionKey('wokao');
        $string = $encrypt->decrypt($str);
        var_dump($string);exit;
    }
    
    /**
     * 查看CHttpRequest中的信息
     */
    public function actionRequest() {
//         Yii::app()->request->url
        // /manage/user.html?searchType=name&name=%E5%9C%B0%E6%96%B9&startTime=&endTime=
        // Yii::app()->request->hostInfo     http://wslm2.csc86.com
        // Yii::app()->request->baseUrl      ''
        // Yii::app()->request->scriptUrl    /index.php
        // Yii::app()->request->pathInfo     manage/user.html
        // Yii::app()->request->requestUri   /manage/user.html?searchType=name&name=%E5%9C%B0%E6%96%B9&startTime=&endTime=
        // Yii::app()->request->queryString  searchType=name&name=%E5%9C%B0%E6%96%B9&startTime=&endTime=
        // Yii::app()->request->serverName   wslm2.csc86.com
        // Yii::app()->request->serverPort   80
        // Yii::app()->request->urlReferrer  是哪个页面连过来的，没有就是本页，http://wslm2.csc86.com/manage/user.html
        // Yii::app()->request->userAgent    Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0
        // Yii::app()->request->userHostAddress 用户ip地址
    }
    
    

    
}
