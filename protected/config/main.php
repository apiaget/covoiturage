<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Covoiturage',
	'aliases'=>array(
		'vendor' => realpath(__DIR__ . '/../../vendor'),
	),
	'sourceLanguage'=>'fr_fr',
	'language' => 'fr',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		//preloading yiimailer
		'ext.YiiMailer.YiiMailer',
		'ext.PhpActiveResource.ActiveResource'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'covoiturage',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'caseSensitive'=>false,
			'rules'=>array(

				array('Api_<controller>/connexion', 'pattern'=>'api/<controller:\w+>/connexion', 'verb'=>'POST'), //deconnexion
				array('Api_<controller>/deconnexion', 'pattern'=>'api/<controller:\w+>/deconnexion', 'verb'=>'POST'), //deconnexion

				// requête GET /api/rides/1   "passe" par le fichier       protected/controllers/api_RidesController.php
				// 														OU protected/controllers/api/RidesController.php
				array('Api_<controller>/update', 'pattern'=>'api/rides/<id:\d+>/<controller:\w+>', 'verb'=>'PUT'),
				array('Api_<controller>/view', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'GET'),
				array('Api_<controller>/list', 'pattern'=>'api/<controller:\w+>', 'verb'=>'GET'),
				array('Api_<controller>/update', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'PUT'),
				array('Api_<controller>/delete', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'DELETE'),
				array('Api_<controller>/create', 'pattern'=>'api/<controller:\w+>', 'verb'=>'POST'),


				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			)
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=covoiturag_db',
			'emulatePrepare' => true,
			'username' => 'username',
			'password' => 'motdepasse',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	'params'=>array(
		'adminEmail'=>'webmaster@covoiturage.ch',
		'rideListNumber'=>100, //nombre de ride à charger lors du listing de ceux-ci à travers l'api
		'townsListNumber'=>10, //nombre de ride à charger lors du listing de ceux-ci à travers l'api
		'IDAPP'=>"***",         //app id facebook
		'SECRETAPP'=>"***",     //secret key facebook
        'FACEBOOKPAGE'=>"***",  //page id facebook
	),
);