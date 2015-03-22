<?php

class Api_RegistrationsController extends Controller
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
			array('allow',
				'actions'=>array('update'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' action
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 * @throws CHttpException
	 */
	public function actionUpdate($id)
	{
		$token = $_GET['token'];
		$today = date('Y-m-d 00:00:00', time());
		header('Content-type: ' . 'application/json');
		$userRequest = User::model()->find('token=:token', array(':token' => $token));

		$registrations = Registration::model()->findAll('user_fk = :user AND ride_fk = :ride AND date >= :today', array(':today' => $today, ':user' => $userRequest->id, ':ride' => $id));

        foreach($registrations as $registration){
            $registration->delete();
        }
        $data = CJSON::decode(file_get_contents('php://input'));
        $newRegistrations = $data['registrations'];
        foreach($newRegistrations as $newRegistration)
        {
            $newReg = new Registration;
            $newReg->ride_fk = $id;
            $newReg->user_fk = $userRequest->id;
            $newReg->date = $newRegistration;
            $newReg->save();
        }

        //On veut indiquer au conducter que les inscriptions au trajet qu'il propose ont été modifiées
        $ride=Ride::model()->find('id=:rideId', array(':rideId'=>$id));
        $driver = $ride->driver;
        $registrations = Registration::model()->findAll('user_fk = :user AND ride_fk = :ride AND date >= :today', array(':today' => $today, ':user' => $userRequest->id, ':ride' => $id));
        $subject = "Covoiturage CPNV - Un utilisateur a modifié ses inscriptions à ton trajet";
        $mail = new YiiMailer('modificationsinscriptions', array(
            'ride' => $ride,
            'registrations' => $registrations,
        ));
        $driver->sendEmail($mail,$subject);

		$registrations = array("registrations" => $registrations);
		echo CJSON::encode($registrations);
		Yii::app()->end();
	}
}