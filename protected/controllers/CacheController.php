<?php

/**
 * Cache练习
 * @author Bear
 * @version 1.0
 * @copyright http://maimengmei.com
 * @created 2014-04-30 11:09
 */
class CacheController extends Controller
{
    /**
     * 片段缓存
     */
    public function actionFragment() {
    	if ($this->beginCache('abc', array('duration'=>3600))) :
    	echo 'ABCdsaf我冻死了122333尼玛发';
    	echo '<br />我也懂呀ko《br/>';
    	$this->endCache();
    	endif;
    	echo 'wo che';
    }

    /**
     * redis操作
     */
    public function actionRedis() {
        $redis = new Redis();
        $redis->connect('192.168.17.134', 6379, 300); // 特别注意了：这里只是设置，只有在操作数据时才真正链接服务器，所以这里返回的是：object(Redis)
//      $redis->setRange('key', 6, "redis"); /* returns 11 */
        try {
            $boolean = $redis->set('key', '欧阳海雄');
        } catch (RedisException $e) {
            echo $e->getMessage();
            if (0 == $e->getCode()) {
            	die(' 无法链接redis服务器');
            }
        } catch (Exception $e) {
            echo '未知错误！';
            print_r($e);
        }
        var_dump($boolean);
        var_dump($redis);
        echo '<br />';
        $redis2 = new Redis();
        $redis2->open('192.168.0.52', 6379, 0);
        //$boolean = $redis2->set('key', '欧阳海雄');
        var_dump($boolean);
        $str = $redis2->get('key');
        echo '主：' . $str;
        echo '<br />';
        $str2 = $redis->get('key');
        echo '从：' . $str2;
        echo '<br /><br />';
        
//         $redis2->setRange('key', 2, 'redis'); // 在字符串的第二个位置替换字符串
        $redis3 = new Redis();
        $redis3->connect('192.168.17.130', 6379);
        $str3 = $redis3->get('key');
        echo '130从：' . $str3;
    }

}
