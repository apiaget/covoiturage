<?php

class FacebookController extends Controller
{
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

	protected function afterRender($view, &$output) {
		parent::afterRender($view,$output);
		//Yii::app()->facebook->addJsCallback($js); // use this if you are registering any additional $js code you want to run on init()
		//Yii::app()->facebook->initJs($output); // this initializes the Facebook JS SDK on all pages
		//Yii::app()->facebook->renderOGMetaTags(); // this renders the OG tags
		return true;
	}


	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		//die('test');

		//$userid = Yii::app()->facebook->getUserId() ;
		//$accessToken = Yii::app()->facebook->getToken() ;
		//$longLivedSession = Yii::app()->facebook->getLongLivedSession() ;
		//$exchangeToken = Yii::app()->facebook->getExchangeToken() ;
		//$loginUrl = Yii::app()->facebook->getLoginUrl() ;
		//$reRequestUrl = Yii::app()->facebook->getReRequestUrl() ;
		////$accessToken = Yii::app()->facebook->accessToken() ;
		//$sessionInfo = Yii::app()->facebook->getSessionInfo() ;
		//$signedRequest = Yii::app()->facebook->getSignedRequest() ;
		//$signedRequestData = Yii::app()->facebook->getSignedRequestData() ;
		//$property = Yii::app()->facebook->getSignedRequestProperty('property_name') ;
		//$logoutUrl = Yii::app()->facebook->getLogoutUrl('http://example.com/after-logout') ;
		////$graphPageObject = Yii::app()->facebook->makeRequest('/SOME_PAGE_ID')->getGraphObject(\Facebook\GraphPage::className()) ;
//
		//try {
//
		//	$response = Yii::app()->facebook->makeRequest('/me/feed', 'POST', array(
		//		'link' => 'www.example.com',
		//		'message' => 'User provided message'
		//	));//->getGraphObject();
		//	var_dump($response);
		//	//echo "Posted with id: " . $response->getProperty('id');
//
		//} catch (\Facebook\FacebookRequestException $e) {
//
		//	echo "Exception occurred, code: " . $e->getCode();
		//	echo " with message: " . $e->getMessage();
//
		//}


		//Yii::app()->facebook->destroySession();
		session_start();
		\Facebook\FacebookSession::setDefaultApplication( Yii::app()->params['IDAPP'], Yii::app()->params['SECRETAPP']);
		$helper = new \Facebook\FacebookRedirectLoginHelper('http://localhost/covoiturage/facebook');
		//echo $helper->getLoginUrl(['email']);
		$this->render('index', array('test' => $helper->getLoginUrl(['email'])));
	}



	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}


	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
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