<?php
define('ROOT', __DIR__ . '/');
require 'core/App.php';
$config = require 'app/config/config.php';
(new Core\App($config))->load();
?>