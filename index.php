<?php
header("Content-Type:text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");

// change the following paths if necessary
$yii=dirname(__FILE__).'/libs/yiiframework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true); // 打开这个就没有debug效果

require_once($yii);
Yii::createWebApplication($config)->run();
