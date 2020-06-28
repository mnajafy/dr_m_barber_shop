<?php
require 'core/Autoloader.php';
$config = require 'app/config/config.php';
(new \Core\App($config))->run();