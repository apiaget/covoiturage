<?php

/**
 * This is the model class for table "registrations".
 *
 * The followings are the available columns in table 'registrations':
 * @property integer $id
 * @property integer $user_fk
 * @property integer $ride_fk
 * @property string $startDate
 * @property string $endDate
 * @property integer $accepted
 *
 * The followings are the available model relations:
 * @property Users $userFk
 * @property Rides $rideFk
 * @property Votes[] $votes
 */
class Registration extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'registrations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_fk, ride_fk', 'required'),
			array('user_fk, ride_fk, accepted', 'numerical', 'integerOnly'=>true),
			array('startDate, endDate', 'safe'),
			array('startDate', 'doublon'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_fk, ride_fk, startDate, endDate, accepted', 'safe', 'on'=>'search'),
		);
	}

	public function doublon($attribute,$params)
	{
		//this = la registration testée
		/* on veut s'arranger pour augmenter les registrations et les trucs du genre
		 * c'est ici qu'en enregistrera la registration
		 *
		 */
		//$this->addError($attribute, 'message');
		/*foreach($this->userFk->registrations as $reg){


			//on fait les saves ici
	    	if(!$reg->ok){
	    	  $this->addError($attribute, 'message');
	  		}
	  	}*/
		
		$registrations = Registration::model()->findAll('ride_fk = :ride AND user_fk = :user ORDER BY startDate ASC', array(':ride'=>$this->ride_fk, ':user' => User::currentUser()->id));
		
		//si l'utilisateur n'a pas encore fait de registration pour ce ride
		if(count($registrations)==0)
		{
			if($this->placeDispoRide())
			{
				$this->save(false);
			}
			else
			{
				$this->addError($attribute, 'Il n\'y a plus de place dans la voiture pour les dates sélectionnées.');
			}
		}
		else //si l'utilisateur a déjà au moins une registration sur ce ride
		{
			$noSave=0;
			//Modifier pour que l'utilisateur puisse agrandir ses registrations ou avoir plusieurs plages de réservations
			foreach ($registrations as $registration) {
				if(strtotime($this->endDate. ' + 7 days')>=strtotime($registration->startDate) && strtotime($this->startDate)<strtotime($registration->startDate))
				{
					if($this->placeDispoRide())
					{
						$registration->startDate=date("Y-m-d 00:00:00",strtotime($this->startDate));
						$registration->save(false);
					}else
					{
						$this->addError($attribute, 'Il n\'y a plus de place dans la voiture pour les dates sélectionnées.');
					}
					$noSave++;
				}
				else if(strtotime($this->startDate. ' - 7 days')<=strtotime($registration->endDate) && strtotime($this->endDate)>strtotime($registration->endDate))
				{
					if($this->placeDispoRide())
					{
						$registration->endDate=date("Y-m-d 00:00:00",strtotime($this->endDate));
						$registration->save(false);
					}else
					{
						$this->addError($attribute, 'Il n\'y a plus de place dans la voiture pour les dates sélectionnées.');
					}
					$noSave++;
				}else{
					$this->addError($attribute, 'Vous avez déjà une réservation englobant ces dates.');
					$noSave++;
				}
			}
			//reprendre les registrations avec la nouvelle registration créée
			$registrations = Registration::model()->findAll('ride_fk = :ride AND user_fk = :user ORDER BY startDate ASC', array(':ride'=>$this->ride_fk, ':user' => User::currentUser()->id));
			//Fusion des différentes registrations pour une personne
			for($i=0;$i<count($registrations)-1;$i++)
			{
				if(strtotime($registrations[$i]->endDate)>=strtotime($registrations[$i+1]->startDate)&&$noSave==0)
				{
					if($registrations[$i]->accepted==1||$registrations[$i+1]->accepted==1)
					{
						$registrations[$i]->accepted=1;
					}
					else{
						$registrations[$i]->accepted=0;
					}
					$registrations[$i]->endDate=$registrations[$i+1]->endDate;
					$registrations[$i]->save(false);
					$registrations[$i+1]->delete(false);
				}
			}

			if($noSave==0)
			{
				$this->save(false);
			}	
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'userFk' => array(self::BELONGS_TO, 'User', 'user_fk'),
			'rideFk' => array(self::BELONGS_TO, 'Ride', 'ride_fk'),
			'votes' => array(self::HAS_MANY, 'Votes', 'passenger_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_fk' => 'User Fk',
			'ride_fk' => 'Ride Fk',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'accepted' => 'Accepted',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_fk',$this->user_fk);
		$criteria->compare('ride_fk',$this->ride_fk);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('accepted',$this->accepted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Registration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/*
	*	PlaceDisponible? entre deux dates et pour un ride
	*	Test si y'a des places disponibles sur le ride auquel la registration s'applique
	*	return true si y'a de la place, false sinon
	*
	*/
	public function placeDispoRide()
	{
		$registrations = Registration::model()->findAll('ride_fk = :ride AND user_fk!=:user AND (
				(:dateDebut <= startDate AND :dateFin >= endDate)
				OR (:dateDebut <= endDate AND :dateDebut >= startDate)
				OR (:dateFin >= startDate AND :dateFin <= endDate)
				OR (:dateDebut >= startDate AND :dateFin <= endDate))',
		array(':ride'=>$this->ride_fk,':user' => User::model()->currentUser()->id ,':dateDebut' => $this->startDate, ':dateFin' => $this->endDate));

		$ride = Ride::model()->findByPk($this->ride_fk);

		$dateRemplissageTableau = $date=date('Y-m-d 00:00:00', strtotime($this->startDate));
		$seatsTaken = array();
		$i=0;
		do{ //prépare le tableau où sera indiqué le nombre de places utilisées par date
			$seatsTaken[$i]=0;
			$i++;
			$dateRemplissageTableau = date('Y-m-d 00:00:00', strtotime($dateRemplissageTableau. ' + 7 days'));
		}while(strtotime($dateRemplissageTableau)<=strtotime($this->endDate));

		$i=0;
		do{ //remplis le tableau
			foreach ($registrations as $registration) {
				if($registration->startDate <= $date && $registration->endDate >= $date && $registration->accepted==1)
				{
					$seatsTaken[$i]=$seatsTaken[$i]+1;
				}
			}
			$i++;
			$date = date('Y-m-d 00:00:00', strtotime($date. ' + 7 days'));
		}while(strtotime($date)<=strtotime($this->endDate));

		//si le tableau contient le nombre de place pour le ride, ça veut dire qu'il n'y a plus de places
		return !(in_array($ride->seats,$seatsTaken));
	}
}
