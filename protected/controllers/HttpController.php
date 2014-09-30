<?php

/**
 * Http请求
 * @author Bear
 * @version 1.0
 * @copyright http://maimengmei.com
 * @created 2014-08-14 20:22
 */
class HttpController extends Controller
{
    /**
     * zend 的http请求类(不提倡单独用，耦合性太强，不好修改)
     */
    public function actionZend() {
//         Yii::import(ROOT_PATH . '/../libraries/*');
//         Yii::import('/home/u32/www/libraries/*');
        Yii::import('application.extensions.*');
        require_once('Zend/Http/Client.php');
        $httpClient = new Zend_Http_Client('http://wslm1.csc86.com/api/weixin/getTicket?sceneId=5', array(
        	'timeout' => 30,
            'maxredirects' => 0,
        ));
        $response = $httpClient->request('GET');
        $body = $response->getBody();
        
        
        Common_Tool::prePrint($body);
    }
    
    /**
     * http_client类测试
     */
    public function actionClient() {
        $url = 'http://wslm1.csc86.com/api/weixin/getTicket';
        $method = 'post';
        $data = array('sceneId'=>6);
        $timeout = 60;
    	$client = new Common_HttpClient();
    	$result = $client->sendRequest($url, $data, $method, $timeout);
    	if (!empty($result)) {
    		$result = json_decode($result);
    	}
    	Common_Tool::prePrint($result);
    }
    

}
