<?php

class ApiController extends Controller
{
    // Members
    /**
    * Key which has to be in HTTP USERNAME and PASSWORD headers
    */
    Const APPLICATION_ID = 'ASCCPE';

    /**
    * Default response format
    * either 'json' or 'xml'
    */
    private $format = 'json';
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
    }
    public function actionUpdate()
    {
        $this->_checkAuth();
        $token = $_GET['token'];

        if($_GET['model']=='users') {
            $data = CJSON::decode(file_get_contents('php://input'));
            $userToUpdate = User::model()->find('token=:token', array(':token' => $token));
            //on ne peut pas changer ni le nom, ni le prénom
            $userToUpdate->firstname = isset($data['firstname']) ? $data['firstname'] : $userToUpdate->firstname;
            $userToUpdate->firstname = isset($data['firstname']) ? $data['firstname'] : $userToUpdate->firstname;
            $userToUpdate->firstname = isset($data['firstname']) ? $data['firstname'] : $userToUpdate->firstname;
            $userToUpdate->firstname = isset($data['firstname']) ? $data['firstname'] : $userToUpdate->firstname;
            $userToUpdate->firstname = isset($data['firstname']) ? $data['firstname'] : $userToUpdate->firstname;
            $userToUpdate->firstname = isset($data['firstname']) ? $data['firstname'] : $userToUpdate->firstname;
            $userToUpdate->firstname = isset($data['firstname']) ? $data['firstname'] : $userToUpdate->firstname;
            $userToUpdate->firstname = isset($data['firstname']) ? $data['firstname'] : $userToUpdate->firstname;

            $userToUpdate->save();

            //$model->lastname = $data['lastname'];
            //$model->email = $data['email'];
/*
            if (!$model->save()) {
                $errors = array();
                foreach ($model->getErrors() as $e) $errors = array_merge($errors, $e);
                $this->sendResponse(500, implode("<br />", $errors));
            }

            $this->sendResponse(200);*/
        }
    }
    public function actionDelete()
    {
    }

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