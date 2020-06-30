<?php
$db    = require 'db.php';
$rules = require 'rules.php';
return [
    'db'         => $db,
    'layout'     => 'page',
    'basePath'   => dirname(dirname(__DIR__)),
    'viewPath'   => dirname(dirname(__DIR__)) . '\App\views',
    'layoutPath' => dirname(dirname(__DIR__)) . '\App\layout',
    'urlManager' => [
        'class' => '\Core\UrlManager',
        'rules' => $rules
    ],
];
