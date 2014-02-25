<?php

class RidesController extends Controller
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
				'actions'=>array('index','view','create'),
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
		//die($_GET['date']);
		$cpnvId="j.le.noob";
		$user=User::model()->find('cpnvId=:cpnvId', array(':cpnvId'=>$cpnvId));

					//$registration=new Registration;
			    if(isset($_POST['date'])){ //l'utilisateur désire s'inscrire
			    	if(isset($_POST['recurrence'])){ //le trajet PEUT être récurrent
			    		if(isset($_POST['allerretour'])){ //le trajet possède un aller-retour
			    		//	SI la date est correcte
			    		//		SI l'utilisateur a coché la réccurence
			    		//			SI allerretour existe et l'utilisateur a coché l'allerretour
			    		//			créer un enregistrement pour l'utilisateur dans registrations avec une startDate égale à $_POST['date'] et une
			    		//				endDate égale à l'endDate du ride avec un ride_fK égal à $ride->bindedride
			    		//			FIN SI
			    		//			créer un enregistrement pour l'utilisateur dans registrations avec une startDate égale à $_POST['date'] et une
			    		//				endDate égale à l'endDate du ride
			    		//			rediriger l'utilisateur sur la page d'accueil
			    		//		SINON
			    		//			SI allerretour existe et l'utilisateur a coché l'allerretour
			    		//			créer un enregistrement pour l'utilisateur dans registrations avec une startDate et une endDate égale à $_POST['date']
			    		//				avec un ride_fK égal à $ride->bindedride
			    		//			FIN SI
			    		//			créer un enregistrement pour l'utilisateur dans registrations avec une startDate et une endDate égale à $_POST['date']
			    		//			rediriger l'utilisateur sur la page d'accueil
			    		//		FIN SI		
			    		//	SINON
			    		//		Rediriger l'utilisateur sur la page du trajet et afficher l'erreur comme quoi la date est incorrecte
			    		//	FIN SI
			    		}else{ //le trajet ne possède PAS d'aller-retour
			    		//	SI la date est correcte
			    		//		SI l'utilisateur a coché la réccurence
			    		//			créer un enregistrement pour l'utilisateur dans registrations avec une startDate égale à $_POST['date'] et une
			    		//				endDate égale à l'endDate du ride
			    		//			rediriger l'utilisateur sur la page d'accueil
			    		//		SINON
			    		//			créer un enregistrement pour l'utilisateur dans registrations avec une startDate et une endDate égale à $_POST['date']
			    		//			rediriger l'utilisateur sur la page d'accueil
			    		//		FIN SI		
			    		//	SINON
			    		//		Rediriger l'utilisateur sur la page du trajet et afficher l'erreur comme quoi la date est incorrecte
			    		//	FIN SI
			    		}
			    	}else{ //le trajet n'est JAMAIS récurrent
			    		//	SI la date est correcte
			    		//		créer un enregistrement pour l'utilisateur dans registrations avec une startDate et une endDate égale à $_POST['date']
			    		//		rediriger l'utilisateur sur la page d'accueil
			    		//	SINON
			    		//		Rediriger l'utilisateur sur la page du trajet et afficher l'erreur comme quoi la date est incorrecte
			    		//	FIN SI
			    	}
			    	$this->redirect(Yii::app()->getRequest()->getUrlReferrer());
			    }

		$today = date('Y-m-d 00:00:00', time());
		$registrations=Registration::model()->findAll('ride_fk=:ride_fk AND endDate>=:today', array(':ride_fk'=>$id, ':today'=>$today));

		$this->render('view',array('ride'=>$this->loadModel($id),'user'=>$user,'registrations'=>$registrations));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Ride;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ride']))
		{
			$model->attributes=$_POST['Ride'];
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

		if(isset($_POST['Ride']))
		{
			$model->attributes=$_POST['Ride'];
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
	/*public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}*/

	/**
	 * Lists all models.
	 */
	/*public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Ride');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
	/*public function actionAdmin()
	{
		$model=new Ride('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ride']))
			$model->attributes=$_GET['Ride'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}*/

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ride the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ride::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ride $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ride-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
