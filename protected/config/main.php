<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Covoiturage',
	'sourceLanguage'=>'fr_fr',
	'language' => 'fr',
	
	//preloading yiimailer
	//'ext.YiiMailer.YiiMailer',
	
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
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'covoiturage',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
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
			'rules'=>array(
				/*'<var:(login|logout|contact|home)>'=>'site/<var>',*/
				//array('api/update', 'pattern'=>'api/rides/<id:\d+>/registrations', 'verb'=>'PUT'),
				//array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
				//array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
				//array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
				//array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
				//array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
				//array('api/<controller:\w+>'=>'api_<controller>/index'),


				// requête GET /api/rides/1   "passe" par le fichier       protected/controllers/api_RidesController.php
				// 														OU protected/controllers/api/RidesController.php
				array('api_<controller>/update', 'pattern'=>'api/rides/<id:\d+>/<controller:\w+>', 'verb'=>'PUT'),

				array('api_<controller>/view', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'GET'), //Fonctionne
				array('api_<controller>/list', 'pattern'=>'api/<controller:\w+>', 'verb'=>'GET'),
				array('api_<controller>/update', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'PUT'),
				array('api_<controller>/delete', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'DELETE'),
				array('api_<controller>/create', 'pattern'=>'api/<controller:\w+>', 'verb'=>'POST'),






				//array('api/<controller:\w+>/<id:\d+>'=>'Api/<controller>/view'),
				//array('api/<controller:\w+>/<action:\w+>/<id:\d+>'=>'Api/<controller>/<action>'),
				//array('api/<controller:\w+>/<action:\w+>'=>'Api/<controller>/<action>'),

				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
			'showScriptName'=>false,
		),
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=covoiturage',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
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

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@covoiturage.ch',
		'mode'=>"maison", //maison = work without connection to intranet
						  //intranet = has access to intranet and can create automatically new users
		'ExecutionTime'=>'no', //yes = show execution time, no = don't show execution time
		'Votes'=>'no', //yes = show reputation values, no = don't show reputation
		'rideListNumber'=>100, //nombre de ride à charger lors du listing de ceux-ci à travers l'api
	),
	
);