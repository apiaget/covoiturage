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

		$registrations = Registration::model()->findAll('user_fk = :user AND ride_fk = :ride AND date >= :today', array(':today' => $today, ':user' => $userRequest->id, ':ride' => $id));

		$registrations = array("registrations" => $registrations);

		echo CJSON::encode($registrations);
		Yii::app()->end();

		//die;
		//$ride = Ride::model()->find('id=:id and visibility=1', array(':id' => $id));
		//if(isset($ride) && $ride->driver_fk == $userRequest->id){
		//	$data = CJSON::decode(file_get_contents('php://input'));
		//	$ride->departuretown_fk = isset($data['departuretown']['id']) ? $data['departuretown']['id'] : $ride->departuretown_fk;
		//	$ride->departure = isset($data['departure']) ? "1970-01-01 ".$data['departure'] : $ride->departure;
		//	$ride->arrivaltown_fk = isset($data['arrivaltown']['id']) ? $data['arrivaltown']['id'] : $ride->arrivaltown_fk;
		//	$ride->arrival = isset($data['arrival']) ? "1970-01-01 ".$data['arrival'] : $ride->arrival;
		//	$ride->startDate = isset($data['startdate']) ? $data['startdate'] : $ride->startDate;
		//	$ride->endDate = isset($data['enddate']) ? $data['enddate'] : $ride->endDate;
		//	$ride->description = isset($data['description']) ? $data['description'] : $ride->description;
		//	$ride->seats = isset($data['seats']) ? $data['seats'] : $ride->seats;
		//	$ride->monday =  isset($data['recurrence']['monday']) ? $data['recurrence']['monday'] : $ride->monday;
		//	$ride->tuesday =  isset($data['recurrence']['tuesday']) ? $data['recurrence']['tuesday'] : $ride->tuesday;
		//	$ride->wednesday =  isset($data['recurrence']['wednesday']) ? $data['recurrence']['wednesday'] : $ride->wednesday;
		//	$ride->thursday =  isset($data['recurrence']['thursday']) ? $data['recurrence']['thursday'] : $ride->thursday;
		//	$ride->friday =  isset($data['recurrence']['friday']) ? $data['recurrence']['friday'] : $ride->friday;
		//	$ride->saturday =  isset($data['recurrence']['saturday']) ? $data['recurrence']['saturday'] : $ride->saturday;
		//	$ride->sunday =  isset($data['recurrence']['sunday']) ? $data['recurrence']['sunday'] : $ride->sunday;
		//	$ride->visibility =  isset($data['visibility']) ? $data['visibility'] : $ride->visibility;
		//	$ride->save(); //Si on met update(), les données ne sont pas revalidées
//
		//	if(count($ride->errors)>0){
		//		header('HTTP/1.1 400');
		//	}else{
		//		header('HTTP/1.1 200');
		//		$registrationsArray = array($ride->registrations);
		//		usort($registrationsArray[0], function( $a, $b ) {
		//			return strtotime($a["date"]) - strtotime($b["date"]);
		//		});
		//		$rideArray = array(
		//			"id"=>$ride->id,
		//			"departuretown" => array("id"=>$ride->departuretown->id,"name"=>$ride->departuretown->name),
		//			"departure"=>date("H:i",strtotime($ride->departure)),
		//			"arrivaltown" => array("id"=>$ride->arrivaltown->id,"name"=>$ride->arrivaltown->name),
		//			"arrival"=>date("H:i",strtotime($ride->arrival)),
		//			"startdate"=>$ride->startDate,
		//			"enddate"=>$ride->endDate,
		//			"description"=>$ride->description,
		//			"seats"=>$ride->seats,
		//			"isrecurrence"=>$ride->startDate!=$ride->endDate,
		//			"recurrence" => array(
		//				"monday" => $ride->monday,
		//				"tuesday" => $ride->tuesday,
		//				"wednesday" => $ride->wednesday,
		//				"thursday" => $ride->thursday,
		//				"friday" => $ride->friday,
		//				"saturday" => $ride->saturday,
		//				"sunday" => $ride->sunday
		//			),
		//			"registrations"=> $registrationsArray
		//		);
		//		echo CJSON::encode($rideArray);
		//	}
		//	Yii::app()->end();
		//}else if(!isset($ride)){
		//	throw new CHttpException(404,'Ride not found.');
		//}else {
		//	throw new CHttpException(403,'You have no rights to update that ride.');
		//}
	}

}