<?php
$db    = require 'db.php';
$rules = require 'rules.php';
return [
    'id'           => 'dr_m_barber_shop',
    'db'           => $db,
    'layout'       => 'page',
    'basePath'     => dirname(dirname(__DIR__)),
    'viewPath'     => dirname(dirname(__DIR__)) . '/app/views',
    'layoutPath'   => dirname(dirname(__DIR__)) . '/app/layout',
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
