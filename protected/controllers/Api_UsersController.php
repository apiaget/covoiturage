<?php

class Api_UsersController extends Controller
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
		);
	}

	protected function beforeAction($event)
	{
		$auth = new AuthenticationCheck;
		$auth->checkAuth();
		return true;
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
				'actions'=>array('view','update'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
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
		header('Content-type: ' . 'application/json');
		$token = $_GET['token'];

		$requestedUser = User::model()->find('id=:id', array(':id' => $id));

		if ($requestedUser['token'] == $token) { //l'utilisateur demande ses propres réglages

			echo CJSON::encode(array('firstname' => $requestedUser->firstname,
				'lastname' => $requestedUser->lastname,
				'email' => $requestedUser->email,
				'phone' => $requestedUser->telephone,
				'privacy' => array(
					'hideEmail' => $requestedUser->hideEmail,
					'hidePhone' => $requestedUser->hideTelephone
				),
				'notifications' => array(
					'notifComment' => $requestedUser->notifComment,
					'notifDeleteRide' => $requestedUser->notifDeleteRide,
					'notifRegistration' => $requestedUser->notifInscription,
					'notifChange' => $requestedUser->notifModification,
					'notifUnsubscribe' => $requestedUser->notifUnsuscribe,
				)
			));
			Yii::app()->end();

		} else { //Un utilisateur demande les infos d'un autre utilisateur
			$returnUserArray = array();
			$returnUserArray['lastname'] = $requestedUser->lastname;
			$returnUserArray['firstname'] = $requestedUser->firstname;
			if ($requestedUser->hideEmail != 1) {
				$returnUserArray['email'] = $requestedUser->email;
			}
			if ($requestedUser->hideTelephone != 1) {
				$returnUserArray['phone'] = $requestedUser->telephone;
			}
			echo CJSON::encode($returnUserArray);
			Yii::app()->end();
		}
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 * @throws CHttpException
	 */
	public function actionUpdate($id)
	{
		header('Content-type: ' . 'application/json');
		$token = $_GET['token'];
		$userToUpdate = User::model()->find('id=:id', array(':id' => $id));
		$userRequest = User::model()->find('token=:token', array(':token' => $token));

		if(isset($userRequest) && $userToUpdate->id == $userRequest->id) { //on s'assure que l'utilisateur déclanchant la requête (identifié par le token soit le même que l'utilisateur à mettre à jour)
			$data = CJSON::decode(file_get_contents('php://input'));
			//on ne peut pas changer ni le nom, ni le prénom
			$userToUpdate->email = isset($data['email']) ? $data['email'] : $userToUpdate->email;
			$userToUpdate->telephone = isset($data['phone']) ? $data['phone'] : $userToUpdate->telephone;
			$userToUpdate->hideEmail = isset($data['privacy']['hideEmail']) ? $data['privacy']['hideEmail'] : $userToUpdate->hideEmail;
			$userToUpdate->hideTelephone = isset($data['privacy']['hidePhone']) ? $data['privacy']['hidePhone'] : $userToUpdate->hideTelephone;
			$userToUpdate->notifComment = isset($data['notifications']['notifComment']) ? $data['notifications']['notifComment'] : $userToUpdate->notifComment;
			$userToUpdate->notifDeleteRide = isset($data['notifications']['notifDeleteRide']) ? $data['notifications']['notifDeleteRide'] : $userToUpdate->notifDeleteRide;
			$userToUpdate->notifInscription = isset($data['notifications']['notifRegistration']) ? $data['notifications']['notifRegistration'] : $userToUpdate->notifInscription;
			$userToUpdate->notifModification = isset($data['notifications']['notifChange']) ? $data['notifications']['notifChange'] : $userToUpdate->notifModification;
			$userToUpdate->notifUnsuscribe = isset($data['notifications']['notifUnsubscribe']) ? $data['notifications']['notifUnsubscribe'] : $userToUpdate->notifUnsuscribe;

			$userToUpdate->update();
		}else{
			throw new CHttpException(403,'You have no rights to update that user.');
		}
		Yii::app()->end();
	}
}