<?php
define('ROOT', __DIR__ . '/');
require 'core/App.php';
$rules = require 'app/config/rules.php';
(new Core\App())->load();
?>