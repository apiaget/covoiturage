<?php

/**
 * This is the model class for table "registrations".
 *
 * The followings are the available columns in table 'registrations':
 * @property integer $id
 * @property integer $user_fk
 * @property integer $ride_fk
 * @property datetime $date
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
			array('user_fk, ride_fk', 'numerical', 'integerOnly'=>true),
			//array('startDate, endDate', 'safe'),
			//array('startDate', 'doublon'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_fk, ride_fk, date', 'safe', 'on'=>'search'),
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
}
