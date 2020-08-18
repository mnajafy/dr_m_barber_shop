<?php
require 'core/Autoloader.php';
$config = require 'app/config/config.php';
(new \core\web\Application($config))->run();