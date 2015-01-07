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
        $this->_checkAuth();
        $token = $_GET['token'];

        if ($_GET['model']=='rides') {
            header('Content-type: ' . 'application/json');

            $today = date('Y-m-d 00:00:00', time());

            $rides = Ride::model()->with('departuretown')->with('arrivaltown')->findAll(array('condition'=>'enddate >= :today and visibility = 1', 'limit' => Yii::app()->params['rideListNumber'], 'params'=>array(':today'=>$today)));
            //TODO : liste de rides : ne voit pas ses propres trajets mais quand même les trajets auquel il est inscrit
            //       , 2 chargements, un pour la liste et un par ride cliqué
            //       tes trajets : voit tous les trajets qui le concerne.
            //$result = "";
            $array = array();
            foreach($rides as $ride){
                //$registrationsArray = array($ride->registrations);
                //usort($registrationsArray[0], function( $a, $b ) {
                //    return strtotime($a["date"]) - strtotime($b["date"]);
                //});


                $rideArray = array(
                    "id"=>$ride->id,
                    "departuretown" => array("id"=>$ride->departuretown->id,"name"=>$ride->departuretown->name),
                    "departure"=>date("H:i",strtotime($ride->departure)),
                    "arrivaltown" => array("id"=>$ride->arrivaltown->id,"name"=>$ride->arrivaltown->name),
                    "arrival"=>date("H:i",strtotime($ride->arrival)),
                    "startdate"=>$ride->startDate,
                    "enddate"=>$ride->endDate,
                    "description"=>$ride->description,
                    "seats"=>$ride->seats,
                    "isrecurrence"=>$ride->startDate!=$ride->endDate,
                    "recurrence" => array(
                        "monday" => $ride->monday,
                        "tuesday" => $ride->tuesday,
                        "wednesday" => $ride->wednesday,
                        "thursday" => $ride->thursday,
                        "friday" => $ride->friday,
                        "saturday" => $ride->saturday,
                        "sunday" => $ride->sunday
                    ),
                    //"registrations" => $registrationsArray
                );
                array_push($array, $rideArray);
            }

            echo CJSON::encode($array);

            //var_dump($rides);
            /*
             * {
                "departuretown": {"id": 1000, "name": "Sainte-Croix"},
                "departure": "17:00",
                "arrivaltown": {"id": 1001, "name": "Lausanne"},
                "arrival":"18:00",
                "startdate":"2015-03-31",
                "enddate":"2015-04-28",
                "description":"description de test",
                "seats": 3,
                "recurrence": {
                    "monday":"1",
                    "tuesday":"0",
                    "wednesday":"0",
                    "thursday":"1",
                    "friday":"0",
                    "saturday":"1",
                    "sunday":"0"
                }
            }
             * */
        }
        Yii::app()->end();

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
            // TODO effectuer une validation à l'aide d'un regex
            // TODO les valeurs par défaut sont probablement moisies
            $userRequest = User::model()->find('token=:token', array(':token' => $token));
            $ride = new Ride();
            $ride->driver_fk = $userRequest->id;
            $data = CJSON::decode(file_get_contents('php://input'));
            $ride->departuretown_fk = isset($data['departuretown']['id']) ? $data['departuretown']['id'] : 1;
            $ride->departure = isset($data['departure']) ? "1970-01-01 ".$data['departure'] : "";
            $ride->arrivaltown_fk = isset($data['arrivaltown']['id']) ? $data['arrivaltown']['id'] : 1;
            $ride->arrival = isset($data['arrival']) ? "1970-01-01 ".$data['arrival'] : "";
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

            if(isset($userRequest) && $userToUpdate->id == $userRequest->id) { //on s'assure que l'utilisateur déclanchant la requête (identifié par le token soit le même que l'utilisateur à mettre à jour)
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
        }else if($_GET['model']=='rides'){
            $userRequest = User::model()->find('token=:token', array(':token' => $token));
            $ride = Ride::model()->find('id=:id', array(':id' => $_GET['id']));
            if(isset($ride) && $ride->driver_fk == $userRequest->id){
                $data = CJSON::decode(file_get_contents('php://input'));
                $ride->departuretown_fk = isset($data['departuretown']['id']) ? $data['departuretown']['id'] : $ride->departuretown_fk;
                $ride->departure = isset($data['departure']) ? "1970-01-01 ".$data['departure'] : $ride->departure;
                $ride->arrivaltown_fk = isset($data['arrivaltown']['id']) ? $data['arrivaltown']['id'] : $ride->arrivaltown_fk;
                $ride->arrival = isset($data['arrival']) ? "1970-01-01 ".$data['arrival'] : $ride->arrival;
                $ride->startDate = isset($data['startdate']) ? $data['startdate'] : $ride->startDate;
                $ride->endDate = isset($data['enddate']) ? $data['enddate'] : $ride->endDate;
                $ride->description = isset($data['description']) ? $data['description'] : $ride->description;
                $ride->seats = isset($data['seats']) ? $data['seats'] : $ride->seats;
                $ride->monday =  isset($data['recurrence']['monday']) ? $data['recurrence']['monday'] : $ride->monday;
                $ride->tuesday =  isset($data['recurrence']['tuesday']) ? $data['recurrence']['tuesday'] : $ride->tuesday;
                $ride->wednesday =  isset($data['recurrence']['wednesday']) ? $data['recurrence']['wednesday'] : $ride->wednesday;
                $ride->thursday =  isset($data['recurrence']['thursday']) ? $data['recurrence']['thursday'] : $ride->thursday;
                $ride->friday =  isset($data['recurrence']['friday']) ? $data['recurrence']['friday'] : $ride->friday;
                $ride->saturday =  isset($data['recurrence']['saturday']) ? $data['recurrence']['saturday'] : $ride->saturday;
                $ride->sunday =  isset($data['recurrence']['sunday']) ? $data['recurrence']['sunday'] : $ride->sunday;
                $ride->visibility =  isset($data['visibility']) ? $data['visibility'] : $ride->visibility;
                $ride->save(); //Si on met update(), les données ne sont pas revalidées
                Yii::app()->end();
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