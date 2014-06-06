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
				'actions'=>array('index','view','create','update'),
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
		$user=User::currentUser();
		$today = date('Y-m-d 00:00:00', time());
		
		$registrations=Registration::model()->findAll('ride_fk=:ride_fk AND endDate>=:today', array(':ride_fk'=>$id, ':today'=>$today));

		//ne pas permettre l'affichage de rides désactivés
		$ride=$this->loadModel($id);
		if($ride->visibility==0)
		{
			$this->redirect(Yii::app()->user->returnUrl);
		}

		//Suppression
		if(isset($_POST['supprimer'])){
			//change la visibilité du ride
			$ride=$this->loadModel($id);
			$ride->visibility=0;
			$ride->save(false);

			foreach($registrations as $registration)
			{
				if($registration->userFk->notifDeleteRide==1)
				{
					$subject = "Covoiturage CPNV - Un de vos trajets a été supprimé";
					$mail = new YiiMailer('suppression', array(
						'registration' => $registration,	
					));
					$registration->userFk->sendEmail($mail,$subject);
				}
			}
			//redirection sur la page d'accueil
			$this->redirect(Yii::app()->user->returnUrl);
		}
		//Inscription aller-retour
		if(isset($_POST['inscrireAllerRetour'])&&$_POST['dateDebut']!=""&&$_POST['dateFin']!="")
		{
			$reg = new Registration;
			$reg->user_fk=User::model()->currentUser()->id;
			$reg->ride_fk=$this->loadModel($id)->id;
			$reg->startDate=date("Y-m-d 00:00:00",strtotime($_POST['dateDebut']));
			$reg->endDate=date("Y-m-d 00:00:00",strtotime($_POST['dateFin']));
			$reg->accepted=0;

			$regRetour = new Registration;
			$regRetour->user_fk=User::model()->currentUser()->id;
			$regRetour->ride_fk=$this->loadModel($id)->bindedride;
			$regRetour->startDate=date("Y-m-d 00:00:00",strtotime($_POST['dateDebut']));
			$regRetour->endDate=date("Y-m-d 00:00:00",strtotime($_POST['dateFin']));
			$regRetour->accepted=0;

			//Les registrations sont valides ?
			$result=$reg->validate();
			$resultRetour=$regRetour->validate();

			//Notification
			if($result && $resultRetour)
			{
				if($reg->rideFk->driver->notifInscription==1)
				{
					$subject = "Covoiturage CPNV - Un utilisateur s'est inscrit à votre trajet";
					$mail = new YiiMailer('inscription', array(
						'registration' => $reg,	
					));
					$regRetour->rideFk->driver->sendEmail($mail,$subject);
					$mail = new YiiMailer('inscription', array(
						'registration' => $regRetour,	
					));
					$regRetour->rideFk->driver->sendEmail($mail,$subject);
				}
			}
			else
			{
				if($result)
				{
					if($reg->rideFk->driver->notifInscription==1)
					{
						
						$subject = "Covoiturage CPNV - Un utilisateur s'est inscrit à votre trajet";
						$mail = new YiiMailer('inscription', array(
							'registration' => $reg,	
						));

						$reg->rideFk->driver->sendEmail($mail,$subject);
					}
				}
				if($resultRetour)
				{
					if($reg->rideFk->driver->notifInscription==1)
					{

						$subject = "Covoiturage CPNV - Un utilisateur s'est inscrit à votre trajet";
						$mail = new YiiMailer('inscription', array(
							'registration' => $regRetour,	
						));

						$regRetour->rideFk->driver->sendEmail($mail,$subject);
					}
				}
			}
			$this->redirect(Yii::app()->getRequest()->getUrlReferrer());
		}
		//inscription à l'aller
		if(isset($_POST['inscrire'])&&$_POST['dateDebut']!=""&&$_POST['dateFin']!="") 
		{
			$reg = new Registration;
			$reg->user_fk=User::model()->currentUser()->id;
			$reg->ride_fk=$this->loadModel($id)->id;
			$reg->startDate=date("Y-m-d 00:00:00",strtotime($_POST['dateDebut']));
			$reg->endDate=date("Y-m-d 00:00:00",strtotime($_POST['dateFin']));
			$reg->accepted=0;
			//on rempli les paramètres de la registration

			$result = $reg->validate();
			
	
			//Notification
			if($result)
			{
				if($reg->rideFk->driver->notifInscription==1)
				{
					$subject = "Covoiturage CPNV - Un utilisateur s'est inscrit à votre trajet";
						$mail = new YiiMailer('inscription', array(
							'registration' => $reg,	
						));

						$reg->rideFk->driver->sendEmail($mail,$subject);
				}
			}

			$errors = $reg->getErrors(); //chope les erreurs retournées par le modèle de $reg (Registration)
			if(count($errors)!=0)
			{
				Yii::app()->user->setFlash('startDate', $errors['startDate'][0]);
			}
			$this->redirect(Yii::app()->getRequest()->getUrlReferrer());
		}

		//désinscription
		if(isset($_POST['desinscrire'])){
			
			foreach($registrations as $registration){
				if($registration->userFk->id == User::model()->currentUser()->id)
				{
					$registration->delete();

					//Notification
					if($registration->rideFk->driver->notifUnsuscribe==1)
					{
						$subject = "Covoiturage CPNV - Un utilisateur s'est désinscrit de l'un de vos trajets";
						$mail = new YiiMailer('desinscription', array(
							'registration' => $registration,	
						));
						$registration->rideFk->driver->sendEmail($mail, $subject);
					}
				}
			}
			$this->redirect(Yii::app()->user->returnUrl);
		}
		//Validation
		if(isset($_POST['valider'])){
			$reg=$_POST['idReg'];
			foreach($registrations as $registration){
				if($registration->id == $reg)
				{
					$registration->accepted=1;
					$registration->save(false);
					//Notification
					if($registration->userFk->notifValidation==1)
					{
						$subject = "Covoiturage CPNV - Votre inscription a été validée";
						$mail = new YiiMailer('validation', array(
							'registration' => $registration,
							
						));
						$registration->userFk->sendEmail($mail,$subject);
					}
				}
			}
		}
			

		if(isset($_POST['editer'])){
			//change la visibilité du ride
			$ride=$this->loadModel($id);

			//redirection sur la page d'accueil
			$this->redirect(array('rides/update','id'=>$ride->id));
		}
		
		$this->render('view',array('ride'=>$this->loadModel($id),'user'=>$user,'registrations'=>$registrations));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$ride=new Ride;
		$rideRetour=new Ride;
		$user=User::model()->currentUser();


		if(isset($_POST['Ride']))
		{
			$ride->attributes=$_POST['Ride'];
			$ride->startDate=date("Y-m-d",strtotime($ride->startDate));
			$ride->endDate=date("Y-m-d",strtotime($ride->endDate));
			
			$rideRetour->attributes=$_POST['Ride_retour'];
			$ride->driver_fk=User::currentUser()->id;
			
			$ride->day=date('N', strtotime($ride->startDate));
			

			//si retour
			if($_POST['retour']=='oui')
			{
				$rideRetour->driver_fk=User::currentUser()->id;
				$rideRetour->arrivaltown_fk=$ride->departuretown_fk;
				$rideRetour->departuretown_fk=$ride->arrivaltown_fk;
				$rideRetour->seats=$ride->seats;

				$rideRetour->startDate=$ride->startDate;
				$rideRetour->endDate=$ride->endDate;
				$rideRetour->day=$ride->day;

				$rideValid=$ride->validate();
				$rideRetourValid=$rideRetour->validate();
				
				if($rideValid&&$rideRetourValid)
				{
					$ride->save();
					//Récupère l'id du ride allé et le rajoute dans le bindedride du ride retour
					$rideRetour->bindedride=$ride->id;
					$rideRetour->save();
					//Récupère l'id du ride retour et le rajoute dans le bindedride du ride allé
					$ride->bindedride=$rideRetour->id;
					$ride->update();
					//redirection accueil
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			else
			{
				if($ride->validate())
				{
					$ride->save();
					//redirection accueil
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		}

		$this->render('create',array(
			'ride'=>$ride,
			'rideretour'=>$rideRetour,
			'user'=>$user,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$ride=$this->loadModel($id);
		$user=User::model()->currentUser();
		if(isset($ride->bindedride))
		{
			$rideRetour=$this->loadModel($ride->bindedride);
		}
		else
		{
			$rideRetour = new Ride;
		}

		if($ride->driver->id!=User::model()->currentUser()->id || $ride->visibility==0)
		{

			$this->redirect(array('site/index'));
		}

		if(isset($_POST['Ride']))
		{
			$ride->attributes=$_POST['Ride'];
			$ride->startDate=date("Y-m-d",strtotime($ride->startDate));
			$ride->endDate=date("Y-m-d",strtotime($ride->endDate));
			
			
			$ride->driver_fk=User::currentUser()->id;
			
			$ride->day=date('N', strtotime($ride->startDate));
			$today = date('Y-m-d 00:00:00', time());
			$registrations=Registration::model()->findAll('ride_fk=:ride_fk AND endDate>=:today', array(':ride_fk'=>$id, ':today'=>$today));
			
			//si retour
			if(isset($_POST['retour'])&&$_POST['retour']=='oui')
			{
				$rideRetour->attributes=$_POST['Ride_retour'];
				$rideRetour->driver_fk=User::currentUser()->id;
				$rideRetour->arrivaltown_fk=$ride->departuretown_fk;
				$rideRetour->departuretown_fk=$ride->arrivaltown_fk;
				$rideRetour->seats=$ride->seats;

				$rideRetour->startDate=$ride->startDate;
				$rideRetour->endDate=$ride->endDate;
				$rideRetour->day=$ride->day;

				$rideValid=$ride->validate();
				$rideRetourValid=$rideRetour->validate();

				if($rideValid&&$rideRetourValid)
				{
					$registrationsRetour=Registration::model()->findAll('ride_fk=:ride_fk AND endDate>=:today', array(':ride_fk'=>$rideRetour->bindedride, ':today'=>$today));
					$ride->save();
					//Récupère l'id du ride allé et le rajoute dans le bindedride du ride retour
					$rideRetour->save();
					//Récupère l'id du ride retour et le rajoute dans le bindedride du ride aller

					foreach($registrations as $registration)
					{
						if($registration->userFk->notifModification==1)
						{
							$subject = "Covoiturage CPNV - Un de vos trajets a été modifié";
							$mail = new YiiMailer('modification', array(
								'registration' => $registration,	
							));
							$registration->userFk->sendEmail($mail,$subject);
						}
					}
					foreach($registrationsRetour as $registration)
					{
						if($registration->userFk->notifModification==1)
						{
							$subject = "Covoiturage CPNV - Un de vos trajets a été modifié";
							$mail = new YiiMailer('modification', array(
								'registration' => $registration,	
							));
							$registration->userFk->sendEmail($mail,$subject);
						}
					}
					$this->redirect(array('view','id'=>$ride->id));
				}
			}
			//si pas de retour
			else
			{

				if($ride->validate())
				{
					$ride->save();
					
					//Notification
					foreach($registrations as $registration)
					{
						if($registration->userFk->notifModification==1)
						{
							$subject = "Covoiturage CPNV - Un de vos trajets a été modifié";
							$mail = new YiiMailer('modification', array(
								'registration' => $registration,	
							));
							$registration->userFk->sendEmail($mail,$subject);
						}
					}
					//redirection accueil
					$this->redirect(array('view','id'=>$ride->id));
				}
			}
		}



		

		$this->render('update',array(
			'ride'=>$ride,
			'user'=>$user,
			'rideretour'=>$rideRetour,
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