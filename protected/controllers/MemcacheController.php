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
    
    /**
     * 测试、使用memcache
     */
    public function actionTest() {
        //$memcache = new Memcache();
        //$memcache->addserver('192.168.0.141', 11211);
        //$version = $memcache->getversion(); // 1.4.15 获取memcache服务器的版本号
        //$boolean = $memcache->set(md5('key'), '$this is a test string.', 32, 0); // 后两参数是，压缩率，过期时间(0 为永不过期)
        //$boolean = $memcache->add(md5('key'), '如果有,就无法改变值', MEMCACHE_COMPRESSED, 0);// 过期时间可以是Unix时间戳，也可以是秒数，但不能超过30天（2592000秒）
        //$obj = new stdClass();
        //$obj->aa = 'aa';
        //$obj->bb = 123;
        //$boolean = $memcache->add(md5('obj'), $obj, 2, 60);
        //Common_Tool::prePrint($boolean, false);
        //$string = $memcache->get(md5('obj'));
        //Common_Tool::prePrint($string);
        //MEMCACHE_COMPRESSED (integer)        用于调整在使用 Memcache::set(), Memcache::add() 和 Memcache::replace() 几个函数时的压缩比率。
        //MEMCACHE_HAVE_SESSION (integer)      如果通信对话的处理（session handler）被允许使用值为 1，其他情况值为 0。
        
        $memcache = memcache_connect('localhost', 11211);
        memcache_close($memcache);
    }
    
    /* bool Memcache::addServer ( string $host [, int $port [, bool $persistent [, int $weight [, int $timeout [, int $retry_interval [, bool $status [, callback $failure_callback ]]]]]]] )
        向对象添加一个服务器

    　　参数
    　　host               服务器域名或 IP
    　　port               端口号，默认为 11211
    　　persistent         是否使用常连接，默认为 TRUE
    　weight             权重，在多个服务器设置中占的比重
    　　timeout            超时连接失效的秒数，修改默认值 1 时要三思，有可能失去所有缓存方面的优势导致连接变得很慢
    　　retry_interval    服务器连接失败时的重试频率，默认是 15 秒一次，如果设置为 -1 将禁止自动重试，当扩展中加载了 dynamically via dl() 时，无论本参数还是常连接设置参数都会失效。
    　　每一个失败的服务器在失效前都有独自的生存期，选择后端请求时会被跳过而不服务于请求。一个过期的连接将成功的重新连接或者被标记为失败的连接等待下一次重试。这种效果就是说每一个 web server 的子进程在服务于页面时的重试连接都跟他们自己的重试频率有关。
    　　status             控制服务器是否被标记为 online，设置这个参数为 FALSE 并设置 retry_interval 为 -1 可以使连接失败的服务器被放到一个描述不响应请求的服务器池子中，对这个服务器的请求将失败，接受设置为失败服务器的设置，默认参数为 TRUE，代表该服务器可以被定义为 online。
    　　failure_callback   失败时的回调函数，函数的两个参数为失败服务器的 hostname 和 port
    　　------------------------------------------------------------
    　　返回值
    　　成功返回 TRUE，失败返回 FALSE。 */
}
