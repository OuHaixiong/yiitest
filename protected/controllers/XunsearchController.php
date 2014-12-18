<?php

/**
 * XunSearch练习
 * @author Bear
 * @version 1.0
 * @copyright http://maimengmei.com
 * @created 2014-11-11 20:09
 */
class XunsearchController extends Controller
{
    /**
     * 连接
     */
    public function actionConnect() {
        require_once ROOT_PATH . '/../libraries/xunsearch/lib/XS.php';
//         $xs = new XS('demo');// 自动使用 $prefix/sdk/php/app/demo.ini 作项目配置文件
        $file = ROOT_PATH . '/../libraries/xunsearch/app/demo.ini';
        $xs = new XS($file);// 使用 /path/to/demo.ini
        // $xs->defaultCharset
        $documents = $xs->search->search('测试');

        Common_Tool::prePrint($documents);
    }
    
    /**
     * 测试
     */
    public function actionTest() {
        require_once ROOT_PATH . '/../libraries/xunsearch/lib/XS.php';
        try {
            $xs = new XS('demo');
            $docs = $xs->search->setQuery('第三')->setLimit(5)->search();
            foreach ($docs as $doc) { //XSDocument
//                 foreach ($doc as $k=>$v) {
//                 	echo $v;
//                 }
//                 Common_Tool::prePrint($doc);
                echo $doc->rank() . ". " . $doc->subject . " [" . $doc->percent() . "%]\n";
                echo $doc->message . "\n" . $doc->weight();
                // rank() 取得搜索结果文档的序号值 (第X条结果) 
                // percent() 取得搜索结果文档的匹配百分比 (结果匹配度, 1~100) 
                // weight() 取得搜索结果文档的权重值 (浮点数) 
            }
        } catch (XSException $e) {
            echo $e;         // 直接输出异常描述
            if (YII_DEBUG) { // 如果是 DEBUG 模式，则输出堆栈情况
                echo "\n" . $e->getTraceAsString() . "\n";
            }
        }
    }
    
    /**
     * 测试添加索引
     */
    public function actionAdd() {
        require_once ROOT_PATH . '/../libraries/xunsearch/lib/XS.php';
        $doc = new XSDocument();
        $doc->pid = 4;
        $doc->subject = 'Hello, 测试,第三名后面的第四名';
        $doc->message = '第四条测试的内容在此';
        $doc->chrono = 123456789;
        $xs = new XS('demo');
        $boolean = $xs->index->add($doc);
        Common_Tool::prePrint($boolean);
    }
    
    /**
     * 建立微盟成员索引
     */
    public function actionWeiMeng() {
        $dsn = 'mysql:host=192.168.0.63;dbname=csc_wslm';
        $username = 'yaorenquan';
        $password = 'yrq869918';
        
        $connection = new CDbConnection($dsn, $username, $password);
        $connection->charset = 'utf8';

        try {
            require_once ROOT_PATH . '/../libraries/xunsearch/lib/XS.php';
            $xs = new XS('wei_meng');
            $xs->index->clean();
            Common_Tool::prePrint('已清空索引', false);
            
            $connection->active = true; // 开启数据库连接
            $sql = 'select * from `csc_user`';
            $command = $connection->createCommand($sql);
            $dataReader = $command->query(); // CDbDataReader
            foreach ($dataReader as $row) {
                $data = array(
                	'id' => $row['id'],
                    'userName' => $row['userName'],
                    'weixinAccount' => $row['weixinAccount'],
                    'fansNumber' => $row['fansNumber']
                );
                $doc = new XSDocument();
                $doc->setFields($data);
                
                $xs->index->add($doc);// 添加到索引数据库中
            }
            Common_Tool::prePrint('添加索引成功，正在关闭数据库连接', false);
            $connection->active = false; // 关闭数据库连接
        } catch (CDbException $e) {
            if (2005 == $e->getCode()) {
                Common_Tool::prePrint('无法连接数据库');
            }
            Common_Tool::prePrint($e->getMessage());
        } catch (Exception $e) {
            Common_Tool::prePrint($e->getMessage());
        }
    }
    
    /**
     * 搜索微盟成员索引
     */
    public function actionSearchWeiMeng() {
        require_once ROOT_PATH . '/../libraries/xunsearch/lib/XS.php';
        $xs = new XS('wei_meng');
        $search = $xs->search;
        $search->setMultiSort(array('fansNumber'=>false)); // true：正序，小到大；flase：倒序，大到小 
//         $search->setQuery('qq.com');
        $search->setLimit(20, 0); // limit(条数, 偏移量)
        $search->setFuzzy();
        $docs = $search->search('163');
//         $docs = $search->search('id:3');
//         $docs = $xs->search->search('140 OR id:3');
//         $docs = $xs->search->setFuzzy()->search('13');
        $html = '';
        foreach ($docs as $doc) {
        	$html .= 'id:' . $doc->id . '  userName:' . $doc->userName . '  weixinAccount:' . $doc->weixinAccount . 
        	'  fansNumber:' . $doc->fansNumber . '<br />';
        	
        }
        Common_Tool::prePrint($xs->search->getQuery(), false);
        echo $html;
    }

}
