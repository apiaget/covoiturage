<?php

class ApiController extends Controller
{
    /**
    * @return array action filters
    */
    public function filters()
    {
        return array();
    }

    // Actions
    public function actionList()
    {
    }
    public function actionView()
    {
        $this->_checkAuth();
        $token = $_GET['token'];

        if($_GET['model']=='users') {
            $requestedUser = User::model()->find('id=:id', array(':id' => $_GET['id']));

            header('Content-type: ' . 'application/json');
            if ($requestedUser['token'] == $token) { //l'utilisateur demande ses propres réglages

                echo CJSON::encode(array('firstname' => $requestedUser->firstname,
                    'lastname' => $requestedUser->lastname,
                    'email' => $requestedUser->email,
                    'phone' => $requestedUser->telephone,
                    'privacy' => array(
                        'hideEmail' => $requestedUser->hideEmail,
                        'hidePhone' => $requestedUser->hideTelephone
                    ),
                    'notifications' => array(
                        'notifComment' => $requestedUser->notifComment,
                        'notifDeleteRide' => $requestedUser->notifDeleteRide,
                        'notifRegistration' => $requestedUser->notifInscription,
                        'notifChange' => $requestedUser->notifModification,
                        'notifUnsubscribe' => $requestedUser->notifUnsuscribe,
                    )
                ));
                Yii::app()->end();

            } else { //Un utilisateur demande les infos d'un autre utilisateur
                $returnUserArray = array();
                $returnUserArray['lastname'] = $requestedUser->lastname;
                $returnUserArray['firstname'] = $requestedUser->firstname;
                if ($requestedUser->hideEmail != 1) {
                    $returnUserArray['email'] = $requestedUser->email;
                }
                if ($requestedUser->hideTelephone != 1) {
                    $returnUserArray['phone'] = $requestedUser->telephone;
                }
                echo CJSON::encode($returnUserArray);
                Yii::app()->end();
            }
        }

    }
    public function actionCreate()
    {
        $this->_checkAuth();
        $token = $_GET['token'];

        if($_GET['model']=='rides') {
            // TODO effectuer une validation à l'aide d'un regex !!!
            $userRequest = User::model()->find('token=:token', array(':token' => $token));
            $ride = new Ride();
            $ride->driver_fk = $userRequest->id;
            $data = CJSON::decode(file_get_contents('php://input'));
            $ride->departuretown_fk = isset($data['departuretown']['id']) ? $data['departuretown']['id'] : 1;
            $ride->departure = isset($data['departure']) ? "0000-00-00 ".$data['departure'] : "";
            $ride->arrivaltown_fk = isset($data['arrivaltown']['id']) ? $data['arrivaltown']['id'] : 1;
            $ride->arrival = isset($data['arrival']) ? "0000-00-00 ".$data['arrival'] : "";
            $ride->startDate = isset($data['startdate']) ? $data['startdate'] : "";
            $ride->endDate = isset($data['enddate']) ? $data['enddate'] : "";
            $ride->description = isset($data['description']) ? $data['description'] : "";
            $ride->seats = isset($data['seats']) ? $data['seats'] : 0;
            $ride->monday =  isset($data['recurrence']['monday']) ? $data['recurrence']['monday'] : 0;
            $ride->tuesday =  isset($data['recurrence']['tuesday']) ? $data['recurrence']['tuesday'] : 0;
            $ride->wednesday =  isset($data['recurrence']['wednesday']) ? $data['recurrence']['wednesday'] : 0;
            $ride->thursday =  isset($data['recurrence']['thursday']) ? $data['recurrence']['thursday'] : 0;
            $ride->friday =  isset($data['recurrence']['friday']) ? $data['recurrence']['friday'] : 0;
            $ride->saturday =  isset($data['recurrence']['saturday']) ? $data['recurrence']['saturday'] : 0;
            $ride->sunday =  isset($data['recurrence']['sunday']) ? $data['recurrence']['sunday'] : 0;
            $ride->visibility =  isset($data['visibility']) ? $data['visibility'] : 1;
            $ride->save();
            header('HTTP/1.1 201');
            Yii::app()->end();
        }
    }
    public function actionUpdate()
    {
        $this->_checkAuth();
        $token = $_GET['token'];

        if($_GET['model']=='users') {
            $userToUpdate = User::model()->find('id=:id', array(':id' => $_GET['id']));
            $userRequest = User::model()->find('token=:token', array(':token' => $token));

            if(isset($userRequest) && $userToUpdate->id==$userRequest->id) { //on s'assure que l'utilisateur déclanchant la requête (identifié par le token soit le même que l'utilisateur à mettre à jour)
                $data = CJSON::decode(file_get_contents('php://input'));
                //on ne peut pas changer ni le nom, ni le prénom
                $userToUpdate->email = isset($data['email']) ? $data['email'] : $userToUpdate->email;
                $userToUpdate->telephone = isset($data['phone']) ? $data['phone'] : $userToUpdate->telephone;
                $userToUpdate->hideEmail = isset($data['privacy']['hideEmail']) ? $data['privacy']['hideEmail'] : $userToUpdate->hideEmail;
                $userToUpdate->hideTelephone = isset($data['privacy']['hidePhone']) ? $data['privacy']['hidePhone'] : $userToUpdate->hideTelephone;
                $userToUpdate->notifComment = isset($data['notifications']['notifComment']) ? $data['notifications']['notifComment'] : $userToUpdate->notifComment;
                $userToUpdate->notifDeleteRide = isset($data['notifications']['notifDeleteRide']) ? $data['notifications']['notifDeleteRide'] : $userToUpdate->notifDeleteRide;
                $userToUpdate->notifInscription = isset($data['notifications']['notifRegistration']) ? $data['notifications']['notifRegistration'] : $userToUpdate->notifInscription;
                $userToUpdate->notifModification = isset($data['notifications']['notifChange']) ? $data['notifications']['notifChange'] : $userToUpdate->notifModification;
                $userToUpdate->notifUnsuscribe = isset($data['notifications']['notifUnsubscribe']) ? $data['notifications']['notifUnsubscribe'] : $userToUpdate->notifUnsuscribe;

                $userToUpdate->update();
            }else{
                throw new CHttpException(403,'You have no rights to update that user.');
            }
        }
    }
    public function actionDelete()
    {
    }


    /**
     * Check if the user of the request is well authenticated. It means that the token sent with the request should correspond to a user and that this token sould still be valid.
     * A token is valid for 1 month after the last request made.
     * @throws CHttpException when the token doesn't correspond to a user or if the user's token is outdated (token lasts 1 month after last connection)
     */
    private function _checkAuth()
    {
        $token = $_GET['token'];
        $now = date('Y-m-d H:i:s', time()); //temps maintenant
        if($token == ''){
            //Doit s'arrêter car aucun token n'est fourni
            throw new CHttpException(401,'You are not authenticated.');
        }

        $requestUser = User::model()->find('token=:token and validbefore>:validtime', array(':token'=>$token, 'validtime'=>$now));

        if($requestUser == null)
        {
            //Doit demander une authentification du user (car le token n'est plus valide)
            throw new CHttpException(401,'You are not authenticated.');
        }else{
            //met à jour la validité du token
            $requestUser->validbefore = date("Y-m-d H:i:s",strtotime("+1 month", strtotime($now)));
            $requestUser->save();
        }
    }
}

?>