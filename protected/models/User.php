<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $cpnvId
 * @property string $email
 * @property integer $hideEmail
 * @property string $telephone
 * @property integer $hideTelephone
 * @property integer $notifInscription
 * @property integer $notifComment
 * @property integer $notifUnsuscribe
 * @property integer $notifDeleteRide
 * @property integer $notifModification
 * @property integer $blacklisted
 * @property integer $admin
 *
 * The followings are the available model relations:
 * @property Comments[] $comments
 * @property Registrations[] $registrations
 * @property Rides[] $rides
 * @property Votes[] $votes
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hideEmail, hideTelephone, notifInscription, notifComment, notifUnsuscribe, notifDeleteRide, notifModification, blacklisted, admin', 'numerical', 'integerOnly'=>true),
			array('cpnvId, telephone', 'length', 'max'=>45),
			array('email', 'required', 'message'=>'L\'adresse email fournie ne semble pas être valide'),
			array('email', 'length', 'max'=>60),
			array('email', 'email', 'message'=>'L\'adresse email fournie ne semble pas être valide'),
			array('telephone', 'required'),
			array('telephone', 'telephoneStrength'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cpnvId, email, hideEmail, telephone, hideTelephone, notifInscription, notifComment, notifUnsuscribe, notifDeleteRide, notifModification, blacklisted, admin', 'safe', 'on'=>'search'),
		);
	}




	/**
	 * check if the user password is strong enough
	 * check the password against the pattern requested
	 * by the strength parameter
	 * This is the 'passwordStrength' validator as declared in rules().
	 */
	public function telephoneStrength($attribute,$params)
	{
	    $pattern = '/^(0{1})([1-9]{2})(\s|-|.{0,1})(\d{3})(\s|-|.{0,1})(\d{2})(\s|-|.{0,1})(\d{2})$/';  
	    if(!preg_match($pattern, $this->$attribute))
	      $this->addError($attribute, 'Le numéro de téléphone ne semble pas valide');
	}




	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'comments' => array(self::HAS_MANY, 'Comments', 'author_fk'),
			'registrations' => array(self::HAS_MANY, 'Registrations', 'user_fk'),
			'rides' => array(self::HAS_MANY, 'Rides', 'driver_fk'),
			'votes' => array(self::HAS_MANY, 'Votes', 'targetuser_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cpnvId' => 'Cpnv',
			'email' => 'Email',
			'hideEmail' => 'Hide Email',
			'telephone' => 'Téléphone',
			'hideTelephone' => 'Hide Telephone',
			'notifInscription' => 'Notif Inscription',
			'notifComment' => 'Notif Comment',
			'notifUnsuscribe' => 'Notif Unsuscribe',
			'notifDeleteRide' => 'Notif Delete Ride',
			'notifModification' => 'Notif Modification',
			'blacklisted' => 'Blacklisted',
			'admin' => 'Admin',
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
		$criteria->compare('cpnvId',$this->cpnvId,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('hideEmail',$this->hideEmail);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('hideTelephone',$this->hideTelephone);
		$criteria->compare('notifInscription',$this->notifInscription);
		$criteria->compare('notifComment',$this->notifComment);
		$criteria->compare('notifUnsuscribe',$this->notifUnsuscribe);
		$criteria->compare('notifDeleteRide',$this->notifDeleteRide);
		$criteria->compare('notifModification',$this->notifModification);
		$criteria->compare('blacklisted',$this->blacklisted);
		$criteria->compare('admin',$this->admin);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
