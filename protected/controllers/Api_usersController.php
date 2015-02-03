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
				'actions'=>array('view','update','connexion','deconnexion'),
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
					'hideEmail' => (bool)$requestedUser->hideEmail,
					'hidePhone' => (bool)$requestedUser->hideTelephone
				),
				'notifications' => array(
					'notifComment' => (bool)$requestedUser->notifComment,
					'notifDeleteRide' => (bool)$requestedUser->notifDeleteRide,
					'notifRegistration' => (bool)$requestedUser->notifInscription,
					'notifChange' => (bool)$requestedUser->notifModification,
					'notifUnsubscribe' => (bool)$requestedUser->notifUnsuscribe,
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

	public function actionConnexion()
	{
		header('Content-type: ' . 'application/json');

		$email = "";
		$password = "";

		$data = CJSON::decode(file_get_contents('php://input'));
		if (isset($data['email']) && isset($data['password'])) {
			$email = $data['email'];
			$password = $data['password'];
		} else {
			throw new CHttpException(400, 'email or password field not filled');
		}

		$ch = curl_init();

		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_CAINFO => getcwd() . "/cert/cacert.pem", //http://curl.haxx.se/docs/caextract.html (contient bon nombre de certificats de CA)
			CURLOPT_URL => 'https://intranet.cpnv.ch/connexion',
			CURLOPT_HTTPHEADER => array(
				"Accept:application/json"
			),
			CURLOPT_POSTFIELDS => http_build_query(array(
				'user_session[email]' => $email,
				'user_session[password]' => $password,
			))
		));

		$server_output = curl_exec($ch);


		if ($server_output === false) {
			//n'arrive pas à atteindre l'intranet
			var_dump(curl_error($ch));
		} else if (isset(json_decode($server_output)->errors) && in_array("Identifiant ou mot de passe invalide", json_decode($server_output)->errors[0])){ //erreur d'authentification
			throw new CHttpException(401, 'Identifiant ou mot de passe invalide');
		}else{
			//ça marche
			$userData = json_decode($server_output);
			$friendlyid=$userData->friendly_id;

			$userRequest = User::model()->find('cpnvId=:friendlyid', array(':friendlyid' => $friendlyid));
			if($userRequest!=null){ //utilisateur déjà existant
				$userRequest->token = md5(uniqid($friendlyid, true));
				$userRequest->validbefore = date("Y-m-d H:i:s",strtotime("+1 month", strtotime(date('Y-m-d H:i:s', time()))));
				$userRequest->save(false);

				$returnToken = array();
				$returnToken['id'] = $userRequest->id;
				$returnToken['token'] = $userRequest->token;
				echo CJSON::encode($returnToken);
				Yii::app()->end();
			}else{ //utilisateur non existant dans la DB de covoiturage
				$user = new User();
				$user->cpnvId = $friendlyid;
				$user->email = $userData->corporate_email;
				$user->firstname=$userData->firstname;
				$user->lastname=$userData->lastname;
				$user->hideEmail = false;
				$user->hideTelephone = false;
				$user->notifInscription = true;
				$user->notifComment = true;
				$user->notifUnsuscribe = true;
				$user->notifDeleteRide = true;
				$user->notifModification = true;
				$user->blacklisted = false;
				$user->admin = false;
				$user->token = md5(uniqid($friendlyid, true));
				$user->validbefore = date("Y-m-d H:i:s",strtotime("+1 month", strtotime(date('Y-m-d H:i:s', time()))));
				$user->save(false);

				$returnToken = array();
				$returnToken['id'] = $userRequest->id;
				$returnToken['token'] = $user->token;
				echo CJSON::encode($returnToken);
				Yii::app()->end();
			}
		}

		curl_close ($ch);

		die("tentative de connexion");

		//$userToUpdate = User::model()->find('id=:id', array(':id' => $id));


		Yii::app()->end();
	}

	public function actionDeconnexion()
	{
		die("tentative de déconnexion");
		header('Content-type: ' . 'application/json');
		$token = $_GET['token'];

		Yii::app()->end();
	}

}