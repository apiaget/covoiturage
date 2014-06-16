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
 * @property integer $notifValidation
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
			array('hideEmail, hideTelephone, notifInscription, notifComment, notifUnsuscribe, notifDeleteRide, notifModification, notifValidation, blacklisted, admin', 'numerical', 'integerOnly'=>true),
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

	public function reputation(){
		$votes = Vote::model()->findAll('targetuser_fk=:userid', array(':userid'=>$this->id));
		if(count($votes)!=0){
			$total=0;
			foreach ($votes as $vote) {
				$total+=$vote->vote;
			}
			return array($total*20/count($votes), count($votes));
		}else{
			return array(0, 0);
		}
	}

	public function nom(){
		if(Yii::app()->params['mode']=="maison")
		{
			return $this->currentUser()->cpnvId;
		}
		$IU = new IntranetUser();
		$intranet_user = $IU->find($this->cpnvId);
		// WARNING: TODO: handle dropped users
		return $intranet_user->lastname;
	}
	public function prenom(){
		if(Yii::app()->params['mode']=="maison")
		{
			return $this->currentUser()->cpnvId;
		}
		$IU = new IntranetUser();
		$intranet_user = $IU->find($this->cpnvId);
		// WARNING: TODO: handle dropped users
		return $intranet_user->firstname;
	}

	public static function currentUser(){
		$user = User::model()->find('cpnvId=:cpnvId', array(':cpnvId'=>$_SERVER['HTTP_X_FORWARDED_USER']));
		if (!$user) {
			$IU =new IntranetUser();
			$intranet_user = $IU->find($_SERVER['HTTP_X_FORWARDED_USER']);
			if ($intranet_user === false) throw new CException('Unknown intranet user');
			$user = new User();
			$user->cpnvId = $intranet_user->friendly_id;
			$user->email = $intranet_user->corporate_email;
			$user->prenom=$intranet_user->firstname;
			$user->nom=$intranet_user->lastname;
			$user->hideEmail = 0;
			$user->hideTelephone = 0;
			$user->notifInscription = 1;
			$user->notifComment = 1;
			$user->notifUnsuscribe = 1;
			$user->notifDeleteRide = 1;
			$user->notifModification = 1;
			$user->notifValidation = 1;
			$user->blacklisted = 0;
			$user->admin = 0;
			$user->save(false);
		}
		return $user;
	}
	
	public function sendEmail($mail,$subject){

		$mail->IsSMTP();
		$mail->setFrom('info@covoiturage.ch', 'covoiturage.ch');
		$mail->setSubject($subject);
		$mail->setTo($this->email);
		$mail->Host='mail.cpnv.ch';
		$mail->send();
	}
}
