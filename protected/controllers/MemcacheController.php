<?php

/**
 * MemCache练习
 * @author Bear
 * @version 1.0
 * @copyright xiqiyanyan.com
 * @created 2014-04-30 11:09
 */
class MemcacheController extends Controller
{
    /**
     * 连接本地memcache服务器
     */
    public function actionConnect() {
        $memcache = new Memcache();
        $memcache->connect('127.0.0.1', 11211);
        //$memcache->set('key', ' This is 欧海雄', null, time()+30); // 插入一条缓存，缓存时间为30s
        $value = $memcache->get('key'); // 有返回其数据，没有返回false
        Common_Tool::prePrint($value);
    }
    
}
