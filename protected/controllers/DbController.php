<?php

/**
 * 数据库练习
 * @author Bear
 * @version 1.0.0
 * @copyright http://xiqiyanyan.com
 * @created 2014-3-10 09:37
 */
class DbController extends Controller
{
    public function actionTest() {
        Common_Tool::prePrint(Yii::app()->db);
    }
    
    public function actionConnection() {
        $dsn = 'mysql:host=127.0.0.1;dbname=YiiTest';
        $username = 'root';
        $password = '123456';
        
        $connection = new CDbConnection($dsn, $username, $password);
        try {
        	$connection->active = true; // 开启数据库连接
        	$username = 'ouyanyan';
        	$password = '123456';
        	$salt = 'ABCDEF';
        	$time = date('Y-m-d H:i:s');
        	$pwd = new Common_PwdCrypt();
        	$password = $pwd->encryptPassword($password, $salt);
        	$sql = "INSERT INTO `User` ( `Username`, `Password`, `Salt`, `CreatedTime`) VALUES ('{$username}', '{$password}', '{$salt}', '{$time}');";
//         	$command = $connection->createCommand($sql);
//         	$num = $command->execute();
//         	echo $num;

        	/* $sql="INSERT INTO tbl_user (username, email) VALUES(:username,:email)";
        	$command=$connection->createCommand($sql);
        	// replace the placeholder ":username" with the actual username value
        	$command->bindParam(":username",$username,PDO::PARAM_STR);
        	// replace the placeholder ":email" with the actual email value
        	$command->bindParam(":email",$email,PDO::PARAM_STR);
        	$command->execute();
        	// insert another row with a new set of parameters （使用新的参数集插入另一行）插入第二条数据
        	$command->bindParam(":username",$username2,PDO::PARAM_STR);
        	$command->bindParam(":email",$email2,PDO::PARAM_STR);
        	$command->execute(); */
        	
        	/* $command = $connection->createCommand();
        	$transaction = $connection->beginTransaction(); //<!-- 事务处理
        	try {
        		$ids = array(1,3);
        		foreach ($ids as $id) {
        			$num = $command->delete('User', '`ID`=:id', array(':id'=>$id));
        			if ($num < 1) {
        				throw new Exception('不存在的id：'. $id . '记录');
        			}
        		}
        		$transaction->commit();
        	} catch (Exception $e) {
        		$transaction->rollback();
        		Common_Tool::prePrint($e->getMessage());
        	} */
        	
        	$connection->enableParamLogging = true;
        	$command = $connection->createCommand();
        	$command->from('User');
        	$command->where('Username=:username', array(':username'=>'ouhaixiong'));
        	Common_Tool::prePrint($command->text);

        	$connection->active = false; // 关闭数据库连接
        } catch (CDbException $e) {
            if (2005 == $e->getCode()) {
            	Common_Tool::prePrint('无法连接数据库');
            }
        } catch (Exception $e) {
            Common_Tool::prePrint($e->getMessage());
        }   
    }
    
    /**
     * 测试sql注入
     */
    public function actionSqlInjection() {//         /db/sqlInjection?id=101; delete FROM tb_user_member where user_id=28
        $id = Yii::app()->request->getParam('id', 0);
        $db = Yii::app()->db;
//         $db = new CDbConnection();
        $id = $db->quoteValue($id); // 加引号, 特别注意了，这里加完引号后是单引号，然后字符串中如果有单引号的话，会自动转义掉
        $sql = "select * from `tb_user_member` where `user_id`={$id}";  Common_Tool::prePrint($sql);
        $result = $db->createCommand($sql)->queryAll();
        Common_Tool::prePrint($result);
    }
    
    /**
     * XSS攻击
     */
    public function actionXss() {//  /db/xss?abc=101;%27%20%3Cscript%3Ealert%2888%29;%3C/script%3E
    	$abc = Yii::app()->request->getParam('abc');
    	echo CHtml::encode($abc);
    	$html = '<h2>这里是html的H2</h2><p>哦哦啊啊';
//     	echo CHtml::encode($html);
		$htmlPurifier = new CHtmlPurifier(); 
    	echo $htmlPurifier->purify($html); // 很好，很强大，在页面如果变量是html格式，就按这个输出，它会帮你补全html标签
    }
    
    /**
     * 测试save 方法是否有防sql攻击
     */
    public function actionCreate() {
        $ar = new UserMember();
        $data = array();
        $data['accountNum'] = 60975;
        $data['nickname'] = '欧海雄';
        $data['email'] = '258333309@qq.com';
        $data['phoneNum'] = '15019261350';
        $data['sign'] = 'this is a sign\'); delete FROM tb_user_member;';
        $boolean = $ar->create($data); // CDbConnection

        Common_Tool::prePrint($boolean);
    }

}
