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
		$cpnvId="Joël";
		$user=User::model()->find('cpnvId=:cpnvId', array(':cpnvId'=>$cpnvId));
		$today = date('Y-m-d 00:00:00', time());
		
		$registrations=Registration::model()->findAll('ride_fk=:ride_fk AND endDate>=:today', array(':ride_fk'=>$id, ':today'=>$today));

		
		
		//ne pas afficher les rides effacés
		$ride=$this->loadModel($id);
		if($ride->visibility==0)
		{
			$this->redirect(Yii::app()->user->returnUrl);
		}

		if(isset($_POST['modification'])){

			foreach($registrations as $registration)
			{
				if($registration->rideFk->id == $ride->id && $registration->userFk->notifModification==1)
				{
					$start=date("d-m-Y",strtotime($registration->rideFk->startDate));
					$end=date("d-m-Y",strtotime($registration->rideFk->endDate));
					$villeDepart=$registration->rideFk->departuretown->name;
					$villeArrivee=$registration->rideFk->arrivaltown->name;
					$startHour=date("H:i",strtotime($registration->rideFk->departure));
					$endHour=date("H:i",strtotime($registration->rideFk->arrival));
					$sujet="CPNV Covoiturage - Un de vos trajets a été modifié";
					$text="Le trajet du ".$start." ".$startHour." au ".$end." ".$endHour." ayant comme parcours  ".$villeDepart." - ".$villeArrivee." a été modifié.";
					$registration->userFk->sendEmail($sujet, $text);
				}
			}
		}


		if(isset($_POST['supprimer'])){
			//change la visibilité du ride
			$ride=$this->loadModel($id);
			$ride->visibility=0;
			$ride->save(false);

			foreach($registrations as $registration)
			{

				//$registraion->userFk->sendMail('suppression', ride);
				//if($registration->rideFk->id == $ride->id && $registration->userFk->notifDeleteRide==1)
				if($registration->userFk->notifDeleteRide==1)
				{
					//$registration->
					$start=date("d-m-Y",strtotime($registration->rideFk->startDate));
					$end=date("d-m-Y",strtotime($registration->rideFk->endDate));
					$villeDepart=$registration->rideFk->departuretown->name;
					$villeArrivee=$registration->rideFk->arrivaltown->name;
					$startHour=date("H:i",strtotime($registration->rideFk->departure));
					$endHour=date("H:i",strtotime($registration->rideFk->arrival));
					$sujet="CPNV Covoiturage - Un de vos trajets a été supprimé";
					$text="Le trajet du ".$start." ".$startHour." au ".$end." ".$endHour." ayant comme parcours  ".$villeDepart." - ".$villeArrivee." a été supprimé.";
					$registration->userFk->sendEmail($sujet, $text);
				}
			}
			//redirection sur la page d'accueil
			$this->redirect(Yii::app()->user->returnUrl);
		}
		if(isset($_POST['inscrireAllerRetour'])&&$_POST['dateDebut']!=""&&$_POST['dateFin']!="")
		{
			var_dump($_POST);
			die;
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
						$prenom=$reg->user_fk=User::model()->currentUser()->prenom();
						$nom=$reg->user_fk=User::model()->currentUser()->nom();
						$start=date("d-m-Y",strtotime($reg->startDate));
						$end=date("d-m-Y",strtotime($reg->endDate));
						$startretour=date("d-m-Y",strtotime($regRetour->startDate));
						$endretour=date("d-m-Y",strtotime($regRetour->endDate));
						$villeDepart=$reg->ride_fk=$this->loadModel($id)->departuretown->name;
						$villeArrivee=$reg->ride_fk=$this->loadModel($id)->arrivaltown->name;
						$startHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->departure));
						$endHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->arrival));
						$startHourRetour=date("H:i",strtotime($regRetour->ride_fk=$this->loadModel($id)->departure));
						$endHourRetour=date("H:i",strtotime($regRetour->ride_fk=$this->loadModel($id)->arrival));
						$sujet="CPNV Covoiturage - Un utilisateur s'est inscrit à un de vos trajets";
						$text="L'utilisateur ".$nom." ".$prenom." s'est inscrit à votre trajet du ".$start." à ".$startHour." au ".$end." à ".$endHour." ayant comme trajet ".$villeDepart." - ".$villeArrivee." et aussi à son retour 
						(du ".$startretour." au ".$endretour." de ".$endHourRetour." à ".$startHourRetour.")";
						$reg->ride_fk=$this->loadModel($id)->driver->sendEmail($sujet, $text);
					}
			}
			else
			{
				if($result)
				{
					if($reg->rideFk->driver->notifInscription==1)
					{
						$prenom=$reg->user_fk=User::model()->currentUser()->prenom();
						$nom=$reg->user_fk=User::model()->currentUser()->nom();
						$start=date("d-m-Y",strtotime($reg->startDate));
						$end=date("d-m-Y",strtotime($reg->endDate));
						$villeDepart=$reg->ride_fk=$this->loadModel($id)->departuretown->name;
						$villeArrivee=$reg->ride_fk=$this->loadModel($id)->arrivaltown->name;
						$startHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->departure));
						$endHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->arrival));
						$sujet="CPNV Covoiturage - Un utilisateur s'est inscrit à un de vos trajets";
						$text="L'utilisateur ".$nom." ".$prenom." s'est inscrit à votre trajet du ".$start." à
						".$startHour." au ".$end." à ".$endHour." ayant comme trajet ".$villeDepart." - ".$villeArrivee.".";
						$reg->ride_fk=$this->loadModel($id)->driver->sendEmail($sujet, $text);
					}
				}
				if($resultRetour)
				{
					if($reg->rideFk->driver->notifInscription==1)
					{
						$prenom=$regRetour->user_fk=User::model()->currentUser()->prenom();
						$nom=$regRetour->user_fk=User::model()->currentUser()->nom();
						$start=date("d-m-Y",strtotime($regRetour->startDate));
						$end=date("d-m-Y",strtotime($regRetour->endDate));
						$villeDepart=$regRetour->ride_fk=$this->loadModel($id)->departuretown->name;
						$villeArrivee=$regRetour->ride_fk=$this->loadModel($id)->arrivaltown->name;
						$startHour=date("H:i",strtotime($regRetour->ride_fk=$this->loadModel($id)->departure));
						$endHour=date("H:i",strtotime($regRetour->ride_fk=$this->loadModel($id)->arrival));
						$sujet="CPNV Covoiturage - Un utilisateur s'est inscrit à un de vos trajets";
						$text="L'utilisateur ".$nom." ".$prenom." s'est inscrit à votre trajet du ".$start." à ".$startHour." au ".$end." à ".$endHour." ayant comme trajet ".$villeDepart." - ".$villeArrivee.".";
						$regRetour->ride_fk=$this->loadModel($id)->driver->sendEmail($sujet, $text);
					}
				}
			}
			$this->redirect(Yii::app()->getRequest()->getUrlReferrer());
		}
		if(isset($_POST['inscrire'])&&$_POST['dateDebut']!=""&&$_POST['dateFin']!="") //&&$_POST['dateDebut']!="" && $_POST['dateDebut'] != ""
		{
			$reg = new Registration;
			$reg->user_fk=User::model()->currentUser()->id;
			$reg->ride_fk=$this->loadModel($id)->id;
			$reg->startDate=date("Y-m-d 00:00:00",strtotime($_POST['dateDebut']));
			$reg->endDate=date("Y-m-d 00:00:00",strtotime($_POST['dateFin']));
			$reg->accepted=0;
			//on rempli les paramètres de la registration
			

			/*if(isset($_POST['allerretour']))
			{
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
							$prenom=$reg->user_fk=User::model()->currentUser()->prenom();
							$nom=$reg->user_fk=User::model()->currentUser()->nom();
							$start=date("d-m-Y",strtotime($reg->startDate));
							$end=date("d-m-Y",strtotime($reg->endDate));
							$startretour=date("d-m-Y",strtotime($regRetour->startDate));
							$endretour=date("d-m-Y",strtotime($regRetour->endDate));
							$villeDepart=$reg->ride_fk=$this->loadModel($id)->departuretown->name;
							$villeArrivee=$reg->ride_fk=$this->loadModel($id)->arrivaltown->name;
							$startHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->departure));
							$endHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->arrival));
							$startHourRetour=date("H:i",strtotime($regRetour->ride_fk=$this->loadModel($id)->departure));
							$endHourRetour=date("H:i",strtotime($regRetour->ride_fk=$this->loadModel($id)->arrival));
							$sujet="CPNV Covoiturage - Un utilisateur s'est inscrit à un de vos trajets";
							$text="L'utilisateur ".$nom." ".$prenom." s'est inscrit à votre trajet du ".$start." à ".$startHour." au ".$end." à ".$endHour." ayant comme trajet ".$villeDepart." - ".$villeArrivee." et aussi à son retour 
							(du ".$startretour." au ".$endretour." de ".$endHourRetour." à ".$startHourRetour.")";
							$reg->ride_fk=$this->loadModel($id)->driver->sendEmail($sujet, $text);
						}
				}
				else
				{
					if($result)
					{
						if($reg->rideFk->driver->notifInscription==1)
						{
							$prenom=$reg->user_fk=User::model()->currentUser()->prenom();
							$nom=$reg->user_fk=User::model()->currentUser()->nom();
							$start=date("d-m-Y",strtotime($reg->startDate));
							$end=date("d-m-Y",strtotime($reg->endDate));
							$villeDepart=$reg->ride_fk=$this->loadModel($id)->departuretown->name;
							$villeArrivee=$reg->ride_fk=$this->loadModel($id)->arrivaltown->name;
							$startHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->departure));
							$endHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->arrival));
							$sujet="CPNV Covoiturage - Un utilisateur s'est inscrit à un de vos trajets";
							$text="L'utilisateur ".$nom." ".$prenom." s'est inscrit à votre trajet du ".$start." à
							".$startHour." au ".$end." à ".$endHour." ayant comme trajet ".$villeDepart." - ".$villeArrivee.".";
							$reg->ride_fk=$this->loadModel($id)->driver->sendEmail($sujet, $text);
						}
					}
					if($resultRetour)
					{
						if($reg->rideFk->driver->notifInscription==1)
						{
							$prenom=$regRetour->user_fk=User::model()->currentUser()->prenom();
							$nom=$regRetour->user_fk=User::model()->currentUser()->nom();
							$start=date("d-m-Y",strtotime($regRetour->startDate));
							$end=date("d-m-Y",strtotime($regRetour->endDate));
							$villeDepart=$regRetour->ride_fk=$this->loadModel($id)->departuretown->name;
							$villeArrivee=$regRetour->ride_fk=$this->loadModel($id)->arrivaltown->name;
							$startHour=date("H:i",strtotime($regRetour->ride_fk=$this->loadModel($id)->departure));
							$endHour=date("H:i",strtotime($regRetour->ride_fk=$this->loadModel($id)->arrival));
							$sujet="CPNV Covoiturage - Un utilisateur s'est inscrit à un de vos trajets";
							$text="L'utilisateur ".$nom." ".$prenom." s'est inscrit à votre trajet du ".$start." à ".$startHour." au ".$end." à ".$endHour." ayant comme trajet ".$villeDepart." - ".$villeArrivee.".";
							$regRetour->ride_fk=$this->loadModel($id)->driver->sendEmail($sujet, $text);
						}
					}
				}
				//on rempli les paramètre de la registration
			}else
			{*/
				$result = $reg->validate();

				//Notification
				if($result)
				{
						if($reg->rideFk->driver->notifInscription==1)
						{
							$prenom=$reg->user_fk=User::model()->currentUser()->prenom();
							$nom=$reg->user_fk=User::model()->currentUser()->nom();
							$start=date("d-m-Y",strtotime($reg->startDate));
							$end=date("d-m-Y",strtotime($reg->endDate));
							$villeDepart=$reg->ride_fk=$this->loadModel($id)->departuretown->name;
							$villeArrivee=$reg->ride_fk=$this->loadModel($id)->arrivaltown->name;
							$startHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->departure));
							$endHour=date("H:i",strtotime($reg->ride_fk=$this->loadModel($id)->arrival));
							$sujet="CPNV Covoiturage - Un utilisateur s'est inscrit à un de vos trajets";
							$text="L'utilisateur ".$nom." ".$prenom." s'est inscrit à votre trajet du ".$start." à ".$startHour." au ".$end." à ".$endHour." ayant comme trajet ".$villeDepart." - ".$villeArrivee.".";
							$reg->ride_fk=$this->loadModel($id)->driver->sendEmail($sujet, $text);
						}
				}

				$errors = $reg->getErrors(); //chope les erreurs retournées par le modèle de $reg (Registration)
				if(count($errors)!=0)
				{
					Yii::app()->user->setFlash('startDate', $errors['startDate'][0]);
				}
			//}
			$this->redirect(Yii::app()->getRequest()->getUrlReferrer());
		}
		/*else if(isset($_POST['dateB']) && $_POST['dateB']==""){
			Yii::app()->user->setFlash('error', "Date incorrecte !");
			$this->redirect(Yii::app()->getRequest()->getUrlReferrer());
		}*/
			if(isset($_POST['desinscrire'])){
				
				foreach($registrations as $registration){
					if($registration->userFk->id == User::model()->currentUser()->id)
					{
						$registration->delete();
						//$registration->save();

						//Notification
						if($registration->rideFk->driver->notifUnsuscribe==1)
						{
							$prenom=$registration->userFk->prenom();
							$nom=$registration->userFk->nom();
							$start=date("d-m-Y",strtotime($registration->rideFk->startDate));
							$end=date("d-m-Y",strtotime($registration->rideFk->endDate));
							$villeDepart=$registration->rideFk->departuretown->name;
							$villeArrivee=$registration->rideFk->arrivaltown->name;
							$startHour=date("H:i",strtotime($registration->rideFk->departure));
							$endHour=date("H:i",strtotime($registration->rideFk->arrival));
							$sujet="CPNV Covoiturage - Un utilisateur s'est désinscrit à un de vos trajets";
							$text="L'utilisateur ".$nom." ".$prenom." s'est désinscrit de votre trajet du ".$start." à ".$startHour." au ".$end." à ".$endHour." ayant comme trajet ".$villeDepart." - ".$villeArrivee.".";
							$registration->rideFk->driver->sendEmail($sujet, $text);
						}
					}
				}
				$this->redirect(Yii::app()->user->returnUrl);
			}

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
							$start=date("d-m-Y",strtotime($registration->rideFk->startDate));
							$end=date("d-m-Y",strtotime($registration->rideFk->endDate));
							$villeDepart=$registration->rideFk->departuretown->name;
							$villeArrivee=$registration->rideFk->arrivaltown->name;
							$startHour=date("H:i",strtotime($registration->rideFk->departure));
							$endHour=date("H:i",strtotime($registration->rideFk->arrival));
							$text="Votre inscription au ride du 
							".$start." à ".$startHour." au ".$end." à ".$endHour." ayant comme trajet ".$villeDepart." - ".$villeArrivee."
							 a été validée";
							$sujet="CPNV Covoiturage - Validation de votre inscription";
							$registration->userFk->sendEmail($sujet, $text);
							
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
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ride']))
		{
			//var_dump($_POST);
			$ride->attributes=$_POST['Ride'];
			$ride->startDate=date("Y-m-d",strtotime($ride->startDate));
			$ride->endDate=date("Y-m-d",strtotime($ride->endDate));
			$rideRetour->attributes=$_POST['Ride_retour'];
			$ride->driver_fk=User::currentUser()->id;
			
			$jour=date('N', strtotime($ride->startDate));
			$ride->day=$jour;

			//si retour
			if($_POST['retour']=='oui')
			{
				
				$rideRetour->driver_fk=User::currentUser()->id;
				$rideRetour->arrivaltown_fk=$ride->departuretown_fk;
				$rideRetour->departuretown_fk=$ride->arrivaltown_fk;
				$rideRetour->seats=$ride->seats;

				$rideRetour->startDate=$ride->startDate;
				$rideRetour->endDate=$ride->endDate;
				//die($ride->endDate);
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
					/*$ride->startDate=date("Y-m-d",strtotime($ride->startDate));
					$ride->endDate=date("Y-m-d",strtotime($ride->endDate));*/
					$ride->save();
					//redirection accueil
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			

			
			
			//if($ride->save())
				//$this->redirect(array('view','id'=>$ride->id));
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
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);


		if(isset($_POST['Ride']))
		{
			$ride->attributes=$_POST['Ride'];
			$ride->startDate=date("Y-m-d",strtotime($ride->startDate));
			$ride->endDate=date("Y-m-d",strtotime($ride->endDate));
			$rideRetour->attributes=$_POST['Ride_retour'];
			$ride->driver_fk=User::currentUser()->id;
			
			$jour=date('N', strtotime($ride->startDate));
			$ride->day=$jour;

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
					/*$ride->startDate=date("Y-m-d",strtotime($ride->startDate));
					$ride->endDate=date("Y-m-d",strtotime($ride->endDate));*/
					$ride->save();
					//redirection accueil
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			$this->redirect(array('view','id'=>$ride->id));
		}

		/*if(isset($_POST['Ride']))
		{
			$ride->attributes=$_POST['Ride'];
			if($ride->save())
				$this->redirect(array('view','id'=>$ride->id));
		}*/

		if($ride->driver->id!=User::model()->currentUser()->id)
		{
			//$this->redirect(Yii::app()->getRequest()->getUrlReferrer());
			$this->redirect(array('site/index'));
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