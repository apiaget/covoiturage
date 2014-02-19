<?php

/**
 * This is the model class for table "rides".
 *
 * The followings are the available columns in table 'rides':
 * @property integer $id
 * @property integer $driver
 * @property integer $departuretown
 * @property integer $arrivaltown
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
 * @property Towns $departuretown0
 * @property Towns $arrivaltown0
 * @property Ride $bindedride0
 * @property Ride[] $rides
 * @property Users $driver0
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
			array('driver, departuretown, arrivaltown', 'required'),
			array('driver, departuretown, arrivaltown, bindedride, seats, day', 'numerical', 'integerOnly'=>true),
			array('description, departure, arrival, startDate, endDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, driver, departuretown, arrivaltown, bindedride, description, departure, arrival, seats, startDate, endDate, day', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'comments' => array(self::HAS_MANY, 'Comments', 'ride'),
			'registrations' => array(self::HAS_MANY, 'Registrations', 'ride'),
			'ridebadges' => array(self::HAS_MANY, 'Ridebadges', 'ride'),
			'departuretown0' => array(self::BELONGS_TO, 'Town', 'departuretown'),
			'arrivaltown0' => array(self::BELONGS_TO, 'Town', 'arrivaltown'),
			'bindedride0' => array(self::BELONGS_TO, 'Ride', 'bindedride'),
			'rides' => array(self::HAS_MANY, 'Ride', 'bindedride'),
			'driver0' => array(self::BELONGS_TO, 'User', 'driver'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'driver' => 'Driver',
			'departuretown' => 'Departuretown',
			'arrivaltown' => 'Arrivaltown',
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
		$criteria->compare('driver',$this->driver);
		$criteria->compare('departuretown',$this->departuretown);
		$criteria->compare('arrivaltown',$this->arrivaltown);
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
}
