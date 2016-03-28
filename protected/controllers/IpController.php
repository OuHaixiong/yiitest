<?php

/**
 * 获取ip地址测试
 * @author Bear
 * @version 1.0.0
 * @copyright http://xiqiyanyan.com
 * @created 2014-3-10 09:37
 */
class IpController extends Controller
{
    /**
     * 测试负载均衡下面获取用户ip地址
     */
    public function actionIndex() {
        var_dump("这里是192.168.17.130");
        Common_Tool::prePrint(Common_Tool::getClientRealIp(), false);
        Common_Tool::prePrint(Common_Tool::getIp());
    }
    
    public function actionTest() {
        
    }
    
}