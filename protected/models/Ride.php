<?php

/**
 * This is the model class for table "rides".
 *
 * The followings are the available columns in table 'rides':
 * @property integer $id
 * @property integer $driver_fk
 * @property integer $departuretown_fk
 * @property integer $arrivaltown_fk
 * @property integer $bindedride
 * @property string $description
 * @property string $departure
 * @property string $arrival
 * @property integer $seats
 * @property string $startDate
 * @property string $endDate
 * @property integer $day
 *
 * The followings are the available model relations:
 * @property Comments[] $comments
 * @property Registrations[] $registrations
 * @property Ridebadges[] $ridebadges
 * @property Towns $departuretownFk
 * @property Towns $arrivaltownFk
 * @property Ride $bindedride0
 * @property Ride[] $rides
 * @property Users $driverFk
 */
class Ride extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rides';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('driver_fk, departuretown_fk, arrivaltown_fk, seats', 'required'),
			array('driver_fk, departuretown_fk, arrivaltown_fk, bindedride, seats, day', 'numerical', 'integerOnly'=>true),
			array('description, departure, arrival, startDate, endDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, driver_fk, departuretown_fk, arrivaltown_fk, bindedride, description, departure, arrival, seats, startDate, endDate, day', 'safe', 'on'=>'search'),
		
			array('departure, arrival', 'required', 'message'=>'Les heures ne sont pas valides'),
			array('startDate, endDate', 'required', 'message'=>'Vous devez choisir une date'),
			array('endDate', 'endDateValidation'),
			array('startDate', 'startDateValidation'),
			array('arrival', 'timeformat'),
			array('departure', 'timeformat'),
			array('arrival', 'timeValidation')
		);
	}
	public function timeValidation($attribute)
	{
		if(strtotime($this->arrival)<strtotime($this->departure))
		{
			 $this->addError($attribute, 'L\'heure de la fin du trajet doit être plus grande que l\'heure de départ');
		}
	}
	public function endDateValidation($attribute)
	{
	     if(strtotime($this->endDate)<strtotime($this->startDate))
	     {
	     	 $this->addError($attribute, 'La date de fin du trajet n\'est pas valide');
	     }
	     if(date('N',strtotime($this->endDate))!=date('N',strtotime($this->startDate)))
	     {
	     	 $this->addError($attribute, 'Les jours de la semaine des deux dates ne correspondent pas');
	     }
	     
	}
	public function startDateValidation($attribute)
	{
		$date = date("d.m.Y");
		if(strtotime($this->startDate)<strtotime($date))
		{
			 $this->addError($attribute, 'La date du trajet ne doit pas être située dans le passé');
		}
	}
	public function timeformat($attribute)
	{
	    $pattern = '/^(([0-1]){1,}([0-9]{1,})|(2[0-3]))(:)([0-5]{1}[0-9]{1})$/';
 
	    if(!preg_match($pattern, $this->$attribute))
	    {
	      $this->addError($attribute, 'Le format des heures doit être le suivant : hh:mm');
	     }
	}



	protected function afterFind ()
    {
            // convert to display format
        $this->departure = strtotime ($this->departure);
        $this->departure = date ('H:i', $this->departure);
        $this->arrival = strtotime ($this->arrival);
        $this->arrival = date ('H:i', $this->arrival);

        parent::afterFind ();
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'comments' => array(self::HAS_MANY, 'Comment', 'ride_fk'),
			'registrations' => array(self::HAS_MANY, 'Registration', 'ride_fk'),
			'ridebadges' => array(self::HAS_MANY, 'Ridebadge', 'ride_fk'),
			'departuretown' => array(self::BELONGS_TO, 'Town', 'departuretown_fk'),
			'arrivaltown' => array(self::BELONGS_TO, 'Town', 'arrivaltown_fk'),
			'trajetretour' => array(self::BELONGS_TO, 'Ride', 'bindedride'),
			'rides' => array(self::HAS_ONE, 'Ride', 'bindedride'),
			'driver' => array(self::BELONGS_TO, 'User', 'driver_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'driver_fk' => 'Driver Fk',
			'departuretown_fk' => 'Departuretown Fk',
			'arrivaltown_fk' => 'Arrivaltown Fk',
			'bindedride' => 'Bindedride',
			'description' => 'Description',
			'departure' => 'Departure',
			'arrival' => 'Arrival',
			'seats' => 'Seats',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'day' => 'Day',
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
		$criteria->compare('driver_fk',$this->driver_fk);
		$criteria->compare('departuretown_fk',$this->departuretown_fk);
		$criteria->compare('arrivaltown_fk',$this->arrivaltown_fk);
		$criteria->compare('bindedride',$this->bindedride);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('departure',$this->departure,true);
		$criteria->compare('arrival',$this->arrival,true);
		$criteria->compare('seats',$this->seats);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('day',$this->day);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ride the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function showDuringHolidays($date)
	{
		if (strtotime($this->endDate)-strtotime($this->startDate)>0 && Holiday::model()->isHoliday($date)) {
			return false;
		}
		return true;
	}
}
