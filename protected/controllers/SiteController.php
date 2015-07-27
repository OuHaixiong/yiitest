<?php

class SiteController extends Controller
{
	public $layout='column1';

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 * //<!-- 这里定义了 错误的处理方式和页面
	 */
	public function actionError()
	{
	    $error = Yii::app()->errorHandler->error;
// 	    var_dump($error);exit;  // 请求错误信息，是一个数组，如果是404会返回$error['code'] = 404;
	    if($error)
	    {
	    	if(Yii::app()->request->isAjaxRequest) {
	    		echo $error['message'];
	    	} else {
	    	    //<!-- 这里可以获取用户的头信息，看是手机还是pc访问，对手机端调用不同的视图进行处理
	    	    //<!-- 这里也可以判断错误代码，对404和500等信息进行处理
	    	    
	    	    /* $user_agent	= strtolower( Yii::app()->request->getUserAgent() );
	    	    $file404	= CSC_TEMPLATE_DIR.'404.php';
	    	    $file500	= CSC_TEMPLATE_DIR.'500.php';
	    	    if (preg_match('/iphone|android|ipad|mobile/', $user_agent)) {
	    	        $file404	= CSC_TEMPLATE_DIR.'404mobile.php';
	    	        $file404	= file_exists($file404)? $file404 : CSC_TEMPLATE_DIR.'404.php';
	    	        $file500	= CSC_TEMPLATE_DIR.'500mobile.php';
	    	        $file500	= file_exists($file500)? $file500 : CSC_TEMPLATE_DIR.'500.php';
	    	    }
	    	    if(@$error['code']==404){
	    	        $this->renderFile($file404);
	    	    }else{
	    	        $this->renderFile($file500);
	    	    } */
	    	    
	        	$this->render('error', $error);
	    	}
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (!defined('CRYPT_BLOWFISH')||!CRYPT_BLOWFISH)
			throw new CHttpException(500,"This application requires that PHP was compiled with Blowfish support for crypt().");
		
		$model=new LoginForm;
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
// 		$_POST['LoginForm'] = array(
// 		    'username'   => 'admin',
// 		    'password'   => '123456',
// 		    'rememberMe' => '0'
// 		);
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm']; //<!-- 收集用户输入的数据
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) { //<!-- 验证用户输入，如果验证通过则重定位到前个页面
				$this->redirect(Yii::app()->user->returnUrl);
			} 
			else {
// 				var_dump($model->getErrors());exit;
			}
		}
		
		// display the login form 显示登陆表单
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
