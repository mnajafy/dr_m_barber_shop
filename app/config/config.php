<?php
$db    = require 'db.php';
$rules = require 'rules.php';
return [
    'id'           => 'barber_shop',
    'db'           => $db,
    'layout'       => 'page',
    'basePath'     => dirname(dirname(__DIR__)),
    'viewPath'     => dirname(dirname(__DIR__)) . '/app/views',
    'layoutPath'   => dirname(dirname(__DIR__)) . '/app/layout',
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
    'session'      => [
        'name'         => 'barber_shop_session_id',
        'cookieParams' => [
            //'lifetime' => 0,
            //'path'     => '/',
            //'domain'   => '',
            //'secure'   => false,
            //'samesite' => 'Strict',
            'httponly' => true,
        ]
    ],
    'errorHandler' => [
        'errorAction' => 'default/error',
    ],
    'urlManager'   => [
        'class' => '\core\web\UrlManager',
        'rules' => $rules
    ],
    'modules'      => [
        'aa' => [
            'class' => '\app\modules\aa\Module'
        ]
    ]
];
