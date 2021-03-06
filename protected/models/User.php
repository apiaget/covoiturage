<?php
/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $cpnvId
 * @property string $email
 * @property integer $hideEmail
 * @property string $firstname
 * @property string $lastname
 * @property string $telephone
 * @property integer $hideTelephone
 * @property integer $notifInscription
 * @property integer $notifDeleteRide
 * @property integer $notifModification
 * @property integer $notifComment
 * @property integer $blacklisted
 * @property integer $admin
 * @property varchar $token
 * @property datetime $validbefore
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
			array('hideEmail, hideTelephone, notifInscription, notifComment, notifDeleteRide, notifModification, blacklisted, admin', 'numerical', 'integerOnly'=>true),
			array('cpnvId, telephone', 'length', 'max'=>45),
			array('email', 'required', 'message'=>'L\'adresse email fournie ne semble pas être valide'),
			array('email', 'length', 'max'=>60),
			array('email', 'email', 'message'=>'L\'adresse email fournie ne semble pas être valide'),
			array('telephone', 'telephoneStrength'),
			array('telephone', 'required'),
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
	public function attributeLabels()
	{

	/**
	 * @return array customized attribute labels (name=>label)
	 */
		return array(
			'id' => 'ID',
			'cpnvId' => 'Cpnv',
			'email' => 'Email',
			'hideEmail' => 'Hide Email',
			'telephone' => 'Téléphone',
			'hideTelephone' => 'Hide Telephone',
			'notifInscription' => 'Notif Inscription',
			'notifDeleteRide' => 'Notif Delete Ride',
            'notifModification' => 'Notif Modification',
            'notifComment' => 'Notif Comment',
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
        $criteria->compare('notifDeleteRide',$this->notifDeleteRide);
        $criteria->compare('notifModification',$this->notifModification);
        $criteria->compare('notifComment',$this->notifComment);
        $criteria->compare('blacklisted',$this->blacklisted);
		$criteria->compare('admin',$this->admin);
		$criteria->compare('token',$this->token);
		$criteria->compare('validbefore',$this->validbefore);

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
	
	public function sendEmail($mail,$subject){

		/*$mail->IsSMTP();
		$mail->setFrom('info@covoiturage.ch', 'covoiturage.ch');
		$mail->setSubject($subject);
		$mail->setTo($this->email);
		$mail->Host='mail.cpnv.ch';
		//$mail->send();*/

		$mail->IsSMTP();
		$mail->setFrom('info@covoiturage.ch', 'covoiturage.ch');
		$mail->setSubject($subject);
		$mail->setTo($this->email);
		$mail->Host='mail.cpnv.ch';
        $mail->send();

/*        $mail->IsSMTP();
        $mail->setFrom('email', 'nom');
        $mail->setTo($this->email);
        $mail->setSubject($subject);
        $mail->Host = "smtp";
        $mail->Port = 587; //ou 587
        $mail->SMTPAuth = true;
        $mail->Username = "username";
        $mail->Password = "password";
        //$mail->SMTPDebug = 1;
        $mail->SMTPSecure = 'tls';
        $mail->IsHTML(true);
        $mail->send();*/

	}
}
