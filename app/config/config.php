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
        'i18n'         => [
            'translations' => [
                'app' => [
                    'class'    => 'core\i18n\PhpMessageSource',
                    'basePath' => '@app/app/translations'
                ]
            ]
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
        'errorHandler' => [
            'errorAction' => 'default/error',
        ],
        'request' => [
            'csrfParam' => $appID . '_csrf',
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
