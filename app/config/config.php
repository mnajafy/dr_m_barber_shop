<?php
$db = require 'db.php';
$rules = require 'rules.php';
$config = [
    'db' => $db,
    'urlManager' => [
        'rules' => $rules,
    ],
];

return $config;

?>