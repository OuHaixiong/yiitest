<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
header("Content-Type:text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");

defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__)); // 不包括 /

// change the following paths if necessary
$yii = ROOT_PATH . '/../libraries/yiiframework/yii.php';
$config = ROOT_PATH . '/protected/config/main.php';

// $config = ROOT_PATH . '/protected/config/main2.php';  // 用命令行模式运的配置
// if( isset($argv[1]) ){
// 	$_GET['r'] = $argv[1];
// }

/* $include_path = get_include_path();
$include_path .= PATH_SEPARATOR . CSC_LIBS_DIR . 'common/modules';
$include_path .= PATH_SEPARATOR . CSC_LIBS_DIR . 'common/javamaps';
set_include_path( $include_path ); 设置包含路径*/

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true); // 打开这个就没有debug效果


// var_dump($argv[1]);exit;
require_once($yii);
Yii::createWebApplication($config)->run();
