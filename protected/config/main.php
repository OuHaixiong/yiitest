<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',//当前应用根目录的绝对物理路径
	'name'=>'Yii Blog Demo',//当前应用的名称

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'defaultController'=>'post', //设置默认控制器类

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true, //允许自动登录
		    'identityCookie'=>array('domain'=>'.ipinla.com'), // 有多个分站时，同步登陆，基本于cookie
		),
		
	    /* 'cache' => array(//缓存组件
			'class' => 'CMemCache',//缓存组件类
	        'servers' => array( //MemCache缓存服务器配置
	    	    array('host'=>'server1', 'prot'=>'11211', 'weight'=>60),//缓存服务器1
	            array('host'=>'server2', 'port'=>11211, 'weight'=>40),//缓存服务器2
	        ),
		), */

        'cache' => array(
            'class' => 'system.caching.CFileCache',
            'cachePath' => ROOT_PATH . '/data/cache/',
//             'directoryLevel' => 2
        ),
        
	    
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=vragon_debug',
			//'connectionString' => 'sqlite:protected/data/blog.db', //连接数据库的DSN字符串
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
			//'tablePrefix' => 'tbl_', //数据表前缀
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',// 使用SiteController控制器类中的actionError方法显示错误
			//遇到错误时，运行的操作。控制器名和方法名均小写，并用斜线“/”隔开
		),
		'urlManager'=>array( //URL路由管理器
			'urlFormat'=>'path', //URL格式。 共支持两种格式：'path'格式（如：/path/to/EntryScript.php/name1/value1/name2/value2...）和'get'格式（如: /path/to/EntryScript.php?name1=value1&name2=value2...）。当使用'path'格式时，需要设置如下的规则：
			'showScriptName' => false, //<!-- 设为false时，隐藏index.php
			'rules'=>array(  //URL规则。语法：<参数名:正则表达式> 
				'post/<id:\d+>/<title:.*?>'=>'post/view', //将post/12/helloword指向post/view?id=12&title=helloword
				'posts/<tag:.*?>'=>'post/index',//将posts/hahahaha指向post/index?tag=hahahaha
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter', //处理记录信息的类
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',//处理错误信息的类
					'levels'=>'error, warning',//错误等级
				),
					
				// uncomment the following to show log messages on web pages
				// 如要将错误记录消息在网页上显示，取消下面的注释即可（下面显示页面日志）
				array(
					'class'=>'CWebLogRoute', // 我们将看到被执行的 SQL 语句被显示在每个页面的底部
// 					'levels' => 'trace', // 级别为trace
// 					'categories' => 'system.db.*' //只显示关于数据库信息,包括数据库连接,数据库执行语句
				),

			),
		),
	),
	'modules' => array(
	        'backend' => array(
// 	                'class'       => 'application.modules.backend.BackendModule',
	                'postPerPage' => 20
	        ),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);