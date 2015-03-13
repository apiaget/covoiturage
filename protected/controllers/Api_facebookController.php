<?php

class Api_facebookController extends Controller
{

    protected function beforeAction($event)
    {
        $auth = new AuthenticationCheck;
        $auth->checkAuth();
        return true;
    }

    /**
     * Récupère les posts de la page CPNV covoiturage
     * @throws \Facebook\FacebookRequestException
     */
	public function actionList()
	{
		session_start();
		\Facebook\FacebookSession::setDefaultApplication(Yii::app()->params['IDAPP'], Yii::app()->params['SECRETAPP']);
        $session = new \Facebook\FacebookSession(Yii::app()->params['IDAPP']."|".Yii::app()->params['SECRETAPP']); //get the application's access token
        $request = new \Facebook\FacebookRequest(
            $session,
            'GET',
            '/'.Yii::app()->params['FACEBOOKPAGE'].'/feed' //Id de la page CPNV covoiturage
        );
        $response = $request->execute();
        $graphObject = $response->getGraphObject(\Facebook\GraphPage::className())->asArray();

        $result = array();
        foreach($graphObject['data'] as $post){
            $postArray = array();
            if("status"==$post->type){
                $postArray['text'] = $post->message;
                $postArray['date'] = $post->updated_time;
            }else if("photo"==$post->type){
                $postArray['text'] = $post->message;
                $postArray['date'] = $post->updated_time;

                //get the photo
                $request = new \Facebook\FacebookRequest(
                    $session,
                    'GET',
                    '/'.$post->object_id
                );
                $response = $request->execute();
                $graphPhoto = $response->getGraphObject(\Facebook\GraphObject::className())->asArray();

                $postArray['image'] = $graphPhoto['images'][2]->source;
            }
            array_push($result, $postArray);

        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }
}