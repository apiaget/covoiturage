<?php
    class AuthenticationCheck extends CApplicationComponent
    {
        public function checkAuth()
        {
            if(!isset($_GET['token'])){
                if(Yii::app()->urlManager->parseUrl(Yii::app()->request)=="Api_users/connexion"){
                    return true;
                }
                throw new CHttpException(401,'You are not authenticated.');
            }
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