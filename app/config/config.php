<?php
$appID = 'barber_shop';
$db    = require 'db.php';
return [
    'id'         => $appID,
    'layout'     => 'page',
    'basePath'   => dirname(dirname(__DIR__)),
    'viewPath'   => dirname(dirname(__DIR__)) . '/app/views',
    'layoutPath' => dirname(dirname(__DIR__)) . '/app/layout',
    'modules'    => [
        'aa' => [
            'class' => '\app\modules\aa\Module'
        ]
    ],
    'services'   => [
        'db'           => $db,
        'errorHandler' => [
            'errorAction' => 'default/error',
        ],
        'urlManager'   => [
            'class' => '\core\web\UrlManager',
            'rules' => [
                ''       => 'home/index',
                'login'  => 'auth/login',
                'logout' => 'auth/logout',
            //''                                           => 'default/index',
            //'<controller>/<action>/<id:[0-9]+>'          => '<controller>/<action>',
            //'<module>/<controller>/<action>/<id:[0-9]+>' => '<module>/<controller>/<action>',
            //'<module>/<controller>/<action>'             => '<module>/<controller>/<action>',
            //'<controller>/<action>'                      => '<controller>/<action>',
            ]
        ],
        'session'      => [
            'name'         => $appID . '_phpsessid',
            'cookieParams' => [
                //'lifetime' => 0,
                //'path'     => '/',
                //'domain'   => '',
                //'secure'   => false,
                //'samesite' => 'Strict',
                'httponly' => true,
            ]
        ],
        'cookie'       => [
            'params' => [
                //'expires'  => 0,
                //'path'     => '/',
                //'domain'   => '',
                //'secure'   => false,
                //'samesite' => 'Strict'
                'httponly' => true,
            ]
        ],
        'user'         => [
            'identityClass' => 'app\models\User'
        ],
    ]
];
