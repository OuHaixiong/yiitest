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
    	echo 88;
    }
    
    public function actionBb($v1 = null) {

        var_dump($_POST);exit;
    }
    
    public function actionTest() {
    	$str = '';
    	if ($str) {
    		echo 'Y';
    	} else {
    		echo 'No';
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
