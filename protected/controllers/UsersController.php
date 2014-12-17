<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','modif'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if(strpos(Yii::app()->request->getAcceptTypes(),'application/json')!==false){ //demande du json
			$token = Yii::app()->request->getQuery('token');
			$now = date('Y-m-d H:i:s', time());; //temps maintenant
			if($token == ''){
				//Doit s'arrêter car aucun token n'est fourni
				throw new CHttpException(403,'You are not authenticated.');
			}

			$requestUser = User::model()->find('token=:token and validbefore>:validtime', array(':token'=>$token, 'validtime'=>$now));

			if($requestUser == null)
			{
				//Doit demander une authentification du user (car le token n'est plus valide)
				throw new CHttpException(403,'You are not authenticated.');
			}else{
				//met à jour la validité du token
				$requestUser->validbefore = date("Y-m-d H:i:s",strtotime("+1 month", strtotime($now)));
				$requestUser->save();
			}

			$requestedUser = $this->loadModel($id);

			if($requestedUser['token'] == $token){ //l'utilisateur demande ses propres réglages

				echo CJSON::encode(array('firstname'=>$requestedUser->firstname,
					'lastname'=>$requestedUser->lastname,
					'email'=>$requestedUser->email,
					'phone'=>$requestedUser->telephone,
					'privacy'=>array(
						'hideEmail'=>$requestedUser->hideEmail,
						'hidePhone'=>$requestedUser->hideTelephone
					),
					'notifications'=>array(
						'notifComment'=>$requestedUser->notifComment,
						'notifDeleteRide'=>$requestedUser->notifDeleteRide,
						'notifRegistration'=>$requestedUser->notifInscription,
						'notifChange'=>$requestedUser->notifModification,
						'notifUnsubscribe'=>$requestedUser->notifUnsuscribe,
					)
					));
				Yii::app()->end();

			}else { //Un utilisateur demande les infos d'un autre utilisateur
				$returnUserArray = array();
				$returnUserArray['lastname'] = $requestedUser->lastname;
				$returnUserArray['firstname'] = $requestedUser->firstname;
				if($requestedUser->hideEmail!=1){
					$returnUserArray['email'] = $requestedUser->email;
				}
				if($requestedUser->hideTelephone!=1){
					$returnUserArray['phone'] = $requestedUser->telephone;
				}
				echo CJSON::encode($returnUserArray);
				Yii::app()->end();
			}
		}else {
			$this->render('view', array(
				'model' => $this->loadModel($id),
			));
		}
	}

	public function actionModif()
	{
		$user=User::currentUser();

		if(isset($_POST['User']))
		{
			$user->attributes=$_POST['User'];
			if($user->save())
				$this->redirect(array('modif'));
		}
		$this->render('modif',array('user'=>$user,));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
