<?php

/**
 * This is the model class for table "rides".
 *
 * The followings are the available columns in table 'rides':
 * @property integer $id
 * @property integer $users_id
 * @property integer $towns_id
 * @property integer $towns_id1
 * @property integer $rides_id
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
 * @property Rides $rides
 * @property Rides[] $rides1
 * @property Towns $towns
 * @property Towns $townsId1
 * @property Users $users
 */
class Rides extends CActiveRecord
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
			array('users_id, towns_id, towns_id1', 'required'),
			array('users_id, towns_id, towns_id1, rides_id, seats, day', 'numerical', 'integerOnly'=>true),
			array('description, departure, arrival, startDate, endDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, users_id, towns_id, towns_id1, rides_id, description, departure, arrival, seats, startDate, endDate, day', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comments', 'rides_id'),
			'registrations' => array(self::HAS_MANY, 'Registrations', 'rides_id'),
			'ridebadges' => array(self::HAS_MANY, 'Ridebadges', 'rides_idr'),
			'rides' => array(self::BELONGS_TO, 'Rides', 'rides_id'),
			'rides1' => array(self::HAS_MANY, 'Rides', 'rides_id'),
			'towns' => array(self::BELONGS_TO, 'Towns', 'towns_id'),
			'townsId1' => array(self::BELONGS_TO, 'Towns', 'towns_id1'),
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		//print_r(CHtml::listData(users::model()->findAll(), 'id', 'telephone'));
		return array(
			'id' => 'ID',
			'users_id' => 'Users',
			'towns_id' => 'Towns',
			'towns_id1' => 'Towns Id1',
			'rides_id' => 'Rides',
			'description' => 'Description',
			'departure' => 'Departure',
			'arrival' => 'Arrival',
			'seats' => 'Seats',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'day' => 'Day',
		);
		/*return array(
			'id' => 'ID',
			//'users_id' => current(CHtml::listData(users::model()->findAll(), 'users_id', 'telephone')),
			current(CHtml::listData(users::model()->findAll(), 'users_id', 'telephone')) => 'Users',

			'towns_id' => 'Towns',
			'towns_id1' => 'Towns Id1',
			'rides_id' => 'Rides',
			'description' => 'Description',
			'departure' => 'Departure',
			'arrival' => 'Arrival',
			'seats' => 'Seats',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'day' => 'Day',
		);*/
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
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('towns_id',$this->towns_id);
		$criteria->compare('towns_id1',$this->towns_id1);
		$criteria->compare('rides_id',$this->rides_id);
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
	 * @return Rides the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
