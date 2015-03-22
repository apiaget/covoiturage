<?php

class Api_TownsController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    protected function beforeAction($event)
    {
        $auth = new AuthenticationCheck;
        $auth->checkAuth();
        return true;
    }



    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('list'),
                'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' action
                'actions'=>array('admin'),
                'users'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }


    public function actionList(){
        $token = $_GET['token'];
        header('Content-type: ' . 'application/json');

        if (isset($_GET['q']) && $_GET['q'] != '') { //voit que les villes qui contiennent le texte de la requête
            $towns = Town::model()->findAll(array('condition' => 'name like :query', 'limit' => Yii::app()->params['townsListNumber'], 'params' => array(':query'=>'%'.$_GET['q'].'%')));
            $townsArray = array();
            foreach($towns as $t){
                array_push($townsArray, array("id" => $t->id, "name"  => $t->name));
            }
            echo CJSON::encode($townsArray);
        }
        Yii::app()->end();
    }
}