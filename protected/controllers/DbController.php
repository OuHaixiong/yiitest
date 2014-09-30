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
        	// insert another row with a new set of parameters
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

}
