<?php
$config = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Базовый сервис',
	'preload'=>array('log','bootstrap'),
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
        'language'=>'ru',
	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'111',
			'ipFilters'=>array('127.0.0.1','::1'),
		),
                'admin'=>array(
                    'preload' => array('bootstrap'),
                ),
	),

	'components'=>array(
                'robokassa' => array(
                    'class' => 'application.components.robokassa.Robokassa',
                    'sMerchantLogin' => 'putevye-listy',
                    'sMerchantPass1' => 'ghjGoi5d',
                    'sMerchantPass2' => '9785kjrD',
                    'sCulture' => 'ru',
                    'sIncCurrLabel' => '',
                    'orderModel' => 'Order',
                    'priceField' => 'amount',
                    'isTest' => true,
                ),
		'user'=>array(
                    'allowAutoLogin'=>true,
                    'loginUrl' => '/site/login',
		),
                'mail' => array(
                    'class' => 'ext.yii-mail.YiiMail',
                    'transportType' => 'smtp',
                    'transportOptions' => array(
                        'host' => 'smtp.yandex.ru',
                        'username' => '',
                        'password' => '',
                    ),
                    'logging' => true,
                ),
                    'MailSender' => array(
                    'class'=>'application.components.MailSender'
                ),
		'bootstrap'=>array(
                    'class'=>'ext.bootstrap.components.Bootstrap',
                ),
		'authManager'=>array(
                    'class'=>'CDbAuthManager',
                    'connectionID'=>'db'
                ),
		'urlManager'=>array(
			 'urlFormat'=>'path',
                            'showScriptName'=>false,
                            'rules'=>array(
                                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                            ),
		),
//		'errorHandler'=>array(
//			// use 'site/error' action to display errors
//			'errorAction'=>'site/error',
//		),
//		'log'=>array(
//			'class'=>'CLogRouter',
//			'routes'=>array(
//				array(
//					'class'=>'CFileLogRoute',
//					'levels'=>'error, warning',
//				),
//				// uncomment the following to show log messages on web pages
//				/*
//				array(
//					'class'=>'CWebLogRoute',
//				),
//				*/
//			),
//		),
	),
	// using Yii::app()->params['paramName']
	'params'=>array(
		'adminEmail'=>'webmaster@example.com',
	),
);
$path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'author' . DIRECTORY_SEPARATOR;
$dir = dir($path);
while (($entry = $dir->read()) !== false) 
{
    if (is_file($path . $entry) && $entry[0] === '.' && $entry != '.gitignore') 
    {
        $YII_ENVIRONMENT = substr($entry, 1);
        break;
    }
}
unset($dir);

if (isset($YII_ENVIRONMENT) && file_exists(__DIR__ . '/.' . $YII_ENVIRONMENT . '.php')) 
{
    $custom = include(__DIR__ . '/.' . $YII_ENVIRONMENT . '.php');
    $config = CMap::mergeArray($config, $custom);
}
return $config;